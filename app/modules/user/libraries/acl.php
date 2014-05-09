<?php
/**
 * Basic ACL class integrated with codeigniter.
 * Taken from http://www.tastybytes.net/blog/simple-acl-class-for-codeigniter
 */
class acl
{
	var $perms = array();		//Array : Stores the permissions for the user
	var $userID;			//Integer : Stores the ID of the current user
	var $userRoles = array();	//Array : Stores the roles of the current user
	var $ci;
	function __construct($config=array()) {
		$this->ci = &get_instance();
		
		
		$this->userID = (is_array($config) && array_key_exists('userID', $config)) ? floatval($config['userID']) : $this->ci->session->userdata('id');
		/*
		if(is_array($config) && array_key_exists('userID', $config))
			floatval($config['userID']);	
			
			$config['userID'] = $this->ci->session->userdata('id');
*/
		
		$this->userRoles = $this->getUserRoles();
		$this->buildACL();
	}

	function buildACL() {
		//first, get the rules for the user's role
		if (count($this->userRoles) > 0)
		{
			$this->perms = array_merge($this->perms,$this->getRolePerms($this->userRoles));
		}
		//then, get the individual user permissions
		$this->perms = array_merge($this->perms,$this->getUserPerms($this->userID));
	}

	function getPermKeyFromID($permID) {
		$this->ci->db->select('key');
		$this->ci->db->where('id',floatval($permID));
		$sql = $this->ci->db->get('permissions',1);
		$data = $sql->result();
		return $data[0]->key;
	}

	function getPermNameFromID($permID) {
		$this->ci->db->select('name');
		$this->ci->db->where('id',floatval($permID));
		$sql = $this->ci->db->get('permissions',1);
		$data = $sql->result();
		return $data[0]->name;
	}

	function getRoleNameFromID($roleID) {

		$this->ci->db->select('name');
		$this->ci->db->where('id',floatval($roleID),1);
		$sql = $this->ci->db->get('roles');
		$data = $sql->result();
		return $data[0]->name;
	}

	function getUserRoles() {

		$this->ci->db->where(array('id'=>floatval($this->userID)));
		$this->ci->db->order_by('created','asc');
		$sql = $this->ci->db->get('users');
		$data = $sql->result();

		$resp = array();
		foreach( $data as $row )
		{
			$resp[] = $row->role_id;
		}
		return $resp;
	}

	function getAllRoles($format='ids') {
		$format = strtolower($format);
		$this->ci->db->order_by('name','asc');
		$sql = $this->ci->db->get('roles');
		$data = $sql->result();

		$resp = array();
		foreach( $data as $row )
		{
			if ($format == 'full')
			{
				$resp[] = array("id" => $row->id,"name" => $row->name);
			} else {
				$resp[] = $row->id;
			}
		}
		return $resp;
	}

	function getAllPerms($format='ids') {
		$format = strtolower($format);
		//$strSQL = "SELECT * FROM `".DB_PREFIX."permissions` ORDER BY `permKey` ASC";

		$this->ci->db->order_by('key','asc');
		$sql = $this->ci->db->get('permissions');
		$data = $sql->result();

		$resp = array();
		foreach( $data as $row )
		{
			if ($format == 'full')
			{
				$resp[$row->key] = array('id' => $row->id, 'name' => $row->name, 'key' => $row->key);
			} else {
				$resp[] = $row->id;
			}
		}
		return $resp;
	}

	function getRolePerms($role) {
		if (is_array($role))
		{
			$this->ci->db->where_in('role_id',$role);
		} else {
			$this->ci->db->where(array('role_id'=>floatval($role)));

		}
		$this->ci->db->order_by('id','asc');
		$sql = $this->ci->db->get('permissions_roles'); //$this->db->select($roleSQL);
		$data = $sql->result();
		$perms = array();
		foreach( $data as $row )
		{
			$pK = strtolower($this->getPermKeyFromID($row->permission_id));
			if ($pK == '') { continue; }
			if ($row->value === '1') {
				$hP = true;
			} else {
				$hP = false;
			}
			$perms[$pK] = array('perm' => $pK,'inheritted' => true,'value' => $hP,'name' => $this->getPermNameFromID($row->permission_id),'id' => $row->permission_id);
		}
		return $perms;
	}

	function getUserPerms($userID) {
		$this->ci->db->where('user_id',floatval($userID));
		$this->ci->db->order_by('created','asc');
		$sql = $this->ci->db->get('permissions_users');
		$data = $sql->result();

		$perms = array();
		foreach( $data as $row )
		{
			$pK = strtolower($this->getPermKeyFromID($row->permission_id));
			if ($pK == '') { continue; }
			if ($row->value == '1') {
				$hP = true;
			} else {
				$hP = false;
			}
			$perms[$pK] = array('perm' => $pK,'inheritted' => false,'value' => $hP,'name' => $this->getPermNameFromID($row->permission_id),'id' => $row->permission_id);
		}
		return $perms;
	}

	function hasRole($roleID) {
		foreach($this->userRoles as $k => $v)
		{
			if (floatval($v) === floatval($roleID))
			{
				return true;
			}
		}
		return false;
	}

	function hasPermission($permKey) {
		$permKey = strtolower($permKey);
		if (array_key_exists($permKey,$this->perms))
		{
			if ($this->perms[$permKey]['value'] === '1' || $this->perms[$permKey]['value'] === true)
			{
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}