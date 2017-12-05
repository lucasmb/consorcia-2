<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buildings extends CI_Controller {

    
        function __construct()
        {
            parent::__construct();
        }

        public function deactivate_building(){
            $id_building = $this->input->post('id',true);
            $building = Building::find($id_building);
            $building->is_active = 0;
            if ($building->save()) {
                echo "<h2>Edificio eliminado</h2>";
                echo "Contactesé con el administrador para realizar una reactivación del edificio eliminado";
            } else {
                echo "No se pudo eliminar el edificio";
            }
        }
	
        public function get_building_data(){
            $id_building = $this->input->post('id',true);
            $building = Building::find($id_building);
            $args['building'] = $building;
            $body = $this->load->view('/ajax/building/building_info',$args, true); 
            echo $body;
            
        }
        
        public function add_building(){
            $body = $this->load->view('/ajax/building/add_building',null, true);
            echo $body;
            
        }
        
        public function edit_building(){
            $id = $this->input->post("id",true);
            $data['building'] = Building::find($id);      
            $body = $this->load->view('/ajax/building/edit_building',$data, true);
            echo $body;
            
        }
        
        public function view_income_summary(){
            $id = $this->input->post("id",true);
            $building = Building::find($id);            
            $data['order'] = "ASC";
            $data['sort_column'] = "floor";
            $data['aditional_income'] = "";
            $data['building'] = $building;
            $data['unpaid_properties'] = $building->unpaid_current_properties();      
            $data['paid_properties'] = $building->paid_current_properties();;      
            $body = $this->load->view('/ajax/building/income_summary',$data);
            echo $body;            
        }
        
        public function refresh_buildings($id){
            echo get_select_building($id);
        }
        
        public function save_building(){

            $this->load->library("form_validation");
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            
            if ($this->form_validation->run('add_building') == FALSE){
                echo validation_errors();
            }
            else{
                $attr['name'] = $this->input->post('name',true);
                $attr['city_id'] = $this->input->post('city',true);
                $attr['street'] = $this->input->post('street',true);
                $attr['number'] = $this->input->post('number',true);
                $attr['balance'] = $this->input->post('balance',true);
                $attr['balance_extraordinary'] = $this->input->post('balance_extraordinary',true);
                $attr['earning_monthly'] = $this->input->post('earning_monthly',true);
                $attr['type_expense_period_id'] = $this->input->post('type_expense_period',true);
                if ($attr['type_expense_period_id'] == 1 )
                    $attr['actual_period'] = date("Y-m-01");
                else
                    $attr['actual_period'] = date("Y-m-d" , strtotime("-1 month",strtotime(date("Y-m-01"))));
                $attr['total_coefficient'] = $this->input->post('total_coefficient',true);
                $attr['cuit'] = $this->input->post('cuit',true);
                $attr['tax_percentage'] = $this->input->post('tax_percentage',true);
                $attr['tax_late_percentage'] = $this->input->post('tax_late_percentage',true);
                $attr['has_reserve_fund'] = $this->input->post('has_reserve_fund',true);
                $attr['reserve_fund'] = $this->input->post('reserve_fund',true);
                $attr['put_reserve_value_manually'] = $this->input->post('put_reserve_value_manually',true);
                $attr['earn_static_fund'] = $this->input->post('earn_static_fund',true);
                if ( $attr['earn_static_fund'] == 1 )
                    $attr['earn_static_fund_value'] = $this->input->post('earn_static_fund_value',true);
                else 
                    $attr['earn_percentage_value'] = $this->input->post('earn_percentage_value',true);
                
                
                
                $building = Building::create($attr);
                
                echo "success:".$building->id.":";
            }
        }
        
        public function save_edit_building(){
            $id = $this->input->post("building_id",true);
            $this->load->library("form_validation");
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            
            if ($this->form_validation->run('add_building') == FALSE){
                echo validation_errors();
            }
            else{

                $building = Building::find($id);
                if ($building){
                    $building->name = $this->input->post('name',true);
                    $building->city_id = $this->input->post('city',true);
                    $building->street = $this->input->post('street',true);
                    $building->number = $this->input->post('number',true);
                    $building->type_expense_period_id = $this->input->post('type_expense_period',true);
                    $building->balance = (float)$this->input->post('balance',true);
                    $building->balance_extraordinary = $this->input->post('balance_extraordinary',true);
                    $building->earning_monthly = $this->input->post('earning_monthly',true);
                    $building->total_coefficient = $this->input->post('total_coefficient',true);
                    $building->cuit = $this->input->post('cuit',true);
                    $building->tax_percentage = $this->input->post('tax_percentage',true);
                    $building->tax_late_percentage = $this->input->post('tax_late_percentage',true);
                    $building->has_reserve_fund = $this->input->post('has_reserve_fund',true);
                    $building->reserve_fund = $this->input->post('reserve_fund',true);
                    $building->put_reserve_value_manually = $this->input->post('put_reserve_value_manually',true);
                    $building->reserve_percentage = $this->input->post('reserve_percentage',true);
                    $building->earn_static_fund = $this->input->post('earn_static_fund',true);
                    
                    if ( $building->earn_static_fund == 1 )
                        $building->earn_static_fund_value = $this->input->post('earn_static_fund_value',true);
                    else 
                        $building->earn_percentage_value = $this->input->post('earn_percentage_value',true);

                    $building->save();

                    echo "success:".$building->id.":";
                }
            }
        }
        
        public function close_period(){
            $id = $this->input->post("building_id",true);
            
            if ($id != ""){
                $building = Building::find($id);

                if ($building->close_period())
                    echo "<h2>Se ha cerrado el periodo de manera correcta</h2>";
                else 
                    echo "<h2>No se pudo cerrar el periodo</h2>";                
            }
        }
        
        public function reopen_period(){
            $id = $this->input->post("building_id",true);
            
            if ($id != ""){
                $building = Building::find($id);

                if ($building->reopen_period())
                    echo "<h2>Se realizó la apertura de el anterior periodo</h2>";
                else 
                    echo "<h2>No se pudo reabrir el periodo</h2>";                
            }
        }
        
        public function pay_actual_days($id){
            
            $building = Building::find($id);
            $data['building'] = $building;
            $data['actual_pay_days'] = $building->actual_pay_days();
            $body = $this->load->view('/ajax/reports/actual_pay_days',$data);
            echo $body; 
        }


        public function autogenerated_pay_days($id){
            
            $building = Building::find($id);
            $data['building'] = $building;
            $data['autogenerated_pay_days'] = BuildingAutogeneratedDays::find_by_sql("SELECT * FROM building_autogenerated_days as bad WHERE bad.building_id = ". $building->id);
            $data['week_days'] = unserialize(WEEK_DAYS_LABEL);
            $data['multiplicities'] = unserialize(MULTIPLICITY_AUTOGENERATED_DAYS_LABEL);
            $body = $this->load->view('/ajax/reports/autogenerated_pay_days',$data);
            echo $body; 
        }

}

/* End of file building.php */
/* Location: ./application/controllers/building.php */