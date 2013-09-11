<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgot extends EX_Controller {
    
    public function __construct()
    {   
        parent::__construct();
        
        $this->load->library(array('form_validation', 'Ag_auth'));
        $this->load->spark('messages/1.0.3');
        $this->load->helper(array('url', 'email', 'ag_auth'));
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
        if($this->input->post())
        {
            $email = $this->input->post('email');
                
            // test for a user with the submitted email address
            $user = new user();
            $user = $user->get_where(array('email' => $email));

            if($user->exists()) // if user is found
            {
                // generate a new identier to validate the request
                $token = $user->generate_token();
                $user->token = $token;
                $user->save();
                
                // email the user with a link to reset their password
                $this->_email_password_link($token, $email);
                
                redirect('user/forgot/sent');
            }
            else // else warn the user 
            {
                $this->messages->add('No user was found with this email address.  Please try again.', 'error');
            }
        }
        
        $this->data['title'] = 'Forgotten Password?';
        $this->data['description'] = 'Forgot your password to labourmarket.com?  Retrieve it here.';
        $this->view = 'user/forgot/forgot';
        
        $this->render();
    }

    public function sent()
    {
        $this->data['title'] = 'Password instructions sent';
        $this->data['description'] = '';
        $this->view = 'user/forgot/sent';
        
        $this->render();
    }

    public function reset($token)
    {
        // test for a user with the identifier
        
        $user = new user();
        $user = $user->get_where(array('token' => $token));
        
        if($this->input->post())
        {
            // update their password
            $data = $this->input->post();       
           
            $user->password = $data['password'];
            $user->token = '';
            $user->save();
            
            $this->view = 'user/forgot/reset_success';
            
            // redirect to login
        }
        else 
        {
            if($user->exists())
            {
                $this->view = 'user/forgot/reset';
            }
            else 
            {
                $this->view = 'user/forgot/reset_not_found';    
            }
        }
        
        $this->data['user'] = $user;
        $this->data['title'] = 'Reset your password';
        $this->data['description'] = 'Reset your password with to labourmarket.com.';
        
        
        $this->render();
    }
    
    public function _email_password_link($token, $email)
    {
        $url = site_url('user/forgot/reset');    
            
        $message = $this->load->view('forgot/forgot-email', array('token' => $token, 'url' => $url), true);
        
        // send confirmation email
        $this->load->library('email');
        $this->email->from('info@labourmarket.com', 'labourmarket');
        $this->email->to($email);
             
        $this->email->subject('Your password reset request for labourmarket.com');
        $this->email->message($message);        
                                    
        $this->email->send();        
    }
}
/* End of file logout.php */
/* Location: ./app/modules/user/controllers/logout.php */