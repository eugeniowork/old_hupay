<?php
session_start();
include "../class/connect.php";
include "../class/attendance_notif.php";
include "../class/date.php";


if (isset($_POST["attendance_notif_id"])){
	$emp_id = $_SESSION["id"];
	$attendance_notif_id = $_POST["attendance_notif_id"];

	$attendance_notif_class = new AttendanceNotif;
	$date_class = new date;

	//echo $attendance_notif_class->checkOwnAttendanceNotif($attendance_notif_id,$emp_id);

	if ($attendance_notif_class->checkExistAttendanceNotifId($attendance_notif_id) == 0){
		echo "Error";
	}

	else if ($attendance_notif_class->checkOwnAttendanceNotif($attendance_notif_id,$emp_id) == 0){
		echo "Error";
	}
	else {
		$row = $attendance_notif_class->getRequestAttendanceById($attendance_notif_id);
		$date = $date_class->dateDefault($row->date);

		$time_in_values = explode(":", $row->time_in);
		$hour_time_in = $time_in_values[0];
		$final_hour_time_in = $hour_time_in;
		$min_time_in = $time_in_values[1];
		if (strlen($min_time_in) == 1){
			$min_time_in ="0".$min_time_in;
		}

		$period_time_in = "AM";
		if ($hour_time_in > 11){
			$period_time_in = "PM";
			$final_hour_time_in =  $final_hour_time_in - 12;
			if (strlen($final_hour_time_in) == 1){
				$final_hour_time_in ="0".$final_hour_time_in;
			}
		}

		$time_out_values = explode(":", $row->time_out);
		$hour_time_out = $time_out_values[0];
		$final_hour_time_out = $hour_time_out;
		$min_time_out = $time_out_values[1];
		if (strlen($min_time_out) == 1){
			$min_time_out ="0".$min_time_out;
		}

		$period_time_out = "AM";
		if ($hour_time_out > 11){
			$period_time_out = "PM";
			$final_hour_time_out =  $final_hour_time_out - 12;
			if (strlen($final_hour_time_out) == 1){
				$final_hour_time_out = "0".$final_hour_time_out;
			}
		}

		//echo $period_time_in;

?>
	<form class="form-horizontal" action="" method="post" id="cancel_file_attendance_info_form">

		<!--<font><b>Note:</b> Used Military Time</font> -->
		<div class="form-group">
			<center><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span>&nbsp;Are you sure you want to cancel you attendance file request on <b><?php echo $date_class->dateFormat($row->date); ?></b> with the <b>time in <?php echo $final_hour_time_in . ":".$min_time_in . " " . $period_time_in; ?></b> and <b>time out <?php echo $final_hour_time_out . ":" .$min_time_out . " " .$period_time_out;  ?></b>?</center>
		</div>
	
	</form>

	<script>
		$(document).ready(function(){
			$("a[id='cancel_yes_attendance_updates']").on("click",function(){
				$("#cancel_file_attendance_info_form").attr("action","php script/cancel_attendance_request_updates.php");
				$("#cancel_file_attendance_info_form").append("<input type='hidden' value='<?php echo $attendance_notif_id; ?>' name='attendance_notif_id'/>");
				$("#cancel_file_attendance_info_form").submit();
			});
		});

	</script>

<?php
	} // end of else
} // end of if
else {
	header("Location:../MainForm.php");
}

?>