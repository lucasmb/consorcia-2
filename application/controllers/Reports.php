<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends Backend {

    
    function __construct()
    {
        parent::__construct();
    }
        
    public function index()
    {     
        $data['buildings'] = Building::find('all');  
        $this->load->view('common/header');
        $this->load->view('report/report_home',$data);            
        $this->load->view('common/footer');

        var_dump($_SERVER);
    }
     
    public function report_expenses_bill()
    {     
        ini_set('memory_limit', '-1');
        
        $id_building = $this->input->post("building_expense",true);
        
        if ($id_building != ""){
        
            $building = Building::find($id_building);
            $data['properties'] = $building->properties;
            $data['building'] = $building;

            if ($building->payment_type <> BUILDING_PAYMENT_TYPE_MANUAL) {
                $this->load->view('reportHTML/expense_bank_bill_single',$data);    
            } else {
                $this->load->view('reportHTML/expense_bill',$data);    
            }
            
        
        }
        /*if ($id_building != ""){
            require_once("/system/helpers/dompdf/dompdf_config.inc.php");
            $this->load->helper(array('dompdf', 'file'));

            $building = Building::find($id_building);
            $data['properties'] = $building->properties;
            
            if ($building->id == 43)
                $html = $this->load->view('report/expense_bill',$data,true);
            else
                $html = $this->load->view('report/expense_bill_TEMP',$data,true);

            $dompdf = new DOMPDF();
            
            $dompdf->load_html(utf8_decode($html));
            $dompdf->set_paper("a4", "landscape" );
            $dompdf->render();
            $dompdf->stream("Recibo-Expensas Calle: ".$building->street." - ".$building->number.".pdf");


        }*/
    }
     
    public function report_expenses_month_building()
    {     
        ini_set('memory_limit', '-1');
        $id_building = $this->input->post("building_summary",true);
        
        if ($id_building != ""){

            $building = Building::find($id_building);
            $data['properties'] = $building->get_properties_sorted_by("floor" , "ASC");
            $data['extraordinary_periods'] = null;
            $data['pay_days'] = $building->actual_pay_days();
            $data['building'] = $building;
            $this->load->view('reportHTML/expenses_summary_building',$data);

        }
        /*if ($id_building != ""){
            require_once("/system/helpers/dompdf/dompdf_config.inc.php");
            $this->load->helper(array('dompdf', 'file'));

            $building = Building::find($id_building);
            $data['properties'] = $building->get_properties_sorted_by("floor" , "ASC");
            //$data['extraordinary_periods'] = $building->get_extraordinary_period_for_pay();
            $data['extraordinary_periods'] = null;
            $data['pay_days'] = $building->actual_pay_days();
            $data['building'] = $building;
            
            //$html = $this->load->view('report/expenses_summary_building',$data,true);
            if ($building->id == 43)
                $html = $this->load->view('report/expenses_summary_building_temp',$data,true);    
            else
                $html = $this->load->view('report/expenses_summary_building',$data,true);
            
            $dompdf = new DOMPDF();
            
            $dompdf->set_paper("a4", "letter" );
            $dompdf->load_html(utf8_decode($html));                
            $dompdf->render();
            $dompdf->stream("Informe-Expensas Calle ".$building->street." - ".$building->number.".pdf");


        }*/
    }
        
    public function report_expenses_summary_building_only_extraordinary()
    {     
        ini_set('memory_limit', '-1');
        $id_building = $this->input->post("building_summary_extraordinary",true);
        
        if ($id_building != ""){

            $building = Building::find($id_building);

            $data['extraordinary_periods'] = $building->get_extraordinary_period_for_pay();
            $data['properties'] = end($data['extraordinary_periods'])->properties_implied();
            $data['pay_days'] = $building->actual_pay_days();
            $data['building'] = $building;

            $this->load->view('reportHTML/expenses_summary_building_only_extraordinary', $data);

        }
        
    }
        
    public function monthly_capitulation(){
        ini_set('memory_limit', '-1'); 
        $id_building = $this->input->post("building_capitulation",true);
        
        if ($id_building != ""){
            require_once("/system/helpers/dompdf/dompdf_config.inc.php");
            $this->load->helper(array('dompdf', 'file'));

            $building = Building::find($id_building);
            $data['building'] = $building;
            $data['aditional_incomes'] = $building->get_last_month_aditional_incomes();
            $data['expenses'] = $building->get_last_month_expenses();
            
            //$data['extraordinary_periods'] = $building->get_extraordinary_period_for_pay();
            $data['extraordinary_periods'] = $building->get_extraordinary_period();
            

            //$html = $this->load->view('report/expenses_summary_building',$data,true);
            $html = $this->load->view('report/monthly_capitulation_building',$data,true);

            $dompdf = new DOMPDF();

            $dompdf->set_paper("a4", "letter" );
            $dompdf->load_html(utf8_decode($html));                
            $dompdf->render();
            $dompdf->stream("Rendición de cuenta - Calle ".$building->street." - ".$building->number.".pdf");
        }
    }
        
    public function monthly_capitulation_only_ordinary(){
        ini_set('memory_limit', '-1'); 
        $id_building = $this->input->post("building_capitulation_ordinary",true);
        
        if ($id_building != ""){
            
            $building = Building::find($id_building);
            $data['building'] = $building;
            $data['special_expenses'] = $building->get_two_month_back_special_expenses();
            $data['aditional_incomes'] = $building->get_last_month_aditional_incomes();
            
            if ($building->type_capitulation_report == 1){
                $data['expenses'] = $building->get_last_month_expenses();
                $this->load->view('reportHTML/monthly_capitulation_only_ordinary',$data);
            }
            elseif ($building->type_capitulation_report == 2) {
                $data['laboral_expenses'] = $building->get_last_laboral_month_expenses();
                $data['non_laboral_expenses'] = $building->get_last_non_laboral_month_expenses();
                $data['estimative_expenses'] = $building->get_last_estimative_expenses();
                $this->load->view('reportHTML/monthly_capitulation_only_ordinary_short_description',$data);
            }
            /*
            require_once("/system/helpers/dompdf/dompdf_config.inc.php");
            $this->load->helper(array('dompdf', 'file'));

            if ($building->type_capitulation_report == 1){
                $data['expenses'] = $building->get_last_month_expenses();
                $html = $this->load->view('report/monthly_capitulation_only_ordinary',$data,true);
            }
            elseif ($building->type_capitulation_report == 2) {
                $data['laboral_expenses'] = $building->get_last_laboral_month_expenses();
                $data['non_laboral_expenses'] = $building->get_last_non_laboral_month_expenses();
                $html = $this->load->view('report/monthly_capitulation_only_ordinary_short_description',$data,true);
            }

            $dompdf = new DOMPDF();

            $dompdf->set_paper("a4", "letter" );
            $dompdf->load_html(utf8_decode($html));                
            $dompdf->render();
            $dompdf->stream("Rendición de cuenta - Calle ".$building->street." - ".$building->number.".pdf");*/
        }
    }
        
    public function monthly_capitulation_only_extraordinary(){
        ini_set('memory_limit', '-1'); 
        $id_building = $this->input->post("building_capitulation_extraordinary",true);
        
        if ($id_building != ""){/*
            require_once("/system/helpers/dompdf/dompdf_config.inc.php");
            $this->load->helper(array('dompdf', 'file'));

            $building = Building::find($id_building);
            $data['building'] = $building;
            $data['aditional_incomes'] = $building->get_last_month_aditional_incomes();
            $data['expenses'] = $building->get_last_month_expenses();
            
            //$data['extraordinary_periods'] = $building->get_extraordinary_period_for_pay();
            $data['extraordinary_periods'] = $building->get_extraordinary_period();
            

            //$html = $this->load->view('report/expenses_summary_building',$data,true);
            $html = $this->load->view('report/monthly_capitulation_only_extraordinary',$data,true);

            $dompdf = new DOMPDF();

            $dompdf->set_paper("a4", "letter" );
            $dompdf->load_html(utf8_decode($html));                
            $dompdf->render();
            $dompdf->stream("Rendición de cuenta - Calle ".$building->street." - ".$building->number.".pdf");*/
            
            $building = Building::find($id_building);
            $data['building'] = $building;
            $data['aditional_incomes'] = $building->get_last_month_aditional_incomes();
            $data['expenses'] = $building->get_last_month_expenses();
            
            //$data['extraordinary_periods'] = $building->get_extraordinary_period_for_pay();
            $data['extraordinary_periods'] = $building->get_actives_extraordinaries_period();
            
            if ($building->type_capitulation_report == 1){
                $data['expenses'] = $building->get_last_month_expenses();
                $this->load->view('reportHTML/monthly_capitulation_only_extraordinary',$data);
            }
            elseif ($building->type_capitulation_report == 2) {
                $data['laboral_expenses'] = $building->get_last_laboral_month_expenses();
                $data['non_laboral_expenses'] = $building->get_last_non_laboral_month_expenses();
                $this->load->view('reportHTML/monthly_capitulation_only_extraordinary_short_description',$data);
            }
        }
    }
        
    public function report_extraordinary_expenses_bill()
    {     
        ini_set('memory_limit', '-1');
        $id_building = $this->input->post("building_expense_extra",true);
        
        if ($id_building != ""){
            
            $building = Building::find($id_building);
            $data['building'] = $building;
            //$data['properties'] = $building->properties;
            //$data['extraordinary_periods'] = $building->get_extraordinary_period_for_pay();
            $data['extraordinary_periods'] = $building->get_actives_extraordinaries_period();
            
            if ( count($data['extraordinary_periods']) > 0 ){

                if ($building->payment_type <> BUILDING_PAYMENT_TYPE_MANUAL) {
                    $this->load->view('reportHTML/expense_bank_extraordinary_bill',$data);
                } else {
                    $this->load->view('reportHTML/expense_extraordinary_bill',$data);
                }
                
            }
            else
                echo "El edificio seleccionado no tiene expensas extraordinarias a cobrar";
            
            /*require_once("/system/helpers/dompdf/dompdf_config.inc.php");
            $this->load->helper(array('dompdf', 'file'));

            $building = Building::find($id_building);
            $data['properties'] = $building->properties;
            $data['extraordinary_periods'] = $building->get_extraordinary_period_for_pay();
            
            if ( count($data['extraordinary_periods']) > 0 ){

                $html = $this->load->view('report/expense_extraordinary_bill',$data,true);

                $dompdf = new DOMPDF();

                $dompdf->load_html(utf8_decode($html));
                $dompdf->set_paper("a4", "landscape" );
                $dompdf->render();
                $dompdf->stream("Recibo-Expensas Extraordinaria Calle: ".$building->street." - ".$building->number.".pdf");
            }
            else
                echo "El edificio seleccionado no tiene expensas extraordinarias a cobrar";
*/
        }
    }
        
    public function report_expenses_payment_bill()
    {     
        ini_set('memory_limit', '-1');
        $id_building = $this->input->post("building_expense",true);
        
        if ($id_building != ""){
            require_once("/system/helpers/dompdf/dompdf_config.inc.php");
            $this->load->helper(array('dompdf', 'file'));

            $building = Building::find($id_building);
            $data['building'] = $building;
            $data['unpaid_properties'] = $building->unpaid_current_properties();  
            
            $html = $this->load->view('report/expense_payment_bill',$data,true);

            $dompdf = new DOMPDF();
            
            $dompdf->load_html(utf8_decode($html));
            $dompdf->set_paper("a4", "landscape" );
            $dompdf->render();
            $dompdf->stream("Planilla Cobro Expensas Calle: ".$building->street." - ".$building->number.".pdf");


        }
    }
    
        
    public function report_extraordinary_expenses_payment_bill()
    {     
        ini_set('memory_limit', '-1');
        $id_building = $this->input->post("building_expense_extra",true);
        
        if ($id_building != ""){
            require_once("/system/helpers/dompdf/dompdf_config.inc.php");
            $this->load->helper(array('dompdf', 'file'));

            $building = Building::find($id_building);
            $data['properties'] = $building->properties;
            $data['building'] = $building;
            $data['periods_for_pay'] = $building->get_extraordinary_period_for_pay();
            
            if ( count($data['periods_for_pay']) > 0 ){
            
                $html = $this->load->view('report/expense_extraordinary_payment_bill',$data,true);

                $dompdf = new DOMPDF();

                $dompdf->load_html(utf8_decode($html));
                $dompdf->set_paper("a4", "landscape" );
                $dompdf->render();
                $dompdf->stream("Planilla Cobro Expensas Extraordinaria Calle: ".$building->street." - ".$building->number.".pdf");
            }
            else
                echo "El edificio seleccionado no tiene expensas extraordinarias a cobrar";

        }
    }
        
        
    public function blank_expense(){     

        
        $property_id = $this->input->post("properties_blank_select",true);
        $bill_description = $this->input->post("bill_description",true);
        $bill_concept = $this->input->post("bill_concept",true);
        $bill_value = $this->input->post("bill_value",true);
        
        if ($property_id != ""){
            
            $property = Property::find($property_id);
            $data['property'] = $property;
            $data['building'] = $property->building;
            $data['bill_description'] = $bill_description;
            $data['bill_concept'] = $bill_concept;
            $data['bill_value'] = $bill_value;
            $this->load->view('reportHTML/expense_blank_bill',$data);
        
        }
        /*require_once("/system/helpers/dompdf/dompdf_config.inc.php");
        $this->load->helper(array('dompdf', 'file'));

        
        $html = $this->load->view('report/expense_blank_payment_bill',null,true);

        $dompdf = new DOMPDF();

        $dompdf->load_html(utf8_decode($html));
        $dompdf->set_paper("a4", "landscape" );
        $dompdf->render();
        $dompdf->stream("Planilla en Blanco de Expensas.pdf");*/

    }
    
    public function report_extraordinary_and_ordinary_expense_bill()
    {     
        ini_set('memory_limit', '-1');
        $id_building = $this->input->post("building_expense_extraordinary_and_ordinary",true);
        
        if ($id_building != ""){
            require_once("/system/helpers/dompdf/dompdf_config.inc.php");
            $this->load->helper(array('dompdf', 'file'));

            $building = Building::find($id_building);
            $data['properties'] = $building->properties;
            $data['building'] = $building;
            $data['extraordinary_periods'] = $building->get_extraordinary_period_for_pay();
            
            
            $html = $this->load->view('report/expense_extraordinary_and_ordinary_bill',$data,true);

            $dompdf = new DOMPDF();

            $dompdf->load_html(utf8_decode($html));
            $dompdf->set_paper("a4", "landscape" );
            $dompdf->render();
            $dompdf->stream("Recibo-Expensas Ordinarias con Extraordinaria incluidas Calle: ".$building->street." - ".$building->number.".pdf");

        }
    }
 
    public function test_report()
    {     
            
            ini_set('memory_limit', '-1');
                        
            $building = Building::find(37);
            
            $data['building'] = $building;
            $data['aditional_incomes'] = $building->get_last_month_aditional_incomes();

            if ($building->type_capitulation_report == 1){
                $data['expenses'] = $building->get_last_month_expenses();
                $this->load->view('reportHTML/monthly_capitulation_only_ordinary',$data);
            }
            elseif ($building->type_capitulation_report == 2) {
                $data['laboral_expenses'] = $building->get_last_laboral_month_expenses();
                $data['non_laboral_expenses'] = $building->get_last_non_laboral_month_expenses();
                $this->load->view('reportHTML/monthly_capitulation_only_ordinary_short_description',$data);
            }
            
    }
        
    public function report_short_monthly_balance_capitulation(){
        
        ini_set('memory_limit', '-1');
                    
        $building_id = $this->input->post("building_balance_select",true);
        $start_period = $this->input->post("month_building_from",true);
        $end_period = $this->input->post("month_building_to",true);
        
        if ($building_id != "" && $start_period != "" && $end_period != ""){
            
            $building = Building::find($building_id);
            
            $data['expense_laboral_balance_tags'] = $building->get_expense_balance_tags_for_periods_laboral($start_period,$end_period);
            $data['expense_laboral_others_tags'] = $building->get_expense_other_tags_for_periods_laboral($start_period,$end_period);
            
            $data['expense_non_laboral_balance_tags'] = $building->get_expense_balance_tags_for_periods_non_laboral($start_period,$end_period);
            $data['expense_non_laboral_others_tags'] = $building->get_expense_other_tags_for_periods_non_laboral($start_period,$end_period);
            
            $data['income_balance_tags'] = $building->get_aditional_income_tags_for_periods($start_period,$end_period);
            $data['other_incomes'] = $building->get_others_aditional_income_tags_for_periods($start_period,$end_period);
            
            $data['periods'] = $building->get_building_transactions_for_periods($start_period,$end_period);
        
            $data['building'] = $building;
            
            $this->load->view('reportHTML/short_monthly_balance_capitulation',$data);
        }
        
    }
    
    public function schedule_sheet()
    {
        
        $date = new DateTime($this->input->post("date_schedule_sheet",true));

        $options = array('is_active' => 1, 'payment_type' => 0);
        $buildings = Building::all($options);
        $data['buildings'] = $buildings;
        $data['date_time'] = $date;

        $this->load->view('reportHTML/schedule_sheet',$data);

    }

    public function bank_payment_sheet()
    {
        
        $options = array('is_active' => 1, 'payment_type' => 3);
        $buildings = Building::all($options);
        $data['buildings'] = $buildings;
        
        $this->load->view('reportHTML/bank_payment_sheet',$data);

    }
        
    public function send_emails()
    {     

        $id_building = 37;
        
        if ($id_building != ""){
            
            $building = Building::find($id_building);
            $data['building'] = $building;
            $data['aditional_incomes'] = $building->get_last_month_aditional_incomes();
            
            if ($building->type_capitulation_report == 1){
                $data['expenses'] = $building->get_last_month_expenses();
                $view = $this->load->view('reportHTML/monthly_capitulation_only_ordinary',$data,true);


                /*
                
                $config = Array(
                    'protocol' => 'smtp',
                    'smtp_host' => 'ssl://smtp.googlemail.com',
                    'smtp_port' => 465,
                    'smtp_user' => 'highsagan@gmail.com', // change it to yours
                    'smtp_pass' => 'Saganp455', // change it to yours
                    'mailtype' => 'html',
                    'charset' => 'iso-8859-1',
                    'wordwrap' => TRUE
                );
                
                $this->load->library('email', $config);

                $this->email->set_newline("\r\n");

                $this->email->from('highsagan@gmail.com');
                $this->email->to('lucasborio@gmail.com');
                
                $this->email->subject('Resumen expensas');
                $this->email->message("prueba para ver si llegan los mails");   

                if($this->email->send()){
                    echo 'Email sent.';
                } else {
                    show_error($this->email->print_debugger());
                }

                */

                $this->load->library('email');

                $this->email->from('highsagan@gmail.com', 'Your Name');
                $this->email->to('lucasborio@gmail.com');


                $this->email->subject('Email Test');
                $this->email->message('Testing the email class.');

                if($this->email->send()){
                    echo 'Email sent.';
                } else {
                    show_error($this->email->print_debugger());
                }



                
            }
        }

    }
}

/* End of file building.php */
/* Location: ./application/controllers/reports.php */