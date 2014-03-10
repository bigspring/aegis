<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Requires:
 * AG Auth - 
 */
class Restricted_Controller extends Template_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->library(array('user/Ag_auth'));
        $this->load->helper(array('user/ag_auth'));
        
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