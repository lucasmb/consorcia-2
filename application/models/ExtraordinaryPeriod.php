<?php

define("EXTRAORDINARY_PERIOD_STATE_CREATED", 0);
define("EXTRAORDINARY_PERIOD_STATE_ACTIVE", 1);
define("EXTRAORDINARY_PERIOD_STATE_CLOSE", 3);

class ExtraordinaryPeriod extends ActiveRecord\Model{
    
    static $belongs_to = array(
        array('building', 'readonly' => true)    
    );
    
    static $has_many = array(
        array('extraordinary_transaction', 'class_name' => 'ExtraordinaryTransaction', 'foreign_key' => 'extraordinary_period_id'),
        array('extraordinary_expense', 'class_name' => 'ExtraordinaryExpense', 'foreign_key' => 'extraordinary_period_id')
    );
    
    public static function last_active_for_building($building) {
        
        $result = ExtraordinaryPeriod::find_by_sql("SELECT *
                                                      FROM extraordinary_periods et
                                                     WHERE et.building_id = $building
                                                       AND et.state = 1
                                                  ORDER BY et.id ASC");
        return $result;
    }

    public function get_number_of_fees($building_actual_period){
        
        $result = ExtraordinaryPeriod::find_by_sql("SELECT PERIOD_DIFF(".$building_actual_period->format("Ym").",".$this->date_from->format("Ym").") as meses");
        
        if ($result[0])
            return $result[0]->meses + 1;
        else            
            return 0;
    }
    
    public function properties_implied(){
        switch ($this->properties_type) {
            case 1:
                return $this->building->properties;
                break;
            
            case 2:
                $properties = Property::all(array('conditions' => "building_id = $this->building_id AND
                                                                   auxiliary_property_of IS NULL"));
                return $properties;

                break;

            case 3:
                $properties = Property::find_by_sql("SELECT * "
                        . "                            FROM properties "
                        . "                           WHERE id IN (SELECT epp.property_id "
                        . "                                          FROM extraordinary_period_properties epp "
                        . "                                         WHERE epp.extraordinary_period_id = $this->id)");
                return $properties;

                break;

            default:
                break;
        }
    }
    
    public function get_properties_pay_unfinish_properties(){
        $properties = Property::find_by_sql("SELECT * "
                        . "                    FROM properties "
                        . "                   WHERE id IN (SELECT epp.property_id "
                        . "                                  FROM extraordinary_period_properties epp "
                        . "                                 WHERE epp.extraordinary_period_id = $this->id"
                        . "                                   AND epp.state = 1)");
        return $properties;
    }
    
    public function cant_properties_implied(){
        $properties = $this->properties_implied();
        return count($properties);
    }
    
    public function total_coefficient_of_properties_implied(){
        switch ($this->properties_type) {
            case 1:
                return $this->building->properties;
                break;
            
            case 2:
                // si se arreglan los coeficientes
                $coefficient = Property::find_by_sql("SELECT SUM(coefficient) as sum
                                                        FROM properties 
                                                       WHERE building_id = $this->building_id");
                        
                //return $coefficient[0]->sum;
                
                // si se mantienen como estan
                $coefficient_null = Property::find_by_sql("SELECT SUM(coefficient) as sum
                                                        FROM properties 
                                                       WHERE building_id = $this->building_id
                                                         AND auxiliary_property_of IS NOT NULL");
                        
                return $coefficient[0]->sum - $coefficient_null[0]->sum;

                break;

            case 3:
                
                $coefficient = Property::find_by_sql("SELECT SUM(coefficient) as sum
                                                        FROM properties 
                                                       WHERE id IN (SELECT epp.property_id "
                        . "                                          FROM extraordinary_period_properties epp "
                        . "                                         WHERE epp.extraordinary_period_id = $this->id)");
               
                return $coefficient[0]->sum;
                
                break;

            default:
                break;
        }
    }   
    
    public function get_actives_properties(){
        
        $properties = Property::find_by_sql("  SELECT * "
                                . "              FROM properties p "
                                . "             WHERE p.id IN "
                                . "                     (SELECT prop.id "
                                . "                        FROM extraordinary_period_properties epp "
                                . "                  INNER JOIN properties prop ON (prop.id = epp.property_id) "
                                . "                       WHERE epp.extraordinary_period_id = $this->id AND epp.state = 1)");
        
        
        return $properties;
        
    }
    
    public function get_current_paid_properties(){
        
        $properties = array();
        $propeties_active = $this->properties_implied();
        
        foreach ($propeties_active as $property) {
            if ($property->has_paid_current_extraordinary_month($this->id)){
                $properties[] = $property;
            }
        }
        
        return $properties;
        
    }
    
    public function get_current_unpaid_properties(){
        
        $properties = array();
        $propeties_active = $this->get_actives_properties();
        
        foreach ($propeties_active as $property) {
            if (!$property->has_paid_current_extraordinary_month($this->id)){
                $properties[] = $property;
            }
        }
        
        return $properties;
        
    }
    
    public function has_current_income_activity(){
        $has_income_activity = false;
        foreach ($this->extraordinary_transaction as $income):
            if ($income->period_date->format("Y-m-d") == $this->building->actual_period->format("Y-m-d"))
                $has_income_activity = true;
        endforeach;
        return $has_income_activity;
    }
    
    public function has_all_pays_done(){
        
        $all_pays_done = true;
        
        foreach ($this->properties_implied() as $property):
            if (!$property->has_paid_all_extraordinary($this))
                $all_pays_done = false;
        endforeach;
        
        return $all_pays_done;
        
    }
    
    public function has_current_expense_activity(){
        $has_expense_activity = false;
        foreach ($this->extraordinary_expense as $expense):
            if ($expense->period_date->format("Y-m-d") == $this->building->actual_period->format("Y-m-d"))
                $has_expense_activity = true;
        endforeach;
        return $has_expense_activity;
    }

    public function period_state_name(){

        switch ($this->state) {
            case 0:
                return "Creada";
                break;
            case 1:
                return "En curso";
                break;
            case 3:
                return "Cerrada";
                break;
            
            default:
                return "Otro";
                break;
        }

    }
    
}

?>