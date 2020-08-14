<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/attendance_notif.php";
include "../class/time_in_time_out.php";
include "../class/date.php";
include "../class/attendance_notifications.php";



if (isset($_POST["attendance_date"]) && isset($_POST["hour_time_in"]) && isset($_POST["min_time_in"]) 
	 && isset($_POST["hour_time_out"]) && isset($_POST["period_time_in"]) && isset($_POST["period_time_out"]) && isset($_POST["min_time_out"]) 
	 && isset($_POST["remarks"])) {

	$emp_id = $_SESSION["id"];


	$emp_info_class = new EmployeeInformation;
	$row = $emp_info_class->getEmpInfoByRow($emp_id);

	$head_emp_id = $row->head_emp_id;

	$attendace_notif_class = new AttendanceNotif;
	$date_class = new date;

	//$attendance_date = $date_class->dateDefaultDb($_POST["attendance_date"]);

	$biod_id = $row->bio_id;

	// time in
	$hour_time_in = $_POST["hour_time_in"];

	if ($hour_time_in < 10){
		$hour_time_in = "0" . $hour_time_in;
	}

	$min_time_in = $_POST["min_time_in"];

	if ($min_time_in < 10){
		$min_time_in = "0" . $min_time_in;
	}

	//$sec_time_in = $_POST["sec_time_in"];
	$period_time_in = $_POST["period_time_in"];
	if ($period_time_in == "PM" && $hour_time_in != 12){
		$hour_time_in = $hour_time_in + 12;
	}

	$time_in = $hour_time_in . ":" . $min_time_in . ":" . "00";

	// time out
	$hour_time_out = $_POST["hour_time_out"];

	if ($hour_time_out < 10){
		$hour_time_out = "0" . $hour_time_out;
	}

	$min_time_out = $_POST["min_time_out"];

	if ($min_time_out < 10){
		$min_time_out = "0" . $min_time_out;
	}
	//$sec_time_out = $_POST["sec_time_out"];
	$period_time_out = $_POST["period_time_out"];
	if ($period_time_out == "PM" && $hour_time_out != 12){
		$hour_time_out = $hour_time_out + 12;
	}

	$time_out = $hour_time_out . ":" . $min_time_out . ":" . "00";

	$remarks = $_POST["remarks"];

	$current_date = $date_class->getDate();

	
	$attendance_class = new Attendance;

	$attendance_date_month = substr($_POST["attendance_date"],0,2);
	$attendance_date_day = substr(substr($_POST["attendance_date"], -7), 0,2);
	$attendance_date_year = substr($_POST["attendance_date"], -4);




	if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["attendance_date"])) {
    	$_SESSION["add_error_attendance"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Attendance Date</b> not match to the current format mm/dd/yyyy";

    	echo "1";

	}

	// for validating leap year
	else if ($attendance_date_year % 4 == 0 && $attendance_date_month == 2 && $attendance_date_day >= 30){
		$_SESSION["add_error_attendance"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Attendance Date</b> date";
		echo "2";
	}

	// for validating leap year also
	else if ($attendance_date_year % 4 != 0 && $attendance_date_month == 2 && $attendance_date_day >= 29){
		$_SESSION["add_error_attendance"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Attendance Date</b> date";

		echo "3";
	}

	// mga month na may 31
	else if (($attendance_date_month == 4 || $attendance_date_month == 6 || $attendance_date_month == 9 || $attendance_date_month == 11)
			&& $attendance_date_day  >= 31){
		$_SESSION["add_error_attendance"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Attendance Date</b> date";

		echo "4";
	}

	// if failed
	else if ($attendance_class->getRowsTimeInOut($date_class->dateDefaultDb($_POST["attendance_date"]),$biod_id) != 0){
		$_SESSION["add_error_attendance"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The date <b>".$_POST["attendance_date"]."</b> is already exist in your attendance list.</center>";
	
		echo "5";
	}

	// if the value of period time in and period time out is not AM and PM
	else if (($period_time_in != "AM" && $period_time_in != "PM") || ($period_time_out != "AM" && $period_time_out != "PM")){
		$_SESSION["add_error_attendance"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> Theres an error during saving of data.</center>";

		echo "6";
	}

	// kapag mas malaki ung time out sa time in
	else if ($time_out <= $time_in && $time_out != "000:000:00"){
		$_SESSION["add_error_attendance"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> <b>Time out</b> cannot greater than or equal to <b>Time in</b>.</center>";

		echo "7";
	}

	// if success
	else {

		echo "SUCCESS?";

		$attendance_date = $date_class->dateDefaultDb($_POST["attendance_date"]);

		// 
		// if exist in the attendance notif update lang
		if ($attendace_notif_class->existDateEmpId($emp_id,$attendance_date) != 0) {





			$attendance_notif_id = $attendace_notif_class->getAttendanceNotifIdByEmpIdDate($emp_id,$attendance_date);

			$notif_status = 0;
			// ibig sabihin staff xa
			if ($head_emp_id != 0){
				$notif_status = 4;
			}

			// ibig sabihin head xa
			else if ($head_emp_id == 0){
				$notif_status = 0;
			}


			$attendace_notif_class->updateAddAttendanceNotif($attendance_notif_id,$time_in,$time_out,$remarks,$notif_status,$current_date);


			

			// mabigyan ng notifications admin,hr lang so ung role id is 2 and 1
			$emp_id_values = explode("#",$emp_info_class->getEmpIdByNotification($emp_id));


			$count = $emp_info_class->getEmpIdByNotificationCount($emp_id) - 1;


			$final_attendance_date = $date_class->dateFormat($attendance_date);

			$date_create = date_create($time_in);
			$final_time_in = date_format($date_create, 'g:i A');

			$date_create = date_create($time_out);
			$final_time_out = date_format($date_create, 'g:i A');

			$counter = 0;
			do {

				$emp_id = $emp_id_values[$counter];
				$approver_id = $_SESSION["id"];
				$notifType = "Add Attendance on $final_attendance_date with time in $final_time_in and time out $final_time_out";
				$status = "Pending";
				$dateTime = $date_class->getDateTime();

				$attendance_notifications_class = new Attendance_Notifications; // for notifications totally
				$attendance_notifications_class->insertNotifications($emp_id,$approver_id,$attendance_notif_id,'0','0',$notifType,"Add Attendance",$status,$dateTime); //$emp_id,$approve_emp_id,$status,$dateTime,$dateCreated)

				$counter++;
			}while($counter <= $count);
		}

		// if not exist add lang
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


			$attendace_notif_class->insertAttendanceNotif($emp_id,$head_emp_id,"0",$attendance_date,$time_in,$time_out,$remarks,$notif_status,$current_date);
			
			// mabigyan ng notifications admin,hr lang so ung role id is 2 and 1
			$emp_id_values = explode("#",$emp_info_class->getEmpIdByNotification($emp_id));


			$count = $emp_info_class->getEmpIdByNotificationCount($emp_id) - 1;


			$final_attendance_date = $date_class->dateFormat($attendance_date);

			$date_create = date_create($time_in);
			$final_time_in = date_format($date_create, 'g:i A');

			$date_create = date_create($time_out);
			$final_time_out = date_format($date_create, 'g:i A');

			$counter = 0;
			do {

				$emp_id = $emp_id_values[$counter];
				$approver_id = $_SESSION["id"];
				$notifType = "Add Attendance on $final_attendance_date with time in $final_time_in and time out $final_time_out";
				$status = "Pending";
				$dateTime = $date_class->getDateTime();

				$attendance_notifications_class = new Attendance_Notifications; // for notifications totally
				$attendance_notifications_class->insertNotifications($emp_id,$approver_id,$attendace_notif_class->attendanceNotifLastId(),'0','0',$notifType,"Add Attendance",$status,$dateTime); //$emp_id,$approve_emp_id,$status,$dateTime,$dateCreated)

				$counter++;
			}while($counter <= $count);

		}
		
		$_SESSION["add_success_attendance"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> <b>".$_POST["attendance_date"]."</b> with <b>time in ".$_POST["hour_time_in"] . ":" . $_POST["min_time_in"]." " .$period_time_in. "</b> and <b>time out ".$_POST["hour_time_out"]. ":" . $_POST["min_time_out"]. " $period_time_out</b> is successfully submitted.</center>";		
	


		
		

		



		/*
		// for notifications

		





		// for giving a notifications for approval
		
		*/

	}

	header("Location:../view_attendance.php");



}

// if edited in the inspect element
else {
	header("Location:../MainForm.php");
}

?>