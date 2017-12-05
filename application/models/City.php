<?php

class City extends ActiveRecord\Model{
    static $belongs_to = array(
        array('zone', 'readonly' => true)
    );
}
?>