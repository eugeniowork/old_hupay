<?php
session_start();
include "../class/connect.php";
include "../class/events.php";
$events_class = new Events;

if (isset($_SESSION["delete_events_id"])){
	$events_id = $_SESSION["delete_events_id"];

	// for information purpose
	$row = $events_class->getEventsInfoById($events_id);

	$events_class->deleteEventsInfo($events_id); // delete events info
	

	//$holiday_class->delete_holiday($holiday_id); // deletion query

	if ($events_class->checkExistImage($events_id)){

		$row_img = $events_class->getEventsImagesById($events_id);
		$path = $row_img->imagePath;

		//echo $path;

		// unlink
		unlink("../".$path);

		$events_class->deleteEventsImage($events_id); // delete events images


	}


	$_SESSION["success_msg_del_events"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The event <b>$row->events_title</b> is successfully deleted</center>";
	header("Location:../event_list.php");

}

else {
	header("Location:../MainForm.php");
}

?>