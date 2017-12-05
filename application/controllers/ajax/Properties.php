<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Properties extends CI_Controller {

    
        function __construct()
        {
            parent::__construct();
        }
		
        
        public function add_property(){
            $id_building = $this->input->post("id",true);
            if ($id_building != ""){
                $args['id_building'] = $id_building;
                $args['building'] = Building::find($id_building);
                $body = $this->load->view("/ajax/property/property_form",$args, true);
                echo $body;
            }
        }
        
        public function edit_property(){
            $id_property = $this->input->post("id",true);
            if ($id_property != ""){
                
                $property = Property::find($id_property);
                $args['property'] = $property;
                $args['building'] = $property->building;
                $args['id_building'] = $property->building_id;
                $args['owner'] = $property->owner;
                $args['auxiliary_property_of'] = $property->auxiliary_property_of;
                $body = $this->load->view("/ajax/property/property_form",$args, true);
                echo $body;
            }
        }
        
        public function modify_property(){
            $owner_id = $this->input->post("owner");
            $property_id = $this->input->post("id_property");
            $this->load->library("form_validation");
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            
            if ($property_id != ""){
                $property = Property::find($property_id);
                if ($owner_id == ""){
                    // Nuevo Propietario
                    if ($this->form_validation->run('save_property_new_owner') == FALSE){
                        echo validation_errors ();
                        die();
                    }                        
                    else{
                        $attr_person['name'] = $this->input->post("name",true);
                        $attr_person['lastname'] = $this->input->post("lastname",true);
                        $attr_person['phone'] = $this->input->post("phone",true);
                        $attr_person['alternative_phone'] = $this->input->post("alternative_phone",true);
                        $attr_person['street'] = $this->input->post("street",true);
                        $attr_person['number_street'] = $this->input->post("number_street",true);
                        $attr_person['floor'] = $this->input->post("floor_owner",true);
                        $attr_person['appartment'] = $this->input->post("appartment_owner",true);
                        $attr_person['document'] = $this->input->post("document",true);
                        $attr_person['type_document_id'] = $this->input->post("type_document",true);
                        $attr_person['cuit'] = $this->input->post("cuit",true);
                        $attr_person['type'] = "1";

                        $owner = Person::create($attr_person);
                    }
                
                }
                else{
                // Propietario ya cargado    
                    $owner = Person::find($owner_id);
                }
                
                $auxiliary_property_of = $this->input->post("auxiliary_property_of",true);
                if ($auxiliary_property_of != "")
                    $property->auxiliary_property_of = $auxiliary_property_of;
                $property->floor = $this->input->post("floor",true);
                $property->appartment = $this->input->post("appartment",true);
                $property->owner_id = $owner->id;
                $property->building_id = $this->input->post("id_building",true);
                $property->coefficient = $this->input->post("coefficient",true);
                $property->functional_unity = $this->input->post("functional_unity",true);
                $property->balance = $this->input->post("balance",true);
                $property->balance_reserve = $this->input->post("balance_reserve",true);
                $property->balance_extraordinary  = $this->input->post("balance_extraordinary",true);
                //$property->meters = $this->input->post("meters",true);
                $property->static_reserve_value = $this->input->post("static_reserve_value",true);

                $property->save();
                
                echo "success:".$this->input->post("id_building",true).":";
            }
        }
        
        public function save_property(){
            $owner_id = $this->input->post("owner");
            $this->load->library("form_validation");
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            if ($owner_id == ""){
                // Nuevo Propietario
                if ($this->form_validation->run('save_property_new_owner') == FALSE)
                    echo validation_errors ();                
                else{
                    
                    $attr_person['name'] = $this->input->post("name",true);
                    $attr_person['lastname'] = $this->input->post("lastname",true);
                    $attr_person['phone'] = $this->input->post("phone",true);
                    $attr_person['alternative_phone'] = $this->input->post("alternative_phone",true);
                    $attr_person['street'] = $this->input->post("street",true);
                    $attr_person['number_street'] = $this->input->post("number_street",true);
                    $attr_person['floor'] = $this->input->post("floor_owner",true);
                    $attr_person['appartment'] = $this->input->post("appartment_owner",true);
                    $attr_person['document'] = $this->input->post("document",true);
                    $attr_person['type_document_id'] = $this->input->post("type_document",true);
                    $attr_person['cuit'] = $this->input->post("cuit",true);
                    $attr_person['type'] = "1";
                    
                    $owner = Person::create($attr_person);
                    
                    $attr_property['floor'] = $this->input->post("floor",true);
                    $attr_property['appartment'] = $this->input->post("appartment",true);
                    $attr_property['owner_id'] = $owner->id;
                    $attr_property['building_id'] = $this->input->post("id_building",true);
                    $attr_property['coefficient'] = $this->input->post("coefficient",true);
                    $attr_property['functional_unity'] = $this->input->post("functional_unity",true);
                    $attr_property['balance'] = $this->input->post("balance",true);
                    $attr_property['balance_reserve'] = $this->input->post("balance_reserve",true);
                    $attr_property['balance_extraordinary'] = $this->input->post("balance_extraordinary",true);
                    //$attr_property['meters'] = $this->input->post("meters",true);
                    $attr_property['static_reserve_value'] = $this->input->post("static_reserve_value",true);
                    
                    $auxiliary_property_of = $this->input->post("auxiliary_property_of",true);
                    if ($auxiliary_property_of != "")
                        $attr_property["auxiliary_property_of"] = $auxiliary_property_of;
                    
                    Property::create($attr_property);
                    
                    echo "success:".$this->input->post("id_building",true).":";
                }                    
                
            }
            else{
                // Propietario ya cargador
                if ($this->form_validation->run('save_property_with_owner') == FALSE)
                    echo validation_errors ();
                else{
                    
                    $owner = Person::find($owner_id);
                    
                    if ($owner){
                        
                        $attr_property['floor'] = $this->input->post("floor",true);
                        $attr_property['appartment'] = $this->input->post("appartment",true);
                        $attr_property['owner_id'] = $owner->id;
                        $attr_property['building_id'] = $this->input->post("id_building",true);
                        $attr_property['coefficient'] = $this->input->post("coefficient",true);
                        $attr_property['functional_unity'] = $this->input->post("functional_unity",true);
                        $attr_property['balance'] = $this->input->post("balance",true);
                        $attr_property['balance_reserve'] = $this->input->post("balance_reserve",true);
                        $attr_property['balance_extraordinary'] = $this->input->post("balance_extraordinary",true);
                        //$attr_property['meters'] = $this->input->post("meters",true);
                        $attr_property['static_reserve_value'] = $this->input->post("static_reserve_value",true);
                        $auxiliary_property_of = $this->input->post("auxiliary_property_of",true);
                        if ($auxiliary_property_of != "")
                            $attr_property["auxiliary_property_of"] = $auxiliary_property_of;

                        Property::create($attr_property);


                        echo "success:".$this->input->post("id_building",true).":";
                    }
                }                
            }
        }
        
        public function view_owner(){
            $owner_id = $this->input->post("id");
            $only_view = $this->input->post("view");
            
            if (!is_null($only_view) && $only_view):
                echo "<h1>Propietario</h1>";
                echo "<div class='contenedor_property'>";
            endif;
            
            if ($owner_id){
                $owner = Person::find($owner_id);
                $args['owner'] = $owner;
                echo $this->load->view("ajax/property/owner",$args, true);
            }
            else
                echo $this->load->view("ajax/property/owner", null, true);

        }
      
        public function delete_property(){
            $property_id = $this->input->post("id",true);
            
            if ($property_id){
                $property = Property::find($property_id);
                if ($property->delete())
                    echo "ok";
            }
            else
                echo "error al eliminar";
        }
        
        public function order_properties(){
            
            $building_id = $this->input->post("building_id",true);
            $sort_column = $this->input->post("sort_column",true);
            $order = $this->input->post("order",true);
            
            if ($building_id != '' && $sort_column != ''){                
                if ($order == "ASC")
                    $args["order"] = "DESC";
                else
                    $args["order"] = "ASC";
                
                $building = Building::find($building_id);
                $args["properties"] = $building->get_properties_sorted_by($sort_column, $order);
                $args["sort_column"] = $sort_column;
                
                echo $this->load->view("ajax/building/properties_table",$args, true);
            }
            
        }
    
}

/* End of file properties.php */
/* Location: ./application/controllers/properties.php */