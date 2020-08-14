<?php
class Connect_db{

	protected function connect(){
		$servername = 'localhost';
		$username = 'root';
		$password = '';
		$dbname = 'live_db_hr_payroll';
		//$dbname = 'test_live_db_hr_payroll';
		// mysqli connect
		$conn = mysqli_connect($servername,$username,$password,$dbname);


		//mysql_connect(servername,username,passwrd,dbname);

		return $conn;

	}

}

?>