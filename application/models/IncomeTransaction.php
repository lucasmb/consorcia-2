<?php

class IncomeTransaction extends ActiveRecord\Model{
    
    static $belongs_to = array(
        array('property')
    );
    
	public static function exist_bank_payment($bank_payment_id) {
		$income = IncomeTransaction::find_by_sql("SELECT it.*
                                                    FROM income_transactions it 
                                                   WHERE it.bank_payment_id = '$bank_payment_id'");

        if (count($income) > 0) {
            return true;
        } else{
            return false;
        }
	}

    public static function exist_bank_payment_by_code($payment_code) {
        //$payment = PDO::quote($payment_code);
        $payment = addslashes($payment_code);
        $income = IncomeTransaction::find_by_sql("SELECT it.* FROM income_transactions it WHERE SUBSTRING(it.bank_payment_id,41,56) = '$payment'");
        return count($income) > 0;
    }

}
?>
