<?php
include "../class/connect.php";
include "../class/working_hours_class.php";

$working_hours_class = new WorkingHours;

if (isset($_POST["working_hours_id"])){
	$wokingHoursId = $_POST["working_hours_id"];

	if ($working_hours_class->checkExistWorkingHoursId($wokingHoursId) == 0){
		echo "Error";
	}


	else {
		$row = $working_hours_class->getWorkingHoursInfoById($wokingHoursId);

?>
	<form class="" action="" method="post" id="form_deleteWorkingHours">
		<div class="container-fluid">
			<div style="text-align:center;">					
				<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to delete the <b>Working Hours</b> of <b><?php echo $row->timeFrom . "-" . $row->timeTo; ?></b> ?</b></span>
			</div>						
		</div>
	</form>

<?php
	} // end of else
} // end of if isset

else {
	header("Location:../MainForm.php");
}


?>