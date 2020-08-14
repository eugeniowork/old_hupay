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

	$_SESSION["attendance_id_file_ot"] = $attendance_id;

	$attendance_class = new Attendance;
	$exist_attendance = $attendance_class->existAttendance($attendance_id,$bio_id);
	
	// if not exist
	if ($exist_attendance == 0){
		echo "Error";
	}

	// if success
	else {	
		$row = $attendance_class->getInfoByAttendaceId($attendance_id);

		$time_out = explode(":",$row->time_out);
	
		$date_class = new date;

?>
	<form class="form-horizontal" action="" method="post" id="file_ot_form">

		<font><b>Note:</b> Used Military Time</font>

		<div class="form-group">
			<div class="col-sm-12">				
				<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Date: <?php echo $date_class->dateFormat($row->date); ?></label>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-12">		
					<?php
						$date_create = date_create($row->date);
						$day = date_format($date_create, 'l');

						if ($day == "Saturday" || $day == "Sunday") {
							echo '<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> From: '. $row->time_in.'</label>';
						}
						else {
							echo '<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> From: 18:30</label>';
						}
					?>		
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Time Out:&nbsp;<span class="red-asterisk">*</span></label>
			</div>
			<div class="col-sm-3" style="margin-left:40px;margin-right:-20px;">
				<input type="text" id="number_only" name="hour_time_out" value="<?php echo $time_out[0]; ?>" class="form-control" placeholder="H" required="required">
			</div>
			<div class="col-sm-1" style="margin-top:10px;">
				:
			</div>
			<div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
				<input type="text" id="number_only" name="min_time_out" value="<?php echo $time_out[1]; ?>" class="form-control" placeholder="M" required="required">
			</div>
			<div class="col-sm-1" style="margin-top:10px;">
				:
			</div>
			<div class="col-sm-3" style="margin-left:-20px;">
				<input type="text" id="number_only" name="sec_time_out" value="<?php echo $time_out[2]; ?>" class="form-control" placeholder="S" required="required">
			</div>
		</div>


		<div class="form-group">
			<div class="col-sm-12">				
				<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Choose Type of Overtime &nbsp;<span class="red-asterisk">*</span></label>
				<select name="type_ot" class="form-control">
					<option value=""></option>

					<?php
						if ($day == "Saturday" || $day == "Sunday") {
							echo '<option value="Restday">Restday</option>';
							echo '<option value="Restday / Holiday">Restday / Holiday</option>';
						}
						else {
							echo '<option value="Regular">Regular</option>';
							echo '<option value="Holiday">Holiday</option>';
							//echo '<option value="Restday">Restday</option>';
						}
					?>

				</select>		
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
				<input type="button" id="file_ot_btn" value="File Overtime" class="btn btn-success"/>
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


	     // for handling security in time out hours
	    $("input[name='hour_time_out']").on('input', function(){
	      if ($(this).val() >= 25){
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
		$("input[id='file_ot_btn']").on("click", function () {


			if ($("input[name='hour_time_out']").val() != "" && $("input[name='min_time_out']").val() != "" && $("input[name='sec_time_out']").val() != "" && $("select[name='type_ot']").val() != "" && $("textarea[name='remarks']").val() != ""){
				 
				//var time_out = $("input[name='hour_time_out']").val()  + ":"+ $("input[name='min_time_out']").val() + ":"+ $("input[name='sec_time_out']").val();				
				//var datastring = "time_out=" + time_out;

				 $("#file_ot_form").attr("action","php script/add_attendance_ot.php");
        		 $("#file_ot_form").submit();			


				 /*$.ajax({
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
		            		
		            	}

		            } 
	         	}); */

				
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