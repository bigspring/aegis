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

	public $styles = array();
	public $scripts = array();

    public function __construct() {
        $this->load->spark('messages/1.0.3');
        $this->load->helper('url');

        // initialise vars
        $this->data = array(
            'id' => null,
            'entity' => null,
            'page_title' => null,
            'page_description' => null,
            'h1title' => null,
            'scripts' => null,
            'styles' => null,
            'main' => null,
            'body_classes' => null
        );

        $this->_setup_data();
        $this->nav = $this->_getNav(); // load the navigation

        parent::__construct();
    }

    public function _setup_data() {
        $this->config->load('template');

        $this->data['page_title'] = $this->config->item('site_name');
        $this->data['page_description'] = $this->config->item('site_description');
        return true;
    }

	/**
	 * Fetch existing data and add to form when required
	 */
	private function _process_form_elements() {

		if(!array_key_exists('form', $this->data)) // if we have no form variable, return
			return;

		// assign any existing values to their respective fields (excluding password types)
		foreach ($this->data['form']['fields'] as $k => &$v) {

			foreach ($this->data['main'] as $dv) {

				if (array_key_exists($k, $dv)) {

					// add existing value except for password fields
					if ($v['type'] != 'password') {
						$v['value'] = $dv->$k;
					}
					if (isset($this->data['main']->error)) {
						// add validation error message where present
						if (array_key_exists($k, $this->data['main']->error)) {
							$v['validation'] = $this->data['main']->error->$k;
						}
					}

				} elseif (array_key_exists('relation', $v)) {

					foreach ($dv->$v['relation'] as $rv) {
						$v['value'][$rv->id] = $rv->name;
					}
				}
			}
		}
	}

    /**
     * Renders the output views for the controller
     * @param string $type
     */
    public function _render($type = null)
    {
	    // if we don't have a view, assume the convention (browse / view / add / edit / delete)
	    /*if(!$this->view)
		$this->view = $this->router->fetch_module() . '/' . $this->router->class . '/' . $this->router->method;*/

	    // load any specific methods related to this project
	    $this->_get_app_specifics();

	    // assign data to the view layer
	    /*$this->load->vars($this->data);
		$this->load->helper('template');*/

	    $nav = $this->load->config('navigation');

	    $this->_process_form_elements();

	    // get meta data
	    $this->data['meta_data'] = array(
		    'description' => $nav[$this->router->fetch_module()][$this->router->fetch_class()]['description'],
		    'icon' => $nav[$this->router->fetch_module()][$this->router->fetch_class()]['icon']
	    );

	    $this->load->helper('twiggy');

	    if (!empty($this->styles)) {
		    $this->twiggy->set('template_styles', $this->styles);
	    }

	    if (!empty($this->scripts)) {
		    $this->twiggy->set('template_scripts', $this->scripts);
	    }

	    // set all data items as twig variables
	    foreach ($this->data as $key => $value) {

		    $this->twiggy->set($key, $value);
	    }

	    $this->twiggy->display('crud/'.$this->router->method);
    }
}