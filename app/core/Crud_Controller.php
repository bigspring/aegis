<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Crud_Controller extends Restricted_Controller {

	/**
	 * @var null Name for the CRUD entity
	 */
	protected $entity_name = null;

	/**
	 * @var null Related datamapper model name
	 */
	protected $model_name = null;

	public $autoload = array('helper' => array('form', 'date'));

	public function __construct()
	{
		// initialise vars
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		parent::__construct();
	}

	/**
	 * General add function for an entity
	 *
	 * @param mixed $data $this->input->post() but with vars cleaned for insertion to DB
	 */
	public function add($data = false)
	{
		$class = $this->model_name;
		$entity = new $class;
		$this->load->helper('inflector');

		// if we haven't been passed data but post data exists, set $data = post
		if($data == false && $this->input->post())
			$data = $this->input->post();

		// if we have data
		if($data)
		{
			// check the form for elements which should not be saved and remove them
			foreach($data AS $key => $value)
			{
				// check if the current entity has this property, update it
				if(property_exists($entity, $key))
					$entity->$key = $value;
			}

			$entity->createdby = $this->session->userdata('id');

			$relations = array();

			// if we have been passed relations, accept them
			if(array_key_exists('relations', $data))
				$relations = $data['relations'];

			// check if the entity has relationships and if so, populate the relationship
			if($entity->has_many || $entity->has_one)
			{
				foreach($entity->has_many AS $related_entity)
				{
					$ids = null;

					//dump($data);
					//echo plural($related_entity['class']) . '<br/>';

					// create a new related entity
					// get the IDs passed from the form
					if(array_key_exists(plural($related_entity['class']), $data))
						$ids = $data[plural($related_entity['class'])];

					// cycle through IDs and add the entity objects to our array for saving later
					if($ids)
					{
						foreach($ids AS $id)
						{
							$relation = new $related_entity['class'];
							$relation = $relation->where('id', $id)->get();
							// add the relation to our array
							$relations[] = $relation;
						}
					}
				}

				// @TODO - this may need fixing later when we have a has one relationship
				foreach($entity->has_one AS $related_entity)
				{
					$ids =  null;

					if(array_key_exists(plural($related_entity['class']), $data))
						$ids = $data[plural($related_entity['class'])];

					if($ids)
					{
						// cycle through IDs and add the entity objects to our array for saving later
						foreach($ids AS $id)
						{
							$relation = new $related_entity['class'];
							$relation = $relation->where('id', $id)->get();
							// add the relation to our array
							$relations[] = $relation;
						}
					}
				}
			}

			// false, there was an error
			if(!$entity->save($relations))
			{

				if($entity->valid)
				{
					// insert / update failure
					$this->messages->add('Could not add the ' . strtolower($this->entity_name) . '.', 'error');
				}
				else
				{
					// validation failure
					$this->messages->add('Please check the values in the form below and try again.', 'error');
				}
			}
			else
			{
				$this->messages->add('The ' . strtolower($this->entity_name) . ' has been added.', 'success');

				// if we have a custom redirect, use it or use the controller's default
				if($this->redirect)
				{
					if(strpos($this->redirect, 'edit')) // check if we're trying to redirect to an edit page, and if so append the ID
						redirect($this->redirect . $entity->id);
					else
						redirect($this->redirect);
				}

				redirect(plural($this->entity_name));
			}
		}

		$this->data['_entity'] = $this->entity_name;
		$this->data['_crud'] = 'Add';
		$this->data['entity'] = 'Add ' . $this->entity_name;
		$this->data['h1title'] = 'Add ' . $this->entity_name;
		$this->data['title'] = 'Add ' . $this->entity_name;
		$this->data['main'] = $entity;

		$this->_render();
	}

	/**
	 * Handles editing an entity
	 */
	public function edit($id, $data = false)
	{
		$class = $this->model_name;
		$entity = new $class;
		$update = $entity->where(array('id' => $id))->get();

		$relations = null;
		$related_to_delete = null;

		// if we haven't been passed data but post data exists, set $data = post
		if($data == false && $this->input->post())
			$data = $this->input->post();

		// if we have post data
		if($data)
		{
			// set the entity to the form element results
			foreach($data AS $key => $value)
			{
				// check if the current entity has this property, update it
				if(property_exists($update, $key))
					$update->$key = $value;
			}

			$update->modifiedby = $this->session->userdata('id');

			// if we have been passed relations, accept them
			if(array_key_exists('relations', $data))
				$relations = $data['relations'];

			// if we have related to delete, store them for deletion later
			if(array_key_exists('related_to_delete', $data))
				$related_to_delete = $data['related_to_delete'];

			// check if the entity has relationships and if so, populate the relationship
			if($entity->has_many || $entity->has_one)
			{
				foreach($entity->has_many AS $related_entity)
				{
					$ids = null;

					// create a new related entity
					// get the IDs passed from the form
					if(array_key_exists(plural($related_entity['class']), $data))
						$ids = $data[plural($related_entity['class'])];

					// cycle through IDs and add the entity objects to our array for saving later
					if($ids)
					{
						foreach($ids AS $id)
						{
							$relation = new $related_entity['class'];
							$relation = $relation->where('id', $id)->get();
							// add the relation to our array
							$relations[] = $relation;
						}
					}
				}

				// @TODO - this may need fixing later when we have a has one relationship
				foreach($entity->has_one AS $related_entity)
				{
					$ids =  null;

					if(array_key_exists(plural($related_entity['class']), $data))
						$ids = $data[plural($related_entity['class'])];

					if($ids)
					{
						// cycle through IDs and add the entity objects to our array for saving later
						foreach($ids AS $id)
						{
							$relation = new $related_entity['class'];
							$relation = $relation->where('id', $id)->get();
							// add the relation to our array
							$relations[] = $relation;
						}
					}
				}
			}

			if(!$update->save($relations))
			{
				if($update->valid)
				{
					// insert / update failure
					$this->messages->add('Could not update the ' . strtolower($this->entity_name) . '.', 'error');
				}
				else
				{
					// validation failure
					$this->messages->add('Please check the values in the form below and try again.', 'error');
				}
			}
			// handle validation
			else
			{
				// delete any related entities we no longer require
				if($related_to_delete)
					$update->delete($related_to_delete);

				$this->messages->add('The ' . strtolower($this->entity_name) . ' has been updated.', 'success');

				// if we have a custom redirect, use it
				if($this->redirect)
				{
					redirect($this->redirect);
				}

				$this->load->helper('inflector');
				redirect(plural($this->entity_name));
			}
		}

		$this->data['_entity'] = $this->entity_name;
		$this->data['_crud'] = 'Edit';
		$this->data['h1title'] = 'Edit ' . $this->entity_name . ' - ' . $entity->name;
		$this->data['title'] = 'Edit ' . $this->entity_name . ' - ' . $entity->name;
		$this->data['main'] = $update;
		$this->data['item_id'] = $id;

		$this->_render();
	}

	/**
	 * Handles editing an entity
	 */
	public function delete($id, $confirm=false)
	{
		// get the entity
		$model = $this->model_name;
		$entity = new $model();
		$entity = $entity->where(array('id' => $id))->get();

		// echoes prompt for modal via twiggy
		if (!$confirm) {

			$data = array(
				'entity' => strtolower($this->entity_name),
				'item' => $entity->name,
				'crud_path' => $this->router->fetch_module() . '/' . $this->router->class . '/',
				'id' => $id
			);
			$this->twiggy->set('modal', $data, true);

			echo $this->twiggy->template('crud/delete-form')->display();

			return true;
		}

		if($this->input->post())
		{
			// delete the entity
			$result = $entity->delete();

			if(!$result)
				$this->messages->add('Could not delete the ' . strtolower($this->entity_name) . '.', 'error');
			else
				$this->messages->add('The ' . strtolower($this->entity_name) . ' has been deleted.', 'success');

			// if we have a redirect, use it, else use the default redirect
			$this->load->helper('inflector');

			if($this->redirect)
				redirect($this->redirect);
			else
				redirect(plural($this->entity_name));
		}

		$this->data['_entity'] = $this->entity_name;
		$this->data['_crud'] = 'Delete';
		$this->data['h1title'] = 'Delete ' . $this->entity_name . ' - ' . $entity->name;
		$this->data['title'] = 'Delete ' . $this->entity_name . ' - ' . $entity->name;
		$this->data['main'] = $entity;
		$this->data['item_id'] = $id;

		$this->_render();
	}

	/**
	 * Renders the list view for an entity
	 */
	public function browse()
	{
		$this->load->helper('inflector');
		$this->data['_entity'] = plural($this->entity_name);
		$this->data['_crud'] = 'All';
		$this->data['h1title'] = 'Browse ' . plural($this->entity_name);
		$this->data['title'] = $this->data['h1title'];
		$this->data['entity'] = $this->data['h1title'];

		if (!isset($this->data['main'])) {
			// get all entities
			$data = new $this->model_name();
			$this->data['main'] =  $data->get();
		}

		$this->_render();
	}

	/**
	 * Renders the single view for an entity
	 * @param int $id entity id
	 */
	public function view($id)
	{
		$this->data['_entity'] = $this->entity_name;
		$this->data['_crud'] = 'View';
		$this->data['title'] = 'View ' . $this->entity_name;
		$this->data['item_id'] = $id;

		$entity = new $this->model_name();
		$this->data['main'] = $entity->get_where(array('id' => $id));

		if ($this->data['main']->file) {
			foreach ($this->data['main']->file as $file) {
				$this->data['files'][] = array(
					'url' => $file->url,
					'thumbnail_urls' => unserialize($file->thumbnail_url)
				);
			}
		}

		$this->_render();
	}

	/**
	 * Processes post data based on the id and or data passed
	 * @param mixed $data the data to process, not required as data will be taken from $this->input->post if not
	 * @param int $id the ID of the entity required
	 * @param string $redirect false = do not redirect, null = use default redirect, str = use this redirect
	 */
	public function _process_post($data = null, $id = false, $redirect = false, $message = false)
	{
		$class = $this->model_name;
		$entity = new $class;
		$relations = array();
		$related_to_delete = array();

		// if we haven't been passed data but post data exists, set $data = post
		if($data == false && $this->input->post())
			$data = $this->input->post();

		if($id) // if we have been provided with an ID, get the entity that relates to that ID
		{
			$relations = null;
			$related_to_delete = null;
			$entity = $entity->where(array('id' => $id))->get();
		}

		if($data)
		{
			// set the entity to the form element results
			foreach($data AS $key => $value)
			{
				// check if the current entity has this property, update it
				if(property_exists($entity, $key))
					$entity->$key = $value;
			}

			// if we have been passed relations, accept them
			if(array_key_exists('relations', $data))
				$relations = $data['relations'];


			// if we have related to delete, store them for deletion later
			if(array_key_exists('related_to_delete', $data))
				$related_to_delete = $data['related_to_delete'];

			// check if the entity has relationships and if so, populate the relationship
			if($entity->has_many || $entity->has_one)
			{
				foreach($entity->has_many AS $related_entity)
				{
					$ids = null;

					// create a new related entity
					// get the IDs passed from the form
					if(array_key_exists(plural($related_entity['class']), $data))
						$ids = $data[plural($related_entity['class'])];

					// cycle through IDs and add the entity objects to our array for saving later
					if($ids)
					{
						foreach($ids AS $id)
						{
							$relation = new $related_entity['class'];
							$relation = $relation->where('id', $id)->get();
							// add the relation to our array
							$relations[] = $relation;
						}
					}
				}

				// @TODO - this may need fixing later when we have a has one relationship
				foreach($entity->has_one AS $related_entity)
				{
					$ids =  null;

					if(array_key_exists(plural($related_entity['class']), $data))
						$ids = $data[plural($related_entity['class'])];

					if($ids)
					{
						// cycle through IDs and add the entity objects to our array for saving later
						foreach($ids AS $id)
						{
							$relation = new $related_entity['class'];
							$relation = $relation->where('id', $id)->get();
							// add the relation to our array
							$relations[] = $relation;
						}
					}
				}
			}

			if(!$entity->save($relations))
			{
				if($entity->valid && $message != false)
				{
					// insert / update failure
					$this->messages->add('Could not update the ' . strtolower($this->entity_name) . '.', 'error');
				}
				else
				{
					// validation failure
					if($message != false)
					{
						$this->messages->add('Please check the values in the form below and try again.', 'error');
					}

				}
			}
			// handle validation
			else
			{
				// delete any related entities we no longer require
				if($related_to_delete)
					$entity->delete($related_to_delete);

				if($message != FALSE)
				{
					$this->messages->add('The ' . strtolower($this->entity_name) . ' has been updated.', 'success');
				}

				if($redirect != false) // passing a false will stop the redirect, so check that
				{

					if(!$redirect && $this->redirect) // if we haven't been passed a redirect but have a custom redirect, use it
					{
						die('default redirect');
						redirect($this->redirect);
					}
					elseif(!$redirect) // if we haven't been passed a redirect and don't have a custom one, use the default
					{
						die('default redirect');
						$this->load->helper('inflector');
						redirect(plural($this->entity_name));
					}
				}
			}
		}

		return $entity;
	}

	/**
	 * Handles adding and deleting relationships for an entity
	 *
	 * @param mixed $data The object containing post data to process
	 * @param string $class The string containing the name of the datamapper class to use
	 * @param array $existing An array of existing IDs
	 */
	public function _process_relationships($data, $class, $existing)
	{
		$related_to_delete_ids = array();

		// check for languages
		if(array_key_exists($class, $data))
		{
			foreach($data[$class] AS $k => $v)
			{
				$maintained_relations[] = $v;

				if(!in_array($v, $existing)) // if this skill hasn't already been selected, create a new related skill
				{
					$relation = new $class($v);
					$data['relations'][] = $relation;
				}
			}

			$related_to_delete_ids = array_diff($existing, $maintained_relations);
		}
		else // otherwise we should delete all relationships
		{
			$related_to_delete_ids = $existing;
		}

		// find out which relationships to delete
		foreach($related_to_delete_ids AS $to_delete_id)
		{
			$deleted_relation = new $class($to_delete_id);
			$data['related_to_delete'][] = $deleted_relation;
		}

		return $data;
	}
}