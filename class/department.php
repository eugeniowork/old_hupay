<?php
class Department extends Connect_db{

	// for inserting
	public function insertDepartment($department,$date){
		$connect = $this->connect();

		$department = mysqli_real_escape_string($connect,$department);
		$date = mysqli_real_escape_string($connect,$date);

		$insert_qry = "INSERT INTO tb_department (dept_id,Department,DateCreated) VALUES ('','$department','$date')";
		$sql = mysqli_query($connect,$insert_qry);
	}


	public function getAllDepartmentValueToTable(){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_department";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				/*if ($row->Gender == "Male") {
					$pronoun = "his";
				}

				if ($row->Gender == "Female") {
					$pronoun = "her";
				}*/
				// for admin
				//if ($row->emp_id != 1 && $row->ActiveStatus == 1) {
				echo "<tr id='".$row->dept_id."' style='text-align:center;'>";
					echo "<td><a href='#' id='chooseDepartment' title='Choose ".$row->Department . "'>".$row->Department."</a></td>";
				echo "</tr>";
				//}
			}
		}
	}




	// getting the last id of the department
	// for get the last id
	public function lastIdDepartment(){
		$connect = $this->connect();
		$select_last_id_qry = "SELECT * FROM tb_department ORDER BY dept_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->dept_id;
		return $last_id;
	}

	// checking if exist
	public function existDepartment($department){
		$connect = $this->connect();

		$department = mysqli_real_escape_string($connect,$department);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_department WHERE Department = '$department'"));
		return $num_rows;
	}



	// checking if exist by id
	public function existDepartmentById($dept_id){
		$connect = $this->connect();

		$dept_id = mysqli_real_escape_string($connect,$dept_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_department WHERE dept_id = '$dept_id'"));
		return $num_rows;
	}

	// get the current id of the exist Department
	public function getInformationDepartment($department){
		$connect = $this->connect();

		$department = mysqli_real_escape_string($connect,$department);

		$select_qry = "SELECT * FROM tb_department WHERE Department = '$department'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;
	}

	// for getting all position
	public function getDepartmentInfo(){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_department";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				// for retrieval purpose in case of error in employee registration during saving
				$selected = "";
				if (isset($_SESSION["emp_reg_department"])){
					if ($_SESSION["emp_reg_department"] == $row->dept_id) {
							$selected = "selected=selected";
					}
				}

				echo "<option value='$row->dept_id' ".$selected.">";
					echo $row->Department;
				echo "</option>";
			}
		}
	}

	// for get the department value by dept_id
	public function getDepartmentValue($dept_id){
		$connect = $this->connect();

		$dept_id = mysqli_real_escape_string($connect,$dept_id);

		$select_qry = "SELECT * FROM tb_department WHERE dept_id = '$dept_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;
	}


	// for table get all department
	public function getDepartmentToTable(){
		//$row_design = ""; // static value
		$connect = $this->connect();

		
		$select_qry = "SELECT * FROM tb_department";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				/*if ($row_design == "danger"){
					$row_design = "";
				}

				if ($row_design == "warning"){
					$row_design = "danger";
				}

				if ($row_design == "info"){
					$row_design = "warning";
				}

				if ($row_design == "success"){
					$row_design = "info";
				}


				if ($row_design == "active"){
					$row_design = "success";
				}

				if ($row_design == ""){
					$row_design = "active";
				} */

				// check if exist to the position so it will not be deleted or edited
				$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_position WHERE dept_id='$row->dept_id'"));
				if ($num_rows == 0){
					echo "<tr id=".$row->dept_id.">";
						echo "<td>" .$row->Department ."</td>";
						echo "<td>";
							// for admin only
							if ($_SESSION["role"] == 1){
								echo "<span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> <a href='#' id='edit_deptartment' class='action-a'>Edit</a>";
								echo "<span> | </span>";
								echo "<span class='glyphicon glyphicon-trash' style='color:#515a5a'></span> <a href='#' id='delete_department' class='action-a'>Delete</a>";
							}
							else {
								echo "No actions";
							}
						echo "</td>";
					echo "</tr>";
				}
				else {
					echo "<tr id=".$row->dept_id.">";
						echo "<td>" .$row->Department ."</td>";
						echo "<td>";
							// for adming only can edit but cannot delete
							if ($_SESSION["role"] == 1){
								echo "<span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> <a href='#' id='edit_deptartment' class='action-a'>Edit</a>";
							}
							else {
								echo "No actions";
							}
						echo "</td>";
					echo "</tr>";
				}
			}
		}
	}



	// for updating department VALUE
	public function updateDepartment($dept_id,$department){
		$connect = $this->connect();

		$dept_id = mysqli_real_escape_string($connect,$dept_id);
		$department = mysqli_real_escape_string($connect,$department);

		$update_qry = "UPDATE tb_department SET Department = '$department' WHERE dept_id='$dept_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for deleting department by id
	public function deleteDepartment($dept_id){
		$connect = $this->connect();

		$dept_id = mysqli_real_escape_string($connect,$dept_id);

		$delete_qry = "DELETE FROM tb_department WHERE dept_id='$dept_id'";
		$sql = mysqli_query($connect,$delete_qry);
	}



	// for update emp info modal
	public function updateEmpInfo($dept_id){
		$connect = $this->connect();

		$dept_id = mysqli_real_escape_string($connect,$dept_id);
		
		$select_qry = "SELECT * FROM tb_department";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				// for retrieval purpose in case of error in employee registration during saving
				$selected = "";

				if ($dept_id == $row->dept_id) {
						$selected = "selected=selected";
				}
		

				echo "<option value='$row->dept_id' ".$selected.">";
					echo $row->Department;
				echo "</option>";
			}
		}
	}


	// for getting dept it by dept_value
	public function getDeptIdByDepartment($department){
		$connect = $this->connect();

		$department = mysqli_real_escape_string($connect,$department);

		$select_qry = "SELECT * FROM tb_department WHERE Department = '$department'";
		$result =mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row->dept_id;
	}


	public function AllActiveEmpIdLinkDeptIdCount($dept_id){
		$connect = $this->connect();

		$dept_id = mysqli_real_escape_string($connect,$dept_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE dept_id = '$dept_id' AND ActiveStatus = '1'"));
		return $num_rows;
	}



	// for getting all department by department
	public function getAllActiveEmpIdLinkDeptId($dept_id){
		$connect = $this->connect();

		$emp_id_value = "";
		$select_qry = "SELECT * FROM tb_employee_info WHERE dept_id = '$dept_id' AND ActiveStatus = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				//echo $dept_id . " " . $row->emp_id . "<br/>";
				if ($row->emp_id != 1) { // para d mapadalhan c admin ng memo , pero malalaman nya lahat ng memo


					if ($emp_id_value == ""){
						$emp_id_value = $row->emp_id;
					}

					else {
						$emp_id_value = $emp_id_value ."#". $row->emp_id;
					}
				}
			}
		}

		return $emp_id_value;
	}



}

?>