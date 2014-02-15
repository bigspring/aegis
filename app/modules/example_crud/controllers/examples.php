<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Examples extends Crud_Controller {

    public function __construct() {

        $this->redirect = 'example_crud/examples/browse';
        $this->model_name = 'example';
        $this->entity_name = 'Example';
        $this->load->model('user/user');
        $this->load->helper('form');
        parent::__construct();
    }
}