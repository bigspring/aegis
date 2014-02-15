<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Template_Controller extends EX_Controller
{
    public function _construct() {
        $this->load->spark('messages/1.0.3');
    }
}