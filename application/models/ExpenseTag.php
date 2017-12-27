<?php

class ExpenseTag extends ActiveRecord\Model{
    
    static $belongs_to = array(
        array('building', 'readonly' => true),
        array('expense_balance_tag', 'readonly' => true)
    );
    
}
?>