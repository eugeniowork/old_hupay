<?php
session_start();
include "../class/connect.php";
include "../class/attendance_notifications.php";


$attendance_notifications_class = new Attendance_Notifications;
$emp_id = $_SESSION["id"];
$attendance_notifications_class->readAllNotif($emp_id);

//echo "wew";

?>