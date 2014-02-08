<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends EX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->render = '';
		$this->redirect = 'user/users/browse';
		$this->model_name = 'user';
		$this->entity_name = 'User';
		$this->data['entity'] = 'Users';
        $this->load->library('user/ag_auth');
		$this->ag_auth->restrict('admin'); // restrict this controller to admins only
	}
	
	/*
	 * Add method override to get the user groups
	 */
	public function add()
	{
		// get the user groups
		$user_group = new user_group();		
		$this->data['groups'] = $user_group->get()->all_to_single_array('title');
		
		parent::add();
	}
	
	/*
	 * Edit method override to get the user groups
	*/
	public function edit($id)
	{
		$data = null;
		
		// get the user groups
		$user_group = new user_group();
		$this->data['groups'] = $user_group->get()->all_to_single_array('title');
		
		// if we have submitted data
		if($this->input->post())
		{
			// get the submitted data
			$data = $this->input->post();
			
			// check for a password and unset if it doesn't exist so we don't overwrite the current password
			if(array_key_exists('password', $data) && empty($data['password']))
			{
				unset($data['password']);
			}
		}
		
		parent::edit($id, $data);
	}
}
?>