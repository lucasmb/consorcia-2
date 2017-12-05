<?php

class EarningMonthlyLog extends ActiveRecord\Model{
    
    static $belongs_to = array(
        array('building', 'readonly' => true)      
    );
    
}
?>