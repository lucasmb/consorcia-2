<?php

class IncomeBalanceTag extends ActiveRecord\Model{
   
    static $has_many = array(
        array('income_tags',  'readonly' => false)
    );
    
}
?>
