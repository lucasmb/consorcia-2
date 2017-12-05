<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

        function __construct()
        {
            parent::__construct();
        }
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
            $data['users'] = User::find('all');
            $this->load->view('common/header');
            $this->load->view('home/home');
            $this->load->view('common/footer');
            echo phpinfo();
	}

	public function info()
	{
		echo phpinfo();
	}

	public function update_property_pay_bank_id()
	{
            $buildings = Building::find('all');
            foreach ($buildings as $building) {
            	echo $building->name;
            	echo "<BR />";

            	$properties = Property::find('all', array('conditions' => 'building_id = '.$building->id, 'order' => 'id asc'));

				$payment_index = 1;
            	foreach ($properties as $property) {
            		echo $property->id . ' - ' . $property->floor . ' ' . $property->appartment . ' ' . $property->functional_unity . ' - '. $property->bank_payment_id;
            		echo "<BR />";

            		$property->bank_payment_id = $payment_index;
            		$property->save();
            		$payment_index++;
            	}

            }
	}

	public function download_backup_sql()
	{
		$this->load->database();
		$this->backup_tables($this->db->hostname,
							$this->db->username,
							$this->db->password,
							$this->db->database);

	}

	public function upload_backup_sql()
	{
		echo $this->post();
		// $this->db->trans_begin();

		// $query = $this->db->query($SQL);
		// echo $query;

		// if ($this->db->trans_status() === FALSE) {
  //           $this->db->trans_rollback();
  //           echo "fail";
  //       } else {
  //           $this->db->trans_commit();
  //           echo "success";
  //       }
	}

	function backup_tables($host,$user,$pass,$name,$tables = '*')
	{
		$link = mysqli_connect($host,$user,$pass,$name);
		
		
		//get all of the tables
		if($tables == '*')
		{
			$tables = array();
			$result = mysql_query('SHOW TABLES');
			while($row = mysql_fetch_row($result))
			{
				$tables[] = $row[0];
			}
		}
		else
		{
			$tables = is_array($tables) ? $tables : explode(',',$tables);
		}

		$return = "CREATE DATABASE IF NOT EXISTS $name CHARACTER SET utf8 COLLATE utf8_general_ci;\n\n";
		
		//cycle through
		foreach($tables as $table)
		{
			$result = mysql_query('SELECT * FROM '.$table);
			$num_fields = mysql_num_fields($result);
			
			$return.= 'DROP TABLE '.$table.';';
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			$return.= "\n\n".$row2[1].";\n\n";
			
			for ($i = 0; $i < $num_fields; $i++) 
			{
				while($row = mysql_fetch_row($result))
				{
					$return.= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j < $num_fields; $j++) 
					{
						$row[$j] = addslashes($row[$j]);
						$row[$j] = preg_replace("#\n#", "\\n", $row[$j]);
						if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
						if ($j < ($num_fields-1)) { $return.= ','; }
					}
					$return.= ");\n";
				}
			}
			$return.="\n\n\n";
			
		}
		
		//save file
		$this->load->helper('download');
		force_download('db-backup-consorcia-'.date('d-m-Y H:i:s').'.sql', $return, TRUE);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */