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
     * Array of css stylesheets to call
     */
    public $styles = false;
    
    /**
     * Array of JS scripts to call
     */
    public $body_classes = false;

	/*
     * Default where clause for all normal get methods
     */
    public $model_relations = null;
	
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
     * General add function for an entity
     * 
     * @param mixed $data $this->input->post() but with vars cleaned for insertion to DB
     */
    public function add($data = false)
    {
        $class = $this->model_name;
        $entity = new $class;
        $this->load->helper('inflector');
        
        // if we haven't been passed data but post data exists, set $data = post
        if($data == false && $this->input->post())
            $data = $this->input->post();
        
        // if we have data
        if($data)
        { 
            // check the form for elements which should not be saved and remove them
            foreach($data AS $key => $value)
            {
                // check if the current entity has this property, update it
                if(property_exists($entity, $key))
                    $entity->$key = $value;
            }
            
            $relations = array();
            
            // if we have been passed relations, accept them
            if(array_key_exists('relations', $data))
                $relations = $data['relations'];
            
            // check if the entity has relationships and if so, populate the relationship
            if($entity->has_many || $entity->has_one)
            {
                foreach($entity->has_many AS $related_entity)
                {                   
                    $ids = null;
                    
                    // create a new related entity
                    // get the IDs passed from the form
                    if(array_key_exists(plural($related_entity['class']), $data))
                        $ids = $data[plural($related_entity['class'])];
                    
                    // cycle through IDs and add the entity objects to our array for saving later                                                                   
                    if($ids)
                    {
                        foreach($ids AS $id)
                        {
                            $relation = new $related_entity['class'];
                            $relation = $relation->where('id', $id)->get();                     
                            // add the relation to our array
                            $relations[] = $relation;
                        }
                    }                   
                }
                
                // @TODO - this may need fixing later when we have a has one relationship
                foreach($entity->has_one AS $related_entity)
                {
                    $ids =  null;
                    
                    if(array_key_exists(plural($related_entity['class']), $data))
                        $ids = $data[plural($related_entity['class'])];
                    
                    if($ids)
                    {
                        // cycle through IDs and add the entity objects to our array for saving later                                                                   
                        foreach($ids AS $id)
                        {
                            $relation = new $related_entity['class'];
                            $relation = $relation->where('id', $id)->get();                     
                            // add the relation to our array
                            $relations[] = $relation;
                        }
                    }
                }
            }

            // false, there was an error
            if(!$entity->save($relations))
            {
                if($entity->valid)
                {
                    // insert / update failure
                    $this->messages->add('Could not add the ' . strtolower($this->entity_name) . '.', 'error');
                }
                else
                {
                    // validation failure
                    $this->messages->add('Please check the values in the form below and try again.', 'error');
                }
            }
            else
            {
                $this->messages->add('The ' . strtolower($this->entity_name) . ' has been added.', 'success');
                
                // if we have a custom redirect, use it or use the controller's default
                if($this->redirect)
                {
                    if(strpos($this->redirect, 'edit')) // check if we're trying to redirect to an edit page, and if so append the ID
                        redirect($this->redirect . $entity->id);
                    else
                        redirect($this->redirect);
                }
                    
                
                redirect(plural($this->entity_name));
            }
        }
        
        $this->data['entity'] = 'Add ' . $this->entity_name;
        $this->data['h1title'] = 'Add ' . $this->entity_name;
        $this->data['title'] = 'Add ' . $this->entity_name;
        $this->data['main'] = $entity;
        
        $this->_render();  
    }
    
    /**
     * Handles editing an entiry
     */
    public function edit($id, $data = false)
    {
        $class = $this->model_name;
        $entity = new $class;
        $update = $entity->where(array('id' => $id))->get();

        $relations = null;
        $related_to_delete = null;
            
        // if we haven't been passed data but post data exists, set $data = post
        if($data == false && $this->input->post())
            $data = $this->input->post();
        
        // if we have post data
        if($data)
        { 
            // set the entity to the form element results
            foreach($data AS $key => $value)
            {
                // check if the current entity has this property, update it
                if(property_exists($update, $key))
                    $update->$key = $value;
            }
                        
            // if we have been passed relations, accept them
            if(array_key_exists('relations', $data))
                $relations = $data['relations'];

            // if we have related to delete, store them for deletion later
            if(array_key_exists('related_to_delete', $data))
                $related_to_delete = $data['related_to_delete'];

            // check if the entity has relationships and if so, populate the relationship
            if($entity->has_many || $entity->has_one)
            {
                foreach($entity->has_many AS $related_entity)
                {
                    $ids = null;

                    // create a new related entity
                    // get the IDs passed from the form
                    if(array_key_exists(plural($related_entity['class']), $data))
                        $ids = $data[plural($related_entity['class'])];

                    // cycle through IDs and add the entity objects to our array for saving later                                                                                                                                   
                    if($ids)
                    {
                        foreach($ids AS $id)
                        {
                            $relation = new $related_entity['class'];
                            $relation = $relation->where('id', $id)->get();                                         
                            // add the relation to our array
                            $relations[] = $relation;
                        }
                     }                     
                 }
                                
                 // @TODO - this may need fixing later when we have a has one relationship
                 foreach($entity->has_one AS $related_entity)
                 {
                    $ids =  null;
                                        
                    if(array_key_exists(plural($related_entity['class']), $data))
                        $ids = $data[plural($related_entity['class'])];
                                        
                    if($ids)
                    {
                        // cycle through IDs and add the entity objects to our array for saving later                                                                                                                                   
                        foreach($ids AS $id)
                        {
                            $relation = new $related_entity['class'];
                            $relation = $relation->where('id', $id)->get();                                         
                            // add the relation to our array
                            $relations[] = $relation;
                        }
                    }
                }
            }

            if(!$update->save($relations))
            {                   
                if($update->valid)
                {
                    // insert / update failure
                    $this->messages->add('Could not update the ' . strtolower($this->entity_name) . '.', 'error');
                }
                else
                {                   
                    // validation failure
                    $this->messages->add('Please check the values in the form below and try again.', 'error');
                }
            }
            // handle validation
            else
            {                           
                // delete any related entities we no longer require
                if($related_to_delete)
                    $update->delete($related_to_delete);
                
                $this->messages->add('The ' . strtolower($this->entity_name) . ' has been updated.', 'success');
                    
                // if we have a custom redirect, use it
                if($this->redirect)
                {
                    redirect($this->redirect);
                }
                
                $this->load->helper('inflector');
                redirect(plural($this->entity_name));
            }
        }
        
        $this->data['h1title'] = 'Edit ' . $this->entity_name . ' - ' . $entity->name;
        $this->data['title'] = 'Edit ' . $this->entity_name . ' - ' . $entity->name;
        $this->data['main'] = $update;

        $this->_render();
    }
    
    /**
     * Handles editing an entity
     */
    public function delete($id)
    {
        // get the entity
        $model = $this->model_name;
        $entity = new $model();
        $entity = $entity->where(array('id' => $id))->get();
        
        if($this->input->post())
        {
            // delete the entity
            $result = $entity->delete();
            
            if(!$result)
                $this->messages->add('Could not delete the ' . strtolower($this->entity_name) . '.', 'error');
            else
                $this->messages->add('The ' . strtolower($this->entity_name) . ' has been deleted.', 'success');
            
            // if we have a redirect, use it, else use the default redirect
            $this->load->helper('inflector');
            
            if($this->redirect)
                redirect($this->redirect);
            else
                redirect(plural($this->entity_name));
        }
                
        $this->data['h1title'] = 'Delete ' . $this->entity_name . ' - ' . $entity->name;
        $this->data['title'] = 'Delete ' . $this->entity_name . ' - ' . $entity->name;
        $this->data['main'] = $entity;
        
        $this->_render();
    }
    
    /**
     * Renders the list view for an entity
     */
    public function browse()
    {
        $this->load->helper('inflector');
        $this->data['h1title'] = 'Browse ' . plural($this->entity_name);
        $this->data['title'] = $this->data['h1title'];
        $this->data['entity'] = $this->data['h1title'];
        
        // get all entities
        $data = new $this->model_name();
        
        // base 
        if($this->model_relations)
        {
            // if we've been provided a related key in the array, use a related query
            if(array_key_exists('related_where', $this->model_relations))
            {
                if(is_array($this->model_relations['related_where']))
                { // if we've been provided with an array, there must be several relationships so process each one
                    foreach($this->model_relations['related_where'] AS $related)
                    {
                        if(array_key_exists('model', $related)) // if we've been provided a model, must be a relational where
                            $data = $data->where_related($related['model'], $related['field'], $related['value']);
                        else // otherwise a number where
                            $data = $data->where($related['field'], $related['value']);
                    }   
                }
                else
                {
                    $related = $this->model_relations['related_where'];
                    $data = $data->where_related($related['model'], $related['field'], $related['value']);
                }
            }
            else
            {
                $data = $data->where($this->model_relations);
            }
            
            if(array_key_exists('related_order', $this->model_relations))
            {
                $related = $this->model_relations['related_order'];
                $data = $data->order_by_related($related['model'], $related['ordering']);
            }
            
            if(array_key_exists('group_by', $this->model_relations))
            {
                $data = $data->group_by($this->model_relations['group_by']);                
            }
        }
        $this->data['main'] =  $data->get();
        
        $this->_render();
    }
    
    /**
     * Renders the single view for an entity
     * @param int $id entity id
     */
    public function view($id)
    {
        $this->data['title'] = 'View ' . $this->entity_name;
        
        $entity = new $this->model_name();
        $this->data['main'] = $entity->get_where(array('id' => $id));

        $this->_render();        
    }    
    
    /**
     * Processes post data based on the id and or data passed
     * @param mixed $data the data to process, not required as data will be taken from $this->input->post if not
     * @param int $id the ID of the entity required
     * @param str $redirect false = do not redirect, null = use default redirect, str = use this redirect
     */
    public function _process_post($data = null, $id = false, $redirect = false, $message = false)
    {
        $class = $this->model_name;
        $entity = new $class;
        $relations = array();
        $related_to_delete = array();
        
        // if we haven't been passed data but post data exists, set $data = post
        if($data == false && $this->input->post())
            $data = $this->input->post();
        
        if($id) // if we have been provided with an ID, get the entity that relates to that ID
        {
            $relations = null;
            $related_to_delete = null;
            $entity = $entity->where(array('id' => $id))->get();
        }
        
        if($data)
        { 
            // set the entity to the form element results
            foreach($data AS $key => $value)
            {
                // check if the current entity has this property, update it
                if(property_exists($entity, $key))
                    $entity->$key = $value;
            }
                        
            // if we have been passed relations, accept them
            if(array_key_exists('relations', $data))
                $relations = $data['relations'];


            // if we have related to delete, store them for deletion later
            if(array_key_exists('related_to_delete', $data))
                $related_to_delete = $data['related_to_delete'];

            // check if the entity has relationships and if so, populate the relationship
            if($entity->has_many || $entity->has_one)
            {
                foreach($entity->has_many AS $related_entity)
                {
                    $ids = null;

                    // create a new related entity
                    // get the IDs passed from the form
                    if(array_key_exists(plural($related_entity['class']), $data))
                        $ids = $data[plural($related_entity['class'])];

                    // cycle through IDs and add the entity objects to our array for saving later                                                                                                                                   
                    if($ids)
                    {
                        foreach($ids AS $id)
                        {
                            $relation = new $related_entity['class'];
                            $relation = $relation->where('id', $id)->get();                                         
                            // add the relation to our array
                            $relations[] = $relation;
                        }
                     }                     
                 }
                                
                 // @TODO - this may need fixing later when we have a has one relationship
                 foreach($entity->has_one AS $related_entity)
                 {
                    $ids =  null;
                                        
                    if(array_key_exists(plural($related_entity['class']), $data))
                        $ids = $data[plural($related_entity['class'])];
                                        
                    if($ids)
                    {
                        // cycle through IDs and add the entity objects to our array for saving later                                                                                                                                   
                        foreach($ids AS $id)
                        {
                            $relation = new $related_entity['class'];
                            $relation = $relation->where('id', $id)->get();                                         
                            // add the relation to our array
                            $relations[] = $relation;
                        }
                    }
                }
            }

            if(!$entity->save($relations))
            {                   
                if($entity->valid && $message != false)
                {
                    // insert / update failure
                    $this->messages->add('Could not update the ' . strtolower($this->entity_name) . '.', 'error');
                }
                else
                {                   
                    // validation failure
                    if($message != false)
                    {
                        $this->messages->add('Please check the values in the form below and try again.', 'error');    
                    }
                    
                }
            }
            // handle validation
            else
            {                           
                // delete any related entities we no longer require
                if($related_to_delete)
                    $entity->delete($related_to_delete);
                
                if($message != FALSE) 
                {
                    $this->messages->add('The ' . strtolower($this->entity_name) . ' has been updated.', 'success');    
                }
                    
                if($redirect != false) // passing a false will stop the redirect, so check that 
                {

                    if(!$redirect && $this->redirect) // if we haven't been passed a redirect but have a custom redirect, use it
                    {
                        die('default redirect');
                        redirect($this->redirect);
                    }
                    elseif(!$redirect) // if we haven't been passed a redirect and don't have a custom one, use the default
                    {
                        die('default redirect');
                        $this->load->helper('inflector');
                        redirect(plural($this->entity_name));
                    }
                }
            }
        }
        
        return $entity;
    }
    
    /**
     * Handles adding and deleting relationships for an entity
     * 
     * @param mixed $data The object containing post data to process
     * @param string $class The string containing the name of the datamapper class to use
     * @param array $existing An array of existing IDs
     */
    public function _process_relationships($data, $class, $existing)
    {
        $related_to_delete_ids = array();
            
        // check for languages
        if(array_key_exists($class, $data))
        {
            foreach($data[$class] AS $k => $v)
            {
                $maintained_relations[] = $v;
                                    
                if(!in_array($v, $existing)) // if this skill hasn't already been selected, create a new related skill
                {
                    $relation = new $class($v);
                    $data['relations'][] = $relation;
                }   
            }
            
            $related_to_delete_ids = array_diff($existing, $maintained_relations);
        }
        else // otherwise we should delete all relationships
        {
            $related_to_delete_ids = $existing;
        }
            
        // find out which relationships to delete
        foreach($related_to_delete_ids AS $to_delete_id)
        {
            $deleted_relation = new $class($to_delete_id);
            $data['related_to_delete'][] = $deleted_relation;
        }
        
        return $data;
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
