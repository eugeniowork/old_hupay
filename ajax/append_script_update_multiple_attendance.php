<?php
session_start();
include "../class/connect.php";
include "../class/attendance_notif.php";

if(isset($_POST["approve"])) {
	//$attendance_notif_id = $_POST["attendance_notif_id"];
	$approve = $_POST["approve"];


	/*
	$attendance_notif_class = new AttendanceNotif;
	// if edited in the inspect element and attendance notif id is not existed so error 
	if ($attendance_notif_class->checkExistAttendanceNotifId($attendance_notif_id) == 0){
		echo "Error";
	}

	// if success
	else {
		*/
	?>
		<form class="form-horizontal" action="" method="post" id="approve_multiple_update_attendance_form">

			<!--<div style="text-align:center">
				<b><i><?php echo $approve;  ?></i></b>
			</div> -->

			<div class="form-group">
				<div class="col-sm-12">				
					<label class="control-label"><span class="glyphicon glyphicon-lock" style="color:#2E86C1;"></span> Please enter your password:</label>
					<div class="input-group">
						<input type="password" name="password" placeholder="Enter Your Password" class="form-control" required="required">
					 	<input type="text" name="hidden" style="display:none;"/>
					 	<span class="input-group-btn">
					    	<button class="btn btn-primary" type="button" id="enter"><?php echo $approve; ?></button>
					    </span>

					</div>	

				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-12">
					<!-- for message purpose -->
					<strong id="message">&nbsp;</strong>
				</div>
			</div>

		</form>


		<script>
		
			$(document).ready(function(){

				
			});

		</script>



	<?php
	//} // end of else
} // end of if
else {
	header("Location:../Mainform.php");
}

?>