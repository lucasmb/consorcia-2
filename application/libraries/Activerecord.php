<?php
// Load php.activerecord
require_once FCPATH.'../vendor/php-activerecord/php-activerecord/ActiveRecord.php';

// Load CodeIgniter's Model class 
require_once BASEPATH.'/core/Model.php';

class Activerecord {
    
    function __construct() {
        // Load database configuration from CodeIgniter
        include APPPATH.'config/'.ENVIRONMENT.'/database.php';

        // Get connections from database.php
        $dsn = array();
        if ($db) {
            foreach ($db as $name => $db_values) {
                // Convert to dsn format
                $dsn[$name] = 'mysql' .
                        '://'   . $db[$name]['username'] .
                        ':'     . $db[$name]['password'] .
                        '@'     . $db[$name]['hostname'] .
                        '/'     . $db[$name]['database'];
            }
        } 
        
        // Initialize ActiveRecord
        ActiveRecord\Config::initialize(function($cfg) use ($dsn, $active_group){
            $cfg->set_model_directory(APPPATH.'/models');
            $cfg->set_connections($dsn);
            $cfg->set_default_connection($active_group);
        });
    }
}

/* End of file Activerecord.php */
/* Location: ./application/libraries/Activerecord.php */
