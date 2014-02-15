<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Template_Controller extends EX_Controller {

    /*
     * Main data array passed to views
     */
	public $data;

	/*
	 * Main content view to render
	*/
	protected $view = null;

	/*
	 * Navigation structure for output to TWBS
	 */
	private $nav = null;

    /**
     * Array of JS scripts to call
     */
	public $scripts = false;

    /**
     * Array of css stylesheets to call
     */
    public $styles = false;

    /**
     * Array of body classes to be added
     */
    public $body_classes = false;

    public function __construct() {
        $this->load->spark('messages/1.0.3');
        $this->load->helper('url');

        // initialise vars
        $this->data = array(
            'id' => null,
            'entity' => null,
            'title' => null,
            'description' => null,
            'scripts' => null,
            'styles' => null,
            'main' => null
        );

        $this->nav = $this->_getNav(); // load the navigation

        parent::__construct();
    }

    /**
     * Renders the output views for the controller
     * @param string $type
     */
    public function _render($type = null)
    {
        // if we don't have a view, assume the convention (browse / view / add / edit / delete)
        if(!$this->view)
            $this->view = $this->router->fetch_module() . '/' . $this->router->class . '/' . $this->router->method;

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
        $this->load->helper('template');

        // load the views
        $this->load->view('header');
        $this->load->view('subhead');
        if($this->view)
            $this->load->view($this->view);
        $this->load->view('footer');
    }
}