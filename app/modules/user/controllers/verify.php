<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verify extends EX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model_name = 'user';
        $this->entity_name = 'User';
        $this->data['entity'] = 'Users';
    }
    
    /**
     * Method for allowing users to verify their email address
     */
    public function index()
    {
        redirect('user/verify/verification');
    }
    
    /**
     * Method for allowing users to verify their email address
     */
    public function verification($code)
    {
        $user = new user();
        $user = $user->where(array('token' => $code))->get();
        
        if($user->exists())
        {
            $user->verify_user();
            $this->view = 'verify';
        }
        else 
        {
            $this->view = 'failed_verify';    
        }
        
        $this->data['title'] = 'Account Verification';
        $this->data['description'] = '';
        $this->_render();
    }
}