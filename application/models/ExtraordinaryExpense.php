<?php

class ExtraordinaryExpense extends ActiveRecord\Model{
    
    static $belongs_to = array(
        array('building'),
        array('extraordinary_period','foreign_key' => 'extraordinary_period_id'),
        array('expense_tag','foreign_key' => 'expense_tag_id')
        
    );
    
}
?>