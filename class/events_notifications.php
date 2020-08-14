<?php

class EventsNotification extends Connect_db{

	// for inserting 
	public function insertNotifications($notif_id,$emp_id,$events_id,$notif_type,$readStatus,$dateTimeCreated){
		$connect = $this->connect();

		$notif_id = mysqli_real_escape_string($connect,$notif_id);
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$events_id = mysqli_real_escape_string($connect,$events_id);
		$notif_type = mysqli_real_escape_string($connect,$notif_type);
		$readStatus =  mysqli_real_escape_string($connect,$readStatus);
		$dateTimeCreated = mysqli_real_escape_string($connect,$dateTimeCreated);


		$insert_qry = "INSERT INTO tb_events_notif (events_notif_id,notif_id,emp_id,events_id,notif_type,readStatus,dateCreated) VALUES ('','$notif_id','$emp_id','$events_id','$notif_type','$readStatus','$dateTimeCreated')";
		$sql = mysqli_query($connect,$insert_qry);

	}


	public function getAllEventsNotif($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_events_notif WHERE emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				// for getting payroll admin name
				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->notif_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				$notif_name = $row_emp->Firstname . " " . $row_emp->Lastname;

				$dateCreated = date_format(date_create($row->dateCreated), 'F d, Y');

				$time = date_format(date_create($row->dateCreated), 'g:i A');
				
				echo '<li id="events_notif" class="'.$row->events_notif_id.'">
						<div id="">
							<div class="container-fluid">
								<div clas="sm-2">
									<img src="'.$row_emp->ProfilePath.'">
								</div>
								<div class="col-sm-10">
									<b>'.$notif_name.'</b> '.$row->notif_type.' <b>on '.$dateCreated.' at '.$time.'</b>
								</div>
							</div>
						</div>
					</li>';
			}
		}
	}


	// for getting payroll notification where read status = 0
	public function unreadEventsNotifCount($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_events_notif WHERE emp_id = '$emp_id' AND readStatus = '0'"));

		return $num_rows;
	}


	// for updating payroll notification
	public function readAllNotif($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$update_qry = "UPDATE tb_events_notif SET readStatus = '1' WHERE emp_id = '$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}



	// for checking if events notif id is existing in the database
	public function checkExistEventsNotifId($events_notif_id){
		$connect = $this->connect();

		$events_notif_id = mysqli_real_escape_string($connect,$events_notif_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_events_notif WHERE events_notif_id = '$events_notif_id'"));

		return $num_rows;
	}


	// for getting information by $events notif id
	public function getInfoByEventsNotifId($events_notif_id){
		$connect = $this->connect();

		$events_notif_id = mysqli_real_escape_string($connect,$events_notif_id);

		$select_qry = "SELECT * FROM tb_events_notif WHERE events_notif_id = '$events_notif_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;
	}


}


?>