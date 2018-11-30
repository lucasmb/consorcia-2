<?php

class ExpenseBalanceTag extends ActiveRecord\Model{
    
    static $belongs_to = array(
        array('building', 'readonly' => true)
    );
    
    static $has_many = array(
        array('expense_tags',  'readonly' => false)
    );
    
}
?>
