<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Localization extends CI_Controller {

    
        function __construct()
        {
            parent::__construct();
        }
        
	public function get_cities($id = null){
            if ($id != null){
                $selects = get_select_cities($id);
                echo $selects;
            }
            
        }
        
}