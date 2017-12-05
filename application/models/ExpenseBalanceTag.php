<?php

class ExpenseBalanceTag extends ActiveRecord\Model{
    
    static $belongs_to = array(
        array('building', 'readonly' => true)
    );
    
    static $has_many = array(
        array('expenses_tags',  'readonly' => false)
    );
    
}
?>