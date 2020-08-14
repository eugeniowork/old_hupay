<?php
	session_start();
	include "../class/connect.php";
	include "../class/attendance_notifications.php";
	include "../class/emp_information.php";

	$attendance_notification_class = new Attendance_Notifications;
	$emp_info_class = new EmployeeInformation;

	$id = $_SESSION["id"];
	if (isset($_POST["get"]) && isset($_POST["count"])){
		//echo $id;
		$count = $attendance_notification_class->checkExistNotif($id);

		if ($count == $_POST["count"]){
			echo "There is no new notif!";
		}

		else {
			// type=File Leave&id=32393
			$row = $attendance_notification_class->getLastAttendanceNotifications($id);



			$row_emp = $emp_info_class->getEmpInfoByRow($row->notif_emp_id);

			echo $count . "#" . "type=" .$row->type. "&id=".$row->attendance_notification_id . "#".$row_emp->Firstname . " " . $row_emp->Lastname . " about request " . $row->NotifType;
		}

	}
	else {
		header("Location:../index.php");
	}

?>