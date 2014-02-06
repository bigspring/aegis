<?php
/**
 * Template Aegis Controller
 *
 * Use this basic controller as a template for creating new controllers.
 * It is not recommended that you include this file with your application,
 * especially if you use a Template library (as the classes may collide).
 *
 * To use:
 * 1) Copy this file to the lowercase name of your new product_category.
 * 2) Find-and-replace (case-sensitive) 'Entities' with 'Your_entities'
 * 3) Find-and-replace (case-sensitive) 'entities' with 'your_entities'
 * 4) Find-and-replace (case-sensitive) 'Human name' with 'Your entity'
 * 5) Find-and-replace (case-sensitive) 'model' with 'your_model'
 * 6) Edit the file as desired.
 * 
 * @license		MIT License
 * @category	Controllers
 * @author		Jonathan Martin
 * @link		http://www.bigspring.co.uk
 */

class Test extends EX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->ag_auth->restrict('admin'); // restrict this controller to admins only
		$this->render = 'admin';
		$this->redirect = 'admin/entities/browse';
		$this->model_name = 'model';
		$this->entity_name = 'Human Name';
		$this->data['entity'] = 'Human Name';
	}
	
	public function index()
	{
		$config = array('userID'=>$this->session->userdata('id'));
		$this->load->library('user/acl', $config);
		
		$acl_test = $this->router->fetch_class() . '_' . $this->router->fetch_method();
	
		if ( !$this->acl->hasPermission($acl_test) ) {				
			echo 'No permission for ' . $acl_test;
		} else {
			echo $this->load->view('someview.php');
		}	
		
	}
}
?>