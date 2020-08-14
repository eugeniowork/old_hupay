<?php

class Position extends Connect_db{
	// for inserting
	public function insertPosition($dept_id,$position,$date){
		$connect = $this->connect();
		$dept_id = mysqli_real_escape_string($connect,$dept_id);
		$position = mysqli_real_escape_string($connect,$position);
		$insert_qry = "INSERT INTO tb_position (position_id,dept_id,Position,DateCreated) VALUES ('','$dept_id','$position','$date')";
		$sql = mysqli_query($connect,$insert_qry);
	}

	// for checking if existing department id and position
	public function checkExist($dept_id,$position){
		$connect = $this->connect();

		$dept_id = mysqli_real_escape_string($connect,$dept_id);
		$position = mysqli_real_escape_string($connect,$position);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_position WHERE dept_id ='$dept_id' AND Position='$position'"));
		return $num_rows;
	}


	// for checking if position id exist
	public function checkExistPositionId($position_id){
		$connect = $this->connect();

		$position_id = mysqli_real_escape_string($connect,$position_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_position WHERE position_id ='$position_id'"));
		return $num_rows;
	}

	// for getting all position by choosing department using ajax
	public function getPositionInfo($department_id){
		// for static value
		echo "<option value=''></option>";
		$connect = $this->connect();

		$department_id = mysqli_real_escape_string($connect,$department_id);

		$select_qry = "SELECT * FROM tb_position WHERE dept_id='$department_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){
				if ($row->position_id != 1){ // for Admin to eh dapat wlang lalabas
					echo "<option value='$row->position_id'>";
						echo $row->Position;
					echo "</option>";
				}
			}
		}
	}

	// for getting the position static for retrieval if there is an error during saving employee information
	public function getAllPosition($department_id){

		$connect = $this->connect();

		$department_id = mysqli_real_escape_string($connect,$department_id);

		$select_qry = "SELECT * FROM tb_position WHERE dept_id='$department_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				// for retrieval purpose in case of error in employee registration during saving
				$selected = "";
				if ($row->position_id != 1) {
					if (isset($_SESSION["emp_reg_position"])){
						if ($_SESSION["emp_reg_position"] == $row->position_id) {
								$selected = "selected=selected";
						}
					}
					echo "<option value='$row->position_id' ".$selected.">";
						echo $row->Position;
					echo "</option>";
				}
			}
		}
	}

	// for getting all position and display as position list
	public function getPositionToTable(){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_position";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$dept_id = $row->dept_id;
				$select_dept = "SELECT * FROM tb_department WHERE dept_id='$dept_id'";
				$result_dept = mysqli_query($connect,$select_dept);
				$row_dept = mysqli_fetch_object($result_dept);
				$department_val = $row_dept->Department; 


				// check if exist to the position so it will not be deleted or edited
				$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE position_id='$row->position_id'"));
				if ($row->position_id != 1){
					if ($num_rows == 0){
						
						echo "<tr id='$row->position_id'>";	
							echo "<td>" .$row->Position."</td>";
							echo "<td>" . $department_val."</td>";
							echo "<td>";
								if ($_SESSION["role"] == 1) {
									echo "<span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> <a href='#' id='edit_position' class='action-a'>Edit</a>";
									echo "<span> | </span>";
									echo "<span class='glyphicon glyphicon-trash' style='color:#515a5a'></span> <a href='#' id='delete_position' class='action-a'>Delete</a>";
								}
								else {
									echo "No Action";
								}
							echo "</td>";
						echo "</tr>";

					}
					else {
						echo "<tr id='$row->position_id'>";	
							echo "<td>" .$row->Position."</td>";
							echo "<td>" . $department_val."</td>";
							echo "<td>";
								if ($_SESSION["role"] == 1) {
									echo "<span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> <a href='#' id='edit_position' class='action-a'>Edit</a>";
								}
								else {
									echo "No Action";
								}
							echo "</td>";
						echo "</tr>";
					}
				}
			}

		}
	} // end of class


	// for getting information by id
	public function getPositionById($position_id){
		$connect = $this->connect();

		$position_id = mysqli_real_escape_string($connect,$position_id);

		$select_qry = "SELECT * FROM tb_position WHERE position_id='$position_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;

	}

	// for updating position
	public function updatePosition($position_id,$position){
		$connect = $this->connect();

		$position_id = mysqli_real_escape_string($connect,$position_id);
		$position = mysqli_real_escape_string($connect,$position);

		$update_qry = "UPDATE tb_position SET Position='$position' WHERE position_id='$position_id'";
		$result = mysqli_query($connect,$update_qry);
	}


	// check if exist position
	public function existUpdatePosition($position){
		$connect = $this->connect();

		$position = mysqli_real_escape_string($connect,$position);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_position WHERE Position='$position'"));
		return $num_rows;
	}

	// for deleting of position
	public function deletePosition($position_id){
		$connect = $this->connect();
		
		$position_id = mysqli_real_escape_string($connect,$position_id);

		$delete_qry = "DELETE FROM tb_position WHERE position_id='$position_id'";
		$sql = mysqli_query($connect,$delete_qry);

	}


	// for getting the position static for retrieval if there is an error during saving employee information
	public function updateEmpInfo($department_id,$position_id){

		$connect = $this->connect();

		$department_id = mysqli_real_escape_string($connect,$department_id);
		$position_id = mysqli_real_escape_string($connect,$position_id);

		$select_qry = "SELECT * FROM tb_position WHERE dept_id='$department_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				// for retrieval purpose in case of error in employee registration during saving

				if ($row->Position != "Admin") {
					$selected = "";
					if ($position_id == $row->position_id) {
							$selected = "selected=selected";
					}
					echo "<option value='$row->position_id' ".$selected.">";
						echo $row->Position;
					echo "</option>";
				}
			}
		}
	}

}

?>