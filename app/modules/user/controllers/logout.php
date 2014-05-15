<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends EX_Controller {
    
    public function __construct()
    {   
        parent::__construct();
        
        $this->load->library(array('form_validation', 'Ag_auth'));
        $this->load->helper(array('url', 'email', 'ag_auth'));
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {           
        if(logged_in())
        {
            $this->ag_auth->logout();
        }
        
        redirect('/');
    }
}

/* End of file logout.php */
/* Location: ./app/modules/user/controllers/logout.php */