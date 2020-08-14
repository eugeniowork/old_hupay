<?php

class WorkingHours extends Connect_db{

	// for inserting
	public function insertWorkingHours($timeFrom,$timeTo,$dateCreated){
		$connect = $this->connect();

		// for security to avoid sql inject
		$timeFrom = mysqli_real_escape_string($connect,$timeFrom);
		$timeTo = mysqli_real_escape_string($connect,$timeTo);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_working_hours (working_hours_id,timeFrom,timeTo,dateCreated) VALUES ('','$timeFrom','$timeTo','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);

	}

	// check if exist
	public function existWorkingHours($timeFrom,$timeTo){
		$connect = $this->connect();

		// for security to avoid sql inject
		$timeFrom = mysqli_real_escape_string($connect,$timeFrom);
		$timeTo = mysqli_real_escape_string($connect,$timeTo);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_working_hours WHERE timeFrom ='$timeFrom' AND timeTo='$timeTo'"));

		return $num_rows;

	}

	// for puting it in a table
	public function getWorkingHoursInfoToTable(){

		$connect = $this->connect();

		$user_id = $_SESSION["id"];

		$select_qry = "SELECT * FROM tb_working_hours";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$alreadyUsed = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE working_hours_id = '$row->working_hours_id'"));

				$timeFrom = date_format(date_create($row->timeFrom), 'g:i A');
				$timeTo = date_format(date_create($row->timeTo), 'g:i A');

				$working_hours = $timeFrom . "-" . $timeTo;

				echo "<tr id='".$row->working_hours_id."'>";
					echo "<td>".$working_hours."</td>";

					// if already used
					if ($alreadyUsed !=0){
						echo "<td>No Actions</td>";
					}
					// if not yet used
					else {

						if ($user_id != 21){

							echo "<td>";
								echo "<span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> <a href='#' id='edit_working_hours' class='action-a'>Edit</a>";
								echo "<span> | </span>";
								echo "<span class='glyphicon glyphicon-trash' style='color:#515a5a'></span> <a href='#' id='delete_working_hours' class='action-a'>Delete</a>";
							echo "</td>";
						}
						else {
							echo "<td>No Actions</td>";
						}
					}
				echo "</tr>";

			}
		}
	}

	// for checking if there working hours id is exist
	public function checkExistWorkingHoursId($wokingHoursId){
		$connect = $this->connect();

		$wokingHoursId = mysqli_real_escape_string($connect,$wokingHoursId);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_working_hours WHERE working_hours_id = '$wokingHoursId'"));

		return $num_rows;

	}

	// for getting information by working hours id
	public function getWorkingHoursInfoById($wokingHoursId){
		$connect = $this->connect();

		$wokingHoursId = mysqli_real_escape_string($connect,$wokingHoursId);

		$select_qry = "SELECT * FROM tb_working_hours WHERE working_hours_id = '$wokingHoursId'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
	}


	// for exist working hours not the current working id
	// check if exist
	public function updateExistWorkingHours($working_hours_id,$timeFrom,$timeTo){
		$connect = $this->connect();

		// for security to avoid sql inject
		$working_hours_id = mysqli_real_escape_string($connect,$working_hours_id);
		$timeFrom = mysqli_real_escape_string($connect,$timeFrom);
		$timeTo = mysqli_real_escape_string($connect,$timeTo);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_working_hours WHERE timeFrom ='$timeFrom' AND timeTo='$timeTo' AND working_hours_id != '$working_hours_id'"));

		return $num_rows;

	}

	// if no changes was made, when updating
	public function updateNoChangesWorkingHours($working_hours_id,$timeFrom,$timeTo){
		$connect = $this->connect();

		// for security to avoid sql inject
		$working_hours_id = mysqli_real_escape_string($connect,$working_hours_id);
		$timeFrom = mysqli_real_escape_string($connect,$timeFrom);
		$timeTo = mysqli_real_escape_string($connect,$timeTo);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_working_hours WHERE timeFrom ='$timeFrom' AND timeTo='$timeTo' AND working_hours_id = '$working_hours_id'"));

		return $num_rows;

	}


	// for updating working hours
	public function updateWorkingHours($working_hours_id,$timeFrom,$timeTo){
		$connect = $this->connect();

		// for security to avoid sql inject
		$working_hours_id = mysqli_real_escape_string($connect,$working_hours_id);
		$timeFrom = mysqli_real_escape_string($connect,$timeFrom);
		$timeTo = mysqli_real_escape_string($connect,$timeTo);

		$update_qry = "UPDATE tb_working_hours SET timeFrom = '$timeFrom' , timeTo='$timeTo' WHERE working_hours_id = '$working_hours_id'";
		$sql = mysqli_query($connect,$update_qry);

	}


	// for deletion of woking hours
	public function deleteWorkingHours($working_hours_id){
		$connect = $this->connect();

		$working_hours_id = mysqli_real_escape_string($connect,$working_hours_id);

		$delete_qry = "DELETE FROM tb_working_hours WHERE working_hours_id='$working_hours_id'";
		$sql = mysqli_query($connect,$delete_qry);

	}

	// for showing the value in employee registration
	public function getWorkingHoursToRegistration(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_working_hours";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$timeFrom = date_format(date_create($row->timeFrom), 'g:i A');
				$timeTo = date_format(date_create($row->timeTo), 'g:i A');

				$working_hours = $timeFrom . "-" . $timeTo;

				// if has an error in employee registration
				if (isset($_SESSION["emp_reg_workingHours"])){
					$working_hours_id = $_SESSION["emp_reg_workingHours"];
					
					$selected = "";
					if ($working_hours_id == $row->working_hours_id) {
						$selected = "selected=selected";
					}

					echo "<option ".$selected." value='".$row->working_hours_id."'>".$working_hours ."</option>";
				}

				else {				
					echo "<option value='".$row->working_hours_id."'>".$working_hours ."</option>";
				}
			}
		}
	}

	// for getting working hours to update
	public function getWorkingHoursToUpdate($working_hours_id){
		$connect = $this->connect();

		$working_hours_id = mysqli_real_escape_string($connect,$working_hours_id);

		$select_qry = "SELECT * FROM tb_working_hours";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$timeFrom = date_format(date_create($row->timeFrom), 'g:i A');
				$timeTo = date_format(date_create($row->timeTo), 'g:i A');
				$working_hours = $timeFrom . "-" . $timeTo;


				$selected = "";
				if ($working_hours_id == $row->working_hours_id) {
					$selected = "selected=selected";
				}

				echo "<option ".$selected." value='".$row->working_hours_id."'>".$working_hours ."</option>";


			}
		}
	}

}



?>