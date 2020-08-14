<?php
session_start();
include "../class/connect.php";
include "../class/events.php";


if (isset($_POST["events_id"])) {
	$events_id = $_POST["events_id"];

	$events_class = new Events;
	$date_class = new date;

	// if exist
	if ($events_class->existEventsId($events_id) == 0) {
		echo "Error";	
	}
	// if success
	else {
		$row = $events_class->getEventsInfoById($events_id);
		$_SESSION["delete_events_id"] = $events_id;



?>
	<form class="" action="" method="post">
		<div class="container-fluid">
			<div style="text-align:center;">					
				<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to delete the <b><?php  echo $row->events_title; ?></b> event?</b></span>
			</div>						
		</div>
	</form>

<?php
	} // end if else
}
else {
	header("Location:../Mainform.php");
}
?>