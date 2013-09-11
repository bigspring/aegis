<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_Controller extends CI_Controller {
        
    public $model_name = null;
        
    function __construct() 
    {
        parent::__construct();
    }
    
    public function index()
    {
        echo 'index';
    } 
      
    function get()
    {
        echo 'get';
    }
      
    function delete()
    {
        echo 'delete';    
    }
      
    function post()
    {
        echo 'post';
    }
  
    /**
     * Accepts a keyword and looks up against the entity_name's model to find other suggestions
     * @param string $keyword
     * @param string $entity_name 
     */
    public function get_suggestions($keyword = null)
    {            
        if($this->input->is_ajax_request())
        {
            $keyword = ($keyword ? $keyword : $this->input->post('keyword'));
            $entity_name = $this->model_name;
            
            $base_perc = 0.0;
            $return_suggestions = 5;

            $suggestions = array();
            $suggests = array();
            $entities = new $entity_name();
            $entities = $entities->get();

            foreach($entities as $e)
            {
                $similar_chars = similar_text(strtolower($keyword), strtolower($e->name), $p);
                if($p > $base_perc)
                    $suggests[$e->id] = $p; 
            }
            
            arsort($suggests);
            $suggests = array_slice($suggests, 0, $return_suggestions, true);
    
            foreach($suggests AS $k => $v)
            {
                $entity = new $entity_name($k);
                $suggestions[$k] = $entity->name;
            }
            
            echo json_encode($suggestions);
        }
        else 
        {
            return false;
        }
     }  
}