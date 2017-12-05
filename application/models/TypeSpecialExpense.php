<?php

class TypeSpecialExpense extends ActiveRecord\Model{
    
	public function is_divided_by_coeficient(){

		if ($this->id == 1) {
			return true;
		}
		else{
			return false;
		}

	}

}

?>
