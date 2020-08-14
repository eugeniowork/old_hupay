<?php

class WorkingDays extends Connect_db{

	// for getting information by working hours id
	public function getWorkingDaysInfoById($working_days_id){
		$connect = $this->connect();

		$working_days_id = mysqli_real_escape_string($connect,$working_days_id);

		$select_qry = "SELECT * FROM tb_working_days WHERE working_days_id = '$working_days_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
	}


	public function getWorkingDaysInfoToTable(){

		$connect = $this->connect();

		$user_id = $_SESSION["id"];


		//$working_days_id_array = array();
		$select_qry = "SELECT * FROM tb_working_days";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$alreadyUsed = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE working_days_id = '$row->working_days_id'"));

				
				$day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");


				$working_days = $day_of_the_week[$row->day_from] . "-" . $day_of_the_week[$row->day_to];

				//array_push($working_days_id_array,$row->working_days_id);

				echo "<tr id='".$row->working_days_id."'>";
					echo "<td>".$working_days."</td>";

					// if already used
					if ($alreadyUsed !=0){
						echo "<td>No Actions</td>";
					}
					// if not yet used
					else {

						if ($user_id != 21){

							echo "<td>";
								echo "<span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> <a href='#' id='edit_working_days' class='action-a'>Edit</a>";
								echo "<span> | </span>";
								echo "<span class='glyphicon glyphicon-trash' style='color:#515a5a'></span> <a href='#' id='delete_working_days' class='action-a'>Delete</a>";
							echo "</td>";
						}
						else {
							echo "<td>No Actions</td>";
						}
					}
				echo "</tr>";

			}
		}

		//return $working_days_id_array;
	}


	public function existWorkingDays($day_from,$day_to){
		$connect = $this->connect();

		// for security to avoid sql inject
		$day_from = mysqli_real_escape_string($connect,$day_from);
		$day_to = mysqli_real_escape_string($connect,$day_to);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_working_days WHERE day_from ='$day_from' AND day_to='$day_to'"));

		return $num_rows;

	}

	public function existWorkingDaysUpdate($day_from,$day_to,$working_days_id){
		$connect = $this->connect();

		// for security to avoid sql inject
		$day_from = mysqli_real_escape_string($connect,$day_from);
		$day_to = mysqli_real_escape_string($connect,$day_to);
		$working_days_id = mysqli_real_escape_string($connect,$working_days_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_working_days WHERE day_from ='$day_from' AND day_to='$day_to' AND working_days_id != '$working_days_id'"));

		return $num_rows;

	}


	// for inserting
	public function insertWorkingDays($day_from,$day_to){
		$connect = $this->connect();

		// for security to avoid sql inject
		$day_from = mysqli_real_escape_string($connect,$day_from);
		$day_to = mysqli_real_escape_string($connect,$day_to);

		$insert_qry = "INSERT INTO tb_working_days (day_from,day_to) VALUES ('$day_from','$day_to')";
		$sql = mysqli_query($connect,$insert_qry);

	}


	// for inserting
	public function updateWorkingDays($day_from,$day_to,$working_days_id){
		$connect = $this->connect();

		// for security to avoid sql inject
		$day_from = mysqli_real_escape_string($connect,$day_from);
		$day_to = mysqli_real_escape_string($connect,$day_to);
		$working_days_id = mysqli_real_escape_string($connect,$working_days_id);


		$update_qry = "UPDATE tb_working_days SET day_from = '$day_from', day_to = '$day_to' WHERE working_days_id = '$working_days_id'";
		$sql = mysqli_query($connect,$update_qry);

	}

	// for checking if there working hours id is exist
	public function checkExistWorkingDaysId($working_days_id){
		$connect = $this->connect();

		$working_days_id = mysqli_real_escape_string($connect,$working_days_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_working_days WHERE working_days_id = '$working_days_id'"));

		return $num_rows;

	}


	// if no changes was made, when updating
	public function updateNoChangesWorkingDays($day_from,$day_to,$working_days_id){
		$connect = $this->connect();

		// for security to avoid sql inject
		
		$day_from = mysqli_real_escape_string($connect,$day_from);
		$day_to = mysqli_real_escape_string($connect,$day_to);
		$working_days_id = mysqli_real_escape_string($connect,$working_days_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_working_days WHERE day_from ='$day_from' AND day_to='$day_to' AND working_days_id = '$working_days_id'"));

		return $num_rows;

	}


	// for deletion of woking hours
	public function deleteWorkingDays($working_days_id){
		$connect = $this->connect();

		$working_days_id = mysqli_real_escape_string($connect,$working_days_id);

		$delete_qry = "DELETE FROM tb_working_days WHERE working_days_id='$working_days_id'";
		$sql = mysqli_query($connect,$delete_qry);

	}


	// for showing the value in employee registration
	public function getWorkingDaysToRegistration(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_working_days";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){


				$day_from = $row->day_from;
				$day_to = $row->day_to;

				$day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
				$day_of_the_week_value = [0,1,2,3,4,5,6];

				$count = count($day_of_the_week);

				$counter = 0;

				$day_from_value = "";
				$day_to_value = "";
				do {

					if ($day_of_the_week_value[$counter] == $day_from){
						$day_from_value = $day_of_the_week[$counter];
					}

					if ($day_of_the_week_value[$counter] == $day_to){
						$day_to_value = $day_of_the_week[$counter];
					}


					$counter++;
				}while($count > $counter);

				// if has an error in employee registration
				if (isset($_SESSION["emp_reg_workingDays"])){
					$working_days_id = $_SESSION["emp_reg_workingDays"];
					
					$selected = "";
					if ($working_days_id == $row->working_days_id) {
						$selected = "selected=selected";
					}

					echo "<option ".$selected." value='".$row->working_days_id."'>".$day_from_value ." - ". $day_to_value ."</option>";
				}

				else {				
					echo "<option value='".$row->working_days_id."'>".$day_from_value ." - ". $day_to_value  ."</option>";
				}
			}
		}
	}


	// for getting working hours to update
	public function getWorkingDaysToUpdate($working_days_id){
		$connect = $this->connect();

		$working_days_id = mysqli_real_escape_string($connect,$working_days_id);

		$select_qry = "SELECT * FROM tb_working_days";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$day_from = $row->day_from;
				$day_to = $row->day_to;

				$day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
				$day_of_the_week_value = [0,1,2,3,4,5,6];

				$count = count($day_of_the_week);

				$counter = 0;

				$day_from_value = "";
				$day_to_value = "";
				do {

					if ($day_of_the_week_value[$counter] == $day_from){
						$day_from_value = $day_of_the_week[$counter];
					}

					if ($day_of_the_week_value[$counter] == $day_to){
						$day_to_value = $day_of_the_week[$counter];
					}


					$counter++;
				}while($count > $counter);


				$selected = "";
				if ($working_days_id == $row->working_days_id) {
					$selected = "selected=selected";
				}

				echo "<option ".$selected." value='".$row->working_days_id."'>".$day_from_value . " - " . $day_to_value ."</option>";


			}
		}
	}



}



?>