<?php
session_start();
ini_set('max_execution_time', 300);
include "../class/connect.php";
include "../class/attendance_notif.php";
include "../class/time_in_time_out.php";
include "../class/attendance_notifications.php";
include "../class/date.php";
include "../class/emp_information.php";
include "../class/working_hours_class.php";
include "../class/audit_trail_class.php";

if (isset($_POST["password"]) && isset($_GET["approve"])) {

	$date_class = new date;

	$password =  $_POST["password"];
	$attendance_notif_class = new AttendanceNotif;

	$approve = $_GET["approve"];

	



	$attendance_notif_id = $_GET["attendance_notif_id"];
	$row = $attendance_notif_class->getRequestAttendanceById($attendance_notif_id);
	if ($approve == "Approve"){
		
		// for updating notif status in attendance notif table
		

		$notif_status = 0;
		if ($row->head_emp_id == 0 || $row->notif_status == 0){
			$notif_status = 1;
		}


		$attendance_notif_class->updateApproveRequest($attendance_notif_id,$notif_status);


		// ibig sabihin fully approve
		if ($notif_status == 1) {



			$emp_id = $row->emp_id;
			
			$time_in = $row->time_in;
			$time_out = $row->time_out;
			$attendance_date = $row->date; 


			$attendance_id = $row->attendance_id;
			// for updating notif status
			//
			$attendance_class = new Attendance;

			$emp_info_class = new EmployeeInformation;
			$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);
			$bio_id = $row_emp->bio_id;

			// for getting working hour
			$working_hours_class = new WorkingHours;
			$row_wh = $working_hours_class->getWorkingHoursInfoById($row_emp->working_hours_id);
			$emp_time_in = $row_wh->timeFrom;
			$emp_time_out = $row_wh->timeTo;

			//echo $attendance_id;

			// first check if exist ung attendance id kapag exist update lang , kapag d exist insert , ibig sabihin nito nagpa edit lang siya eh
			if ($attendance_class->checkExistByAttendanceId($attendance_id) == 1){

				// for new finger print scaner check din natin kung meron na siyang in at out na mas mataas sa kanyang time in
				$row_att = $attendance_class->getInfoByAttendaceId($attendance_id);
				$db_time_in = $row_att->time_in;
				$db_time_out = $row_att->time_out;

				if ($db_time_in >= $emp_time_out){
					$time_out = $db_time_in;
				}

				if ($db_time_out >= $emp_time_out){
					$time_out = $db_time_out;
				}

				//echo "DITO!";


				$attendance_class->updateRequestTimeInTimeOut($attendance_id,$time_in,$time_out);
				//echo "update";
			}
			// if did not exist insert
			else {
				//echo "insert";
				//echo "DITO1";

				// check muna natin kung exist ung bio id at attendance date
				if ($attendance_class->getRowsTimeInOut($attendance_date,$bio_id) != 0) {

					// for new finger print scaner check din natin kung meron na siyang in at out na mas mataas sa kanyang time in
					$row_att = $attendance_class->getInfoByDateBioId($attendance_date,$bio_id);
					$db_time_in = $row_att->time_in;
					$db_time_out = $row_att->time_out;

					if ($db_time_in >= $emp_time_out){
						$time_out = $db_time_in;
					}

					if ($db_time_out >= $emp_time_out){
						$time_out = $db_time_out;
					}

					$attendance_class->updateAttendanceInfo($attendance_date,$bio_id,$time_in,$time_out);
				}
				else {
					$attendance_class->insert_time_in_time_out($bio_id,$attendance_date,$time_in,$time_out,$date_class->getDate());
				}
			}
		}

		$_SESSION["success"] = "success";

		$task_description = "Approve Attendance Updates";
	}

	else {
		// 
		$attendance_notif_class->updateDisapproveRequest($attendance_notif_id);
		$_SESSION["success_disapprove"] = "success";

		$task_description = "Dispprove Attendance Updates";
	}


	

	$final_attendance_date = $date_class->dateFormat($attendance_date);

	$date_create = date_create($time_in);
	$final_time_in = date_format($date_create, 'g:i A');

	$date_create = date_create($time_out);
	$final_time_out = date_format($date_create, 'g:i A');


	$emp_id = $emp_id = $row->emp_id;
	$approver_id = $_SESSION["id"];
	$notifType = "Update Attendance on $final_attendance_date with time in $final_time_in and time out $final_time_out";
	$status = $approve;
	$dateTime = $date_class->getDateTime();

	// dito ako hihinto


	// for giving a notifications for approval
	$attendance_notifications_class = new Attendance_Notifications; // for notifications totally
	$attendance_notifications_class->insertNotifications($emp_id,$approver_id,$attendance_notif_id,'0','0',$notifType,$approve." Attendance",$status,$dateTime); //$emp_id,$approve_emp_id,$status,$dateTime,$dateCreated)


	// for audit trail
	$audit_trail_class = new AuditTrail;
	$module = "Attendance Updates List";
	$audit_trail_class->insertAuditTrail($emp_id,$approver_id,'0',$module,$task_description,$dateTime);




	header("Location:../attendance_notif_list.php");

}

else {


	header("Location:../MainForm.php");
}




?>