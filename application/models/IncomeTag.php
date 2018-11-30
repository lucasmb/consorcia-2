<?php

class IncomeTag extends ActiveRecord\Model{
    
    static $belongs_to = array(
        array('income_balance_tag', 'readonly' => false)
    );
    
}
