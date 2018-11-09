<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expenses extends Backend {


    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['buildings'] = Building::find('all');            
        $this->load->view('common/header');
        $this->load->view('expense/expense_home',$data);            
        $this->load->view('common/footer');
    }
    
}

/* End of file building.php */
/* Location: ./application/controllers/expenses.php */