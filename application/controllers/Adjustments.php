<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adjustments extends Backend {

    
    function __construct()
    {
        parent::__construct();
    }
        
    public function index()
    {     
            $data['buildings'] = Building::find('all');  
            $this->load->view('common/header');
            $this->load->view('adjustments/adjustments_home',$data);            
            $this->load->view('common/footer');
    }
     
}

/* End of file building.php */
/* Location: ./application/controllers/reports.php */