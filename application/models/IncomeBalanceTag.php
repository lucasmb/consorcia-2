<?php

class IncomeBalanceTag extends ActiveRecord\Model{
   
    static $has_many = array(
        array('incomes_tags',  'readonly' => false)
    );
    
}
?>