<?php

class Pdf_test extends CI_Controller{

    function pdf_test()
    {
        parent::__construct();
    }

    function index(){
        $building = Building::find(36);
        $period = ExtraordinaryPeriod::find(1);
        foreach ($building->properties as $property):
            echo "Propiedad : ".$property->id;
            echo "pago todas las cuotas: ". var_dump($property->has_paid_all_extraordinary($period));
            echo "<br>";
        endforeach;
    }  

}

