<?php

class EstimativeExpenseTransaction extends ActiveRecord\Model{
    
    static $belongs_to = array(
        array('building'),
        array('type_expense','foreign_key' => 'type_expense_id'),
        array('expense_tag','foreign_key' => 'expense_tag_id',)
    );
    
}
?>