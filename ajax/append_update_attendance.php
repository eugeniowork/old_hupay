<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/time_in_time_out.php";
include "../class/date.php";

if (isset($_POST["attendance_id"])) {
	$emp_id = $_SESSION["id"];
	$emp_info_class = new EmployeeInformation;

	$bio_id = $emp_info_class->getEmpInfoByRow($emp_id)->bio_id;


	$attendance_id = $_POST["attendance_id"];

	$_SESSION["attendance_id_update_request"] = $attendance_id;

	$attendance_class = new Attendance;
	$exist_attendance = $attendance_class->existAttendance($attendance_id,$bio_id);
	
	// if not exist
	if ($exist_attendance == 0){
		echo "Error";
	}

	// if success
	else {	
		$row = $attendance_class->getInfoByAttendaceId($attendance_id);

		$time_in = explode(":",$row->time_in);
		$time_out = explode(":",$row->time_out);

		// for time in


		$hour_time_in = $time_in[0];

		$period_time_in = "AM";
		// ibig sabihin 13 and above
		if ($hour_time_in > 12){
			$hour_time_in = $hour_time_in - 12;
			$period_time_in = "PM";
		}

		/*if ($hour_time_in < 10){
			$hour_time_in = "0" . $hour_time_in;
		}*/


		$min_time_in = $time_in[1];

		/*if ($min_time_in < 10){
			$min_time_in = "0" . $min_time_in;
		}*/


		//$sec_time_in = $time_in[2];

		// for time out
		$hour_time_out = $time_out[0];

		$period_time_out = "AM";
		// ibig sabihin 13 and above
		if ($hour_time_out > 12){
			$hour_time_out = $hour_time_out - 12;
			$period_time_out = "PM";
		}

		if ($hour_time_out < 10 && $hour_time_out != 0){
			$hour_time_out = "0" . $hour_time_out;
		}

		$min_time_out = $time_out[1];

		/*if ($min_time_out < 10){
			$min_time_out = "0" . $min_time_out;
		}*/

		




		//$sec_time_out = $time_out[2];

		$date_class = new date;

?>
	<form class="form-horizontal" action="" method="post" id="update_attendance_form">

			<!--<font><b>Note:</b> Used Military Time</font>-->
	
			<div class="form-group">
				<div class="col-sm-12">				
					<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Date: <?php echo $date_class->dateFormat($row->date); ?></label>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-12">
					<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Time In:&nbsp;<span class="red-asterisk">*</span></label>
				</div>
				<div class="col-sm-3" style="margin-left:40px;margin-right:-20px;">
					<input type="text" id="number_only" name="hour_time_in"  value="<?php echo $hour_time_in; ?>" class="form-control" placeholder="H" required="required">
				</div>
				<div class="col-sm-1" style="margin-top:10px;">
					:
				</div>
				<div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
					<input type="text" id="number_only" name="min_time_in" value="<?php echo $min_time_in; ?>" class="form-control" placeholder="M" required="required">
				</div>
				<!--<div class="col-sm-1" style="margin-top:10px;">
						:
				</div> -->
				<div class="col-sm-4" style="">
					<select class="form-control" name="period_time_in" required="required">
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
					<input type="text" id="number_only" name="hour_time_out" value="<?php echo $hour_time_out; ?>" class="form-control" placeholder="H" required="required">
				</div>
				<div class="col-sm-1" style="margin-top:10px;">
					:
				</div>
				<div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
					<input type="text" id="number_only" name="min_time_out" value="<?php echo $min_time_out; ?>" class="form-control" placeholder="M" required="required">
				</div>
				<!--<div class="col-sm-1" style="margin-top:10px;">
									:
				</div> -->
				<div class="col-sm-4" style="">
					<select class="form-control" name="period_time_out" required="required">
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
					<textarea class="form-control" name="remarks" placeholder="Input Remarks" required="required"></textarea>
				</div>
			</div>

			<div class="form-group">
				<div style="text-align:center;">
					<input type="button" id="request_update_btn" value="Request Update" class="btn btn-success"/>
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
	 // for number only
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


	       // for handling security in number only
	    $("input[id='number_only']").on('input', function(){
	       if ($(this).attr("maxlength") != 2){
	            if ($(this).val().length > 2){
	                $(this).val($(this).val().slice(0,-1));
	            }
	           $(this).attr("maxlength","2");
	       }

	   });


	     // for handling security in time in hours
	    $("input[name='hour_time_in']").on('input', function(){
	      if ($(this).val() >= 13){
	      		 $(this).val($(this).val().slice(0,-1));
	      	}

	   });

	      // for handling security in time in hours
	    $("input[name='min_time_in']").on('input', function(){
	      	if ($(this).val() >= 60){
	      		 $(this).val($(this).val().slice(0,-1));
	      	}

	   });

	      // for handling security in time in hours
	    $("input[name='sec_time_in']").on('input', function(){
	      	if ($(this).val() >= 60){
	      		 $(this).val($(this).val().slice(0,-1));
	      	}

	   });


	     // for handling security in time out hours
	    $("input[name='hour_time_out']").on('input', function(){
	      if ($(this).val() >= 13){
	      		 $(this).val($(this).val().slice(0,-1));
	      	}

	   });

	      // for handling security in time out hours
	    $("input[name='min_time_out']").on('input', function(){
	      	if ($(this).val() >= 60){
	      		 $(this).val($(this).val().slice(0,-1));
	      	}

	   });

	      // for handling security in time out hours
	    $("input[name='sec_time_out']").on('input', function(){
	      	if ($(this).val() >= 60){
	      		 $(this).val($(this).val().slice(0,-1));
	      	}

	   });


	    // for update request of time in time out
		$("input[id='request_update_btn']").on("click", function () {

			if ($("input[name='hour_time_in']").val() != "" && $("input[name='min_time_in']").val() != "" && $("input[name='sec_time_in']").val() != "" && $("input[name='hour_time_out']").val() != "" && $("input[name='min_time_out']").val() != "" && $("input[name='sec_time_out']").val() != "" && $("textarea[name='remarks']").val() != ""){
				 
			
				//$(this).attr("type","button"); // for not loading and submitting the form purpose
				var time_in = $("input[name='hour_time_in']").val()  + ":"+ $("input[name='min_time_in']").val() + ":"+ $("input[name='sec_time_in']").val();
				var time_out = $("input[name='hour_time_out']").val()  + ":"+ $("input[name='min_time_out']").val() + ":"+ $("input[name='sec_time_out']").val();				
				var datastring = "time_in="+time_in + "&time_out=" + time_out;
				$("#update_message").html("<center><div class='loader'></div>Loading Information</center>");
				 $.ajax({
		            type: "POST",
		            url: "ajax/update_attendance_script.php",
		            data: datastring,
		            cache: false,
		           // datatype: "php",
		            success: function (data) {
		            	
		            	if (data == "same info") {
		            		$("#update_message").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Request didn't submit, No changes was made in time in or time out.</center>");
		            	}

		            	// if success
		            	else {
		            		 $("#update_attendance_form").attr("action","php script/update_attendance_request.php");
		            		 $("#update_attendance_form").submit();
		            	}

		            } 
	         	}); 

				
			}

			else {
				$("#update_message").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please fill up requied fields.</center>");
			}
		});

	 });


	</script>

<?php
	} // end of else

}
else {
	header("Location:../Mainform.php");
}





?>