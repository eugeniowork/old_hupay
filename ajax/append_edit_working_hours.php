<?php
include "../class/connect.php";
include "../class/working_hours_class.php";

$working_hours_class = new WorkingHours;

if (isset($_POST["working_hours_id"])){
	$wokingHoursId = $_POST["working_hours_id"];

	if ($working_hours_class->checkExistWorkingHoursId($wokingHoursId) == 0){
		echo "Error";
	}

	// if success
	else {

		$row = $working_hours_class->getWorkingHoursInfoById($wokingHoursId);

		$timeFrom = explode(":",$row->timeFrom);
		$timeTo = explode(":",$row->timeTo);

?>
		<div class="container-fluid">
			<form class="form-horizontal" action="" id="form_updateWorkingHours" method="post">	<!-- ../php script/position_department_script.php -->	
				<font><b>Note:</b> Used Military Time</font>
				<div class="form-group">
					<div class="col-sm-12">
						<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Time In:&nbsp;<span class="red-asterisk">*</span></label>
					</div>
					<div class="col-sm-3" style="margin-left:40px;margin-right:-20px;">
						<input type="text" id="number_only" name="update_hour_time_in"  value="<?php echo $timeFrom[0]; ?>" class="form-control" placeholder="H" required="required">
					</div>
					<div class="col-sm-1" style="margin-top:10px;">
						:
					</div>
					<div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
						<input type="text" id="number_only" name="update_min_time_in" value="<?php echo $timeFrom[1]; ?>" class="form-control" placeholder="M" required="required">
					</div>
					<div class="col-sm-1" style="margin-top:10px;">
						:
					</div>
					<div class="col-sm-3" style="margin-left:-20px;">
						<input type="text" id="input_payroll" name="update_sec_time_in" value="00" class="form-control" placeholder="S" required="required">
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Time Out:&nbsp;<span class="red-asterisk">*</span></label>
					</div>
					<div class="col-sm-3" style="margin-left:40px;margin-right:-20px;">
						<input type="text" id="number_only" name="update_hour_time_out" value="<?php echo $timeTo[0]; ?>" class="form-control" placeholder="H" required="required">
					</div>
					<div class="col-sm-1" style="margin-top:10px;">
						:
					</div>
					<div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
						<input type="text" id="number_only" name="update_min_time_out" value="<?php echo $timeTo[1]; ?>" class="form-control" placeholder="M" required="required">
					</div>
					<div class="col-sm-1" style="margin-top:10px;">
						:
					</div>
					<div class="col-sm-3" style="margin-left:-20px;">
						<input type="text" id="input_payroll" name="update_sec_time_out" value="00" class="form-control" placeholder="S" required="required">
					</div>
				</div>	

				<div class="form-group">
					<div style="text-align:center;">
						<input type="submit" id="update_working_hours" value="Update Working Hours" class="btn btn-success"/>
					</div>
				</div>
			</form>
		</div>


		<script>
			$(document).ready(function(){

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


				 // for handling security in time in hours
			    $("input[name='update_hour_time_in']").on('input', function(){
			      if ($(this).val() >= 24){
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
			    $("input[name='update_sec_time_in']").on('input', function(){
			      	if ($(this).val() >= 60){
			      		 $(this).val($(this).val().slice(0,-1));
			      	}

			   });


			     // for handling security in time out hours
			    $("input[name='update_hour_time_out']").on('input', function(){
			      if ($(this).val() >= 24){
			      		 $(this).val($(this).val().slice(0,-1));
			      	}

			   });

			      // for handling security in time out hours
			    $("input[name='update_min_time_out']").on('input', function(){
			      	if ($(this).val() >= 60){
			      		 $(this).val($(this).val().slice(0,-1));
			      	}

			   });

			      // for handling security in time out hours
			    $("input[name='update_sec_time_out']").on('input', function(){
			      	if ($(this).val() >= 60){
			      		 $(this).val($(this).val().slice(0,-1));
			      	}

			   });


			   $("input[id='input_payroll']").keydown(function (e) {
			      //  return false;
			        if(e.keyCode != 116) {
			            return false;
			        }
			      });


			      // onpaste
			     $("input[id='input_payroll").on("paste", function(){
			          return false;
			     });

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



			    $("input[id='update_working_hours']").on("click",function () {
		        // for setting its required attr 
		          $("input[name='update_hour_time_in']").attr("required","required");
		          $("input[name='update_min_time_in']").attr("required","required");
		          $("input[name='update_sec_time_in']").attr("required","required");
		          $("input[name='update_hour_time_out']").attr("required","required");
		          $("input[name='update_min_time_out']").attr("required","required");
		          $("input[name='update_sec_time_out']").attr("required","required");

		          if ($("input[name='update_hour_time_in']").val != "" && $("input[name='update_min_time_in']").val() != "" && $("input[name='update_sec_time_in']").val() != ""  && $("input[name='update_hour_time_out']").val() != "" && $("input[name='update_min_time_out']").val() != "" && $("input[name='update_sec_time_out']").val() != ""){
	              	   $("input[name='update_hour_time_in']").append("<input type='text' name='working_hours_id' value='<?php echo $wokingHoursId; ?>' />");
		               $("#form_updateWorkingHours").attr("action","php script/update_working_hours.php");
		          }

		       });
			});
		</script>
<?php
	}
}

else {
	header("Location:../MainForm.php");
}

?>