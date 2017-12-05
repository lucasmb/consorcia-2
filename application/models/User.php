<?php

class User extends ActiveRecord\Model{
    
    function get_all_users(){
        return User::find('all');
    }

    public static function login($username, $password) {

		$user = User::find_by_sql("SELECT u.*
                               		 FROM users u 
                                    WHERE u.username = '".$username."'
                                      AND u.password = MD5('".$password."')");

        if (count($user) > 0) {
            return $user;
        } else{
            return false;
        }
	}
    
}

?>