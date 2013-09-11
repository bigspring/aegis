<?php
/**
 * Requires:
 * AG Auth - 
 */
class Restricted_Controller extends EX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->library(array('Ag_auth'));
        $this->load->helper(array('ag_auth'));
        
        if(!logged_in())
        {   
            redirect('user/login');
        }
    }
    
    /**
     * General logout method 
     */
    public function logout()
    {
        $this->ag_auth->logout();
    }    
}