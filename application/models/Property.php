<?php
include_once APPPATH.'libraries/RoelaBank.php';

class Property extends ActiveRecord\Model{
    static $belongs_to = array(
        array('owner', 'class_name' => 'Person', 'foreign_key' => 'owner_id', 'readonly' => true),
        array('building', 'readonly' => true)  ,
        array('auxiliary_property_of', 'class_name' => 'Property', 'readonly' => true)
        
    );
    
    static $has_many = array(
        array('property_log', 'readonly' => true),
        array('income_transaction'),
        array('extraordinary_transaction') 
    );
    
    
    public function get_last_income_transaction(){
        $one_month_back = $this->building->one_month_back();
        $transaction = IncomeTransaction::find("all" , array( "conditions" => "property_id = '".$this->id."' AND period_date = '".$one_month_back."' "));

        if ($transaction)
            return $transaction[0];
        else
            return null;
    }
    
    
    public function due_interest_expense(){
        
        // modificar cuando se necesite cambiar el monto de la expensa total
        if ($this->balance < 0)
            return ($this->balance * $this->building->tax_late_percentage) / 100;        
        else
            return 0;

    }
    
    public function actual_common_expense(){
        return ($this->coefficient * $this->building->earning_monthly) / $this->building->total_coefficient;
    }
    
    // return last month special expense for the property
    public function last_month_special_expense(){
        if ($this->building->has_special_expense_last_month()){
            return $this->building->get_special_expense_for_property_last_month($this);
        }
        else{
            return 0;            
        }
    }
    
    public function total_to_pay_expense(){
        if ($this->balance < 0)
            return $this->actual_common_expense() + abs($this->due_interest_expense()) + abs($this->balance) + abs($this->last_month_special_expense());
        else
            return $this->actual_common_expense() + abs($this->due_interest_expense()) + abs($this->last_month_special_expense()) - $this->balance;
    }    
    
    public function due_fund(){
        
        if ($this->balance_reserve < 0){
            return $this->balance_reserve;
        }
        else
            return 0;
        
    }
        
    public function due_interest_fund(){
            
        if ($this->balance_reserve < 0){
            return ($this->balance_reserve * $this->building->reserve_percentage) / 100;            
        }
        else
            return 0;       
        
    }
        
    public function actual_reserve_fund(){
        if ($this->building->put_reserve_value_manually)
            return $this->static_reserve_value;
        else{
            if ($this->building->earn_static_fund)
                return $this->building->earn_static_fund_value * $this->coefficient;
            else
                return ($this->actual_common_expense() * $this->building->earn_percentage_value) / 100;
        }
    }
    
    public function total_to_pay_fund(){
        if ($this->balance < 0)
            return $this->actual_reserve_fund() + abs($this->due_interest_fund()) + abs($this->balance_reserve);
        else 
            return $this->actual_reserve_fund() + abs($this->due_interest_fund()) - $this->balance_reserve;
    }
        
    public function total_to_pay(){
        
        if ($this->building->has_reserve_fund){
            if ($this->balance_reserve < 0)
                $reserve_tmp = abs($this->actual_reserve_fund()) + abs($this->due_interest_fund()) + abs($this->balance_reserve);
            elseif($this->balance_reserve > 0)
                $reserve_tmp = abs($this->actual_reserve_fund()) - $this->balance_reserve;
            else
                $reserve_tmp = abs($this->actual_reserve_fund());
        }
        else
            $reserve_tmp = 0;
        
        
        if ($this->balance < 0)
            $expense_tmp = abs($this->actual_common_expense()) + abs($this->due_interest_expense()) + abs($this->balance);
        elseif ($this->balance > 0)
            $expense_tmp = abs($this->actual_common_expense()) - $this->balance;
        else
            $expense_tmp = abs($this->actual_common_expense());

        // Si el edificio tiene gastos adicionales que se les tiene que cobrar a las unidades 
        // funcionales
        if ($this->building->has_special_expense_last_month()){
            $special_expense_tmp = $this->building->get_special_expense_for_property_last_month($this);
        }
        else{
            $special_expense_tmp = 0;            
        }

        return $expense_tmp + $reserve_tmp + $special_expense_tmp;
    }
    
