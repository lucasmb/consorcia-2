<?php

class Render {

    private $variables = array();
    private $css = array();
    private $javascripts = array();
    private $ci;
    private $page = "Page";


    public function __construct(){
        $this->ci = & get_instance();
    }


    public function set_page($page) {
        $this->page = $page;
    }

    public function add_css($value) {
        $this->css[] = $value;
    }

    public function add_js($value) {
        $this->javascripts[] = $value;
    }

    public function add_var($name, $value) {
        $this->variables[$name] = $value;
    }

    public function get_page() {
        return $this->page;
    }

    public function get_js()
    {
        return $this->javascripts;
    }

    public function get_css()
    {
        return $this->css;
    }

    public function get_variables()
    {
        return $this->variables;
    }

    public function __print_vars()
    {
        echo '<pre>';
        print_r($this->variables);
        echo '</pre>';
        die();
    }

    public function page($page = "", $data = array(), $setup=false, $mobile="")
    {
        $data['page'] = $this->get_page();
        $data = array_merge($this->variables, $data);
        //$this->_ci->load->view("includes/header", $data);
        $this->ci->load->view("includes/header", $data);
        $this->ci->load->view("includes/menu", $data);

        //main page
        $this->ci->load->view($page, $data);

        $js_path = FCPATH.'assets/js/pages/'.$page.'.js' ;
             
             if (file_exists($js_path)) {
                 $this->add_js('pages/'.$page.'.js');
                }

        $this->ci->load->view('includes/footer', $data);

    }

}