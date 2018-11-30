<?php

class ExtraordinaryTransaction extends ActiveRecord\Model{
    
    static $belongs_to = array(
        array('property'),
        array('extraordinary_period')
    );
    
    public static function exist_bank_payment($bank_payment_id) {
        $payment = addslashes($bank_payment_id);
		$income = ExtraordinaryTransaction::find_by_sql("SELECT et.*
                                                   		   FROM extraordinary_transactions et 
                                                  		  WHERE et.bank_payment_id = '$payment'");

        if (count($income) > 0) {
            return true;
        } else{
            return false;
        }
	}

}
?>