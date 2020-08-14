<?php
	class Attendance_Notifications extends Connect_db{

		// for insert
		public function insertNotifications($emp_id,$notif_emp_id,$attendance_notif_id,$attendance_ot_id,$leave_id,$notifType,$type,$status,$dateTime){
			 $connect = $this->connect();

			 $emp_id = mysqli_real_escape_string($connect,$emp_id);
			 $notif_emp_id = mysqli_real_escape_string($connect,$notif_emp_id);
			 $attendance_notif_id = mysqli_real_escape_string($connect,$attendance_notif_id);
			 $leave_id = mysqli_real_escape_string($connect,$leave_id);
			 $attendance_ot_id = mysqli_real_escape_string($connect,$attendance_ot_id);
			 $notifType = mysqli_real_escape_string($connect,$notifType);
			 $type = mysqli_real_escape_string($connect,$type);
			 $status = mysqli_real_escape_string($connect,$status);
			 $dateTime = mysqli_real_escape_string($connect,$dateTime);
			// $dateCreated = mysqli_real_escape_string($connect,$dateCreated);

			 $insert_qry = "INSERT INTO tb_attendance_notifications (attendance_notification_id,emp_id,notif_emp_id,attendance_notif_id,attendance_ot_id,leave_id,NotifType,type,Status,ReadStatus,DateTime)
		 								 VALUES ('','$emp_id','$notif_emp_id','$attendance_notif_id','$attendance_ot_id','$leave_id','$notifType','$type','$status','0','$dateTime')";

			 $sql = mysqli_query($connect,$insert_qry);
		
		}


		// for checking count if meron na siyang notifications sa attendance
		public function checkExistNotif($emp_id){
			$connect = $this->connect();

			$emp_id = mysqli_real_escape_string($connect,$emp_id);

			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notifications WHERE emp_id = '$emp_id'"));
			return $num_rows;
		}


		// for getting the last id in database
		public function getLastAttendanceNotifications($emp_id){
			$connect = $this->connect();

			$emp_id = mysqli_real_escape_string($connect,$emp_id);

			$select_last_id_qry = "SELECT * FROM tb_attendance_notifications WHERE emp_id = '$emp_id' ORDER BY  attendance_notification_id DESC LIMIT 1";
			$result = mysqli_query($connect,$select_last_id_qry);
			$row = mysqli_fetch_object($result);
			return $row;
			//$last_id = $row->attendance_notification_id;
			//return $last_id;
		}


		// for notifications purpose
		public function notifications($emp_id){
			 $connect = $this->connect();

			 $emp_id = mysqli_real_escape_string($connect,$emp_id);

			 $select_qry = "SELECT * FROM tb_attendance_notifications WHERE emp_id='$emp_id' ORDER BY DateTime DESC";
			 if ($result = mysqli_query($connect,$select_qry)){
			 	while ($row = mysqli_fetch_object($result)){

			 		$approver_emp_id = $row->notif_emp_id;
			 		$select_qry_approver = "SELECT * FROM tb_employee_info WHERE emp_id='$approver_emp_id'";
			 		$result_approver = mysqli_query($connect,$select_qry_approver);
			 		$row_approver = mysqli_fetch_object($result_approver);

			 		$profilePath = $row_approver->ProfilePath;

			 		$approver_name = $row_approver->Firstname . " " . $row_approver->Middlename . " " . $row_approver->Lastname;

			 		$date_create = date_create($row->DateTime);
					$dateApprove = date_format($date_create, 'F d, Y');

					$time_create = date_create($row->DateTime);
					$timeApprove = date_format($time_create, 'g:i A');


					// kapag update , add attendance or 
					if ($row->Status != ""){
						$status = "";
						if ($row->Status != "Pending"){
							$status = $row->Status . " your";
						}

				 		echo '<li id="'.$row->attendance_notification_id.'">
							<div id="notif-li">
								<div class="container-fluid">
									<div clas="sm-2">
										<img src="'.$profilePath.'">
									</div>
									<div class="col-sm-10">
										<b>'.$approver_name.'</b> '.$status.' Request '.$row->NotifType.' <b>on '.$dateApprove.' at '.$timeApprove.'</b>
									</div>
								</div>
							</div>
						</li>';
					}


					// for uploading attendance
					else {
						echo '<li id="'.$row->attendance_notification_id.'">
							<div id="notif-li">
								<div class="container-fluid">
									<div clas="sm-2">
										<img src="'.$profilePath.'">
									</div>
									<div class="col-sm-10">
										<b>'.$approver_name.'</b> Already '.$row->NotifType.' <b>on '.$dateApprove.' at '.$timeApprove.'</b>
									</div>
								</div>
							</div>
						</li>';
					}


			 	}
			}
					
					/*
					
					*/
		}


		// iisahin ko na tong notif , mapa OT at maparequest ng time or adding ng time ok pag-aralang mabuti


		// for getting all unread notification
		public function unreadNotif($emp_id){
			$connect = $this->connect();

			$emp_id = mysqli_real_escape_string($connect,$emp_id);

			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notifications WHERE emp_id = '$emp_id' AND ReadStatus = '0'"));
			return $num_rows;
		}



		// for updating all unread notifications to read
		public function readAllNotif($emp_id){
			$connect = $this->connect();

			$emp_id = mysqli_real_escape_string($connect,$emp_id);

			$update_qry = "UPDATE tb_attendance_notifications SET ReadStatus = '1' WHERE emp_id='$emp_id' AND ReadStatus = '0'";
			$sql = mysqli_query($connect,$update_qry);
		}


		// for checking if already exist the uploading attendance notifications without doing anything
		public function existUploadAttendanceNotif($notif_emp_id,$emp_id,$notifType){
			$connect = $this->connect();

			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notifications WHERE emp_id = '$notif_emp_id' AND notif_emp_id = '$emp_id'
																AND NotifType = '$notifType'"));
			return $num_rows;
		}


		// for checking if the attendance_notification_id is exist
		public function checkExistAttendanceNotificationId($attendance_notification_id){
			$connect = $this->connect();

			$attendance_notification_id = mysqli_real_escape_string($connect,$attendance_notification_id);

			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notifications WHERE attendance_notification_id = '$attendance_notification_id'"));
			return $num_rows;
		}


		// for getting all information by attendance notification id
		public function getInformationById($attendance_notification_id){
			$connect = $this->connect();

			$attendance_notification_id = mysqli_real_escape_string($connect,$attendance_notification_id);

			$select_qry = "SELECT * FROM tb_attendance_notifications WHERE attendance_notification_id = '$attendance_notification_id'";
			$result = mysqli_query($connect,$select_qry);
			$row = mysqli_fetch_object($result);
			return $row;
		}


		// for viewing of attendance notifications page , check if the parameter of GET is existing to the database
		// for checking if the attendance_notification_id is exist
		public function checkExistAttendanceTypeAndId($attendance_notification_id,$type){
			$connect = $this->connect();

			$attendance_notification_id = mysqli_real_escape_string($connect,$attendance_notification_id);
			$type = mysqli_real_escape_string($connect,$type);

			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notifications WHERE attendance_notif_id = '$attendance_notification_id' AND type= '$type'"));
			return $num_rows;
		}

		// for checking if the attendance_ot_id is exist
		public function checkExistAttendanceTypeAndIdOT($attendance_ot_id,$type){
			$connect = $this->connect();

			$attendance_ot_id = mysqli_real_escape_string($connect,$attendance_ot_id);
			$type = mysqli_real_escape_string($connect,$type);

			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notifications WHERE attendance_ot_id = '$attendance_ot_id' AND type= '$type'"));
			return $num_rows;
		}


		// for checking if type is not exist
		public function checkExistAttendanceType($type){
			$connect = $this->connect();

			$type = mysqli_real_escape_string($connect,$type);

			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notifications WHERE type= '$type'"));
			return $num_rows;
		}

		


	}

?>