<?php
session_start();
include "../class/connect.php";
include "../class/attendance_notif.php";
include "../class/time_in_time_out.php";
include "../class/date.php";
include "../class/emp_information.php";
include "../class/attendance_notifications.php";

$emp_info_class = new EmployeeInformation;
$date_class = new date;
$attendance_notif_class = new AttendanceNotif;

// for security purpose
if (isset($_POST["hour_time_in"]) && isset($_POST["min_time_in"]) && isset($_POST["period_time_in"]) 
	&& isset($_POST["hour_time_out"]) && isset($_POST["min_time_out"]) && isset($_POST["period_time_out"]) && isset($_POST["remarks"])) {
	
	$emp_id = $_SESSION["id"];



	$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);
	$head_emp_id = $row_emp->head_emp_id;

	$attendance_id = $_SESSION["attendance_id_update_request"];


	// time in
	$hour_time_in = $_POST["hour_time_in"];

	

	$min_time_in = $_POST["min_time_in"];



	//$sec_time_in = $_POST["sec_time_in"];
	$period_time_in = $_POST["period_time_in"];
	if ($period_time_in == "PM" && $hour_time_in != 12){
		$hour_time_in = $hour_time_in + 12;
	}


	$time_in = $hour_time_in . ":" . $min_time_in . ":00";


	$hour_time_out = $_POST["hour_time_out"];



	$min_time_out = $_POST["min_time_out"];


	//$sec_time_out = $_POST["sec_time_out"];
	$period_time_out = $_POST["period_time_out"];
	if ($period_time_out == "PM" && $hour_time_out != 12){
		$hour_time_out = $hour_time_out + 12;
	}

	$time_out = $hour_time_out. ":" . $min_time_out . ":00";

	$remarks = $_POST["remarks"];
	$dateCreated = $date_class->getDate();


	$attendance_class = new Attendance;
	$row = $attendance_class->getInfoByAttendaceId($attendance_id);
	$date = $row->date;


	// if the value of period time in and period time out is not AM and PM
	if (($period_time_in != "AM" && $period_time_in != "PM") || ($period_time_out != "AM" && $period_time_out != "PM")){
		$_SESSION["update_error_overtime"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> Theres an error during saving of data.</center>";
	}

	// kapag mas malaki ung time out sa time in
	else if ($time_out <= $time_in){
		$_SESSION["update_error_overtime"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> <b>Time out</b> cannot greater than or equal to <b>Time in</b>.</center>";
	}


	// if success
	else {

		
		// update if exist
		if ($attendance_notif_class->existAttendanceId($attendance_id) == 1) {


			$notif_status = 0;
			// ibig sabihin staff xa
			if ($head_emp_id != 0){
				$notif_status = 4;
			}

			// ibig sabihin head xa
			else if ($head_emp_id == 0){
				$notif_status = 0;
			}

			$attendance_notif_class->updateAttendanceNotif($attendance_id,$time_in,$time_out,$remarks,$notif_status,$dateCreated);


			//$attendance_notif_class->insertAttendanceNotif($emp_id,$attendance_id,$date,$time_in,$time_out,$remarks,$dateCreated);
			// for notifications
		// mabigyan ng notifications admin,hr lang so ung role id is 2 and 1
			$emp_id_values = explode("#",$emp_info_class->getEmpIdByNotification($emp_id));


			$count = $emp_info_class->getEmpIdByNotificationCount($emp_id) - 1;

			$final_attendance_date = $date_class->dateFormat($date);

			$date_create = date_create($time_in);
			//echo $time_in;
			$final_time_in = date_format($date_create, 'g:i A');

			$date_create = date_create($time_out);
			$final_time_out = date_format($date_create, 'g:i A');

			$counter = 0;
			do {

				$emp_id = $emp_id_values[$counter];
				$approver_id = $_SESSION["id"];
				$notifType = "Update Attendance on $final_attendance_date with time in $final_time_in and time out $final_time_out";
				$status = "Pending";
				$dateTime = $date_class->getDateTime();

				$attendance_notifications_class = new Attendance_Notifications; // for notifications totally
				$attendance_notifications_class->insertNotifications($emp_id,$approver_id,$attendance_notif_class->getAttendanceNotifIdByAttendanceId($attendance_id),'0','0',$notifType,"Update Attendance",$status,$dateTime); //$emp_id,$approve_emp_id,$status,$dateTime,$dateCreated)

				$counter++;
			}while($counter <= $count);

		}

		// insert
		else {
			
			$notif_status = 0;
			// ibig sabihin staff xa
			if ($head_emp_id != 0){
				$notif_status = 4;
			}

			// ibig sabihin head xa
			else if ($head_emp_id == 0){
				$notif_status = 0;
			}


			$attendance_notif_class->insertAttendanceNotif($emp_id,$head_emp_id,$attendance_id,$date,$time_in,$time_out,$remarks,$notif_status,$dateCreated);
			// for notifications
		// mabigyan ng notifications admin,hr lang so ung role id is 2 and 1
			$emp_id_values = explode("#",$emp_info_class->getEmpIdByNotification($emp_id));


			$count = $emp_info_class->getEmpIdByNotificationCount($emp_id) - 1;

			$final_attendance_date = $date_class->dateFormat($date);

			$date_create = date_create($time_in);
			//echo $time_in;
			$final_time_in = date_format($date_create, 'g:i A');

			$date_create = date_create($time_out);
			$final_time_out = date_format($date_create, 'g:i A');

			$counter = 0;
			do {

				$emp_id = $emp_id_values[$counter];
				$approver_id = $_SESSION["id"];
				$notifType = "Update Attendance on $final_attendance_date with time in $final_time_in and time out $final_time_out";
				$status = "Pending";
				$dateTime = $date_class->getDateTime();

				$attendance_notifications_class = new Attendance_Notifications; // for notifications totally
				$attendance_notifications_class->insertNotifications($emp_id,$approver_id,$attendance_notif_class->attendanceNotifLastId(),'0','0',$notifType,"Update Attendance",$status,$dateTime); //$emp_id,$approve_emp_id,$status,$dateTime,$dateCreated)

				$counter++;
			}while($counter <= $count);

		}

		


		
		$_SESSION["success_update_request_attendance"] = "success";
	}

	header("Location:../view_attendance.php");
}

else {
	header("Location:../MainForm.php");
}





?>