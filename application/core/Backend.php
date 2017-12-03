<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Backend extends MY_Controller {

    private $errors = array();

    public function __construct() 
    {
        parent::__construct();

        $this->load->library(array('ion_auth','form_validation'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');

        echo 'Backend';
        	//vd($_SERVER);

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}
		else
		{
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		}

    }


}