    public function due_interest_expense_for_income_transaction($income_transaction) {
        
        if ($income_transaction->last_balance < 0){
            
            if (!$this->building->has_reserve_fund){
                if ($income_transaction->value > (abs($income_transaction->last_balance) + abs(($income_transaction->last_balance * $this->building->tax_late_percentage) / 100))){
                    return abs($income_transaction->last_balance) + abs(($income_transaction->last_balance * $this->building->tax_late_percentage) / 100);
                }
                else{
                    return abs($income_transaction->value);
                }
            }
            else{
                if ($income_transaction->value > (abs($income_transaction->last_balance) + abs($income_transaction->last_balance_reserve) + abs(($income_transaction->last_balance * $this->building->tax_late_percentage) / 100) + abs(($income_transaction->last_balance_reserve * $this->building->reserve_percentage) / 100))){
                    return abs($income_transaction->last_balance) + abs($income_transaction->last_balance_reserve) + abs(($income_transaction->last_balance * $this->building->tax_late_percentage) / 100) + abs(($income_transaction->last_balance_reserve * $this->building->reserve_percentage) / 100);
                }
                else{
                    return abs($income_transaction->value);
                }
            }
        }
        else{
            return 0;
        }
    }
        
    public function total_to_pay_due(){
        return $this->total_to_pay() + ($this->total_to_pay() * $this->building->tax_percentage / 100);
    }
    
