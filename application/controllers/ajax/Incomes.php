<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Incomes extends CI_Controller {

    
        function __construct()
        {
            parent::__construct();
        }
	
        public function view_income_operations(){
            $id = $this->input->post("id",true);
            $building = Building::find($id);     
            $data['building'] = $building;       
            $body = $this->load->view('/ajax/income/income_operations',$data);
            echo $body;            
        }
        
        public function view_income_summary(){
            $id = $this->input->post("id",true);
            $building = Building::find($id);            
            $data['building'] = $building;
            $data['extra_period'] = $building->get_extraordinary_period_for_pay();
            $data['unpaid_properties'] = $building->unpaid_current_properties();      
            $body = $this->load->view('/ajax/income/income_summary',$data);
            echo $body;            
        }
        
        public function view_income_payment(){
            $id = $this->input->post("id",true);
            $building = Building::find($id);            
            $data['order'] = "ASC";
            $data['sort_column'] = "floor";
            $data['aditional_income'] = "";
            $data['building'] = $building;
            $data['unpaid_properties'] = $building->unpaid_current_properties();      
            $data['paid_properties'] = $building->paid_current_properties();
            $body = $this->load->view('/ajax/income/income_payment',$data);
            echo $body;            
        }
        
        public function view_income_extra(){
            $id = $this->input->post("id",true);
            $building = Building::find($id);      
            $data['order'] = "ASC";
            $data['sort_column'] = "floor";
            $data['building'] = $building;
            $data['extraordinary_properties'] = $building->properties;
            $data['extra_period'] = $building->get_extraordinary_period_for_pay();
            $data['periods_for_pay'] = $building->get_actives_or_with_activity_extraordinaries_period();            
            $body = $this->load->view('/ajax/income/income_extra',$data);
            echo $body;            
        }
                
        public function view_income_aditional(){
            $id = $this->input->post("id",true);
            $building = Building::find($id);      
            $actual_period = $building->actual_period->format("Y-m-d");
            $one_month_back = $building->one_month_back();
            $data['order'] = "ASC";
            $data['sort_column'] = "floor";
            
            $data['aditional_income'] = AditionalIncome::find_by_sql("SELECT *
                                                                        FROM aditional_incomes ai
                                                                       WHERE ai.building_id = '". $building->id ."'
                                                                         AND ai.period_date = '$actual_period'");
            
            $data['older_aditional_income'] = AditionalIncome::find_by_sql("SELECT *
                                                                              FROM aditional_incomes ai
                                                                             WHERE ai.building_id = '". $building->id ."'
                                                                               AND ai.period_date = '$one_month_back'");
            
            $data['building'] = $building;
            $body = $this->load->view('/ajax/income/income_aditional',$data);
            echo $body;            
        }
                
        public function view_income_ordinary_and_extraordinary(){
            $id = $this->input->post("id",true);
            $building = Building::find($id);      
            $data['order'] = "ASC";
            $data['sort_column'] = "floor";
            $data['building'] = $building;
            $data['unpaid_properties'] = $building->unpaid_current_properties();      
            $data['paid_properties'] = $building->paid_current_properties();
            $data['extra_period'] = $building->get_extraordinary_period_for_pay();
            $data['periods_for_pay'] = $building->get_extraordinary_period_for_pay();    
            $data['building'] = $building;
            $body = $this->load->view('/ajax/income/income_ordinary_and_extraordinary',$data);
            echo $body;            
        }
                
	public function pay_ordinary_expense(){
            
            $property_id = $this->input->post("property_id",true);
            $type_pay = $this->input->post("total_pay_".$property_id,true);
            
            $property = Property::find($property_id);
            switch ($type_pay) {
                case SECOND_PAY:
                    
                    $attr_income['last_balance'] = $property->balance;
                    $attr_income['value'] = $property->total_to_pay_due();
                    $attr_income['property_id'] = $property->id;
                    $attr_income['type_pay_date'] = $type_pay;
                    $attr_income['date'] = date('Y-m-d');
                    $attr_income['period_date'] = $property->building->actual_period;
                    
                    IncomeTransaction::create($attr_income);
                    
                    $property->balance = 0;
                    $property->save();
                    
                    echo "ok";
                    break;
                case FIRST_PAY:
                    
                    $attr_income['last_balance'] = $property->balance;
                    $attr_income['value'] = $property->total_to_pay();
                    $attr_income['property_id'] = $property->id;
                    $attr_income['type_pay_date'] = $type_pay;
                    $attr_income['date'] = date('Y-m-d');
                    $attr_income['period_date'] = $property->building->actual_period;
                    
                    IncomeTransaction::create($attr_income);

                    $property->balance = 0;
                    $property->save();
                    
                    echo "ok";
                    break;
                case ACCOUNT_PAY:
                    $value = $this->input->post("input_pay_".$property_id,true);
                    if ($value == "" || !is_numeric($value))
                        echo "<h2>Se debe ingresar un valor numerico si se realiza el pago a cuenta $value</h2>";
                    
                    else {
                        $attr_income['last_balance'] = $property->balance;
                        $attr_income['value'] = $value;
                        $attr_income['property_id'] = $property->id;
                        $attr_income['type_pay_date'] = $type_pay;
                        $attr_income['date'] = date('Y-m-d');
                        $attr_income['period_date'] = $property->building->actual_period;

                        IncomeTransaction::create($attr_income);
                        
                        $property->balance = $value - $property->total_to_pay() ;
                        $property->save();
                        
                        echo "ok";
                    }

                    break;

                default:
                    echo "<h2>Para efectuar el pago se debe seleccionar una de las tres opciones de pago disponible</h2>";
                    break;
            }
        }
        
        public function refresh_buildings($id){
            echo get_select_building($id);
        }
        
        public function delete_payment(){
            $id = $this->input->post("id",true);
            
            if ($id != ""){
                $income = IncomeTransaction::find($id);
                if ($income){
                    $income->property->balance = $income->last_balance;
                    if ($income->property->building->has_reserve_fund)
                        $income->property->balance_reserve = $income->last_balance_reserve;
                    $income->property->save();
                    $income->delete();                    
                    
                    echo "ok";
                }
                else
                    echo "<h2>El pago correspondiente no existe</h2>";
            }
            else
                echo "<h2>El pago correspondiente no existe</h2>";
        }
        
        public function get_income_tags(){
            $term = trim(strip_tags($_GET['term']));
            
            $tags = IncomeTag::all(array('conditions' => "name LIKE '%$term%' OR name LIKE '$term%' OR name LIKE '%$term' "));
            
            foreach ($tags as $tag) {
                $array['id'] = $tag->id;
                $array['value'] = $tag->name;
                $response[] = $array;
            }
            
            echo json_encode($response);
        }
        
    public function add_aditional_income(){

        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run('add_additional_income') == FALSE){
            echo validation_errors();
        }
        else{

            $tag = $this->input->post("income_tags",true);
            $value = $this->input->post("value",true);
            $building_id = $this->input->post("building_id",true);

            $tag_income = IncomeTag::find_by_name($tag);
            $building = Building::find($building_id);

            if (!$tag_income){

                $attr_tag['name'] = $tag;
                $tag_income = IncomeTag::create($attr_tag);
            }

            $attr_income['value'] = $value;
            $attr_income['building_id'] = $building_id;
            $attr_income['period_date'] = $building->actual_period;
            $attr_income['priority'] = $this->input->post("priority",true);
            $attr_income['date'] = $this->input->post("date",true);
            $attr_income['income_tag_id'] = $tag_income->id;

            AditionalIncome::create($attr_income);

            echo "success";
        }
    }

    
    
    public function edit_aditional_income(){
        
        $id = $this->input->post("id",true);
        $data['income'] = AditionalIncome::find($id);      
        $body = $this->load->view('/ajax/income/edit_income_aditional',$data);
        echo $body;

    }

    public function delete_aditional_income(){

        $id_income = $this->input->post("id",true);
        $income = AditionalIncome::find($id_income);
        if ($income){
            $income->delete();
            echo "<h2>El ingreso adicional se elimino con exito</h2>";
        }
        else
            echo "<h2>El ingreso adicional no existe</h2>";

    }
    
    public function increment_priority(){
        
        $id = $this->input->post("id",true);
        
        $income = AditionalIncome::find($id);
        
        $income->priority++;
        
        $income->save();
        
    }
    
    public function decrement_priority(){
        
        $id = $this->input->post("id",true);
        
        $income = AditionalIncome::find($id);
        
        $income->priority--;
        
        $income->save();
        
    }
    
    public function save_edit_income_aditional(){
        
        $id = $this->input->post("income_id",true);
        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run('add_additional_income') == FALSE){
            echo validation_errors();
        }
        else{

            $income = AditionalIncome::find($id);
            if ($income){
                
                $descripcion = $this->input->post("income_tags",true);
                $date = $this->input->post("date",true);
            
                if ($date)
                    $income->date = $date;
                else
                    $income->date = date("Y-m-d");
            
                $tag = IncomeTag::find(array('conditions' => "(name = '$descripcion')"));
                        
                if (!$tag){
                    $attr_tag["name"] = $descripcion;
                    $attr_tag["building_id"] = $income->building_id;
                    $tag = IncomeTag::create($attr_tag);                
                }
                
                $income->income_tag_id = $tag->id;
                $income->priority = $this->input->post("priority",true);
                $income->value = $this->input->post("value",true);
                
                $income->save();

                echo "success:".$income->id.":";
            }
        }
    }
    
    public function add_older_expense(){
        
        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run('add_older_additional_income') == FALSE){
            echo validation_errors();
        }
        else{

            $id = $this->input->post("income_older_building_id",true);
            $descripcion = $this->input->post("income_older_tags",true);
            $date = $this->input->post("date_older",true);
            $building = Building::find($id);
            
            if ($date)
                $attr["date"] = $date;
            else
                $attr["date"] = date("Y-m-d");
            
            $tag = IncomeTag::find(array('conditions' => "(name = '$descripcion')"));
                        
            if (!$tag){
                $attr_tag["name"] = $descripcion;
                $attr_tag["building_id"] = $id;
                $tag = IncomeTag::create($attr_tag);                
            }
                
            $attr["income_tag_id"] = $tag->id;
            $attr["period_date"] = $building->actual_period;
            $attr["building_id"] = $id;
            $attr["priority"] = $this->input->post("income_older_priority",true);;
            $attr["value"] = $this->input->post("value_older",true);
                
            AditionalIncome::create($attr);
            
            echo "success";
        }
    }
        
    private function pay_total_expense($id_property){
        
        $property = Property::find($id_property);
        $attr_income['last_balance'] = $property->balance;
        $attr_income['last_balance_reserve'] = $property->balance_reserve;
        
        //$attr_income['value'] = $property->total_to_pay_expense();
        if ($property->building->has_reserve_fund){
            $value_paid_round = round($property->total_to_pay_expense() + $property->total_to_pay_fund() , 0, PHP_ROUND_HALF_DOWN);
            
            // Si tiene fondo de reserve por propiedad agregar por un lado el valor de la expensa menos ese valor
            // y por otro lado el fondo de reserva
            if ($property->building->put_reserve_value_manually){
                
                $attr_income['value_fund'] = $property->total_to_pay_fund();
                $attr_income['value'] = $value_paid_round - $property->total_to_pay_fund();
                
            }
            else{
                
                if($property->building->earn_static_fund){
                    $attr_income['value'] = $value_paid_round - ($property->building->earn_static_fund_value * $property->coefficient);
                    $attr_income['value_fund'] = $value_paid_round - $attr_income['value'];
                }
                else{
                    $attr_income['value'] = $value_paid_round * (100 - $property->building->earn_percentage_value) / 100;
                    $attr_income['value_fund'] = $value_paid_round * $property->building->earn_percentage_value / 100;
                }
                
            }
        }
            
            //$attr_income['value_fund'] = $property->total_to_pay_fund();
        else{
            $attr_income['value'] = round($property->total_to_pay_expense() , 0, PHP_ROUND_HALF_DOWN);
            $attr_income['value_fund'] = 0;
        }
        $attr_income['property_id'] = $property->id;
        $attr_income['type_pay_date'] = FIRST_PAY;
        $attr_income['date'] = date('Y-m-d');
        $attr_income['period_date'] = $property->building->actual_period;

        IncomeTransaction::create($attr_income);

        $property->balance = 0;
        if ($property->building->has_reserve_fund)
            $property->balance_reserve = 0;
        $property->save();
        
    }
    
    private function pay_account_expense($id_property){
        
        $property = Property::find($id_property);
        $value = $this->input->post("input_pay_".$id_property,true);
        
        $attr_income['last_balance'] = $property->balance;
        $attr_income['last_balance_reserve'] = $property->balance_reserve;
        $attr_income['property_id'] = $property->id;
        $attr_income['type_pay_date'] = ACCOUNT_PAY;
        $attr_income['date'] = date('Y-m-d');
        $attr_income['period_date'] = $property->building->actual_period;

        
        
        if ($property->building->has_reserve_fund){
         
            if ($value > $property->total_to_pay_fund()){
               
                $value = $value - $property->total_to_pay_fund();
                $property->balance_reserve = 0;
                $property->balance = $value - $property->total_to_pay_expense();
                
                $attr_income['value'] = $value;
                $attr_income['value_fund'] = $property->total_to_pay_fund();
                
            }
            else{
                $percentage_balance_reserve = $value * $property->building->reserve_percentage / 100;
                $percentage_balance = $value - $percentage_balance_reserve;        
                $property->balance = $percentage_balance - $property->total_to_pay_expense();
                $property->balance_reserve = $percentage_balance_reserve - $property->total_to_pay_fund();
                
                $attr_income['value'] = $percentage_balance;
                $attr_income['value_fund'] = $percentage_balance_reserve;
                
            }
        
        }
        else{
            $property->balance = $value - $property->total_to_pay_expense();            
            $attr_income['value'] = $value;
            $attr_income['value_fund'] = 0;
        }        
        IncomeTransaction::create($attr_income);
        
        $property->save();
    
    }
    
    private function verify_account_payments($account_pay){
        
        if ($account_pay){
            foreach ($account_pay as $property_id) {
                $value = $this->input->post("input_pay_".$property_id,true);
                if ($value == "" || !is_numeric($value)){
                    echo "<h2>En los tipos de pago a cuenta se debe ingresar un valor numerico</h2>";
                    die();
                }
            }
        }
        
    }
    
    public function pay_expenses(){
       
        $total_pay = $this->input->post("total_pay");
        $account_pay = $this->input->post("account_pay");
       
        $this->verify_account_payments($account_pay);
        
        if ($total_pay || $account_pay){
            
            if ($total_pay && $account_pay){
            
                $duplicated = false;
                foreach($total_pay as $id_property):

                    if(is_numeric(array_search($id_property, $account_pay)))
                        $duplicated = true;
                endforeach;
                
                if (!$duplicated){
                
                    foreach ($total_pay as $id_property):                        
                        $this->pay_total_expense($id_property);
                    endforeach;                    

                    foreach ($account_pay as $id_property):                        
                        $this->pay_account_expense($id_property);
                    endforeach;
                    
                    echo "ok";
                }
                else
                    echo '<h2>No se puede realizar el pago debido a que selecciono mas de un pago para alguna propiedad</h2>';
                
            }          
            else{                
                if ($total_pay){
                    foreach ($total_pay as $id_property) {
                        $this->pay_total_expense($id_property);
                    }
                }
                
                if ($account_pay){
                    foreach ($account_pay as $id_property) {
                        $this->pay_account_expense($id_property);
                    }
                }
                
                echo "ok";
            }            
        }
        else{
            echo '<h2>No se ha seleccionado ningun pago</h2>';
        }
        
    }     
    
    function add_extraordinary_period(){
         
        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run('add_extraordinary_period') == FALSE){
            echo validation_errors();
        }
        else{

            $extraordinary_properties = $this->input->post("property_extraordinary_add");
            
            if ($extraordinary_properties){
                
                $id = $this->input->post("building",true);
                $attr['name'] = $this->input->post("name",true);
                $attr['building_id'] = $this->input->post("building",true);
                $attr['tax_percentage'] = $this->input->post("tax_percentage",true);
                $attr['pay_form'] = $this->input->post("pay_form",true);
                $attr['value'] = $this->input->post("value",true);            
                $fees = $this->input->post("fees",true);
                $attr['fees'] = $this->input->post("fees",true);            

                $building = Building::find($id);

                $attr['period_date'] = $building->actual_period->format("Y-m-d");
                $attr['properties_type'] = 3;
                $attr['state'] = 1;
                $attr['date_from'] = date("Y-m-d" ,strtotime ( '+1 month' , strtotime( $building->actual_period->format("Y-m-d"))));
                $attr['date_to'] = date("Y-m-d" ,strtotime ( "+".++$fees." month" , strtotime( $building->actual_period->format("Y-m-d"))));            
                
                $extraordinay_period = ExtraordinaryPeriod::create($attr);
                
                foreach ($extraordinary_properties as $id_property) {
                    
                    $attr_period_prop['property_id'] = $id_property;
                    $attr_period_prop['extraordinary_period_id'] = $extraordinay_period->id;
                    $attr_period_prop['state'] = 1;
                    
                    ExtraordinaryPeriodProperty::create($attr_period_prop);
                }

                echo "success";
                
            }
            else{
                echo "No ha seleccionado ninguna propiedad";
            }
            
        }
    
    }
        
    public function delete_extraordinary(){
        $id = $this->input->post("id",true);

        if ($id != ""){
            $extra = ExtraordinaryPeriod::find($id);
            if ($extra){
                $extra->delete();                    

                echo "ok";
            }
            else
                echo "<h2>No existe la expensas extraordinaria correspondiente</h2>";
        }
        else
            echo "<h2>No existe la expensas extraordinaria correspondiente</h2>";
    }
    
    public function delete_extra_payment(){
        $id = $this->input->post("id",true);
            
        if ($id != ""){
            $income = ExtraordinaryTransaction::find($id);
            if ($income){
                
                $property = Property::find($income->property_id);
                $period = ExtraordinaryPeriod::find($income->extraordinary_period_id);
                
                if ($property->has_finish_paid_extraordinary($period)){
                    $property->set_extraordinary_unfinished($period);        
                }
                
                $income->property->balance_extraordinary = $income->last_balance;
                $income->property->save();
                $income->delete();                    

                echo "ok";
            }
            else
                echo "<h2>El pago correspondiente no existe</h2>";
        }
        else
            echo "<h2>El pago correspondiente no existe</h2>";
    }
    
    private function pay_total_extra_income($id_property,$id_period_extra){
        
        $property = Property::find($id_property);
        $period = ExtraordinaryPeriod::find($id_period_extra);
        
        $attr_income['value'] = round($property->value_to_pay_extraordinary($period), 0 ,PHP_ROUND_HALF_DOWN);        
        $attr_income['last_balance'] = $property->balance_extraordinary;
        $attr_income['property_id'] = $property->id;
        $attr_income['type_pay'] = COMPLETE_PAY;
        $attr_income['date'] = date('Y-m-d');
        $attr_income['period_date'] = $property->building->actual_period;
        $attr_income['extraordinary_period_id'] = $id_period_extra;


        ExtraordinaryTransaction::create($attr_income);

        $property->balance_extraordinary = 0;        
        $property->save();
        
        if ($property->has_finish_paid_extraordinary($period)){
            $property->set_extraordinary_finished($period);
        }
        
    }
    
    private function pay_account_extra_income($id_property , $id_period_extra){
        
        $property = Property::find($id_property);        
        $period = ExtraordinaryPeriod::find($id_period_extra);
        
        $value = $this->input->post("input_pay_".$id_property,true);
        
        $attr_income['last_balance'] = $property->balance_extraordinary;
        $attr_income['value'] = $value;
        $attr_income['property_id'] = $property->id;
        $attr_income['type_pay'] = ACCOUNT_PAY;
        $attr_income['date'] = date('Y-m-d');
        $attr_income['period_date'] = $property->building->actual_period;
        $attr_income['extraordinary_period_id'] = $id_period_extra;

        ExtraordinaryTransaction::create($attr_income);

        $property->balance_extraordinary = $value - $property->value_to_pay_extraordinary($period);
        $property->save();
    
    }
    
    public function pay_extra_incomes(){    
       
        $total_pay = $this->input->post("total_pay",true);
        $account_pay = $this->input->post("account_pay",true);
        $id_period_extra = $this->input->post("extraordinary_period",true);
       
        $this->verify_account_payments($account_pay);
        
        if ($total_pay || $account_pay){
            
            if ($total_pay && $account_pay){
            
                $duplicated = false;
                foreach($total_pay as $id_property):

                    if(is_numeric(array_search($id_property, $account_pay)))
                        $duplicated = true;
                endforeach;
                
                if (!$duplicated){
                
                    foreach ($total_pay as $id_property):                        
                        $this->pay_total_extra_income($id_property,$id_period_extra);
                    endforeach;                    

                    foreach ($account_pay as $id_property):                        
                        $this->pay_account_extra_income($id_property,$id_period_extra);
                    endforeach;
                    
                    echo "ok";
                }
                else
                    echo '<h2>No se puede realizar el pago debido a que selecciono mas de un pago para alguna propiedad</h2>';
                
            }          
            else{                
                if ($total_pay){
                    foreach ($total_pay as $id_property) {
                        $this->pay_total_extra_income($id_property,$id_period_extra);
                    }
                }
                
                if ($account_pay){
                    foreach ($account_pay as $id_property) {
                        $this->pay_account_extra_income($id_property,$id_period_extra);
                    }
                }
                
                echo "ok";
            }            
        }
        else{
            echo '<h2>No se ha seleccionado ningun pago</h2>';
        }        
    
    }
}

/* End of file building.php */
/* Location: ./application/controllers/ajax/incomes.php */
