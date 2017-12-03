<?php

function vd($value, $options = array()) {
    if(ENVIRONMENT == 'production')
        return FALSE;
    $default = array('message' => '');
    $options = array_merge($default, $options);

    var_dump($value);
    die($options['message']);
}

function ed($value, $options = array()) {
    $default = array('message' => '');
    $options = array_merge($default, $options);

    echo($value);
    die($options['message']);
}