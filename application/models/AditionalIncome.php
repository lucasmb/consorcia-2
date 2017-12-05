<?php

class AditionalIncome extends ActiveRecord\Model{
    
    static $belongs_to = array(
        array('building', 'readonly' => true),
        array('income_tag', 'class_name' => 'IncomeTag', 'foreign_key' => 'income_tag_id')

    );
    
}
?>