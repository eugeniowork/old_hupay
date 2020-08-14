<?php
session_start();
include "../class/connect.php";
include "../class/events_notifications.php";


$events_notif_class = new EventsNotification;
$emp_id = $_SESSION["id"];
$events_notif_class->readAllNotif($emp_id);


?>