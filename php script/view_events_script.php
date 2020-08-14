<?php
//session_start();
include "../class/connect.php";
include "../class/events_notifications.php";

$events_notif_class = new EventsNotification;


if (isset($_GET["events_notif_id"])){
	$events_notif_id = $_GET["events_notif_id"];
	// if not exist
	if ($events_notif_class->checkExistEventsNotifId($events_notif_id) == 0){
	}
	else {
		$row = $events_notif_class->getInfoByEventsNotifId($events_notif_id);
		//$_SESSION["view_events_id"]  = $row->events_id;

	}
	header("Location:../MainForm.php#events_".$row->events_id);
}
else {
	header("Location:../MainForm.php");
}

?>