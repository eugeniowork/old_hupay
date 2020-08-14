<?php
include "../class/connect.php";
include "../class/attendance_notifications.php";

if (isset($_POST["attendance_notification_id"])){

	$attendance_notification_id = $_POST["attendance_notification_id"];

	$attendance_notification_class = new Attendance_Notifications;
	
	if ($attendance_notification_class->checkExistAttendanceNotificationId($attendance_notification_id) == 0){
		echo "Error";
	}
	else {
		$row = $attendance_notification_class->getInformationById($attendance_notification_id);
		echo $row->type;
	}
}

else {
	header("Location:../MainForm.php");
}


?>
