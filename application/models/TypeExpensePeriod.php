<?php

class TypeExpensePeriod extends ActiveRecord\Model{
    
    
    public function update_month_name(){
        $date = date("Y-m-d");
        
        if ($this->type_name == "actual")
            $newdate = strtotime ( '+1 month' , strtotime( $date ));
            
        else
            $newdate = strtotime($date);
  
        
        $meses = array("Enero", "Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = intval(date( 'm' , $newdate ));
        $this->month_name = $meses[$mes-1];
        $this->date = date( 'Y-m' , $newdate );
        
        $this->save();
    }
}

?>
