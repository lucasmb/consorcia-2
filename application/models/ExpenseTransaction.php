<?php

class ExpenseTransaction extends ActiveRecord\Model{
    
    static $belongs_to = array(
        array('building'),
        array('type_expense','foreign_key' => 'type_expense_id'),
        array('expense_tag','foreign_key' => 'expense_tag_id',)
    );

    public static function similar_expense($expense) {
        
        $building_id = $expense->building->id;
        $result = ExpenseTransaction::find_by_sql("SELECT *
                                                     FROM expense_transactions et
                                                    WHERE et.building_id = '$building_id'
                                                      AND et.value = $expense->value
                                                      AND et.type_expense_id = $expense->expense_type
                                                      AND et.period_date = '$expense->period_date'");

        if (count($result) > 0) {
        	return $result[0];
        } else {
        	return false;	
        }

    }
    
}
?>