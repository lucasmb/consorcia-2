<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class VerifyLogin extends CI_Controller {
 
    function __construct() {
      parent::__construct();
    }

    function index()
    {

        //This method will have the credentials validation
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if($this->form_validation->run() == FALSE) {
            //Field validation failed.  User redirected to login page
            redirect('login', 'refresh');
        } else if ($this->check_database($username, $password)) {
            //Go to private area
            redirect('home', 'refresh');
        } else {
            redirect('login', 'refresh');
        }

    }

    function check_database($username, $password) {

        $result = User::login($username, $password);

        if($result) {
            $sess_array = array();
            foreach($result as $row) {
                $sess_array = array(
                    'id' => $row->id,
                    'username' => $row->username
                );

                $this->session->set_userdata('logged_in', $sess_array);
            }
            return true;
        } else {
            return false;
        }
    }
    
}