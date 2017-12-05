<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buildings extends MY_Controller {

    
    function __construct()
    {
        parent::__construct();
    }
        
	public function index()
	{
            $data['buildings'] = Building::find('all');            
           // vd($data['buildings']);
            $this->load->view('common/header');
            $this->load->view('building/building_home',$data);            
            $this->load->view('common/footer');
	}
     
}

/* End of file building.php */
/* Location: ./application/controllers/building.php */