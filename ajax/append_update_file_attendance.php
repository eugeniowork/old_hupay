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
	<form class="form-horizontal" action="php script/update_file_request_attendance.php" method="post" id="update_file_attendance_info_form">

		<!--<font><b>Note:</b> Used Military Time</font> -->

		<div class="form-group">
			<div class="col-sm-12">				
				<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Date:&nbsp;<span class="red-asterisk">*</span></label>
				<input type="text" value="<?php echo $date; ?>" name="update_attendance_date" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="readonly_txt" value="" class="form-control" placeholder="Input Date" required="required">
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Time In:&nbsp;<span class="red-asterisk">*</span></label>
			</div>
			<div class="col-sm-3" style="margin-left:40px;margin-right:-20px;">
				<input type="text" value="<?php echo $final_hour_time_in; ?>" id="number_only" name="update_hour_time_in"  value="" class="form-control" placeholder="H" required="required">
			</div>
			<div class="col-sm-1" style="margin-top:10px;">
				:
			</div>
			<div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
				<input type="text" value="<?php echo $min_time_in; ?>" id="number_only" name="update_min_time_in" value="" class="form-control" placeholder="M" required="required">
			</div>
			<!--<div class="col-sm-1" style="margin-top:10px;">
				:
			</div> -->
			<div class="col-sm-4" style="">
				<select class="form-control" name="update_period_time_in" required="required">
					<option></option>
					<option value="AM" <?php if ($period_time_in == "AM") { echo "selected='selected'";} ?> >AM</option>
					<option value="PM" <?php if ($period_time_in == "PM") { echo "selected='selected'";} ?> >PM</option>
				</select>
				<!--<input type="text" id="number_only" name="sec_time_in" value="" class="form-control" placeholder="S" required="required"> -->

			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Time Out:&nbsp;<span class="red-asterisk">*</span></label>
			</div>
			<div class="col-sm-3" style="margin-left:40px;margin-right:-20px;">
				<input type="text" value="<?php echo $final_hour_time_out; ?>" id="number_only" name="update_hour_time_out" value="" class="form-control" placeholder="H" required="required">
			</div>
			<div class="col-sm-1" style="margin-top:10px;">
				:
			</div>
			<div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
				<input type="text" value="<?php echo $min_time_out; ?>" id="number_only" name="update_min_time_out" value="" class="form-control" placeholder="M" required="required">
			</div>
			<!--<div class="col-sm-1" style="margin-top:10px;">
				:
			</div> -->
			<div class="col-sm-4" style="">
				<select class="form-control" name="update_period_time_out" required="required">
					<option></option>
					<option value="AM" <?php if ($period_time_out == "AM") { echo "selected='selected'";} ?> >AM</option>
					<option value="PM" <?php if ($period_time_out == "PM") { echo "selected='selected'";} ?> >PM</option>
				</select>
				<!--<input type="text" id="number_only" name="sec_time_in" value="" class="form-control" placeholder="S" required="required"> -->

			</div>
		</div>



		<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label"><span class="glyphicon glyphicon-map-marker" style="color:#2E86C1;"></span> Remarks:&nbsp;<span class="red-asterisk">*</span></label>
				<textarea class="form-control" name="update_remarks" placeholder="Input Remarks" required="required"><?php echo $row->remarks; ?></textarea>
			</div>
		</div>

		<div class="form-group">
			<div style="text-align:center;">
				<input type="submit" id="request_update_btn" value="Update" class="btn btn-success"/>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-12">
				<!-- for message purpose -->
				<strong id="update_message">&nbsp;</strong>
			</div>
		</div>

