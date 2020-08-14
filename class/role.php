<?php

class Role extends Connect_db{

	// for getting all role and append to the registration form
	public function getAllRole(){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_role";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				// ang purpose nito isa 1 ADMIN user lang , un ang statick
				if ($row->role_value != "Admin") 
				{


					// for retrieval purpose in case of error in employee registration during saving
					$selected = "";
					if (isset($_SESSION["emp_reg_role"])){
						if ($_SESSION["emp_reg_role"] == $row->role_id) {
								$selected = "selected=selected";
								$_SESSION["emp_reg_role"] = null;
						}
					}


					// for roling purpose
					if (isset($_SESSION["role"])){
						$role_id = $_SESSION["role"];

						// if Admin so lahat pde nya icreate na role maliban sa admin
						if ($role_id == 1){
							echo "<option value='$row->role_id' ".$selected.">";
								echo $row->role_value;
							echo "</option>";
						}

						// for HR and Payroll admin pde ding magcreate ng employee pero dapat user lang pde nyang icr8
						if ($role_id == 2 || $role_id == 3){
							if ($row->role_id == 4) {
							echo "<option value='$row->role_id' selected='selected'>"; // auto select para ok na mismo
								echo $row->role_value;
							echo "</option>";
							}
						}

					}
					
					
				}
			}
		}
	} // end of function

	public function existRole($role_id){
		$connect = $this->connect();

		$role_id = mysqli_real_escape_string($connect,$role_id);
		
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_role WHERE role_id = '$role_id'"));
		return $num_rows;
	}


	// for update emp info modal
	/*public function updateEmpInfo($id_role){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_role";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				// ang purpose nito isa 1 ADMIN user lang , un ang statick
				if ($row->role_value != "Admin") 
				{


					// for retrieval purpose in case of error in employee registration during saving
					$selected = "";
				
					if ($id_role == $row->role_id) {
							$selected = "selected=selected";
					
					}
				


					// for roling purpose
					if (isset($_SESSION["role"])){
						$role_id = $_SESSION["role"];

						// if Admin so lahat pde nya icreate na role maliban sa admin
						if ($role_id == 1){
							echo "<option value='$row->role_id' ".$selected.">";
								echo $row->role_value;
							echo "</option>";
						}

						// for HR and Payroll admin pde ding magcreate ng employee pero dapat user lang pde nyang icr8
						if ($role_id == 2 || $role_id == 3){
							if ($row->role_id == 4) {
							echo "<option value='$row->role_id' selected='selected'>"; // auto select para ok na mismo
								echo $row->role_value;
							echo "</option>";
							}
						}

					}
					
					
				}
			}
		}
	} // end of function */
	// for update emp info modal
	public function updateEmpInfo($role_id){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_role";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){
				if ($row->role_value != "Admin") 
				{
					// for retrieval purpose in case of error in employee registration during saving
					$selected = "";

					if ($role_id == $row->role_id) {
							$selected = "selected=selected";
					}
			
					echo "<option value='$row->role_id' ".$selected.">";
						echo $row->role_value;
					echo "</option>";
				}
			}
		}
	}


}

?>