<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class EX_Controller extends CI_Controller {

	/*
	 * Main data array passed to views
	 */
	public $data;
	/*
	 * Main data array passed to views
	*/
	public $render;
	/*
	 * Referring page
	 */
	public $referrer;
	/*
	 * Main content view to render
	*/	
	public $view = null;
	/*
	 * Navigation structure for output to TWBS
	 */
	public $nav = null;
    
    /*
     * A custom redirect string
     */
	public $redirect = false;
	
	/**
	 * Array of JS scripts to call
	 */
	public $scripts = false;
    
    /**
     * Array of JS scripts to call
     */
    public $styles = false;
    
    /**
     * Array of JS scripts to call
     */
    public $body_classes = false;
	
	/**
	 * Array of CSS stylesheets to include
	 */
	//var $stylesheets = false;
	
	function __construct()
	{
		parent::__construct();

		// load libraries
		log_message('debug', 'Application Loaded');
        $this->load->spark('messages/1.0.3');
        $this->config->load('ag_auth');
		$this->load->library(array('form_validation', 'ag_auth'));
        $this->load->helper(array('html', 'url', 'ag_auth'));
		
			
		// initialise vars
		$this->data['entity'] = null;
		$this->data['id'] = null;
		$this->data['title'] = null;
		$this->data['description'] = null;
		$this->nav = $this->_getNav();
						
		// set the referring page
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	}
	
	/**
	 * Index always redirects to the default page
	 */
	public function index()
	{
		redirect($this->redirect);
	}
	
	/**
	 * Renders the output views for the controller 
	 * @param string $type
	 */
	public function _render($type = null)
	{
		// if we don't have a view, assume the convention (browse / view / add / edit / delete)
		if(!$this->view)
			$this->view = $this->router->class . '/' . $this->router->method;

		// if we don't have a title, assume the convention (browse entity)
		if(!$this->data['title'])
		{
			$this->load->helper('inflector');
			$this->data['title'] = humanize($this->router->method . ' ' . $this->router->class);
		} 
		
		// load any specific methods related to this project
		$this->_get_app_specifics();
		
		// assign data to the view layer
		$this->load->vars($this->data);

		// if the render property has been set, use it to work out the subfolder for views
		if($this->render)
			$type = $this->render;
        
        // If the user is using a mobile, use a mobile theme
        $this->load->library('user_agent');
        if( $this->agent->is_mobile() )
        {
            /*
             * Use my template library to set a theme for your staff
             *     http://philsturgeon.co.uk/code/codeigniter-template
             */
            //$this->template->set_theme('mobile');
        }

		// if we have a subfolder specified for the views, load the views
		if($type) {
			// load the views
			$this->load->view($type . '/header');
			$this->load->view($type . '/subhead');
			if($this->view)
				$this->load->view($type . '/' . $this->view);
			$this->load->view($type . '/footer');
		} else {
			// load the views
			$this->load->view('header');
			$this->load->view('subhead');
			if($this->view)
				$this->load->view($this->view);
			$this->load->view('footer');
		}
	}
		
	/**
	 * Handles uploaded files
	 */
	public function _upload_files($element, $type = null)
	{
		$this->config->load('upload');
		
		if(!$type)
			$config['upload_path'] = './assets/uploads/' . $this->config->item($element);
		else 
			$config['upload_path'] = './assets/uploads/' . $this->config->item($type);
			
		// initialise the upload config
		$config['allowed_types'] = 'gif|jpg|png|pdf';
		$config['max_size']	= '0';
		$config['max_width'] = '0';
		$config['max_height'] = '0';
		$config['remove_spaces'] = true;
			
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
        
        if(!$this->upload->do_upload($element))
        {
            $this->messages->add(' File upload error: ' . $this->upload->display_errors(), 'error');
            return false;
        }
        else
        {       
            // get the uploaded file data
            $filedata = $this->upload->data();
            
            if($filedata['is_image'] == 1) // if the uploaded file is an image, create the relevant file sizes by calling the method 
            {
                $this->_process_image($filedata);
            }
            
            return $filedata;
        }
	}
	
	/**
	 * Accepts filedata returned by the upload class and resizes the image to the sizes defined in images.php
	 * @param unknown_type $filedata
	 */
	public function _process_image($filedata)
	{
		$this->load->library('image_lib');
		$warn = false;
		
		// prepare the config options
		$this->config->load('images', TRUE);

		// get config defaults from config file
		$config['image_library'] = $this->config->item('image_library', 'images');
		$config['maintain_ratio'] = $this->config->item('maintain_ratio', 'images');
		
		$sizes = $this->config->item('sizes', 'images');
		
		foreach($sizes AS $size) // loop through all the sizes and create the necessary images
		{
			if($size['width'] < $filedata['image_width'] && $size['height'] < $filedata['image_height']) // check the image is large than we need 
			{
				$this->image_lib->clear();
				
				$config['width']	 	= $size['width'];
				$config['height']		= $size['height'];
				$config['source_image']	= $filedata['full_path'];
				$config['new_image']	= $filedata['file_path'] . $filedata['raw_name'] . '-' . $size['width'] . 'x' . $size['height'] . $filedata['file_ext'];
				
				// resize the image
				$this->image_lib->initialize($config);
				$this->image_lib->resize();
			}
			else // otherwise warn the user
			{
				if($warn == false)
				{
					$this->messages->add('Not all image sizes could be created.  Please ensure the image you\'re uploading is large enough.', 'error');
					$warn = true;
				}
			}
		}
	}
	
	/**
	 * General method used for calling any functions specific to this instance of the app
	 */
	// @TODO need to see if we can force child classes to use this
	public function _get_app_specifics()
	{
	}
	
	/**
	 * Returns the navigation structure defined in the navigation config file
	 */
	// @TODO need to see if we can force child classes to use this
	protected function _getNav()
	{
		return $this->config->item('nav');
	}
}