</form>
<script>
	$(document).ready(function(){
		  // for handling security in time in hours
	    $("input[name='update_hour_time_in']").on('input', function(){
	      if ($(this).val() >= 13){
	      		 $(this).val($(this).val().slice(0,-1));
	      	}

	   });

	      // for handling security in time in hours
	    $("input[name='update_min_time_in']").on('input', function(){
	      	if ($(this).val() >= 60){
	      		 $(this).val($(this).val().slice(0,-1));
	      	}

	   });

	      // for handling security in time in hours
	   /* $("input[name='sec_time_in']").on('input', function(){
	      	if ($(this).val() >= 60){
	      		 $(this).val($(this).val().slice(0,-1));
	      	}

	   });*/


	     // for handling security in time out hours
	    $("input[name='update_hour_time_out']").on('input', function(){
	      if ($(this).val() >= 13){
	      		 $(this).val($(this).val().slice(0,-1));
	      	}

	   });

	      // for handling security in time out hours
	    $("input[name='update_min_time_out']").on('input', function(){
	      	if ($(this).val() >= 60){
	      		 $(this).val($(this).val().slice(0,-1));
	      	}

	   });

	  

       // for handling security in number only
	    $("input[id='number_only']").on('input', function(){
	       if ($(this).attr("maxlength") != 2){
	            if ($(this).val().length > 2){
	                $(this).val($(this).val().slice(0,-1));
	            }
	           $(this).attr("maxlength","2");
	       }

	   });

	    $("input[id='number_only']").keydown(function (e) {
	        // Allow: backspace, delete, tab, escape, enter , F5
	        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116]) !== -1 ||
	             // Allow: Ctrl+A, Command+A
	            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
	             // Allow: home, end, left, right, down, up
	            (e.keyCode >= 35 && e.keyCode <= 40)) {
	                 // let it happen, don't do anything
	                 return;
	        }
	        // Ensure that it is a number and stop the keypress
	        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	            e.preventDefault();
	        }
	    });

	     // for security purpose return false 
	     $("input[id='number_only").on("paste", function(){
	          return false;
	     });


	     //$("input[name='update_attendance_date']").dcalendarpicker(); // dateFrom 


	      $("input[id='date_only']").keydown(function (e) {
	      	return false;
	      	/*
		    //alert(e.keyCode);
		 	 //return false;
		 	 // Allow: backspace, delete, tab, escape, enter , F5 , backslash
	        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116,191]) !== -1 ||
	             // Allow: Ctrl+A, Command+A
	            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
	             // Allow: home, end, left, right, down, up
	            (e.keyCode >= 35 && e.keyCode <= 40)) {
	                 // let it happen, don't do anything
	                 return;
	        }
	        // Ensure that it is a number and stop the keypress
	        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	            e.preventDefault();
	        }*/
	 	 });


	      // for security purpose return false 
	     $("input[id='date_only").on("paste", function(){
	          return false;
	     });



	     $("input[id='date_only']").change(function(){
	     	$("div[class='datepicker dropdown-menu']").attr("style","none");
	       	//$("div[class='datepicker']").attr("style","display:none");
	       //	alert("Hello World!");
	 	 });


	 	     // for returning false
	     $(document).on('keypress', 'input[id="readonly_txt"]', function (event) {
	        return false;
	    });


	 	


          $("input[id='request_update_btn']").on("click",function(){
          		$("input[name='update_attendance_date']").attr("required","required");
		        $("input[name='update_hour_time_in']").attr("required","required");
		        $("input[name='update_min_time_in']").attr("required","required");
		        $("select[name='update_period_time_in']").attr("required","required");

		        $("input[name='update_hour_time_out']").attr("required","required");
		        $("input[name='update_min_time_out']").attr("required","required");
		        $("select[name='update_period_time_out']").attr("required","required");
		        $("textarea[name='update_remarks']").attr("required","required");

		        if ($("input[name='update_attendance_date']").val() != "" && $("input[name='update_hour_time_in']").val() !="" && $("input[name='update_min_time_in']").val() != "" && $("input[name='update_hour_time_out']").val() != "" && $("input[name='update_min_time_out']").val() != "" && $("select[name='update_period_time_out']").val() != "" && $("textarea[name='update_remarks']").val() != ""){
	        		//alert("READY FOR APPENDING!");
	        		$("#update_file_attendance_info_form").append("<input type='hidden' value='<?php echo $attendance_notif_id; ?>' name='attendance_notif_id'/>");
		        }
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