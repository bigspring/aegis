<?php
/**
 * This file currently registers variables and functions for Twiggy where required.
 */

// PHP functions
$this->twiggy->register_function('is_array');

$CI =& get_instance();

// CI vars
$this->twiggy->set('module', $this->router->fetch_module(), true);
$this->twiggy->set('sub_module', $this->router->fetch_class(), true);
$this->twiggy->set('user_id', $this->session->userdata('id'));

// CI functions
$this->twiggy->register_function('render_styles');
$this->twiggy->register_function('render_scripts');
$this->twiggy->register_function('get_yes_no_options');
$this->twiggy->register_function('get_submit_text');

// Dependencies
$this->twiggy->set('basecss', site_url('assets/css/base.css'), true);
$this->twiggy->set('navigation', $this->load->config('navigation'));

// Context vars
$this->twiggy->set('crud_url', site_url() . $this->router->fetch_module() . '/' . $this->router->class . '/');

// form vars
$this->twiggy->set('butt_save', array('name' => 'submit', 'type' => 'submit', 'class' => 'btn btn-success', 'value' =>'Save'));

// Form functions
$this->twiggy->register_function('form_open', new Twig_Function_Function('form_open'));
$this->twiggy->register_function('form_hidden', new Twig_Function_Function('form_hidden'));
$this->twiggy->register_function('form_input', new Twig_Function_Function('form_input'));
$this->twiggy->register_function('form_password', new Twig_Function_Function('form_password'));
$this->twiggy->register_function('form_upload', new Twig_Function_Function('form_upload'));
$this->twiggy->register_function('form_textarea', new Twig_Function_Function('form_textarea'));
$this->twiggy->register_function('form_dropdown', new Twig_Function_Function('form_dropdown'));
$this->twiggy->register_function('form_multiselect', new Twig_Function_Function('form_multiselect'));
$this->twiggy->register_function('form_fieldset', new Twig_Function_Function('form_fieldset'));
$this->twiggy->register_function('form_fieldset_close', new Twig_Function_Function('form_fieldset_close'));
$this->twiggy->register_function('form_checkbox', new Twig_Function_Function('form_checkbox'));
$this->twiggy->register_function('form_radio', new Twig_Function_Function('form_radio'));
$this->twiggy->register_function('form_submit', new Twig_Function_Function('form_submit'));
$this->twiggy->register_function('form_label', new Twig_Function_Function('form_label'));
$this->twiggy->register_function('form_reset', new Twig_Function_Function('form_reset'));
$this->twiggy->register_function('form_button', new Twig_Function_Function('form_button'));
$this->twiggy->register_function('form_close', new Twig_Function_Function('form_close'));
$this->twiggy->register_function('form_prep', new Twig_Function_Function('form_prep'));
$this->twiggy->register_function('set_value', new Twig_Function_Function('set_value'));
$this->twiggy->register_function('set_select', new Twig_Function_Function('set_select'));
$this->twiggy->register_function('set_checkbox', new Twig_Function_Function('set_checkbox'));
$this->twiggy->register_function('set_radio', new Twig_Function_Function('set_radio'));
$this->twiggy->register_function('form_open_multipart', new Twig_Function_Function('form_open_multipart'));

$this->twiggy->register_function('get_user');
$this->twiggy->register_function('fetch_notifications');

/**
 * Return fullname of user based on ID
 * @param $id
 * @return string
 */
function get_user($id) {

	$CI =& get_instance();
	$CI->load->model('user/user');
	$user = new User(intval($id));
	return $user->get_fullname();
}

/**
 * Returns array of messages grouped by status
 *
 * @return array|bool
 */
function fetch_notifications() {

	$CI =& get_instance();
	$messages = array();

	if ($CI->messages->count() == 0) return false;

	foreach (array('error', 'success', 'info') as $status) {
		if ($CI->messages->count($status) > 0) $messages[$status] = $CI->messages->get($status);
	}

	return $messages;
}

/* End of file twiggy_helper.php */