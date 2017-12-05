<?php

class PropertyLog extends ActiveRecord\Model{

    static $belongs_to = array(
        array('owner', 'class_name' => 'Person', 'foreign_key' => 'owner_id', 'readonly' => true),
        array('property', 'readonly' => true)      
        
    );
}

?>