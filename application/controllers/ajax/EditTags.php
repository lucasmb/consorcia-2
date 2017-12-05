<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class EditTags extends CI_Controller {

    
        function __construct()
        {
            parent::__construct();
        }
        
	public function index()
	{     
            $data['buildings'] = Building::find('all');  
            $this->load->view('common/header');
            $this->load->view('editTags/editTags_home',$data);            
            //$this->load->view('common/footer');
	}
        
        public function get_balance_tags_for_building(){
            
            $tag_type = $this->input->post("tag_type",true);
            $building_id = $this->input->post("building_id",true);

            if ($tag_type == "expense"){
                echo get_select_balance_expense_tags($building_id);
            }
            else if ($tag_type == "income"){
                echo get_select_balance_income_tags($building_id);
            }
        }
        
        public function get_tags_of_building(){
            
            $tag_type = $this->input->post("tag_type",true);
            $building_id = $this->input->post("building_id",true);
            $super_tag_id = $this->input->post("super_tag_id",true);
            
            if ($tag_type == "expense"){
                echo get_multiselect_expense_tags($building_id,$super_tag_id);
            }
            else if ($tag_type == "income"){
                echo get_multiselect_income_tags($building_id,$super_tag_id);
            }
            
        }


        public function save_balance_tag(){
            
            $tag_type = $this->input->post("tag_type",true);
            $building_id = $this->input->post("building_id",true);
            $tag_name = $this->input->post("tag_name",true);
            $balance_tag_id = $this->input->post("super_tag_id",true);
            
            if ($balance_tag_id != ""){
                // save existing tag
                if ($tag_type == "expense") {
                    $tag = ExpenseBalanceTag::find($balance_tag_id);
                    $tag->name = $tag_name;
                    $tag->save();
                    
                    echo "success";
                }
                else if ($tag_type == "income") {
                    $tag = IncomeBalanceTag::find($balance_tag_id);
                    $tag->name = $tag_name;
                    $tag->save();
                    
                    echo "success";
                }
                
            }
            else{
                // create new tag
                if ($tag_type == "expense") {
                    $attr['building_id'] = $building_id;
                    $attr['name'] = $tag_name;
                    
                    ExpenseBalanceTag::create($attr);
                    
                    echo "success";
                }
                else if ($tag_type == "income") {
                    $attr['name'] = $tag_name;
                    $attr['building_id'] = $building_id;

                    IncomeBalanceTag::create($attr);
                    
                    echo "success";
                }
                
            }
            
        }
        
        
        public function save_balance_tag_relation() {
            
            $tag_type = $this->input->post("tag_type",true);
            $building_id = $this->input->post("building_id",true);
            $selected_tags = $this->input->post("tags_selected",true);
            $balance_tag_id = $this->input->post("super_tag_id",true);
            
            if ($tag_type == "expense"){
                $this->removeAllExpenseTagsFrom($balance_tag_id);
                if (count($selected_tags) > 0){
                    $this->updateAllExpenseTagsFrom($balance_tag_id,$selected_tags);
                }
                echo "success";
            }
            else if ($tag_type == "income"){
                $this->removeAllIncomeTagsFrom($balance_tag_id);
                if (count($selected_tags) > 0){
                    $this->updateAllIncomeTagsFrom($balance_tag_id, $selected_tags);
                }
                echo "success";
            }
            
        }
        
        
        public function selectedTagsToString($selected_tags){
            
            $result = "";
            
            foreach ($selected_tags as $value) {
                
            //} ($selected_tags as $key => $value) {
                $result = $result . $value . ",";    
            }
            
            $result = substr($result, 0, -1);
            
            return $result;
            
        }


        public function removeAllExpenseTagsFrom($balance_tag_id){
            
            ExpenseTag::connection()->query("UPDATE expense_tags "
                    . "                         SET expense_tag_id = NULL "
                    . "                         WHERE expense_tag_id = ". $balance_tag_id);
            
        }
        
        public function updateAllExpenseTagsFrom($balance_tag_id,$selected_tags){
            
            if (count($selected_tags) > 0){
                
                $group = $this->selectedTagsToString($selected_tags);
                ExpenseTag::connection()->query("UPDATE expense_tags "
                        . "                         SET expense_tag_id = ". $balance_tag_id .""
                        . "                       WHERE id IN (". $group .");");
                
            }
            
        }
        
        public function removeAllIncomeTagsFrom($balance_tag_id){
            
            IncomeTag::connection()->query("UPDATE income_tags "
                    . "                SET income_tag_id = NULL "
                    . "              WHERE income_tag_id = ". $balance_tag_id .";");
            
        }
        
        public function updateAllIncomeTagsFrom($balance_tag_id,$selected_tags){
            
            if (count($selected_tags) > 0){
                
                $group = $this->selectedTagsToString($selected_tags);
                IncomeTag::connection()->query("UPDATE income_tags "
                        . "                 SET income_tag_id = ". $balance_tag_id .""
                        . "               WHERE id IN (". $group .");");
                
            }

            
        }
        
        
        
}

/* End of file building.php */
/* Location: ./application/controllers/reports.php */