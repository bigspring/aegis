<?php
/**
 * Created by PhpStorm.
 * User: davidseaton
 * Date: 03/04/2014
 * Time: 09:54
 */

class DataMapperExt extends DataMapper {

	function __construct($id = NULL) {
		parent::__construct($id);
	}

	/**
	 * Get
	 *
	 * Get active objects from the database.
	 *
	 * @param	integer|NULL $limit Limit the number of results.
	 * @param	integer|NULL $offset Offset the results when limiting.
	 * @return	DataMapper Returns self for method chaining.
	 */
	public function get($limit = NULL, $offset = NULL)
	{

		// Check if this is a related object and if so, perform a related get
		if (! $this->_handle_related())
		{
			// invalid get request, return this for chaining.
			return $this;
		} // Else fall through to a normal get

		$query = FALSE;

		// Check if object has been validated (skipped for related items)
		if ($this->_validated && empty($this->parent))
		{
			// Reset validated
			$this->_validated = FALSE;

			// Use this objects properties
			$data = $this->_to_array(TRUE);

			if ( ! empty($data))
			{
				// Clear this object to make way for new data
				$this->clear();

				// Set up default order by (if available)
				$this->_handle_default_order_by();

				// Get by objects properties
				$query = $this->db->get_where($this->table, $data, $limit, $offset);
			} // FIXME: notify user if nothing was set?
		}
		else
		{
			// Clear this object to make way for new data
			$this->clear();

			// Set up default order by (if available)
			$this->_handle_default_order_by();

			// Get by built up query
			$query = $this->db->where(array($this->table.'.is_deleted' => 0))->get($this->table, $limit, $offset);
		}

		// Convert the query result into DataMapper objects
		if($query)
		{
			$this->_process_query($query);
		}

		// For method chaining
		return $this;
	}

	/**
	 * Delete
	 *
	 * Soft deletes the current record. Consequently, NO RECORD IS LOST.
	 * If object is supplied, deletes relations between this object and the supplied object(s).
	 *
	 * @param	mixed $object If specified, delete the relationship to the object or array of objects.
	 * @param	string $related_field Can be used to specify which relationship to delete.
	 * @return	bool Success or Failure of the delete.
	 */
	public function delete($object = '', $related_field = '')
	{
		if (empty($object) && ! is_array($object))
		{
			if ( ! empty($this->id))
			{
				// Begin auto transaction
				$this->_auto_trans_begin();

				// Delete all "has many" and "has one" relations for this object first
				foreach (array('has_many', 'has_one') as $type)
				{
					foreach ($this->{$type} as $model => $properties)
					{
						// do we want cascading delete's?
						if ($properties['cascade_delete'])
						{
							// Prepare model
							$class = $properties['class'];
							$object = new $class();

							$this_model = $properties['join_self_as'];
							$other_model = $properties['join_other_as'];

							// Determine relationship table name
							$relationship_table = $this->_get_relationship_table($object, $model);

							// We have to just set NULL for in-table foreign keys that
							// are pointing at this object
							if($relationship_table == $object->table  && // ITFK
								// NOT ITFKs that point at the other object
								! ($object->table == $this->table && // self-referencing has_one join
									in_array($other_model . '_id', $this->fields)) // where the ITFK is for the other object
							)
							{
								/*$data = array($this_model . '_id' => NULL);

								// Update table to remove relationships
								$this->db->where($this_model . '_id', $this->id);
								$this->db->update($object->table, $data);*/
							}
							else if ($relationship_table != $this->table)
							{

								$data = array($this_model . '_id' => $this->id);

								// Delete relation
								//$this->db->delete($relationship_table, $data);

							}
							// Else, no reason to delete the relationships on this table
						}
					}
				}

				$CI =& get_instance();

				// Delete the object itself
				$this->db->where('id', $this->id);

				$delete_update = array(
					'is_deleted' => 1,
					'deleted' => date('Y-m-d G:i:s'),
					'deletedby' => $CI->session->userdata('id')
				);
				$this->db->update($this->table, $delete_update);

				// Complete auto transaction
				$this->_auto_trans_complete('delete');

				// Clear this object
				$this->clear();

				return TRUE;
			}
		}
		else if (is_array($object))
		{
			// Begin auto transaction
			$this->_auto_trans_begin();

			// Temporarily store the success/failure
			$result = array();

			foreach ($object as $rel_field => $obj)
			{
				if (is_int($rel_field))
				{
					$rel_field = $related_field;
				}
				if (is_array($obj))
				{
					foreach ($obj as $r_f => $o)
					{
						if (is_int($r_f))
						{
							$r_f = $rel_field;
						}
//						$result[] = $this->_delete_relation($o, $r_f);
					}
				}
				else
				{
//					$result[] = $this->_delete_relation($obj, $rel_field);
				}
			}

			// Complete auto transaction
			$this->_auto_trans_complete('delete (relationship)');

			// If no failure was recorded, return TRUE
			if ( ! in_array(FALSE, $result))
			{
				return TRUE;
			}
		}
		else
		{
			// Begin auto transaction
			$this->_auto_trans_begin();

			// Temporarily store the success/failure
			$result = true;

			// Complete auto transaction
//			$this->_auto_trans_complete('delete (relationship)');

			return $result;
		}

		return FALSE;
	}
}