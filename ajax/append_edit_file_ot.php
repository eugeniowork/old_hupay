<?php
session_start();
include "../class/connect.php";
include "../class/attendance_overtime.php";
include "../class/date.php";


if (isset($_POST["attendance_ot_id"])){
	$emp_id = $_SESSION["id"];
	$attendance_ot_id = $_POST["attendance_ot_id"];

	$attendance_ot_class = new Attendance_Overtime;
	$date_class = new date;

	if ($attendance_ot_class->checkExistAttendanceOtId($attendance_ot_id) == 0){
		echo "Error";
	}
	// check kung kanya tlaga
	else if ($attendance_ot_class->checkAttendanceOtIdOwned($attendance_ot_id,$emp_id) == 0){
		echo "Error";
	}
	else {
		$row = $attendance_ot_class->getInfoByAttendanceOtId($attendance_ot_id);
		$date = $date_class->dateDefault($row->date);

		$time_in_values = explode(":", $row->time_from);
		$hour_time_in = $time_in_values[0];
		$final_hour_time_in = $hour_time_in;
		//echo $final_hour_time_in;
		$min_time_in = $time_in_values[1];
		if (strlen($min_time_in) == 1){
			$min_time_in ="0".$min_time_in;
		}

		$period_time_in = "AM";
		if ($hour_time_in > 11){
			$period_time_in = "PM";
			$final_hour_time_in =  $final_hour_time_in - 12;
			//echo $final_hour_time_in;
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

		$type_ot = $row->type_ot;
		$disabled="";
		$required="required='required'";
		$disabled = "";
		if ($type_ot == "Regular"){
			$final_hour_time_in = "";
			$min_time_in = "";
			$period_time_in = "";
			$required = "";
			$disabled = "disabled='disabled'";
		}
		//echo $type_ot;
?>
	<form class="form-horizontal" action="php script/update_file_ot_request.php" method="post" id="update_file_ot_info_form">

		<!--<font><b>Note:</b> Used Military Time</font> -->

		<div class="form-group">
			<div class="col-sm-12">				
				<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Date:&nbsp;<span class="red-asterisk">*</span></label>
				<input type="text" value="<?php echo $date; ?>" name="attendance_date_ot" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="readonly_txt"  class="form-control" placeholder="Input Date" required="required">
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> From:&nbsp;<span class="red-asterisk">*</span></label>
			</div>
			<div class="col-sm-3" style="margin-left:40px;margin-right:-20px;">
				<input type="text" id="number_only" name="update_hour_time_in" <?php echo $required; ?> <?php echo $disabled; ?>  value="<?php echo $final_hour_time_in; ?>" class="form-control" placeholder="H" required="required">
			</div>
			<div class="col-sm-1" style="margin-top:10px;">
				:
			</div>
			<div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
				<input type="text" id="number_only" name="update_min_time_in" <?php echo $required; ?> <?php echo $disabled; ?> value="<?php echo $min_time_in; ?>" class="form-control" placeholder="M" required="required">
			</div>
			<!--<div class="col-sm-1" style="margin-top:10px;">
				:
			</div> -->
			<div class="col-sm-4" style="">
				<select class="form-control" name="update_period_time_in" <?php echo $required; ?> <?php echo $disabled; ?> required="required">
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
				<input type="text" id="number_only" name="update_hour_time_out" value="<?php echo $final_hour_time_out; ?>" class="form-control" placeholder="H" required="required">
			</div>
			<div class="col-sm-1" style="margin-top:10px;">
				:
			</div>
			<div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
				<input type="text"  id="number_only" name="update_min_time_out" value="<?php echo $min_time_out; ?>" class="form-control" placeholder="M" required="required">
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


		<!--
		<div class="form-group">
			<div class="col-sm-12">				
				<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Choose Type of Overtime &nbsp;<span class="red-asterisk">*</span></label>
				<select name="type_ot" class="form-control">
					
				</select>		
			</div>
		</div>
		-->

		<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label"><span class="glyphicon glyphicon-map-marker" style="color:#2E86C1;"></span> Remarks:&nbsp;<span class="red-asterisk">*</span></label>
				<textarea class="form-control" name="update_remarks" placeholder="Input Remarks" required="required"><?php echo $row->remarks; ?></textarea>
			</div>
		</div>

		<div class="form-group">
			<div style="text-align:center;">
				<input type="submit" id="request_update_file_ot_btn" value="Request Update" class="btn btn-success"/>
			</div>
		</div>


	</form>
	<script>
		$(document).ready(function(){
			   // for returning false
	     	$(document).on('keypress', 'input[id="readonly_txt"]', function (event) {
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


			    $("input[id='request_update_file_ot_btn']").on("click",function(){
	          		//$("input[name='update_attendance_date']").attr("required","required");

	          		<?php if ($type_ot != "Regular"){ 
          			?>
			        $("input[name='update_hour_time_in']").attr("required","required");
			        $("input[name='update_min_time_in']").attr("required","required");
			        $("select[name='update_period_time_in']").attr("required","required");
			        

			        $("input[name='update_hour_time_out']").attr("required","required");
			        $("input[name='update_min_time_out']").attr("required","required");
			        $("select[name='update_period_time_out']").attr("required","required");
			        $("textarea[name='update_remarks']").attr("required","required");

			        if ($("input[name='update_hour_time_in']").val() != "" && $("input[name='update_min_time_in']").val() != "" && $("input[name='update_hour_time_out']").val() != "" && $("input[name='update_min_time_out']").val() != "" && $("select[name='update_period_time_out']").val() != "" && $("textarea[name='update_remarks']").val() != ""){
		        		//alert("READY FOR APPENDING!");
		        		$("#update_file_ot_info_form").append("<input type='hidden' value='<?php echo $attendance_ot_id; ?>' name='attendance_ot_id'/>");
		        		$("#update_file_ot_info_form").append("<input type='hidden' value='<?php echo $type_ot; ?>' name='type_ot'/>");
			        }
			        <?php }// end of if 
			        else {
		        	?>
		        	/*
		        	$("input[name='update_hour_time_in']").attr("required","required");
			        $("input[name='update_min_time_in']").attr("required","required");
			        $("select[name='update_period_time_in']").attr("required","required");
			        */
			        

			        $("input[name='update_hour_time_out']").attr("required","required");
			        $("input[name='update_min_time_out']").attr("required","required");
			        $("select[name='update_period_time_out']").attr("required","required");
			        $("textarea[name='update_remarks']").attr("required","required");

			        if ( $("input[name='update_hour_time_out']").val() != "" && $("input[name='update_min_time_out']").val() != "" && $("select[name='update_period_time_out']").val() != "" && $("textarea[name='update_remarks']").val() != ""){
		        		//alert("READY FOR APPENDING!");
		        		$("#update_file_ot_info_form").append("<input type='hidden' value='<?php echo $attendance_ot_id; ?>' name='attendance_ot_id'/>");
			        	$("#update_file_ot_info_form").append("<input type='hidden' value='<?php echo $type_ot; ?>' name='type_ot'/>");
			        }

		        	<?php
			        } // end of else  ?>
	          });

		});
	</script>
<?php
	} // end of else

} // end of if
else {
	header("Location:../dashboard.php");
}

?>