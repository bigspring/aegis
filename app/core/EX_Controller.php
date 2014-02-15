<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class EX_Controller extends MX_Controller {

    /*
     * A custom redirect string
     */
	public $redirect = false;
	
	public function __construct()
	{
		parent::__construct();

		// load libraries
		log_message('debug', 'Core Controller Loaded');
        $this->load->spark('messages/1.0.3');
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
