<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once APPPATH.'libraries/RoelaBank.php';

class BankPayment extends Backend {

    function __construct()
    {
        parent::__construct();
    }
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{

	    $data['buildings'] = Building::find('all', array('conditions' => 'payment_type <> '.BUILDING_PAYMENT_TYPE_MANUAL.''));
	    $this->load->view('common/header');
        $this->load->view('bankPayment/bank_payment_home',$data);            
        $this->load->view('common/footer');

	}

	public function generate_complete_file_bank_payment()
    {  

        $buildings = Building::find('all', array('conditions' => 'payment_type <> '.BUILDING_PAYMENT_TYPE_MANUAL.' AND is_active = 1'));

        if (count($buildings) > 0) {
	
	        $properties = array();
			foreach ($buildings as $building) {
	        	
	        	foreach ($building->properties as $property) {
	        		if ($property->allow_to_pay_by_bank()) {
	        			$properties[] = $property;
	        		}
	        	}

	        }
	        
	        $this->load->helper('download');
	        header('Content-Type: text/html; charset=ASCII');
			$data = RoelaFileUtils::create_pago_mis_cuentas_text_file($properties);
			$name = '20136679083.'.date("Ymd");
			force_download($name, $data, TRUE);
	
        } else {
        	echo "building not found or not";
        }
        
    }

    public function generate_building_file_bank_payment()
    {     
    	$building_id = $this->input->post("building",true);

    	if ($building_id) {
    		
    		$building = Building::find('first', array('conditions' => 'id = '.$building_id.' AND payment_type <> '.BUILDING_PAYMENT_TYPE_MANUAL.' AND is_active = 1'));
			if ($building) {

				$properties = Property::find('all', array('conditions' => 'building_id = '.$building->id, 'order' => 'id asc'));

				$this->load->helper('download');
				header('Content-Type: text/html; charset=ASCII');
				$data = RoelaFileUtils::create_pago_mis_cuentas_text_file($properties);
				$name = '30532696265.20170214';
				force_download($name, $data, TRUE);

			} else {
				echo "building not found or not";
			}


    	} else {
    		echo "Not building id";
    	}
        
    }

    public function generate_validation_file_bank_payment()
    {  

        $buildings = Building::find('all', array('conditions' => 'payment_type <> '.BUILDING_PAYMENT_TYPE_MANUAL.' AND is_active = 1'));

        if (count($buildings) > 0) {
	
	        $properties = array();
			foreach ($buildings as $building) {
	        	
	        	foreach ($building->properties as $property) {
	        		if ($property->allow_to_pay_by_bank()) {
	        			$properties[] = $property;
	        		}
	        	}

	        }
	        
	        $this->load->helper('download');
	        header('Content-Type: text/html; charset=ASCII');
			$data = RoelaFileUtils::create_validation_text_file($properties);
			$name = 'Archivo Validation 20136679083.'.date("Ymd");
			force_download($name, $data, TRUE);
	
        } else {
        	echo "building not found or not";
        }
        
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */