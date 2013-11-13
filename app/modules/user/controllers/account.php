<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends Upload_Controller {
    
    public function __construct()
    {   
        parent::__construct();
        
        $this->load->library(array('form_validation', 'Ag_auth'));
        $this->load->spark('messages/1.0.3');
        $this->load->helper(array('url', 'email', 'ag_auth'));
        
        $this->model_name = 'user';
        
        if(!logged_in())
        {
            redirect('/');
        }
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -  
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {           
        redirect('user/account/manage');
    }
    
    public function manage()
    {
        $user = new user($this->session->userdata('id'));
        
        if(!$user->exists())
            redirect('/');
            
        if($this->input->post())
        {           
            $data = $this->input->post();
            
            if($data['email'] != $user->email) // if the email address has changed, set a flag to resend verfication
            {
                $verify = true;
                $data['token'] = $user->generate_token();
                $this->session->set_userdata('verify_send', $verify);
            }
            
            if($user->password && $data['password'] == '') // if it's an existing user, they must have gone back to the contact form so don't overwrite password unless a new one is entered
            {
                unset($data['password']);
                unset($data['confirmpassword']);
            }

            $user = $this->_process_post($data, $this->session->userdata('id'), false);
            
            if(!$user->errors->all) // if validation has passed, redirect
            {
                if($verify == true) { // if verify flag is set, we need to send a verification email
                    $user->resend_verification($user->id);
                }
                $this->messages->add('Your account has been updated', 'success');
                redirect('user/account/manage');
            }
            else 
            {
                $this->messages->add('There has been a problem updating your account.  Please try again.', 'error');
            }
        }

        $this->scripts[] = 'library/validate/jquery.validate.password.js';
        $this->_prepare_jquery_upload();
        $this->scripts[] = 'modules::user/manage.js';
        $this->data['user'] = $user;
        $this->data['title'] = 'My Account';
        $this->data['description'] = '';
        $this->view = 'manage';
        $this->_render();
    }

    function upload_avatar()
    {
        //if($this->input->is_ajax_request())
        //{
        $this->load->library('avatarhandler');
        //}
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */