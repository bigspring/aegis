<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends Crud_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->render = '';
        $this->redirect = 'user/users/browse';
        $this->model_name = 'user';
        $this->entity_name = 'User';
        $this->data['entity'] = 'Users';
        $this->ag_auth->restrict('admin'); // restrict this controller to admins only
	    $this->data['form'] = $this->_set_form_fields();
    }

	public function index() {

		redirect($this->redirect);
	}

	/*
     * Add method override to get the user groups
     */
    public function add()
    {
        // get the user groups
        $user_group = new user_group();
        $this->data['groups'] = $user_group->get()->all_to_single_array('name');

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
        $this->data['groups'] = $user_group->get()->all_to_single_array('name');

        // if we have submitted data
        if($this->input->post()) {
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

	public function browse() {

		$data = new User();
		$main = $data->get();
		$this->data['main'] = array();

		foreach($main as $user) {

			$temp_user = new stdClass();

			$temp_user->id = $user->id;
			$temp_user->name = $user->get_fullname();
			$temp_user->firstname = $user->firstname;
			$temp_user->lastname = $user->lastname;
			$temp_user->email = $user->email;
			$temp_user->group = $user->user_group_name;
			$temp_user->last_login = $user->get_last_login();

			$this->data['main'][] = $temp_user;
		}

		parent::browse();
	}

	private function fetch_user_groups() {
		$groups = new User_group();
		return $groups->get()->all_to_single_array('name');
	}

	private function _set_form_fields() {

		$data = array();

		$data['class'] = array(
			'class' => 'form'
		);

		$groups = $this->fetch_user_groups();

		$data['fields'] = array(
			'email' => array(
				'type' => 'input',
				'value' => '',
				'label' => 'Email',
				'extra' => 'class="form-control required"',
				'validation' => ''
			),
			'firstname' => array(
				'type' => 'input',
				'value' => '',
				'label' => 'First Name',
				'extra' => 'class="form-control required"',
				'validation' => ''
			),
			'lastname' => array(
				'type' => 'input',
				'value' => '',
				'label' => 'Last Name',
				'extra' => 'class="form-control required"',
				'validation' => ''
			),
			'user_group_id' => array(
				'type' => 'radio',
				'data' => $groups,
				'value' => '',
				'label' => 'Group',
				'extra' => 'class="radio-inline required"',
				'validation' => ''
			),
			'enabled' => array(
				'type' => 'radio',
				'data' => get_yes_no_options(),
				'value' => '',
				'label' => 'Enabled',
				'extra' => 'class="radio-inline required"',
				'validation' => ''
			),
			'password' => array(
				'type' => 'password',
				'value' => '',
				'label' => 'Password',
				'extra' => 'class="form-control"',
				'validation' => ''
			),
			'confirmpassword' => array(
				'type' => 'password',
				'value' => '',
				'label' => 'Confirm Password',
				'extra' => 'class="form-control"',
				'validation' => ''
			)
			/*'createdby' => array(
				'type' => 'hidden',
				'value' => $this->session->userdata('id')
			),
			'modifiedby' => array(
				'type' => 'hidden',
				'value' => $this->session->userdata('id')
			)*/
		);

		return $data;
	}
}