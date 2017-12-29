<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once APPPATH.'libraries/RoelaBank.php';
include_once APPPATH.'libraries/PHPExcel.php';
include_once APPPATH.'libraries/PHPExcel/IOFactory.php';

class BankPayment extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }
	
	public function upload_roela_file_bank_payments()
	{

	    if ($_FILES['file_to_upload']['size'] > 0) {

	    	$fp = fopen($_FILES['file_to_upload']['tmp_name'], 'rb');

	    	$building = array();

    		while ( ($line = fgets($fp)) !== false) {

var_dump($line);
                $line = $this->remove_BOM($line);
                var_dump('removed:' . trim($line) );
                //$line = trim($line);
                if (strlen($line) > 122) {
                    $record = $this->create_record_object_alternative($line);
                } else {
                    $record = $this->create_record_object_standard($line);
                }
                
                $building[$record->building_id][$record->operation_type_id][] = $record;

    		}

    		$data['building'] = $building;
    		$body = $this->load->view('/ajax/bankPayment/roela_bank_payments_from_file', $data, true);
            
            if(strlen($body) == 0)
                echo "nok";
            else
                echo $body;

	    } else {
	    	echo "No se ha seleccionado un archivo o bien el archivo seleccionado no es valido";
	    }

	}

    private function remove_BOM($str="") {
        if(substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf)) {
                $str=substr($str, 3);
        }
        return $str;
    }


    private function create_record_object_standard($line) {
        $record = new stdClass;
        $record->payment_date = strtodate(substr($line, 0, 8), "Ymd");
        $record->account_payment_date = strtotimestamp(substr($line, 8, 8), "Ymd");
        $record->due_date = strtotimestamp(substr($line, 16, 8), "Ymd");
        $record->payment_ammount = sprintf('%0.2f', substr($line, 24, 5) . '.' . substr($line, 29, 2));
        $record->building_id = substr($line, 31, 4);
        $record->property_bank_id = (int)substr($line, 35, 4);
        $record->operation_type_id = (int)substr($line, 39, 1);
        $record->codebar = substr($line, 40, 56);
        $record->bank_payment_id = substr($line, 0, 96);

var_dump($record);
var_dump(mb_substr($line, 0, 96));
        $record_payment_id = addslashes($record->bank_payment_id);

        $record->property = Property::find_by_sql("SELECT p.*
                                                     FROM properties p 
                                                    WHERE p.building_id = $record->building_id
                                                      AND p.bank_payment_id = $record_payment_id")[0];

        return $record;

    }
    
    private function create_record_object_alternative($line) {
vd('alternative');
        $record = new stdClass;
        $record->building_id = (int)substr($line, 31, 4);
        $record->property_bank_id = (int)substr($line, 35, 4);
        $record->operation_type_id = (int)substr($line, 39, 1);
        $record->payment_date = strtodate(substr($line, 0, 8), "Ymd");
        $record->account_payment_date = strtotimestamp(substr($line, 8, 8), "Ymd");
        $record->due_date = strtotimestamp(substr($line, 16, 8), "Ymd");
        $record->payment_ammount = sprintf('%0.2f', substr($line, 24, 5) . '.' . substr($line, 29, 2));
        $record->codebar = substr($line, 40, 56);
        $record->bill_number = substr($line, 96, 20);
        $record->payment_method = substr($line, 116, 3);
        $record->bank_payment_id = substr($line, 0, 159);

var_dump($record->bank_payment_id);
        $record->property = Property::find_by_sql("SELECT p.*
                                                     FROM properties p 
                                                    WHERE p.building_id = $record->building_id
                                                      AND p.bank_payment_id = $record->property_bank_id")[0];

        return $record;

    }

    public function make_roela_bank_payment() {

        $building_id = $this->input->post("building_id");
        $operation_type_id = $this->input->post("operation_type_id");
        $building = Building::find($building_id);

        if ($building && !is_null($operation_type_id)) {

            if (is_extraordinary_operation($operation_type_id)) {
                $this->make_roela_bank_extraordinary_payment($building, $operation_type_id);
            } else {
                $this->make_roela_bank_ordinary_payment($building, $operation_type_id);
            }
            
        }
        
    }

    private function make_roela_bank_extraordinary_payment($building, $operation_type_id) {
        $this->db->trans_begin();
        foreach ($this->input->post("bank_property_paid") as $property_id) {
    
            $property = Property::find($property_id);
            
            if (!is_null($property)) {
                
                $ammount_paid = $this->input->post("ammount_paid_".$property_id);
                $extraordinary_period_id = $this->input->post("extraordinary_period_id_".$property_id);

                $period = ExtraordinaryPeriod::find($extraordinary_period_id);

                $attr_income['last_balance'] = $property->balance_extraordinary;
                $attr_income['period_date'] = $building->actual_period->format("Y-m-d");
                $attr_income['value'] = $ammount_paid;
                $attr_income['date'] = $this->input->post("paid_date_".$property_id);
                $attr_income['type_pay'] = $property->payment_type_for_ammount($ammount_paid, $operation_type_id);
                $attr_income['bank_payment_id'] = $this->input->post("bank_payment_id_".$property_id);
                $attr_income['property_id'] = $property->id;
                $attr_income['extraordinary_period_id'] = $extraordinary_period_id;
                
                ExtraordinaryTransaction::create($attr_income);

                // Update Property Balance 
                $this->update_extraordinary_property_balance($property, $attr_income, $ammount_paid, $period);

            }
            
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    private function update_extraordinary_property_balance($property, $attr_income, $ammount_paid, $period){

        $property->balance_extraordinary = round($property->total_to_pay_all_extraordinaries(), 2) - $ammount_paid;        
        $property->save();

        if ($property->has_finish_paid_extraordinary($period)){
            $property->set_extraordinary_finished($period);
        }
        
    }

    private function make_roela_bank_ordinary_payment($building, $operation_type_id) {
        
        $this->db->trans_begin();
        foreach ($this->input->post("bank_property_paid") as $property_id) {
    
            $property = Property::find($property_id);
            
            if (!is_null($property)) {
                
                $ammount_paid = $this->input->post("ammount_paid_".$property_id);

                $attr_income['last_balance'] = $property->balance;
                $attr_income['last_balance_reserve'] = $property->balance_reserve;  
                $attr_income['period_date'] = $building->actual_period->format("Y-m-d");
                $attr_income['date'] = $this->input->post("paid_date_".$property_id);
                $attr_income['type_pay_date'] = $property->payment_type_for_ammount($ammount_paid, $operation_type_id);
                $attr_income['bank_payment_id'] = $this->input->post("bank_payment_id_".$property_id);
                $attr_income['property_id'] = $property_id;

                if ($attr_income['type_pay_date'] == FIRST_PAY) {
                    $attr_income = $this->add_values_for_total_pay($property, $attr_income, $ammount_paid);
                } else {
                    $attr_income = $this->add_values_for_account_pay($property, $attr_income, $ammount_paid);
                }

                
                IncomeTransaction::create($attr_income);

                // Update Property Balance 
                $this->update_property_balance($property, $attr_income, $ammount_paid);

            }
            
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

    }

    private function add_values_for_total_pay($property, $attr_income, $ammount_paid) {
        
        if ($property->building->has_reserve_fund) {
            $value_paid_round = round($property->total_to_pay_expense() + $property->total_to_pay_fund() , 2);
            // Si tiene fondo de reserve por propiedad agregar por un lado el valor de la expensa menos ese valor
            // y por otro lado el fondo de reserva
            if ($property->building->put_reserve_value_manually){

                $attr_income['value_fund'] = $property->total_to_pay_fund();
                $attr_income['value'] = $value_paid_round - $property->total_to_pay_fund();

            } else {

                if($property->building->earn_static_fund) {
                    $attr_income['value'] = round($value_paid_round - ($property->building->earn_static_fund_value * $property->coefficient), 2);
                    $attr_income['value_fund'] = $value_paid_round - $attr_income['value'];
                } else {
                    $attr_income['value'] = round($value_paid_round * (100 - $property->building->earn_percentage_value) / 100, 2);
                    $attr_income['value_fund'] = round($value_paid_round * $property->building->earn_percentage_value / 100, 2);
                }

            }
        
        } else {

            $attr_income['value'] = round($property->total_to_pay_expense() , 2);
            $attr_income['value_fund'] = 0;

        }

        return $attr_income;

    }

    private function add_values_for_account_pay($property, $attr_income, $ammount_paid) {
        
        if ($property->building->has_reserve_fund){
         
            if ($ammount_paid > $property->total_to_pay_fund()){
                // Si con el monto pagado puede pagar todo el fondo de reserva 
                // se pasa como pago el fondo de reserva y el resto se pasa al balance
                $value = $ammount_paid - round($property->total_to_pay_fund(), 2);
                $property->balance_reserve = 0;
                $property->balance = $value - $property->total_to_pay_expense();
                
                $attr_income['value'] = $value;
                $attr_income['value_fund'] = round($property->total_to_pay_fund(), 2);
                
            } else {

                $percentage_balance_reserve = $ammount_paid * $property->building->reserve_percentage / 100;
                $percentage_balance = $ammount_paid - $percentage_balance_reserve;        
                $property->balance = $percentage_balance - $property->total_to_pay_expense();
                $property->balance_reserve = $percentage_balance_reserve - $property->total_to_pay_fund();
                
                $attr_income['value'] = $percentage_balance;
                $attr_income['value_fund'] = $percentage_balance_reserve;
                
            }
        
        } else {

            $property->balance = $ammount_paid - $property->total_to_pay_expense();            
            $attr_income['value'] = $ammount_paid;
            $attr_income['value_fund'] = 0;

        }

        return $attr_income;
        
        // $property->save();

    }

    private function update_property_balance($property, $attr_income, $ammount_paid) {

        if ($attr_income['type_pay_date'] == FIRST_PAY) {

            $property->balance = 0;
            if ($property->building->has_reserve_fund) {
                $property->balance_reserve = 0;
            }

        } else {

            if ($property->building->has_reserve_fund) {

                if ($ammount_paid > $property->total_to_pay_fund()){
                    $property->balance_reserve = 0;
                    $property->balance = round($ammount_paid - $property->total_to_pay_expense(), 2);
                } else {
                    $percentage_balance_reserve = $ammount_paid * $property->building->reserve_percentage / 100;
                    $percentage_balance = $ammount_paid - $percentage_balance_reserve;        
                    $property->balance = round($percentage_balance - $property->total_to_pay_expense(), 2);
                    $property->balance_reserve = round($percentage_balance_reserve - $property->total_to_pay_fund(), 2);
                }

            } else {
               $property->balance = $ammount_paid - $property->total_to_pay_expense();             
            }

        }

        $property->save();        

    }

    public function upload_roela_file_bank_expenses() {
        
        if ($_FILES['file_to_upload_expenses']['size'] > 0) {

            // Read your Excel workbook
            $objPHPExcel = $this->instance_php_excel_object($_FILES['file_to_upload_expenses']['tmp_name']);

            //  Get worksheet dimensions
            $sheet = $objPHPExcel->getSheet(0); 
            $highestRow = $sheet->getHighestRow(); 
            $highestColumn = $sheet->getHighestColumn();

            $last_account_identifier = 0;
            $sum = 0;
            $expenses = array();

            //  Loop through each row of the worksheet in turn
            for ($row = 1; $row <= $highestRow; $row++) { 
                
                //  Read a row of data into an array
                $date = $this->get_date_from($sheet, $row);
                $account = $this->get_account_from($sheet, $row);
                $concept = $this->get_concept_id_from($sheet, $row);
                $description = $this->get_concept_description_from($sheet, $row);
                $ammount = $this->get_ammount_from($sheet, $row);

                if (!empty($account) && ($account != 0)  && !empty($concept) && !empty($ammount)) {

                    if ($last_account_identifier == $account) {
                        // Mismo edificio - se tiene que acumular los gastos
                        if ((floatval($ammount) < 0) && ($concept != '191')) {
                            $sum = $sum + floatval($ammount);
                        }

                    } else {
                        
                        if ((!empty($last_account_identifier)) && ($last_account_identifier != 0)) {
                            // Datos para usar 
                            $expenses[] = $this->get_expense_object($last_account_identifier, $date, $sum);
                        }
                        // Cambio de edificio - nuevos datos a acumular
                        $last_account_identifier = $account;
                        $sum = 0;
                        
                    }

                } 
                //  Insert row data array into your database of choice here
            }
            
            $data['expenses'] = $expenses;
            $body = $this->load->view('/ajax/bankPayment/roela_bank_expenses_from_excel', $data, true);
            
            if(strlen($body) == 0)
                echo "nok";
            else
                echo $body;

        } else {
            echo "No se ha seleccionado un archivo o bien el archivo seleccionado no es valido";
        }

    }

    private function instance_php_excel_object($filename) {
        try {
            $inputFileName = $filename;
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
            return $objPHPExcel;
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
    }

    private function get_date_from($sheet, $row) {

        $cell = $sheet->getCell(RB_PHPEXCEL_DATE_COLUMN . $row);
        $InvDate= $cell->getValue();
        if (PHPExcel_Shared_Date::isDateTime($cell)) {
            return date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($InvDate)); 
        } else {
            return "";
        }

    }

    private function get_account_from($sheet, $row) {

        $cell = $sheet->getCell(RB_PHPEXCEL_ACCOUNT_COLUMN . $row);
        return intval(str_replace("/", "", $cell->getValue()));

    }

    private function get_concept_id_from($sheet, $row) {

        $cell = $sheet->getCell(RB_PHPEXCEL_CONCEPT_ID_COLUMN . $row);
        return $cell->getValue();

    }

    private function get_concept_description_from($sheet, $row) {

        $cell = $sheet->getCell(RB_PHPEXCEL_CONCEPT_DESCRIPTION_COLUMN . $row);
        return $cell->getValue();

    }

    private function get_ammount_from($sheet, $row) {

        $cell = $sheet->getCell(RB_PHPEXCEL_AMMOUNT_COLUMN . $row);
        return $cell->getValue();

    }

    private function get_expense_object($account_identifier, $date, $ammount) {

        $building = Building::find_by_account_number($account_identifier);

        if ($building) {
            $expense_object = new stdClass;
            // Cambiar el name por el building
            $expense_object->building = $building;
            $expense_object->ammount = $ammount;
            $expense_object->value = abs($ammount);
            $expense_object->description = "SERVICIO RECAUDACION SIRO";
            $expense_object->expense_type = 6;
            $expense_object->period_date = $building->actual_period->format('Y-m-d');
            // Cambiar las fechas para consumir la fecha real
            $date_object = date_create_from_format('Y-m-d', $date);
            $expense_object->date = date_to_monthname($date_object) . '/' . $date_object->format('y');
            $expense_object->similar_expense = ExpenseTransaction::similar_expense($expense_object);

            return $expense_object;
        } else {
            return nil;
        }

    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */