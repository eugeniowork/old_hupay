<?php
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/leave_class.php";

if (isset($_POST["emp_id"]) && isset($_POST["leaveCount"])){
	$emp_id = $_POST["emp_id"];
	$leaveCount = $_POST["leaveCount"];

	//echo $emp_id . " " . $leaveCount;
	$leave_class = new Leave;

	

	// for checking if no updates were taken
	if ($leave_class->noChangesUpdateLeaveCount($emp_id,$leaveCount))
	echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No changes was made, No updates were taken.";
	

	else {
		$leave_class->updateLeaveCount($emp_id,$leaveCount);
		echo "<span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> Leave Count is successfully updated.";
	}

}
else {
	header("Location:../MainForm.php");
}

?>