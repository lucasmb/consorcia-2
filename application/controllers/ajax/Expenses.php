<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expenses extends CI_Controller {

    
    function __construct()
    {
        parent::__construct();
    }

    public function add_expense(){
        
        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run('add_expense') == FALSE){
            echo validation_errors();
        }
        else{

            $id = $this->input->post("building",true);
            $descripcion = $this->input->post("expense_tags",true);
            $type = $this->input->post("type_expense",true);
            $date = $this->input->post("date",true);
            $building = Building::find($id);
            
            if ($date)
                $attr["date"] = $date;
            else
                $attr["date"] = date("Y-m-d");
            
            $tag = ExpenseTag::find(array('conditions' => array('name = ? AND building_id = ?','$descripcion','$building->id')));
                        
            if (!$tag){
                $attr_tag["name"] = $descripcion;
                $attr_tag["building_id"] = $id;
                $tag = ExpenseTag::create($attr_tag);                
            }
                
            $attr["expense_tag_id"] = $tag->id;
            $attr["type_expense_id"] = $type;
            $attr["period_date"] = $building->actual_period;
            $attr["building_id"] = $id;
            $attr["priority"] = $this->input->post("priority",true);;
            $attr["value"] = $this->input->post("value",true);
                
            ExpenseTransaction::create($attr);
            
            echo "success";
        }
    }
    
    public function add_older_expense(){
        
        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run('add_older_expense') == FALSE){
            echo validation_errors();
        }
        else{

            $id = $this->input->post("expense_older_building_id",true);
            $descripcion = $this->input->post("expense_older_tags",true);
            $type = $this->input->post("type_older_expense",true);
            $date = $this->input->post("date_older",true);
            $building = Building::find($id);
            
            if ($date)
                $attr["date"] = $date;
            else
                $attr["date"] = date("Y-m-d");
            
            $tag = ExpenseTag::find(array('conditions' => array('name = ? AND building_id = ?','$descripcion','$building->id')));
                        
            if (!$tag){
                $attr_tag["name"] = $descripcion;
                $attr_tag["building_id"] = $id;
                $tag = ExpenseTag::create($attr_tag);                
            }
                
            $attr["expense_tag_id"] = $tag->id;
            $attr["type_expense_id"] = $type;
            $attr["period_date"] = $building->actual_period;
            $attr["building_id"] = $id;
            $attr["priority"] = $this->input->post("expense_older_priority",true);;
            $attr["value"] = $this->input->post("value_older",true);
                
            ExpenseTransaction::create($attr);
            
            echo "success";
        }
    }    
    
    public function add_extraordinary_expense(){
        
        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run('add_extraordinary_expense') == FALSE){
            echo validation_errors();
        }
        else{

            $id = $this->input->post("building_extra",true);
            $extraordinary_period = $this->input->post("extraordinary_period",true);
            $descripcion = $this->input->post("expense_tags_extraordinary",true);
            $date = $this->input->post("date_extraordinary",true);
            $building = Building::find($id);
            
            if ($date)
                $attr["date"] = $date;
            else
                $attr["date"] = date("Y-m-d");
            
            $tag = ExpenseTag::find(array('conditions' => "(name = '$descripcion')"));
                        
            if (!$tag){
                $attr_tag["name"] = $descripcion;
                $attr_tag["building_id"] = $id;
                $tag = ExpenseTag::create($attr_tag);                
            }
                
            $attr["expense_tag_id"] = $tag->id;
            $attr["extraordinary_period_id"] = $extraordinary_period;
            $attr["period_date"] = $building->actual_period;
            $attr["building_id"] = $id;
            $attr["priority"] = $this->input->post("priority",true);;
            $attr["value"] = $this->input->post("value_extraordinary",true);
                
            ExtraordinaryExpense::create($attr);
            
            echo "success";
        }
    }
    
    public function add_special_expense(){
        
        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run('add_special_expense') == FALSE){
            echo validation_errors();
        }
        else{

            $id = $this->input->post("building_special",true);
            $descripcion = $this->input->post("expense_tags_special",true);
            $type = $this->input->post("type_expense",true);
            $type_special = $this->input->post("type_special_expense",true);
            $date = $this->input->post("date",true);
            $building = Building::find($id);
            
            if ($date)
                $attr["date"] = $date;
            else
                $attr["date"] = date("Y-m-d");
            
            $tag = ExpenseTag::find(array('conditions' => array('name = ? AND building_id = ?','$descripcion','$building->id')));
                        
            if (!$tag){
                $attr_tag["name"] = $descripcion;
                $attr_tag["building_id"] = $id;
                $tag = ExpenseTag::create($attr_tag);                
            }
                
            $attr["expense_tag_id"] = $tag->id;
            $attr["type_expense_id"] = $type;
            $attr["type_special_expense_id"] = $type_special;
            $attr["period_date"] = $building->actual_period;
            $attr["building_id"] = $id;
            $attr["priority"] = $this->input->post("priority",true);;
            $attr["value"] = $this->input->post("value",true);
                
            SpecialExpenseTransaction::create($attr);
            
            echo "success";
        }
    }
    
    public function add_older_special_expense(){
        
        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run('add_older_special_expense') == FALSE){
            echo validation_errors();
        }
        else{

            $id = $this->input->post("special_expense_older_building_id",true);
            $descripcion = $this->input->post("special_expense_older_tags",true);
            $type = $this->input->post("special_type_older_expense",true);
            $type_special = $this->input->post("special_type_older_special_expense",true);
            $date = $this->input->post("special_date_older",true);
            $building = Building::find($id);
            
            if ($date)
                $attr["date"] = $date;
            else
                $attr["date"] = date("Y-m-d");
            
            $tag = ExpenseTag::find(array('conditions' => array('name = ? AND building_id = ?','$descripcion','$building->id')));
                        
            if (!$tag){
                $attr_tag["name"] = $descripcion;
                $attr_tag["building_id"] = $id;
                $tag = ExpenseTag::create($attr_tag);                
            }
                
            $attr["expense_tag_id"] = $tag->id;
            $attr["type_expense_id"] = $type;
            $attr["type_special_expense_id"] = $type_special;
            $attr["period_date"] = $building->actual_period;
            $attr["building_id"] = $id;
            $attr["priority"] = $this->input->post("special_expense_older_priority",true);;
            $attr["value"] = $this->input->post("special_value_older",true);
                
            SpecialExpenseTransaction::create($attr);
            
            echo "success";
        }
    }  

    // New
    public function add_estimative_expense(){
        
        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run('add_estimative_expense') == FALSE){
            echo validation_errors();
        }
        else{

            $id = $this->input->post("building_estimative",true);
            $descripcion = $this->input->post("expense_tags_estimative",true);
            $type = $this->input->post("type_expense",true);
            $date = $this->input->post("date",true);
            $building = Building::find($id);
            
            if ($date)
                $attr["date"] = $date;
            else
                $attr["date"] = date("Y-m-d");
            
            $tag = ExpenseTag::find(array('conditions' => array('name = ? AND building_id = ?','$descripcion','$building->id')));
                        
            if (!$tag){
                $attr_tag["name"] = $descripcion;
                $attr_tag["building_id"] = $id;
                $tag = ExpenseTag::create($attr_tag);                
            }
                
            $attr["expense_tag_id"] = $tag->id;
            $attr["type_expense_id"] = $type;
            $attr["period_date"] = $building->actual_period;
            $attr["building_id"] = $id;
            $attr["priority"] = $this->input->post("priority",true);;
            $attr["value"] = $this->input->post("value",true);
                
            EstimativeExpenseTransaction::create($attr);
            
            echo "success";
        }
    }
    
    public function add_older_estimative_expense(){
        
        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run('add_older_estimative_expense') == FALSE){
            echo validation_errors();
        }
        else{

            $id = $this->input->post("estimative_expense_older_building_id",true);
            $expense_tag_id = $this->input->post("estimative_expense_older_expense_tag_id",true);
            $type = $this->input->post("estimative_type_older_expense",true);
            $date = $this->input->post("estimative_date_older",true);
            $building = Building::find($id);
                
            $attr["expense_tag_id"] = $expense_tag_id;
            $attr["type_expense_id"] = $type;
            $attr["period_date"] = $building->actual_period;
            $attr["building_id"] = $id;
            $attr["date"] = $date;
            $attr["priority"] = $this->input->post("estimative_expense_older_priority",true);;
            $attr["value"] = $this->input->post("estimative_value_older",true);
                
            EstimativeExpenseTransaction::create($attr);
            
            echo "success";
        }
    }
    // New
    
    public function edit_expense(){
        $id = $this->input->post("id",true);
        $data['expense'] = ExpenseTransaction::find($id);      
        $body = $this->load->view('/ajax/expense/edit_expense',$data);
        echo $body;

    }
    
    public function edit_special_expense(){
        $id = $this->input->post("id",true);
        $data['expense'] = SpecialExpenseTransaction::find($id);      
        $body = $this->load->view('/ajax/expense/edit_special_expense',$data);
        echo $body;

    }

    public function edit_extraordinary_expense(){
        $id = $this->input->post("id",true);
        $data['expense'] = ExtraordinaryExpense::find($id);      
        $body = $this->load->view('/ajax/expense/edit_extraordinary_expense',$data);
        echo $body;

    }
    
    public function edit_estimative_expense(){
        $id = $this->input->post("id",true);
        $data['expense'] = EstimativeExpenseTransaction::find($id);      
        $body = $this->load->view('/ajax/expense/edit_estimative_expense',$data);
        echo $body;

    }

    public function delete_expense(){
        $id = $this->input->post("id",true);

        if ($id != ""){
            $expense = ExpenseTransaction::find($id);
            if ($expense){                
                $expense->delete();                    

                echo "ok";
            }
            else
                echo "<h2>El pago correspondiente no existe</h2>";
        }
        else
            echo "<h2>El pago correspondiente no existe</h2>";
    }
    
    public function delete_special_expense(){
        $id = $this->input->post("id",true);

        if ($id != ""){
            $expense = SpecialExpenseTransaction::find($id);
            if ($expense){                
                $expense->delete();                    

                echo "ok";
            }
            else
                echo "<h2>El pago correspondiente no existe</h2>";
        }
        else
            echo "<h2>El pago correspondiente no existe</h2>";
    }

    public function delete_extraordinary_expense(){
        $id = $this->input->post("id",true);

        if ($id != ""){
            $expense = ExtraordinaryExpense::find($id);
            if ($expense){                
                $expense->delete();                    

                echo "ok";
            }
            else
                echo "<h2>El pago correspondiente no existe</h2>";
        }
        else
            echo "<h2>El pago correspondiente no existe</h2>";
    }

    public function delete_estimative_expense(){
        $id = $this->input->post("id",true);

        if ($id != ""){
            $expense = EstimativeExpenseTransaction::find($id);
            if ($expense){                
                $expense->delete();                    

                echo "ok";
            }
            else
                echo "<h2>El pago correspondiente no existe</h2>";
        }
        else
            echo "<h2>El pago correspondiente no existe</h2>";
    }

    public function get_expense_tags(){
        $term = trim(strip_tags($_GET['term']));
        $building_id = trim(strip_tags($_GET['id']));

        $tags = ExpenseTag::all(array('conditions' => "(name LIKE '%$term%' OR name LIKE '$term%' OR name LIKE '%$term') AND building_id = $building_id"));
        //$tags = ExpenseTag::all(array('conditions' => "name LIKE '%$term%' OR name LIKE '$term%' OR name LIKE '%$term'"));

        $response = null;
        foreach ($tags as $tag) {
            $array['id'] = $tag->id;
            $array['value'] = $tag->name;
            $response[] = $array;
        }

        echo json_encode($response);
    }
    
    function get_building_expenses(){
        
        $id = $this->input->post("id",true);
        $building = Building::find($id);
        if ($building){
            $data['building'] = $building;
            $data['expenses'] = $building->get_current_expenses();      
            $data['last_month_expenses'] = $building->get_last_month_expenses();
            $body = $this->load->view('/ajax/expense/expense_items',$data,true);
            
            if(strlen($body) == 0)
                echo "nok";
            else
                echo $body;
        }
        else
            echo "nok";
        
    }

    function get_building_special_expenses(){
        
        $id = $this->input->post("id",true);
        $building = Building::find($id);
        if ($building){
            $data['building'] = $building;
            $data['expenses'] = $building->get_current_special_expenses();      
            $data['last_month_expenses'] = $building->get_last_month_special_expenses();
            $body = $this->load->view('/ajax/expense/expense_special_items',$data,true);
            
            if(strlen($body) == 0)
                echo "nok";
            else
                echo $body;
        }
        else
            echo "nok";
        
    }
    
    function get_building_extraordinary_expenses(){
        
        $id = $this->input->post("id",true);
        $building = Building::find($id);
        if ($building){
            $actual_month = $building->actual_period->format("Y-m-d");
            $data['building'] = $building;
            $data['expenses'] = ExtraordinaryExpense::find("all" , array("conditions" => ("building_id = $id AND period_date = '$actual_month'")));

            $body = $this->load->view('/ajax/expense/extraordinary_expense_items',$data,true);
            
            if(strlen($body) == 0)
                echo "nok";
            else
                echo $body;
        }
        else
            echo "nok";
        
    }

    function get_building_estimative_expenses(){

        $id = $this->input->post("id",true);
        $building = Building::find($id);
        if ($building){
            $data['building'] = $building;
            $data['estimatives_expenses'] = $building->get_current_estimative_expenses();      
            $data['current_expenses'] = $building->get_expenses_for_estimative();
            $body = $this->load->view('/ajax/expense/estimative_expense_items',$data,true);
            
            if(strlen($body) == 0)
                echo "nok";
            else
                echo $body;
        }
        else
            echo "nok";

    }
    
    public function increment_priority(){
        
        $id = $this->input->post("id",true);
        
        $expense = ExpenseTransaction::find($id);
        
        $expense->priority++;
        
        $expense->save();
        
    }
    
    public function decrement_priority(){
        
        $id = $this->input->post("id",true);
        
        $expense = ExpenseTransaction::find($id);
        
        $expense->priority--;
        
        $expense->save();
        
    }
    
    public function increment_extraordinary_priority(){
        
        $id = $this->input->post("id",true);
        
        $expense = ExtraordinaryExpense::find($id);
        
        $expense->priority++;
        
        $expense->save();
        
    }
    
    public function decrement_extraordinary_priority(){
        
        $id = $this->input->post("id",true);
        
        $expense = ExtraordinaryExpense::find($id);
        
        $expense->priority--;
        
        $expense->save();
        
    }
    
    public function increment_estimative_priority(){
        
        $id = $this->input->post("id",true);
        
        $expense = EstimativeExpenseTransaction::find($id);
        
        $expense->priority++;
        
        $expense->save();
        
    }
    
    public function decrement_estimative_priority(){
        
        $id = $this->input->post("id",true);
        
        $expense = EstimativeExpenseTransaction::find($id);
        
        $expense->priority--;
        
        $expense->save();
        
    }

    public function save_edit_expense(){
        
        $id = $this->input->post("expense_id",true);
        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run('add_expense') == FALSE){
            echo validation_errors();
        }
        else{

            $expense = ExpenseTransaction::find($id);
            if ($expense){
                
                $descripcion = $this->input->post("expense_tags",true);
                $type = $this->input->post("type_expense",true);
                $date = $this->input->post("date",true);
            
                if ($date)
                    $expense->date = $date;
                else
                    $expense->date = date("Y-m-d");
            
                $tag = ExpenseTag::find(array('conditions' => "(name = '$descripcion')"));
                        
                if (!$tag){
                    $attr_tag["name"] = $descripcion;
                    $attr_tag["building_id"] = $expense->building_id;
                    $tag = ExpenseTag::create($attr_tag);                
                }
                
                $expense->expense_tag_id = $tag->id;
                $expense->type_expense_id = $type;
                $expense->priority = $this->input->post("priority",true);;
                $expense->value = $this->input->post("value",true);
                
                $expense->save();

                echo "success:".$expense->id.":";
            }
        }
    }
    
    public function save_edit_extraordinary_expense(){
        
        $id = $this->input->post("expense_id",true);
        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run('add_extraordinary_expense') == FALSE){
            echo validation_errors();
        }
        else{

            $expense = ExtraordinaryExpense::find($id);
            if ($expense){
                
                $descripcion = $this->input->post("expense_tags_extraordinary",true);
                $extraordinary_period = $this->input->post("extraordinary_period",true);
                $date = $this->input->post("date_extraordinary",true);
            
                if ($date)
                    $expense->date = $date;
                else
                    $expense->date = date("Y-m-d");
            
                $tag = ExpenseTag::find(array('conditions' => "(name = '$descripcion')"));
                        
                if (!$tag){
                    $attr_tag["name"] = $descripcion;
                    $attr_tag["building_id"] = $expense->building_id;
                    $tag = ExpenseTag::create($attr_tag);                
                }
                
                $expense->expense_tag_id = $tag->id;
                $expense->priority = $this->input->post("priority_extraordinary",true);;
                $expense->value = $this->input->post("value_extraordinary",true);
                
                $expense->save();

                echo "success:".$expense->id.":";
            }
        }
    }

    public function save_edit_special_expense(){
        
        $id = $this->input->post("expense_id",true);
        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run('add_special_expense') == FALSE){
            echo validation_errors();
        }
        else{

            $expense = SpecialExpenseTransaction::find($id);
            if ($expense){
                
                $descripcion = $this->input->post("expense_tags_special",true);
                $type = $this->input->post("type_expense",true);
                $type_special = $this->input->post("type_special_expense",true);
                $date = $this->input->post("date",true);
            
                if ($date)
                    $expense->date = $date;
                else
                    $expense->date = date("Y-m-d");
            
                $tag = ExpenseTag::find(array('conditions' => "(name = '$descripcion')"));
                        
                if (!$tag){
                    $attr_tag["name"] = $descripcion;
                    $attr_tag["building_id"] = $expense->building_id;
                    $tag = ExpenseTag::create($attr_tag);                
                }
                
                $expense->expense_tag_id = $tag->id;
                $expense->type_expense_id = $type;
                $expense->type_special_expense_id = $type_special;
                $expense->priority = $this->input->post("priority",true);;
                $expense->value = $this->input->post("value",true);
                
                $expense->save();

                echo "success:".$expense->id.":";
            }
        }
    }
    
    public function save_edit_estimative_expense(){
        
        $id = $this->input->post("expense_id",true);
        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run('add_expense') == FALSE){
            echo validation_errors();
        }
        else{

            $expense = EstimativeExpenseTransaction::find($id);
            if ($expense){
                
                $descripcion = $this->input->post("expense_tags",true);
                $type = $this->input->post("type_expense",true);
                $date = $this->input->post("date",true);
            
                if ($date)
                    $expense->date = $date;
                else
                    $expense->date = date("Y-m-d");
            
                $tag = ExpenseTag::find(array('conditions' => "(name = '$descripcion')"));
                        
                if (!$tag){
                    $attr_tag["name"] = $descripcion;
                    $attr_tag["building_id"] = $expense->building_id;
                    $tag = ExpenseTag::create($attr_tag);                
                }
                
                $expense->expense_tag_id = $tag->id;
                $expense->type_expense_id = $type;
                $expense->priority = $this->input->post("priority",true);;
                $expense->value = $this->input->post("value",true);
                
                $expense->save();

                echo "success:".$expense->id.":";
            }
        }
    }
    
    public function get_extraordinary_period($building_id){
        echo get_select_extraordinary_periods($building_id);
    }
        
    
}

/* End of file building.php */
/* Location: ./application/controllers/ajax/expenses.php */