    public function has_paid_current_month($aditional_where = ""){

        /*if ($this->building->type_expense_period->type_name == "vencido")
            $where_date =   "AND year(ic.date) = year(b.actual_period) 
                             AND month(ic.date) = month(b.actual_period)";
        else
            $where_date =   "AND year(ic.date) = year(DATE_SUB(b.actual_period, INTERVAL 1 MONTH)) 
                             AND month(ic.date) = month(DATE_SUB(b.actual_period, INTERVAL 1 MONTH))";*/
        $where_date = "AND ic.period_date = b.actual_period";
        $income = IncomeTransaction::find_by_sql("SELECT ic.*
                                                    FROM income_transactions ic,
                                                            buildings b
                                                    WHERE ic.property_id = ".$this->id. "
                                                        AND b.id = ".$this->building_id." ".$where_date." ".$aditional_where);

            
        if ($income)
            return $income;
        else
            return false;
        
    }
    
    public function get_current_pay_month(){
        $income = $this->has_paid_current_month();
        if ($income)
            return $income[0];
        else
            return false;
    }

    public function has_current_unpaid_payments(){
        $income = $this->has_paid_current_month("AND ic.type_pay_date = 'unpaid'");        
        if($income)
            return $income[0];
        else
            return false;
    }
    
    public function has_paid_current_extraordinary_month($extraordinary_period_id , $aditional_where = ""){

        $where_date = "AND et.period_date = b.actual_period";
        $extraordinary = ExtraordinaryTransaction::find_by_sql("SELECT et.*
                                                                  FROM extraordinary_transactions et,
                                                                       buildings b
                                                                 WHERE et.property_id = ".$this->id. "
                                                                   AND et.extraordinary_period_id = $extraordinary_period_id 
                                                                   AND b.id = ".$this->building_id." ".$where_date." ".$aditional_where);
        
        if ($extraordinary) {
            return $extraordinary;
        } else {
            return false;
        }
    }
    
    public function has_paid_all_extraordinary($period_id){
     
        $extraordinaryPeriodProperty = ExtraordinaryTransaction::find_by_sql("SELECT epp.*
                                                                                FROM extraordinary_period_properties epp
                                                                               WHERE epp.property_id = ".$this->id. "
                                                                                 AND epp.extraordinary_period_id = $period_id 
                                                                                 AND epp.state = 3");
        if (count($extraordinaryPeriodProperty) > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function has_finish_paid_extraordinary($period){
        
        $sum_paid = ExtraordinaryTransaction::find_by_sql("SELECT IFNULL (SUM(et.`value`),0) as ammount
                                                                 FROM extraordinary_transactions et
                                                                WHERE et.property_id = ".$this->id."
                                                                  AND et.extraordinary_period_id =  ".$period->id."
                                                                  AND type_pay <> 'unpaid' ");
        $ammount_paid = (float)$sum_paid[0]->ammount;
        $ammount_needed_to_finish = $this->get_ammount_to_pay_for_extraordinary($period);
        
        if ($ammount_paid >= $ammount_needed_to_finish) {
            return true;
        } else {
            return false;
        }
        
    }
    
    public function get_ammount_to_pay_for_extraordinary($period){

        if($period->pay_form == DIVIDE_COEFICCIENT)
            $month_pay = ($this->coefficient * ($period->value / $period->fees)) / $period->total_coefficient_of_properties_implied();
        else
            $month_pay = ($period->value / $period->fees) / $period->cant_properties_implied();
        
        return $month_pay * $period->fees;
        
    }
    
    public function set_extraordinary_finished($period){
        
        $epp = ExtraordinaryPeriodProperty::find_by_sql("SELECT * 
                                                           FROM extraordinary_period_properties epp 
                                                          WHERE epp.property_id = ".$this->id." 
                                                            AND epp.extraordinary_period_id = ".$period->id." ");

        $ext_per_prop = ExtraordinaryPeriodProperty::find($epp[0]->id);
        $ext_per_prop->state = 3;
        $ext_per_prop->save();

    }

    public function set_extraordinary_unfinished($period){
        
        $epp = ExtraordinaryPeriodProperty::find_by_sql("SELECT * 
                                                           FROM extraordinary_period_properties epp 
                                                          WHERE epp.property_id = ".$this->id." 
                                                            AND epp.extraordinary_period_id = ".$period->id." ");

        $ext_per_prop = ExtraordinaryPeriodProperty::find($epp[0]->id);
        $ext_per_prop->state = 1;
        $ext_per_prop->save();

    }

    
    public function has_current_unpaid_extraordinary($extraordinary_period_id){
        $extraordinary = $this->has_paid_current_extraordinary_month($extraordinary_period_id ,"AND et.type_pay = 'unpaid'");        
        if($extraordinary)
            return $extraordinary[0];
        else
            return false;
    }
    
    public function get_last_extraordinary_transaction($extraordinary_period){
        $one_month_back = $this->building->one_month_back();
        $transaction = ExtraordinaryTransaction::find("all" , array( "conditions" => "property_id = '".$this->id."' AND extraordinary_period_id = '".$extraordinary_period->id."' AND period_date = '".$one_month_back."' "));
//        var_die($transaction);
        if ($transaction)
            return $transaction[0];
        else
            return null;
    }    

    public function has_extraordinary_period_active() {

        $building = $this->building;
        $extraordinary_periods = $building->get_actives_extraordinaries_period();

        if ( count($extraordinary_periods) > 0 ){

            $properties_actives = $extraordinary_periods[0]->get_actives_properties();

            foreach ($properties_actives as $key => $value) {
                if ($value->id == $this->id) {
                    return true;
                }
            } 
            return false;

        }

    }
    
    public function value_extraordinary_due($period){
        $last_transaction = $this->get_last_extraordinary_transaction($period);
        if ($last_transaction == null)
            $balance = 0;       
        else{
            $balance = $last_transaction->last_balance;
        }
        
        if($balance < 0)
            return $balance * $period->tax_percentage;
        else
            return $balance;
            
    }
    
    public function value_of_extraordinary_due($period){
        return $this->balance_extraordinary * $period->tax_percentage / 100;
    }
    
    public function value_to_pay_extraordinary($period){
        $last_transaction = $this->get_last_extraordinary_transaction($period);
        if ($last_transaction == null)
            $balance = 0;       
        else{
            $balance = abs($last_transaction->property->balance_extraordinary);
        }
        
        return $this->value_of_extraordinary_fee($period) + $balance + abs($this->value_of_extraordinary_due($period));
            
    }
    
    public function total_to_pay_extraordinary($extraordinary_periods){
        
        if ($extraordinary_periods != null){
            $total = 0;

            foreach ($extraordinary_periods as $period) {
                $total = $total + $this->value_to_pay_extraordinary($period);
            }

            return $total;
        }
        else
            return 0;
            
    }

    public function total_to_pay_all_extraordinaries() {

        $actives_extraordinary_periods = $this->building->get_actives_extraordinaries_period();
        if (!is_null($actives_extraordinary_periods)) {
            return $this->total_to_pay_extraordinary($actives_extraordinary_periods);
        } else { 
            return 0;
        }

    }
    
    public function value_of_extraordinary_fee($period){
        
        if($period->building->actual_period >= $period->date_to){
            return 0;
        }
        else{
            if($period->pay_form == DIVIDE_COEFICCIENT)
                //return ($this->coefficient * ($period->value / $period->fees)) / $this->building->total_coefficient;
                return ($this->coefficient * ($period->value / $period->fees)) / $period->total_coefficient_of_properties_implied();
            else
                //return ($period->value / $period->fees) / $this->building->cant_properties();
                return ($period->value / $period->fees) / $period->cant_properties_implied();
        }
            
    }
    
    public function get_current_extra_pay_month($period){
        $income = $this->has_paid_current_extraordinary_month($period->id);
        if ($income)
            return $income[0];
        else
            return false;
    }
    
    public function has_extraordinary_due(){
        
        if ($this->balance_extraordinary < 0)
            return true;
        else
            return false;
        
    }
    
    public function total_to_pay_all_extraordinary($period){
        return $this->value_of_extraordinary_fee($period) * $period->fees;
    }
    
    /*public function has_paid_all_extraordinary($period){
        
        $paid = $this->get_total_paid_for_period($period);
        
        if ($paid == null){
            return false;
        }
        else{
            
            if ($paid < $this->total_to_pay_all_extraordinary($period)) {
                return false;
            }
            else
                return true;
        }
        
    }*/
    
    public function get_total_paid_for_period($period){
        
        $extraordinary = ExtraordinaryTransaction::find_by_sql("SELECT sum(et.value) as paid
                                                                  FROM extraordinary_transactions et                                                                       
                                                                 WHERE et.property_id = ".$this->id. "
                                                                   AND et.extraordinary_period_id = $period->id 
                                                                   AND et.type_pay <> 'unpaid'");
        
        if ($extraordinary[0]->paid != null)
            return $extraordinary[0]->paid;
        else
            return null;
        
    }

    // Special Expense Methods
    public function get_special_expense_to_pay($special_expense_model){
        if ($special_expense_model->type_special_expense->is_divided_by_coeficient()){
            return  ($this->coefficient * $special_expense_model->value) / $this->building->total_coefficient;
        }
        return 0;
    }

    // Hash for bills
    public function current_hash_ordinary_bill(){
        return md5("ord" . $this->id . $this->building->id . $this->building->actual_period->format("Y-m-d"));
    }

    //************* Roela Bank Methods *************//
    public function get_current_payment_font_code(){
        return RoelaBarcodeUtils::create_codebar_font($this->get_current_payment_code());
    }

    public function get_current_extraordinary_payment_font_code(){
        return RoelaBarcodeUtils::create_codebar_font($this->get_current_extraordinary_payment_code());
    }

    public function is_current_ordinary_close(){
        $total_to_pay = $this->total_to_pay();
        return $total_to_pay < 100000.00;
    }

    public function is_current_extraordinary_close(){
        $total_to_pay = $this->total_to_pay_all_extraordinaries();
        return $total_to_pay < 100000.00;
    }

    public function get_current_payment_code(){
        $total_to_pay = $this->total_to_pay();
        if ($total_to_pay > 99999.99){
            return $this->get_current_payment_open_code($total_to_pay);
        } else {
            return $this->get_current_payment_close_code($total_to_pay);
        }
    }

    public function get_current_extraordinary_payment_code(){
        $total_to_pay = $this->total_to_pay_all_extraordinaries();
        if ($total_to_pay > 99999.99){
            return $this->get_current_extraordinary_payment_open_code();
        } else {
            return $this->get_current_extraordinary_payment_close_code($total_to_pay);
        }
    }

    public function get_bank_identifier_string() {
        return $this->building->get_bank_identifier_string() . sprintf('%04d', $this->bank_payment_id);
    }

    public function get_payment_date() {
        return date("y",strtotime($this->building->date_next_period())) . date("m",strtotime($this->building->date_next_period())) . '15';
    }

    // Ordinary ammount
    public function get_payment_ammount_codebar() {
        $number = number_format($this->total_to_pay(), 2, '', '');
        return sprintf('%07d', $number) ;
    }

    // Extraordinary ammount
    public function get_extraordinary_payment_ammount_codebar() {
        $number = number_format($this->total_to_pay_all_extraordinaries(), 2, '', '');
        return sprintf('%07d', $number) ;
    }

    // Ordinaries Codebar
    public function get_current_payment_close_code($total_to_pay) {
        return RoelaBarcodeUtils::checksum_int25(RB_SIRO_CODE . RB_ORDINARY_EXPENSE . $this->get_bank_identifier_string() . $this->get_payment_date() . $this->get_payment_ammount_codebar() . RB_FILLER_ZEROS_18 . $this->building->get_bank_payment_account_string());
        
    }

    public function get_current_payment_open_code() {
        return RoelaBarcodeUtils::checksum_int25(RB_SIRO_CODE . RB_ORDINARY_EXPENSE_OPEN . $this->get_bank_identifier_string() . RB_FILLER_ZEROS_31 . $this->building->get_bank_payment_account_string());
    }

    // Extraordinary codebar
    public function get_current_extraordinary_payment_close_code($total_to_pay) {
        return RoelaBarcodeUtils::checksum_int25(RB_SIRO_CODE . RB_EXTRAORDINARY_EXPENSE . $this->get_bank_identifier_string() . $this->get_payment_date() . $this->get_extraordinary_payment_ammount_codebar() . RB_FILLER_ZEROS_18 . $this->building->get_bank_payment_account_string());
    }

    public function get_current_extraordinary_payment_open_code() {
        return RoelaBarcodeUtils::checksum_int25(RB_SIRO_CODE . RB_EXTRAORDINARY_EXPENSE_OPEN . $this->get_bank_identifier_string() . RB_FILLER_ZEROS_31 . $this->building->get_bank_payment_account_string());
    }

    //************* DIGITAL BILL METHODS *************//
    public function payment_type_for_ammount($ammount, $operation_type_id) {
        
        if ($operation_type_id == RB_ORDINARY_EXPENSE || $operation_type_id == RB_ORDINARY_EXPENSE_OPEN) {
            return $this->payment_type_for_ammount_ordinary($ammount);
        } else if ($operation_type_id == RB_EXTRAORDINARY_EXPENSE || $operation_type_id == RB_EXTRAORDINARY_EXPENSE_OPEN) {
            return $this->payment_type_for_ammount_extraordinary($ammount);
        }
        
    }

    public function payment_type_for_ammount_ordinary($ammount) {
        $ammount_to_pay = round($this->total_to_pay(), 2);
        if ($ammount >= $ammount_to_pay) {
            return FIRST_PAY;
        } else if ($ammount < $ammount_to_pay) {
            return ACCOUNT_PAY;
        }
    }

    public function payment_type_for_ammount_extraordinary($ammount) {
        $ammount_to_pay = round($this->total_to_pay_all_extraordinaries(), 2);
        if ($ammount >= $ammount_to_pay) {
            return COMPLETE_PAY;
        } else if ($ammount < $ammount_to_pay) {
            return ACCOUNT_PAY;
        }
    }

    public function last_extraodinary_active_id() {
        $extraordinary = ExtraordinaryPeriod::last_active_for_building($this->building_id);
        if ($extraordinary[0]) {
            return $extraordinary[0]->id;
        } else {
            return null;
        }
        
    }

    public function allow_to_pay_by_bank() {
        $total_to_pay = $this->total_to_pay();
        if ($total_to_pay > 99999.99){
            return false;
        } else {
            return true;
        }
    }

    public function get_current_payment_digital_date_bill(){
        return date("m",strtotime($this->building->date_next_period())) . date("y",strtotime($this->building->date_next_period()));
    }

    public function get_current_payment_digital_date() {
        return date("Y",strtotime($this->building->date_next_period())) . date("m",strtotime($this->building->date_next_period())) . '15';
    }
    
    public function get_payment_ammount_digital() {
        $number = number_format(round($this->total_to_pay(), 2), 2, '', '');
        return sprintf('%011d', $number);
    }

    public function get_bank_digital_description() {
        return mb_convert_encoding(RoelaBarcodeUtils::replace_special_characters_ascii("EXPENSAS " . $this->building->street . " " . $this->building->number . " " . $this->floor . $this->appartment . " " . $this->owner->lastname . " " . $this->owner->name), "ASCII");
    }

    public function get_bank_digital_short_description() {
        return substr($this->get_bank_digital_long_description(), 0, 15);
    }

    public function get_bank_digital_long_description() {

        $description = $this->get_bank_digital_description();
        if (strlen($description) >= 40) {
            return substr($description, 0, 40);
        } else {
            return $description . str_repeat(RB_FILLER_WHITESPACE, (40 - strlen($description)));
        }

    }

    public function get_bank_digital_identifier(){
        return RB_ORDINARY_EXPENSE . $this->get_bank_identifier_string() . $this->building->get_bank_payment_account_string();
    }

    public function get_current_payment_digital_file_row(){
        return RB_SIRO_FILE_DETAIL_INITIAL_CODE . RB_ORDINARY_EXPENSE . $this->get_bank_identifier_string() . $this->building->get_bank_payment_account_string() . RB_SIRO_FILE_DETAIL_NO_BILL_NUMBER . RB_ORDINARY_EXPENSE . $this->get_current_payment_digital_date_bill() . RB_FILLER_ZEROS_1 . $this->get_current_payment_digital_date() . $this->get_payment_ammount_digital() . $this->get_current_payment_digital_date() . $this->get_payment_ammount_digital() . $this->get_current_payment_digital_date() . $this->get_payment_ammount_digital() . RB_FILLER_ZEROS_19 . RB_ORDINARY_EXPENSE . $this->get_bank_identifier_string() . $this->building->get_bank_payment_account_string() . $this->get_bank_digital_long_description() . $this->get_bank_digital_short_description() . str_repeat(RB_FILLER_WHITESPACE, 60) . RB_FILLER_ZEROS_29;
    }

    public function get_payment_extraordinary_ammount_digital() {
        $number = number_format(round($this->total_to_pay_all_extraordinaries(), 2), 2, '', '');
        return sprintf('%011d', $number);
    }

    public function get_bank_digital_extraordinary_description() {
        return mb_convert_encoding(RoelaBarcodeUtils::replace_special_characters_ascii("EXPENSAS EXTRAORDINARIA " . $this->building->street . " " . $this->building->number . " " . $this->floor . $this->appartment . " " . $this->owner->lastname . " " . $this->owner->name), "ASCII");
    }

    public function get_bank_digital_extraordinary_short_description() {
        return substr($this->get_bank_digital_extraordinary_long_description(), 0, 15);
    }

    public function get_bank_digital_extraordinary_long_description() {

        $description = $this->get_bank_digital_extraordinary_description();
        if (strlen($description) >= 40) {
            return substr($description, 0, 40);
        } else {
            return $description . str_repeat(RB_FILLER_WHITESPACE, (40 - strlen($description)));
        }

    }

    public function get_bank_digital_extraordinary_identifier(){
        return RB_EXTRAORDINARY_EXPENSE . $this->get_bank_identifier_string() . $this->building->get_bank_payment_account_string();
    }

    public function get_current_extraordinary_payment_digital_file_row(){
        return RB_SIRO_FILE_DETAIL_INITIAL_CODE . RB_EXTRAORDINARY_EXPENSE . $this->get_bank_identifier_string() . $this->building->get_bank_payment_account_string() . RB_SIRO_FILE_DETAIL_NO_BILL_NUMBER . RB_EXTRAORDINARY_EXPENSE . $this->get_current_payment_digital_date_bill() . RB_FILLER_ZEROS_1 . $this->get_current_payment_digital_date() . $this->get_payment_extraordinary_ammount_digital() . $this->get_current_payment_digital_date() . $this->get_payment_extraordinary_ammount_digital() . $this->get_current_payment_digital_date() . $this->get_payment_extraordinary_ammount_digital() . RB_FILLER_ZEROS_19 . RB_EXTRAORDINARY_EXPENSE . $this->get_bank_identifier_string() . $this->building->get_bank_payment_account_string() . $this->get_bank_digital_extraordinary_long_description() . $this->get_bank_digital_extraordinary_short_description() . str_repeat(RB_FILLER_WHITESPACE, 60) . RB_FILLER_ZEROS_29;
    }
    //************* Roela Bank Methods *************//

}
?>
