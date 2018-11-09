<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Incomes extends Backend {


    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['buildings'] = Building::find('all');            
        $this->load->view('common/header');

        
        $this->load->view('income/income_home',$data);            
        $this->load->view('common/footer');
    }


}

/* End of file building.php */
/* Location: ./application/controllers/incomes.php */