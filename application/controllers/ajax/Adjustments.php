<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adjustments extends CI_Controller {

    
        function __construct()
        {
            parent::__construct();
        }
	
        public function get_building_base(){
            
            $id_building = $this->input->get('id',true);
            $building = Building::find($id_building);
            
            if ($building) {
                echo $building->earning_monthly;
            } else {
                echo "0.0";
            }
            
        }
        
        public function get_building_estimate(){
            
            $id_building = $this->input->get('id',true);
            $new_earnings = $this->input->get('base',true);
            $building = Building::find($id_building);
            
            if ($building) {

                $older_building_earning = $building->earning_monthly;
                if ($new_earnings) {
                    $building->earning_monthly = $new_earnings;
                    $building->save();    
                }
                
                $data["building"] = $building;
                $data["properties"] = $building->properties;
                $data["new_earnings"] = $new_earnings;
                $body = $this->load->view('/ajax/adjustments/adjustments_properties',$data);

                $building->earning_monthly = $older_building_earning;
                $building->save();
                echo $body;
                
            } else {
                echo "lalala";
            }
            
        }
        
        public function set_building_base(){

            $id_building = $this->input->post('id',true);
            $new_base = $this->input->post('base',true);
            
            if ($id_building && $new_base) {
                $building = Building::find($id_building);
                $building->earning_monthly = $new_base;
                $building->save();
                echo "ok";
            }

        }

}

/* End of file building.php */
/* Location: ./application/controllers/building.php */