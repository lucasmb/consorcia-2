<?php

define("BUILDING_PAYMENT_TYPE_MANUAL", 0);
define("BUILDING_PAYMENT_TYPE_BANK_BARCODE", 1);
define("BUILDING_PAYMENT_TYPE_BANK_DIGITAL", 2);
define("BUILDING_PAYMENT_TYPE_BANK", 3);

class Building extends ActiveRecord\Model{
    static $belongs_to = array(
        array('city', 'readonly' => true),
        array('type_expense_period', 'class_name' => 'TypeExpensePeriod', 'foreign_key' => 'type_expense_period_id')
    );
    
    static $has_many = array(
        array('properties', 'readonly' => true),
        array('aditional_incomes',  'readonly' => true),
        array('building_transactions',  'readonly' => true),        
        array('expense_transactions', 'order' => 'type_expense_id asc , priority desc', 'readonly' => true),
        array('special_expense_transactions', 'order' => 'type_special_expense_id asc , priority desc', 'readonly' => true),
        array('estimative_expense_transactions', 'order' => 'type_expense_id asc , priority desc', 'readonly' => true),
        array('extraordinary_expenses', 'readonly' => true),
        array('extraordinary_periods',  'readonly' => true)
    );
    
    public static function find_by_account_number($account_number) {
        $building = Building::find_by_sql("SELECT b.*
                                           FROM buildings b 
                                          WHERE SUBSTRING(b.bank_account_number,6,5) = '$account_number'");

        if (count($building) > 0) {
            return $building[0];
        } else{
            return false;
        }
    }

    public function get_properties_sorted_by($field , $order){
        switch ($field) {
            case "floor":
                $properties = Property::find_by_sql("SELECT * 
                                                       FROM properties 
                                                      WHERE building_id = '$this->id'
                                                   ORDER BY CASE 
                                                            WHEN floor = 'PB'       THEN 1
                                                            WHEN floor = 'COCH'     THEN 3
                                                            WHEN floor = 'COCHERA'  THEN 3
                                                                                    ELSE 2                        
                                                        END $order , CAST(FLOOR AS UNSIGNED) $order , CAST(appartment AS SIGNED) $order, appartment $order, functional_unity $order");

                break;
            
            case "owner":
                $properties = Property::find_by_sql("SELECT p.*
                                                       FROM properties p INNER JOIN people o on (o.id = p.owner_id)
                                                      WHERE p.building_id = $this->id 
                                                   ORDER BY o.lastname $order, o.name $order");

                break; 

            default:
                $properties = Property::find_by_sql("SELECT *
                                                       FROM properties 
                                                      WHERE building_id = $this->id
                                                   ORDER BY $field $order");
                
                break;
        }
        return $properties;
    }

    public function pay_by_bank(){
        return ($this->payment_type > BUILDING_PAYMENT_TYPE_MANUAL);
    }
       
    public function expense_month_period(){
        
        $this->type_expense_period->update_month_name();
        return $this->type_expense_period->month_name;
    }
    
    public function month_name_actual_period(){
        $meses = array("Enero", "Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = intval(date( 'm' , strtotime($this->actual_period->format("Y-m-d")) ));
        return $meses[$mes - 1];
    }
    
    public function month_name_next_period(){
        $meses = array("Enero", "Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = intval(date( 'm' , strtotime($this->actual_period->format("Y-m-d")) ));
        if ($this->type_expense_period_id == 1)
            return $meses[$mes - 1];
        else
            return $mes;
            return $meses[$mes];
        
    }
    
    public function date_next_period(){
        
        if ($this->type_expense_period_id == 1)
            return $this->actual_period->format("Y-m-d");
        else
            return date('Y-m-d', strtotime("+1 months", strtotime($this->actual_period->format("Y-m-d"))));
        
    }
    
    public function month_name_from_current_period($difference) {
        $meses = array("Enero", "Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        $mes = intval(date( 'm' , strtotime($this->actual_period->format("Y-m-d")) ));
        return $meses[$mes + $difference];
    } 

    public function month_name_last_month(){
        $meses = array("Enero", "Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = intval(date( 'm' , strtotime($this->one_month_back()) ));
        return $meses[$mes - 1];
    }
    
    public function total_month_to_gain($formated = true){
        $sum = 0;
        foreach ($this->properties as $property){
            $sum = $sum + round($property->total_to_pay(), 0 ,PHP_ROUND_HALF_DOWN);
        }
        if ($formated)
            return number_format($sum,2);
        else
            return $sum;
    }
    
    public function unpaid_current_properties(){
        $properties = array();
        foreach ($this->properties as $property){
            if (!$property->has_paid_current_month()){
                $properties[] = $property;
            }
        }
        return $properties;
    }
    
    public function paid_current_properties(){
        $properties = array();
        foreach ($this->properties as $property){
            if ($property->has_paid_current_month()){
                $properties[] = $property;
            }
        }
        return $properties;
    }    
    
    public function get_current_total_incomes_fund(){
        $total = 0;
        foreach ($this->properties as $property){
            $result = $property->has_paid_current_month("AND ic.type_pay_date <> 'unpaid'");            
            if ($result){
                $total = $total + $result[0]->value_fund;
            }
        }
        return $total;
    }
    
    public function get_current_total_incomes_ordinary(){
        $total = 0;
        foreach ($this->properties as $property){
            $result = $property->has_paid_current_month("AND ic.type_pay_date <> 'unpaid'");            
            if ($result){
                //$total = $total + $result[0]->value;
                $total = $total + $result[0]->value;
            }
        }
        return $total;
    }
    
    public function get_current_total_incomes(){
        $total = 0;
        foreach ($this->properties as $property){
            $result = $property->has_paid_current_month();            
            if ($result){
                $total = $total + $result[0]->value + $result[0]->value_fund;
            }
        }
        return $total;
    }
    
    public function get_current_total_aditional_incomes(){
        $total = 0;
        foreach ($this->aditional_incomes as $aditional){            
            if ($aditional->period_date == $this->actual_period){
                $total = $total + $aditional->value;
            }            
        }
        return $total;
    }
    
    public function get_current_total_extraordinary_incomes(){
        
        /*$et = ExtraordinaryTransaction::find_by_sql("SELECT IFNULL (SUM(et.`value`),0.0) AS ammount
                                                       FROM extraordinary_transactions et
                                                      WHERE et.property_id IN (SELECT p.id FROM properties p WHERE p.building_id = ".$this->id.")
                                                        AND et.period_date = '".$this->actual_period->format('Y-m-d')."'
                                                        AND et.type_pay <> 'unpaid' ");
        
        return (float)$et[0]->ammount;*/
        
        
        $total = 0;
        $periods = $this->get_actives_or_with_activity_extraordinaries_period();
        foreach ($periods as $period):
            foreach ($this->properties as $property){
                $income = $property->get_current_extra_pay_month($period);
                if (($income) && ($income->type_pay != "unpaid")){
                    $total = $total + $income->value;
                }            
            }
        endforeach;
        return $total;
    }
    
    public function get_current_total_extraordinary_expenses(){
        
        $ee = ExtraordinaryExpense::find_by_sql("SELECT IFNULL (SUM(ee.`value`),0.0) AS ammount
                                                   FROM extraordinary_expenses ee
                                                  WHERE ee.building_id = ".$this->id."
                                                    AND ee.period_date = '".$this->actual_period->format('Y-m-d')."' ");
        
        return (float)$ee[0]->ammount;
        /*$total = 0;
        foreach ($this->extraordinary_expenses as $extra_expense):
            if ($extra_expense->period_date == $this->actual_period)
                $total = $total + $extra_expense->value;
        endforeach;
        return $total;*/
    }
    
    public function unpaid_one_month_before(){
        $properties = array();
        foreach ($this->properties as $property){
            if ($property->has_unpaid_before_month()){
                $properties[] = $property;
            }
        }
        return $properties;
    }
    
    public function get_current_expenses(){
        
        $expenses = array();
        foreach ($this->expense_transactions as $expense){            
            if ($expense->period_date == $this->actual_period){
                $expenses[] = $expense;
            }
        }
        return $expenses;
        
    }  

    public function get_current_special_expenses(){
        
        $expenses = array();
        foreach ($this->special_expense_transactions as $expense){            
            if ($expense->period_date == $this->actual_period){
                $expenses[] = $expense;
            }
        }
        return $expenses;
        
    }  

    public function get_current_estimative_expenses(){
        
        $expenses = array();
        foreach ($this->estimative_expense_transactions as $expense){            
            if ($expense->period_date == $this->actual_period){
                $expenses[] = $expense;
            }
        }
        return $expenses;
        
    }  

    public function get_expenses_for_estimative(){
       return $this->get_current_expenses(); 
    }
    
    public function get_current_total_expenses(){
        
        $total = 0;
        foreach ($this->expense_transactions as $expense){            
            if ($expense->period_date == $this->actual_period){
                $total = $total + $expense->value;
            }
        }

        if (count($this->get_last_month_special_expenses())){
            foreach ($this->get_last_month_special_expenses() as $special_expense){            
                $total = $total + $special_expense->value;
            }
        }

        return $total;
        
    }    
    
    public function update_balance(){
        $attr['last_balance'] = $this->balance;
        $attr['last_balance_extraordinary'] = $this->balance_extraordinary;
        $attr['last_reserve_fund'] = $this->reserve_fund;
        $attr['period_date'] = $this->actual_period;
        $attr['building_id'] = $this->id;
        $attr['date'] = date("Y-m-d");
        
        BuildingTransaction::create($attr);
        
        $total_incomes = $this->get_current_total_incomes_ordinary() + $this->get_current_total_aditional_incomes(); 
        $total_fund = $this->get_current_total_incomes_fund();
        $total_expenses = $this->get_current_total_expenses();
        
        // updating balance ordinario
        $this->balance = $this->balance + $total_incomes - $total_expenses;

        // updating balance del fondo de reserva
        $this->reserve_fund = $this->reserve_fund + $total_fund;
        
        // updating balance extraordinary
        $this->balance_extraordinary = $this->balance_extraordinary + abs($this->get_current_total_extraordinary_incomes()) - abs($this->get_current_total_extraordinary_expenses());
        
        $this->actual_period = date("Y-m-d", strtotime ( '+1 month' , strtotime($this->actual_period->format('Y-m-d'))));
        
    }
    
    public function save_unpaid_extraordinary_properties(){
        
        //$periods = $this->get_extraordinary_period();
        //$periods = $this->get_extraordinary_period_for_pay();
        $periods = $this->get_actives_or_with_activity_extraordinaries_period();
        
        $unpaids = Array();
        foreach($periods as $period):
            
            //foreach ($period->properties_implied() as $property):
            foreach ($period->get_properties_pay_unfinish_properties() as $property):
            
                //if (!$property->has_paid_all_extraordinary($period->id) && !$property->has_paid_current_extraordinary_month($period->id)){
                if (!$property->has_paid_current_extraordinary_month($period->id)){

                    $attr_extra['last_balance'] = $property->balance_extraordinary;
                    $attr_extra['value'] = - $property->value_to_pay_extraordinary($period);
                    $attr_extra['property_id'] = $property->id;
                    $attr_extra['type_pay'] = "unpaid";
                    $attr_extra['date'] = date('Y-m-d');
                    $attr_extra['period_date'] = $this->actual_period;
                    $attr_extra['extraordinary_period_id'] = $period->id;

                    if (is_numeric(array_search($property->id, $unpaids))) {
                        $unpaids[$property->id] += $property->value_to_pay_extraordinary($period);
                    } else {
                        $unpaids[$property->id] = $property->value_to_pay_extraordinary($period);
                    }

                    ExtraordinaryTransaction::create($attr_extra);
                }

            endforeach;

        endforeach;
        
        foreach ($unpaids as $property_id => $new_balance):
            
            $unpaid = Property::find($property_id);
            $unpaid->readonly(false);
            if ($unpaid->balance_extraordinary > 0)
                $unpaid->balance_extraordinary = $unpaid->balance_extraordinary - $new_balance;
            else
                $unpaid->balance_extraordinary =  -$new_balance;
            $unpaid->save();
            
        endforeach;
        
    }
    
    public function save_unpaid_ordinary_properties(){
        $unpaids = $this->unpaid_current_properties();
        foreach($unpaids as $unpaid):

            $attr_income['last_balance'] = $unpaid->balance;
            $attr_income['last_balance_reserve'] = $unpaid->balance_reserve;
            $attr_income['value'] = - $unpaid->total_to_pay_expense();
            $attr_income['value_fund'] = - $unpaid->total_to_pay_fund();
            $attr_income['property_id'] = $unpaid->id;
            $attr_income['type_pay_date'] = "unpaid";
            $attr_income['date'] = date('Y-m-d');
            $attr_income['period_date'] = $this->actual_period;

            IncomeTransaction::create($attr_income);

            $unpaid->readonly(false);
            
            // Se actuliza el balance de expensas ordinarias
            if ($unpaid->balance > 0)
                $unpaid->balance = $unpaid->balance - $unpaid->total_to_pay_expense();
            else
                $unpaid->balance = - $unpaid->total_to_pay_expense();
            
            // Se actuliza el balance de el fondo de reserva
            if($this->has_reserve_fund):
                if ($unpaid->balance_reserve > 0)
                    $unpaid->balance_reserve = $unpaid->balance - $unpaid->total_to_pay_fund();
                else
                    $unpaid->balance_reserve = - $unpaid->total_to_pay_fund();
            endif;
            
            $unpaid->save();

        endforeach;
    }
    
    public function close_period(){
        
        $this->type_expense_period->update_month_name();
        if ($this->actual_period <= $this->type_expense_period->date){
            
            $this->save_unpaid_ordinary_properties();
            $this->save_unpaid_extraordinary_properties();
                        
            $this->update_balance();
            
            $this->save(false);

            $this->add_autogenerated_days();
            
            return true;
        }
        else
            return false;
        
    }

    public function remove_current_extra_unpaid_payments(){
        
        $periods = $this->get_extraordinary_period();
        foreach ($periods as $period):
            foreach ($this->properties as $property):
                $income = $property->has_current_unpaid_extraordinary($period->id);
                if ($income){
                    $property->balance_extraordinary = $income->last_balance;
                    $property->readonly(false);
                    $property->save();

                    $income->readonly(false);
                    $income->delete();
                }
            endforeach;
        endforeach;
        
    }

    public function remove_current_unpaid_payments(){
        
        foreach ($this->properties as $property){
            $income = $property->has_current_unpaid_payments();
            if ($income){
                $property->balance = $income->last_balance;
                $property->balance_reserve = $income->last_balance_reserve;
                $property->readonly(false);
                $property->save();
                
                $income->readonly(false);
                $income->delete();
            }
        }
        
    }
    
    private function get_last_building_transaction(){
        
        $result;
        foreach ($this->building_transactions as $t){            
            if ($t->period_date == $this->actual_period){
                $result = $t;
            }
        }
        return $result;
        
        
    }

    public function add_autogenerated_days()
    {
        $building = $this;

        if ($building->type_expense_period_id == 1){
            $date_month_days = $building->actual_period->format('Y-m-d');
        }
        else if ($building->type_expense_period_id == 2){
            $date_month_days = date('Y-m-d', strtotime('+1 month', strtotime($building->actual_period->format('Y-m-d'))));
        }
        
        $date_month_days = date('Y-m-d', strtotime('-1 day', strtotime($date_month_days)));

        $autogenerated_dates = BuildingAutogeneratedDays::all(array('conditions' => "building_id = " . $building->id));

        $week_days = unserialize(WEEK_DAYS);
        $multiplicities = unserialize(MULTIPLICITY_AUTOGENERATED_DAYS);

        foreach ($autogenerated_dates as $autogenerated_date) {
             
            if ($autogenerated_date->multiplicity == 0){

                $week_day = $week_days[$autogenerated_date->week_day];
                $this->add_building_pay_day($multiplicities[1], $week_day, $autogenerated_date, $date_month_days);
                $this->add_building_pay_day($multiplicities[2], $week_day, $autogenerated_date, $date_month_days);
                $this->add_building_pay_day($multiplicities[3], $week_day, $autogenerated_date, $date_month_days);
                $this->add_building_pay_day($multiplicities[4], $week_day, $autogenerated_date, $date_month_days);

            }
            else{
                
                $multiplicity = $multiplicities[$autogenerated_date->multiplicity];
                $week_day = $week_days[$autogenerated_date->week_day];

                $this->add_building_pay_day($multiplicity, $week_day, $autogenerated_date, $date_month_days);

            }

        }

    }

    public function add_building_pay_day($multiplicity, $week_day, $autogenerated_date, $date_month_days){

        $datetime_month_days = new DateTime($date_month_days);

        $date = $datetime_month_days->modify("" .$multiplicity ." ". $week_day ."")->format('Y-m-d');

        if (date('d', strtotime($date)) < 21){
            $attr['date'] = $date;
            $attr['building_id'] = $autogenerated_date->building_id;
            $attr['hour_start'] = $autogenerated_date->hour_start;
            $attr['hour_end'] = $autogenerated_date->hour_end;
            $attr['minuts_start'] = $autogenerated_date->minuts_start;
            $attr['minuts_end'] = $autogenerated_date->minuts_end;
            $attr['period_date'] = $autogenerated_date->building->actual_period->format('Y-m-d');
            $attr['autogenerated'] = 1;

            BuildingPayDay::create($attr);

        }

    }
        
    public function reopen_period(){
        
        $last_date_open = date( 'Y-m-d', (strtotime( '-1 month' , strtotime( $this->type_expense_period->date->format('Y-m-d')))));
        
        if (($this->type_expense_period->type_name == "vencido" && $this->actual_period->format('Y-m-d') > "2012-06-01") ||
             ($this->type_expense_period->type_name == "actual" && $this->actual_period->format('Y-m-d') > "2012-07-01")) {
        // go back a month        
            $this->remove_autogenerated_days();

            $this->actual_period = date( 'Y-m-d', (strtotime( '-1 month' , strtotime($this->actual_period->format('Y-m-d')))));
            
            $last_transaction = $this->get_last_building_transaction();

            $this->balance = $last_transaction->last_balance;
            $this->reserve_fund = $last_transaction->last_reserve_fund;
            $this->balance_extraordinary = $last_transaction->last_balance_extraordinary;
            
            $last_transaction->readonly(false);
            $last_transaction->delete();
            
            $this->save();
            
        // elimina las transacciones extraordinarias inpagas
            $this->remove_current_extra_unpaid_payments();
        // elimina las transacciones inpagas
            $this->remove_current_unpaid_payments();
            
            return true;
        }
        else
            return false;
    }

    public function remove_autogenerated_days()
    {
        $building = Building::find($this->id);

        $days = BuildingPayDay::find_by_sql("SELECT * FROM building_pay_days as bpd WHERE building_id = ". $building->id ." AND autogenerated = 1 AND period_date = '" . $building->actual_period->format('Y-m-d') . "'");

        foreach ($days as $day) {
            $day->readonly(false);
            $day->delete();
        }

    }
    
    public function one_month_back(){
        return date("Y-m-d", strtotime("-1 month",strtotime($this->actual_period->format("Y-m-d"))));
    }

    public function two_month_back(){
        return date("Y-m-d", strtotime("-2 month",strtotime($this->actual_period->format("Y-m-d"))));
    }
    
    public function initial_day_last_period(){
        return date("Y-m-d", strtotime("-1 month -1 day",strtotime($this->actual_period->format("Y-m-d"))));
    }
    
    public function last_day_last_period(){
        return date("Y-m-d", strtotime("-1 day",strtotime($this->actual_period->format("Y-m-d"))));
    }


    public function get_last_month_aditional_incomes(){
        $result = Array();        
        $one_month_back = $this->one_month_back();
        foreach ($this->aditional_incomes as $ai){
            if ($ai->period_date->format("Y-m-d") == $one_month_back){
                $result[] = $ai;
            }
        }
        if (count($result) > 0)
            return $result;
        else
            return null;
    }
    
    
    public function get_last_month_expenses(){
        $result = Array();
        $one_month_back = $this->one_month_back();
        foreach ($this->expense_transactions as $e){                    
            if ($e->period_date->format("Y-m-d") == $one_month_back){
                $result[] = $e;
            }
        }
        if (count($result) > 0)
            return $result;
        else
            return null;
    }
    
    // Special expenses methods
    public function get_two_month_back_special_expenses(){
        $result = Array();
        $two_month_back = $this->two_month_back();
        foreach ($this->special_expense_transactions as $e){                    
            if ($e->period_date->format("Y-m-d") == $two_month_back){
                $result[] = $e;
            }
        }
        if (count($result) > 0)
            return $result;
        else
            return null;
    }

    public function get_total_special_expense_two_month_back(){
        $total = 0;
        $two_month_back = $this->two_month_back();
        foreach ($this->special_expense_transactions as $e){                    
            if ($e->period_date->format("Y-m-d") == $two_month_back){
                $total = $total + $e->value;
            }
        }
        return $total;
    }

    public function get_last_month_special_expenses(){
        $result = Array();
        $one_month_back = $this->one_month_back();
        foreach ($this->special_expense_transactions as $e){                    
            if ($e->period_date->format("Y-m-d") == $one_month_back){
                $result[] = $e;
            }
        }
        if (count($result) > 0)
            return $result;
        else
            return null;
    }
    
    public function get_last_laboral_month_expenses(){
        $result = Array();
        $one_month_back = $this->one_month_back();
        foreach ($this->expense_transactions as $e){                    
            if ($e->period_date->format("Y-m-d") == $one_month_back &&
                $e->type_expense_id == 1){
                $result[] = $e;
            }
        }
        if (count($result) > 0)
            return $result;
        else
            return null;
    }
    
    
    public function get_last_non_laboral_month_expenses(){
        
        $one_month_back = $this->one_month_back();
        $expenses = ExpenseTransaction::find_by_sql("SELECT et.* 
                                                       FROM expense_transactions et 
                                                      WHERE et.building_id = $this->id
                                                        AND et.period_date = '$one_month_back'
                                                        AND et.type_expense_id <> 1 
                                                   ORDER BY et.priority DESC");
        
        if (count($expenses) > 0)
            return $expenses;
        else
            return null;
    }

    public function get_last_estimative_expenses(){

        $one_month_back = $this->one_month_back();
        $expenses = EstimativeExpenseTransaction::find_by_sql("SELECT et.* 
                                                       FROM estimative_expense_transactions et 
                                                      WHERE et.building_id = $this->id
                                                        AND et.period_date = '$one_month_back'
                                                   ORDER BY et.priority DESC");
        
        if (count($expenses) > 0)
            return $expenses;
        else
            return null;   
    }
    
    public function get_last_month_building_transaction(){
        $result;
        $one_month_back = $this->one_month_back();
        foreach ($this->building_transactions as $bt){                    
            if ($bt->period_date->format("Y-m-d") == $one_month_back){
                $result = $bt;
            }
        }
        return $result;
    }
    
    public function get_last_month_balance(){
        $result;
        $one_month_back = $this->one_month_back();
        foreach ($this->building_transactions as $bt){                    
            if ($bt->period_date->format("Y-m-d") == $one_month_back){
                $result = $bt->last_balance;
            }
        }
        return $result;
    }
    
    public function total_ordinary_gain_last_month(){
        
        $total = 0;
        $one_month_back = $this->one_month_back();
        
        foreach ($this->properties as $property):
            foreach ($property->income_transaction as $it):
                if ($it->period_date->format("Y-m-d") == $one_month_back && $it->type_pay_date != 'unpaid')
                    $total = $total + round($it->value, 0, PHP_ROUND_HALF_DOWN);
            endforeach;
        endforeach;
        /*$result = IncomeTransaction::find_by_sql(
                                    "SELECT SUM(round(it.value),2)) as total
                                       FROM income_transactions it 
                                 INNER JOIN properties p ON (it.property_id = p.id)
                                 INNER JOIN buildings b  ON (p.building_id = b.id) 
                                      WHERE it.period_date = '$one_month_back'
                                        AND it.type_pay_date <> 'unpaid'
                                        AND b.id = '". $this->id ."'
                                        AND it.property_id IN (SELECT id
                                                                 FROM properties pr
                                                                WHERE pr.building_id = b.id)");
        
        if ($result[0]->total != null)
            return $result[0]->total;        
        else
            return 0;        */
        return $total;
    
    }
    
    public function total_ordinary_gain_without_interest_last_month(){
        
        return $this->total_ordinary_gain_last_month() - $this->total_ordinary_interest_gain_last_month();
    
    }
    
    public function total_ordinary_interest_gain_last_month(){
        
        $total = 0;
        $one_month_back = $this->one_month_back();
        
        foreach ($this->properties as $property):
            foreach ($property->income_transaction as $it):
                if ($it->period_date->format("Y-m-d") == $one_month_back && $it->type_pay_date != 'unpaid')
                    $total = $total + $property->due_interest_expense_for_income_transaction($it);
            endforeach;
        endforeach;
        
        return $total;
    
    }
    
    public function total_fund_gain_last_month(){
        $one_month_back = $this->one_month_back();
        $total = 0;
        foreach ($this->properties as $property):
            foreach ($property->income_transaction as $it):
                if ($it->period_date->format("Y-m-d") == $one_month_back && $it->type_pay_date != 'unpaid')
                    $total = $total + round($it->value_fund, 0, PHP_ROUND_HALF_DOWN);
            endforeach;
        endforeach;
        return $total;
        
        /*
        $result = IncomeTransaction::find_by_sql(
                                    "SELECT SUM(it.value_fund) as total
                                       FROM income_transactions it 
                                 INNER JOIN properties p ON (it.property_id = p.id)
                                 INNER JOIN buildings b  ON (p.building_id = b.id) 
                                      WHERE it.period_date = '$one_month_back'
                                        AND it.type_pay_date <> 'unpaid'
                                        AND b.id = '". $this->id ."'
                                        AND it.property_id IN (SELECT id
                                                                 FROM properties pr
                                                                WHERE pr.building_id = b.id)");
        
        if ($result[0]->total != null)
            return $result[0]->total;        
        else
            return 0; */       
    
    }
    
    public function get_total_incomes_of_last_month(){
        $one_month_back = $this->one_month_back();
        /*$result = self::find_by_sql("SELECT SUM(ai.value) as total
                                       FROM aditional_incomes ai
                                      WHERE ai.building_id = ". $this->id ." AND ai.period_date = '$one_month_back'");
         */
        $result = 0;
        
        foreach ($this->aditional_incomes as $ai):
            if ($ai->period_date->format("Y-m-d") == $one_month_back)
                $result = $result + $ai->value;
        endforeach;
                
        foreach ($this->properties as $property):
            foreach ($property->income_transaction as $it):
                if ($it->period_date->format("Y-m-d") == $one_month_back && $it->type_pay_date != 'unpaid')
                    $result = $result + round($it->value_fund + $it->value, 0, PHP_ROUND_HALF_DOWN);
            endforeach;
        endforeach;
        
        return $result;
        //return $result + $this->total_ordinary_gain_last_month() + $this->total_fund_gain_last_month();        
        
    }
        
    public function total_gain_extra_last_month_for($period){
        $total = 0; 
        $one_month_back = $this->one_month_back();
        foreach ($this->properties as $property):
            foreach ($property->extraordinary_transaction as $et):
                if ($et->period_date->format("Y-m-d") == $one_month_back && $et->type_pay != 'unpaid')
                    $total = $total + round($et->value, 0, PHP_ROUND_HALF_DOWN);
            endforeach;
        endforeach;
        return $total;
        /*$one_month_back = $this->one_month_back();
        $result = self::find_by_sql("SELECT SUM(et.value) as total
                                       FROM extraordinary_transactions et
                                      WHERE et.extraordinary_period_id = ". $period->id ."
                                        AND et.period_date = '$one_month_back' 
                                        AND et.type_pay <> 'unpaid'");
        if ($result[0]->total != null)
            return $result[0]->total;
        else
            return 0;*/
        
    }
        
    public function total_expense_extra_last_month_for($period){
        
        $one_month_back = $this->one_month_back();
        $result = self::find_by_sql("SELECT SUM(ee.value) as total
                                       FROM extraordinary_expenses ee
                                      WHERE ee.extraordinary_period_id = ". $period->id ."
                                        AND ee.period_date = '$one_month_back'");
        if ($result[0]->total != null)
            return $result[0]->total;
        else
            return 0;
        
    }
        
    public function get_extra_expenses_last_month($period){
        
        $one_month_back = $this->one_month_back();
        $result = ExtraordinaryExpense::find_by_sql("SELECT *
                                       FROM extraordinary_expenses ee
                                      WHERE ee.extraordinary_period_id = ". $period->id ."
                                        AND ee.building_id = '". $this->id ."'
                                        AND ee.period_date = '$one_month_back'");
        
        if ($result)
            return $result;
        else
            return null;
        
    }
        
    public function get_total_expense_of_last_month(){
        $one_month_back = $this->one_month_back();
        $result = self::find_by_sql("SELECT SUM(et.value) as total
                                       FROM expense_transactions et
                                      WHERE et.building_id = ". $this->id ." AND et.period_date = '$one_month_back'");
        
        if ($result[0]->total != null)
            return $result[0]->total;
        else
            return 0;
    }
    
    public function get_percentage_from_type_expense_of_last_month($type_expense){
        $one_month_back = $this->one_month_back();
        $result = self::find_by_sql("SELECT SUM(et.value) as total
                                       FROM expense_transactions et
                                      WHERE et.building_id = ". $this->id ." 
                                        AND et.period_date = '$one_month_back'
                                        AND et.type_expense_id = $type_expense");
        if ($result[0]->total != null)
            return $result[0]->total * 100 / $this->get_total_expense_of_last_month();
        else
            return 0;
    }
    
    public function get_percentage_from_non_type_expense_of_last_month($type_expense){
        $one_month_back = $this->one_month_back();
        $result = self::find_by_sql("SELECT SUM(et.value) as total
                                       FROM expense_transactions et
                                      WHERE et.building_id = ". $this->id ." 
                                        AND et.period_date = '$one_month_back'
                                        AND et.type_expense_id <> $type_expense");
        if ($result[0]->total != null)
            return $result[0]->total * 100 / $this->get_total_expense_of_last_month();
        else
            return 0;
    }
    
    public function get_extraordinary_period(){
        $id = $this->id;
        $actual = $this->actual_period->format("Y-m-d");
        $periods = ExtraordinaryPeriod::find_by_sql("SELECT ep.*
                                                       FROM extraordinary_periods ep
                                                      WHERE ep.building_id = $id ");/*
                                                        AND (ep.period_date = '$actual'
                                                         OR '$actual' BETWEEN ep.date_from and ep.date_to)");*/
        return $periods;
    }

    public function has_actives_extraordinaries_period(){
        return (count($this->get_actives_extraordinaries_period()) > 0);
    }

    public function get_actives_extraordinaries_period(){
    
        $id = $this->id;
        $periods = ExtraordinaryPeriod::find_by_sql("SELECT ep.*
                                                       FROM extraordinary_periods ep
                                                      WHERE ep.building_id = $id
                                                        AND ep.properties_type = 3
                                                        AND ep.state = 1");
        return $periods;
        
    }
    
    public function get_actives_or_with_activity_extraordinaries_period(){
    
        $id = $this->id;
        $periods = ExtraordinaryPeriod::find_by_sql("SELECT ep.*
                                                       FROM extraordinary_periods ep
                                                      WHERE ep.building_id = $id
                                                        AND ep.properties_type = 3
                                                        AND '". $this->actual_period->format("Y-m-d") ."' > ep.period_date
                                                        AND (ep.state = 1
                                                         OR (SELECT COUNT(*) FROM extraordinary_transactions et WHERE et.extraordinary_period_id = ep.id AND et.period_date = '".$this->actual_period->format("Y-m-d")."' ) > 0 )");
        return $periods;
        
    }
            
    
    public function get_extraordinary_period_for_pay(){
        $id = $this->id;
        $periods = ExtraordinaryPeriod::find_by_sql("SELECT ep.*
                                                       FROM extraordinary_periods ep
                                                      WHERE ep.building_id = $id
                                                        AND ep.properties_type = 3");
        /*$periods_for_pay = Array();
        $all_paid;
        
        foreach ($periods as $period):
            $all_paid = true;
            foreach ($this->properties as $property):
                if (!$property->has_paid_all_extraordinary($period))
                    $all_paid = false;
            endforeach;
            if (!$all_paid && ($period->period_date->format("Y-m-d") != $this->actual_period->format("Y-m-d")))
                $periods_for_pay[] = $period;
        endforeach;
        
        return $periods_for_pay;*/
        return $periods;
    }
    
    public function get_extraordinary_period_with_current_income_activity(){
        
        $periods = ExtraordinaryPeriod::find_by_sql("SELECT ep.*
                                                       FROM extraordinary_periods ep
                                                      WHERE ep.building_id = $this->id 
                                                        AND ep.state = 1");
        return $periods;
    }
    
    public function get_extraordinary_period_with_current_expense_activity(){
        
        $periods_with_activity = Array();
        foreach( $this->extraordinary_periods as $period):
            if ($period->has_current_expense_activity())
                $periods_with_activity[] = $period;
        endforeach;
        
        return $periods_with_activity;
    }
    
    public function unpaid_extra_current_properties($extraordinary_period){
        $properties = array();
        foreach ($extraordinary_period->properties_implied() as $property){
            if (!$property->has_paid_current_extraordinary_month($extraordinary_period->id)){
                $properties[] = $property;
            }
        }
        return $properties;
    }
    
    public function paid_extra_current_properties($extraordinary_period){
        $properties = array();
        foreach ($extraordinary_period->properties_implied() as $property){
            if ($property->has_paid_current_extraordinary_month($extraordinary_period->id)){
                $properties[] = $property;
            }
        }
        return $properties;
    }  
    
    public function cant_properties(){
        return count($this->properties);
    }
    
    public function actual_pay_days(){
        $actual_period = $this->actual_period->format('Y-m-d');
        $pay_days = BuildingPayDay::find_by_sql("SELECT pd.*
                                       FROM building_pay_days pd
                                      WHERE pd.building_id = $this->id
                                        AND pd.period_date = '$actual_period'
                                     ORDER  BY pd.date ASC");
        
        if ($pay_days){
            return $pay_days;
        }
        else{
            return null;
        }
        
    }
    
    public function get_building_periods(){
        
        $periods = BuildingTransaction::find_by_sql("SELECT bt.period_date
                                                       FROM building_transactions bt
                                                      WHERE bt.building_id = $this->id ");
        
        if (count($periods) > 0) {
            return $periods;
        } else{
            return null;
        }
    }
    
    public function get_expense_tags_for_periods($start_period,$end_period){
        
        $expense_tags = ExpenseTag::find_by_sql("SELECT DISTINCT(et.expense_tag_id),ea.`name`
                                                   FROM expense_transactions et INNER JOIN expense_tags ea ON (et.`expense_tag_id` = ea.`id`)
                                                  WHERE et.building_id = $this->id
                                                    AND (et.period_date BETWEEN '$start_period' AND '$end_period')");

        if (count($expense_tags) > 0) {
            return $expense_tags;
        } else{
            return null;
        }
        
    }
    
    
    /*public function get_expense_tags_for_periods_non_laboral($start_period,$end_period){
        
        $expense_tags = ExpenseTag::find_by_sql("SELECT DISTINCT(et.expense_tag_id),ea.`name`
                                                   FROM expense_transactions et INNER JOIN expense_tags ea ON (et.`expense_tag_id` = ea.`id`)
                                                  WHERE et.building_id = $this->id
                                                    AND (et.period_date BETWEEN '$start_period' AND '$end_period')
                                                    AND et.type_expense_id <> 1");

        if (count($expense_tags) > 0) {
            return $expense_tags;
        } else{
            return null;
        }
        
    }*/
    
    public function get_expense_balance_tags_for_periods_non_laboral($start_period,$end_period){
        $expense_balance_tags = ExpenseBalanceTag::find_by_sql("SELECT ebt.*
                                                                  FROM expense_transactions et INNER JOIN expense_tags ea ON (et.expense_tag_id = ea.id) INNER JOIN expense_balance_tags ebt ON (ea.expense_tag_id = ebt.id)
                                                                 WHERE et.building_id = $this->id
                                                                   AND (et.period_date BETWEEN '$start_period' AND '$end_period')
                                                                   AND et.type_expense_id <> 1
                                                              GROUP BY ebt.id");

        if (count($expense_balance_tags) > 0) {
            return $expense_balance_tags;
        } else{
            return null;
        }
    }
    public function get_expense_other_tags_for_periods_non_laboral($start_period,$end_period){
        $expense_tags = ExpenseTag::find_by_sql("SELECT DISTINCT(et.expense_tag_id),ea.`name`
                                                   FROM expense_transactions et INNER JOIN expense_tags ea ON (et.`expense_tag_id` = ea.`id`)
                                                  WHERE et.building_id = $this->id
                                                    AND (et.period_date BETWEEN '$start_period' AND '$end_period')
                                                    AND et.type_expense_id <> 1
                                                    AND ea.expense_tag_id IS NULL");
        

        if (count($expense_tags) > 0) {
            return $expense_tags;
        } else{
            return null;
        }
    }
    
    /*public function get_expense_tags_for_periods_laboral($start_period,$end_period){
        
        $expense_tags = ExpenseTag::find_by_sql("SELECT DISTINCT(et.expense_tag_id),ea.`name`
                                                   FROM expense_transactions et INNER JOIN expense_tags ea ON (et.`expense_tag_id` = ea.`id`)
                                                  WHERE et.building_id = $this->id
                                                    AND (et.period_date BETWEEN '$start_period' AND '$end_period')
                                                    AND et.type_expense_id = 1");

        if (count($expense_tags) > 0) {
            return $expense_tags;
        } else{
            return null;
        }
        
    }*/
    
    public function get_expense_balance_tags_for_periods_laboral($start_period,$end_period){
        
        $expense_balance_tags = ExpenseBalanceTag::find_by_sql("SELECT ebt.*
                                                                  FROM expense_transactions et INNER JOIN expense_tags ea ON (et.expense_tag_id = ea.id) INNER JOIN expense_balance_tags ebt ON (ea.expense_tag_id = ebt.id)
                                                                 WHERE et.building_id = $this->id
                                                                   AND (et.period_date BETWEEN '$start_period' AND '$end_period')
                                                                   AND et.type_expense_id = 1
                                                              GROUP BY ebt.id");

        if (count($expense_balance_tags) > 0) {
            return $expense_balance_tags;
        } else{
            return null;
        }
    }
 
    public function get_expense_other_tags_for_periods_laboral($start_period,$end_period){
        $expense_tags = ExpenseTag::find_by_sql("SELECT DISTINCT(et.expense_tag_id),ea.`name`
                                                   FROM expense_transactions et INNER JOIN expense_tags ea ON (et.`expense_tag_id` = ea.`id`)
                                                  WHERE et.building_id = $this->id
                                                    AND (et.period_date BETWEEN '$start_period' AND '$end_period')
                                                    AND et.type_expense_id = 1
                                                    AND ea.expense_tag_id IS NULL");
        

        if (count($expense_tags) > 0) {
            return $expense_tags;
        } else{
            return null;
        }
    }
    
    public function get_others_aditional_income_tags_for_periods($start_period,$end_period){
        
        $income_tags = IncomeTag::find_by_sql("SELECT DISTINCT(ai.income_tag_id),ait.name 
                                                   FROM aditional_incomes ai INNER JOIN income_tags ait ON (ai.income_tag_id = ait.id)
                                                  WHERE ai.building_id = $this->id
                                                    AND ait.income_tag_id IS NULL
                                                    AND (ai.period_date BETWEEN '$start_period' AND '$end_period')");
        
        if (count($income_tags) > 0) {
            return $income_tags;
        } else{
            return null;
        }
        
    }
    
    public function get_aditional_income_tags_for_periods($start_period,$end_period){
        
        
        $income_balance_tags = IncomeBalanceTag::find_by_sql("SELECT ibt.name,ibt.id
                                                                FROM aditional_incomes ai 
                                                          INNER JOIN income_tags ait ON (ai.income_tag_id = ait.id) 
                                                          INNER JOIN income_balance_tags ibt ON (ait.income_tag_id = ibt.id)
                                                               WHERE ai.building_id =  $this->id
                                                                 AND (ai.period_date BETWEEN '$start_period' AND '$end_period')
                                                            GROUP BY ibt.id");

        if (count($income_balance_tags) > 0) {
            return $income_balance_tags;
        } else{
            return null;
        }
        
    }
    
    
    
    
    public function get_building_transactions_for_periods($start_period,$end_period){
        
        $building_transactions = BuildingTransaction::find_by_sql("SELECT * 
                                                                     FROM building_transactions bt 
                                                                    WHERE bt.building_id = $this->id
                                                                      AND bt.period_date BETWEEN '$start_period' AND '$end_period'");

        if (count($building_transactions) > 0) {
            return $building_transactions;
        } else{
            return null;
        }
        
    }
    
    public function get_aditional_income_value_for_tag_and_period($income_tag_id,$period_date) {
        
        $income = AditionalIncome::find_by_sql("SELECT * 
                                                  FROM aditional_incomes ai 
                                                 WHERE ai.building_id = $this->id
                                                   AND ai.income_tag_id = $income_tag_id
                                                   AND ai.period_date = '$period_date'");

        if (count($income) > 0) {
            return $income[0]->value;
        } else{
            return 0;
        }
        
    }
    
    public function get_aditional_income_value_for_balance_tag_and_period($income_balance_tag_id,$period_date) {
         
        $income = AditionalIncome::find_by_sql("SELECT SUM(ai.value) as value
                                                  FROM aditional_incomes ai 
                                            INNER JOIN income_tags ait ON (ai.income_tag_id = ait.id) 
                                            INNER JOIN income_balance_tags ibt ON (ait.income_tag_id = ibt.id)
                                                 WHERE ai.building_id = $this->id
                                                   AND ibt.id = $income_balance_tag_id
                                                   AND ai.period_date = '$period_date'");

        if (count($income) > 0) {
            return $income[0]->value;
        } else{
            return 0;
        }
        
    }
    
    public function get_others_aditional_income_value_for_balance_tag_and_period($period_date) {
        
        $income = AditionalIncome::find_by_sql("SELECT SUM(ai.value) as value
                                                  FROM aditional_incomes ai 
                                            INNER JOIN income_tags ait ON (ai.income_tag_id = ait.id) 
                                                 WHERE ai.building_id = $this->id
                                                   AND ait.income_tag_id IS NULL 
                                                   AND ai.period_date = '$period_date'");

        if (count($income) > 0) {
            return $income[0]->value;
        } else{
            return 0;
        }
        
    }
    
    public function get_total_ordinary_incomes_for_period($period_date){
        
        $sum = IncomeTransaction::find_by_sql("SELECT SUM(it.value) as sum_value
                                                 FROM income_transactions it INNER JOIN properties p ON (p.id = it.property_id)
                                                WHERE p.building_id = $this->id
                                                  AND it.period_date = '$period_date'
                                                  AND it.type_pay_date <> 'unpaid'");

        if ($sum[0]->sum_value){
            return $sum[0]->sum_value;
        }
        else{
            return 0;
        }
        
    }
    
    public function get_total_aditional_incomes_value_for_period($period_date){
        
        $sum = AditionalIncome::find_by_sql("SELECT SUM(ai.value) as sum_value
                                               FROM aditional_incomes ai 
                                              WHERE ai.building_id = $this->id
                                                AND ai.period_date = '$period_date'");

        if ($sum[0]->sum_value){
            return $sum[0]->sum_value;
        }
        else{
            return 0;
        }
        //return $sum[0]['sum_value'];
        
    }
    
    
    public function get_expense_value_for_balance_tag_and_period($expense_balance_tag_id,$period_date){
        $income = ExpenseTransaction::find_by_sql("SELECT SUM(et.value) as value
                                                     FROM expense_transactions et 
                                               INNER JOIN expense_tags eta ON (et.expense_tag_id = eta.id) 
                                               INNER JOIN expense_balance_tags ebt ON (eta.expense_tag_id = ebt.id)
                                                    WHERE et.building_id = $this->id
                                                      AND ebt.id = $expense_balance_tag_id
                                                      AND et.period_date = '$period_date'");

        if (count($income) > 0) {
            return $income[0]->value;
        } else{
            return 0;
        }
    }
    
    public function get_expense_value_for_other_tag_and_period($expense_tag_id,$period_date) {
        $income = ExpenseTransaction::find_by_sql("SELECT SUM(et.value) as value
                                                     FROM expense_transactions et 
                                                    WHERE et.building_id = $this->id
                                                      AND et.expense_tag_id = $expense_tag_id
                                                      AND et.period_date = '$period_date'");

        if (count($income) > 0) {
            return $income[0]->value;
        } else{
            return 0;
        }
    }
    /*public function get_expense_value_for_tag_and_period($expense_tag_id,$period_date) {
        
        $expense = ExpenseTransaction::find_by_sql("SELECT * 
                                                  FROM expense_transactions et 
                                                 WHERE et.building_id = $this->id
                                                   AND et.expense_tag_id = $expense_tag_id
                                                   AND et.period_date = '$period_date'");

        if (count($expense) > 0) {
            return $expense[0]->value;
        } else{
            return 0;
        }
        
    }*/
    
    public function get_total_expenses_laboral_value_for_period($period_date) {
        
        $sum = ExpenseTransaction::find_by_sql("SELECT SUM(et.value) as sum_value
                                                  FROM expense_transactions et 
                                                 WHERE et.building_id = $this->id
                                                   AND et.period_date = '$period_date'
                                                   AND et.type_expense_id = 1");

        if ($sum[0]->sum_value){
            return $sum[0]->sum_value;
        }
        else{
            return 0;
        }
        
    }
    
    public function get_total_expenses_non_laboral_value_for_period($period_date) {
        
        $sum = ExpenseTransaction::find_by_sql("SELECT SUM(et.value) as sum_value
                                                  FROM expense_transactions et 
                                                 WHERE et.building_id = $this->id
                                                   AND et.period_date = '$period_date'
                                                   AND et.type_expense_id <> 1");

        if ($sum[0]->sum_value){
            return $sum[0]->sum_value;
        }
        else{
            return 0;
        }
        
    }
    
    // Code For Specials Expenses
    public function get_special_expenses_last_month(){
        
        $expenses = array();
        $one_month_back = $this->one_month_back();
        foreach ($this->special_expense_transactions as $expense){            
            if ($expense->period_date->format("Y-m-d") == $one_month_back){
                $expenses[] = $expense;
            }
        }
        return $expenses;
        
    }

    public function has_special_expense_last_month() {
        
        $expenses = $this->get_special_expenses_last_month();
        if (count($expenses)){
            return true;
        }
        else{
            return false;
        }
        
    }

    public function get_special_expense_for_property_last_month($property_model) {
        
        $total_expense = 0;
        $expenses = $this->get_special_expenses_last_month();

        foreach ($expenses as $expense) {
            if ($expense->type_special_expense->is_divided_by_coeficient()){
                $value_of_expense = ($property_model->coefficient * $expense->value) / $this->total_coefficient;
                $total_expense = $total_expense + $value_of_expense;
            }
        }

        return $total_expense;
        
    }

    // Code For Schedule dates 
    public function have_schedule_date_for_date($date) {

        $building_pay_day = BuildingPayDay::find_by_sql("SELECT *
                                                           FROM building_pay_days
                                                          WHERE building_id = '$this->id' 
                                                            AND date = '". $date->format('Y-m-d') ."'");

        if ($building_pay_day){
            return $building_pay_day[0];
        }
        else {
            return null;
        }

    }

    //************* Roela Bank Methods *************//
    public function get_bank_identifier_string() {
        return sprintf('%04d', $this->id);
    }
 
    public function get_bank_payment_account_string() {
        return $this->bank_account_number;
    }
    //************* Roela Bank Methods *************//

}
?>
