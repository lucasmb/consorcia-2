<?php

class ExtraordinaryPeriodProperty extends ActiveRecord\Model{
    
    static $belongs_to = array(
        array('property', 'readonly' => true),
        array('extraordinary_period', 'readonly' => true)
    );
    
    
}

?>

