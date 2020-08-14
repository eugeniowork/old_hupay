<?php
session_start();
include "../class/connect.php";
include "../class/leave_class.php";
include "../class/emp_information.php";
include "../class/date.php";

if (isset($_POST["leave_id"])){
	$emp_id = $_SESSION["id"];
	$leave_id = $_POST["leave_id"];

	$leave_class = new Leave;
	$date_class = new date;
	$user_class = new EmployeeInformation;

	if ($leave_class->checkExistLeaveId($leave_id) == 0){
		echo "Error";
	}

	else if ($leave_class->checkOwnLeave($leave_id,$emp_id) == 0) {
		echo "Error";
	}

	else {
		$row = $leave_class->getInfoByLeaveId($leave_id);
		$leave_type = $row->LeaveType;
		$file_leave_type = $row->FileLeaveType;
		$remarks = $row->Remarks;
		$lt_id = $row->lt_id;

		$row_emp = $user_class->getEmpInfoByRow($emp_id);

		

		$date_file = date_format(date_create($row->DateCreated),"m/d/Y");

		//echo $leave_type;
		if ($file_leave_type == "Leave with pay"){
			$date_from = $date_class->dateDefault($row->dateFrom);
			$date_to = $date_class->dateDefault($row->dateTo);
?>
			<form class="form-horizontal" action="" method="post" id="update_fileLeaveForm">

				<div class="form-group">
					<div class="col-sm-12">
						<b>File Leave Type:</b> Leave With Pay
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<b>Date File:</b> <?php echo $date_file; ?>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">				
						<label class="control-label"><span class="glyphicon glyphicon-file" style="color:#2E86C1;"></span> Type of Leave:&nbsp;<span class="red-asterisk">*</span></label>
						<select class="form-control" name="update_leaveType" required="required">
							<option value="">Please select</option>
							<?php

								$leave_class = new Leave;

								$leave_class->getLeaveTypeToDropdown($lt_id,"Edit");
							?>	
						</select>
					</div>
				</div>



				<div class="form-group">
					<div class="col-sm-12">				
						<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Date From:&nbsp;<span class="red-asterisk">*</span></label>
						<input type="text" value="<?php echo $date_from; ?>" name="update_dateFrom_Leave" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only"  value="" class="form-control" placeholder="Input Date" required="required">
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">				
						<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Date To:&nbsp;<span class="red-asterisk">*</span></label>
						<input type="text" value="<?php echo $date_to; ?>" name="update_dateTo_Leave" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" value="" class="form-control" placeholder="Input Date" required="required">
					</div>
				</div>


				<div class="form-group">
					<div class="col-sm-12">
						<label class="control-label"><span class="glyphicon glyphicon-map-marker" style="color:#2E86C1;"></span> Remarks:&nbsp;<span class="red-asterisk">*</span></label>
						<textarea class="form-control" name="update_remarks_Leave" placeholder="Input Remarks" required="required"><?php echo $remarks; ?></textarea>
					</div>
				</div>

				<div class="form-group">
					<div style="text-align:center;">
						<input type="submit" id="update_file_leave" value="File Leave" class="btn btn-success"/>
					</div>
				</div>


			</form>


			<script>
				$(document).ready(function(){
					//alert("HELLO WORLD!");
						$("input[name='update_dateFrom_Leave']").dcalendarpicker(); // dateTo_Leave
						$("input[name='update_dateTo_Leave']").dcalendarpicker();
				});


				 $("input[id='date_only']").keydown(function (e) {
			      //  return false;
			        if(e.keyCode != 116) {
			            return false;
			        }
			      });

			        // onpaste
			     $("input[id='date_only").on("paste", function(){
			          return false;
			     });

			     $("input[id='update_file_leave']").on("click",function(){
			     	//alert("HELLO WORLD!");

			     	$("#update_fileLeaveForm" ).submit(function(event) {          
		              event.preventDefault();
		             
		            });

			     	$("select[name='update_leaveType']").attr("required","required");
			     	$("input[name='update_dateFrom_Leave']").attr("required","required");
			     	$("input[name='update_dateTo_Leave']").attr("required","required");
			     	$("textarea[name='update_remarks_Leave']").attr("required","required");

			     	var leave_type = $("select[name='update_leaveType']").val();
			     	var date_from = $("input[name='update_dateFrom_Leave']").val();
			     	var date_to = $("input[name='update_dateTo_Leave']").val();
			     	var remarks = $("textarea[name='update_remarks_Leave']").val();

			     	if (leave_type != "" && date_from != "" && date_to != "" && remarks != ""){

			     		$("#update_fileLeaveForm").append("<input type='hidden' value='<?php echo $leave_id; ?>' name='leave_id' />");
			     		$("#update_fileLeaveForm").attr("action","php script/update_file_leave_with_pay.php");
			     		$("#update_fileLeaveForm" ).unbind().submit();     
			     		//alert("HELLO WORLD!");
			     	}

			     });


			</script>

<?php
		} // end of if

		else if ($file_leave_type == "Leave without pay"){
			$date_from = $date_class->dateDefault($row->dateFrom);
			$date_to = $date_class->dateDefault($row->dateTo);
?>
			<form class="form-horizontal" action="" method="post" id="update_fileFormalLeave_Form">

				<div class="form-group">
					<div class="col-sm-12">
						<b>File Leave Type:</b> Leave Without Pay
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<b>Date File:</b> <?php echo $date_file; ?>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">				
						<label class="control-label"><span class="glyphicon glyphicon-file" style="color:#2E86C1;"></span> Type of Leave:&nbsp;<span class="red-asterisk">*</span></label>
						<select class="form-control" name="update_formal_leaveType" required="required">
							<option value="">Please select</option>
							<?php

								$leave_class = new Leave;

								$leave_class->getLeaveTypeToDropdown($lt_id,"Edit");
							?>	
						</select>
					</div>
				</div>


		
				<div class="form-group">
					<div class="col-sm-12">				
						<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Date From:&nbsp;<span class="red-asterisk">*</span></label>
						<input type="text" name="update_formal_dateFrom_Leave" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only"  value="<?php echo $date_from; ?>" class="form-control" placeholder="Input Date" required="required">
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">				
						<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Date To:&nbsp;<span class="red-asterisk">*</span></label>
						<input type="text" name="update_formal_dateTo_Leave" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" value="<?php echo $date_to; ?>" class="form-control" placeholder="Input Date" required="required">
					</div>
				</div>


				<div class="form-group">
					<div class="col-sm-12">
						<label class="control-label"><span class="glyphicon glyphicon-map-marker" style="color:#2E86C1;"></span> Remarks:&nbsp;<span class="red-asterisk">*</span></label>
						<textarea class="form-control" name="update_formal_remarks_Leave" placeholder="Input Remarks" required="required"><?php echo $remarks; ?></textarea>
					</div>
				</div>

				<div class="form-group">
					<div style="text-align:center;">
						<input type="submit" id="update_file_leave" value="File Leave" class="btn btn-success"/>
					</div>
				</div>

		
			</form>


			<script>
				$(document).ready(function(){
					//alert("HELLO WORLD!");
						$("input[name='update_formal_dateFrom_Leave']").dcalendarpicker(); // dateTo_Leave
						$("input[name='update_formal_dateTo_Leave']").dcalendarpicker();
				});


				 $("input[id='date_only']").keydown(function (e) {
			      //  return false;
			        if(e.keyCode != 116) {
			            return false;
			        }
			      });

			        // onpaste
			     $("input[id='date_only").on("paste", function(){
			          return false;
			     });

			     $("input[id='update_file_leave']").on("click",function(){
			     	//alert("HELLO WORLD!");

			     	$("#update_fileFormalLeave_Form" ).submit(function(event) {          
		              event.preventDefault();
		             
		            });

			     	$("select[name='update_formal_leaveType']").attr("required","required");
			     	$("input[name='update_formal_dateFrom_Leave']").attr("required","required");
			     	$("input[name='update_formal_dateTo_Leave']").attr("required","required");
			     	$("textarea[name='update_formal_remarks_Leave']").attr("required","required");

			     	var leave_type = $("select[name='update_formal_leaveType']").val();
			     	var date_from = $("input[name='update_formal_dateFrom_Leave']").val();
			     	var date_to = $("input[name='update_formal_dateTo_Leave']").val();
			     	var remarks = $("textarea[name='update_formal_remarks_Leave']").val();

			     	if (leave_type != "" && date_from != "" && date_to != "" && remarks != ""){

			     		$("#update_fileFormalLeave_Form").append("<input type='hidden' value='<?php echo $leave_id; ?>' name='leave_id' />");
			     		$("#update_fileFormalLeave_Form").attr("action","php script/update_file_formal_leave.php");
			     		$("#update_fileFormalLeave_Form" ).unbind().submit();     
			     		//alert("HELLO WORLD!");
			     	}

			     });


			</script>

<?php
		} // end of else if

		else if ($file_leave_type == "Morning Halfday Leave with pay" || $file_leave_type == "Afternoon Halfday Leave with pay"){

			$date_from = $date_class->dateDefault($row->dateFrom);
			$date_to = $date_class->dateDefault($row->dateTo);
?>
			<form class="form-horizontal" action="" method="post" id="update_fileHalfdaylLeave_Form">

				<div class="form-group">
					<div class="col-sm-12">
						<b>File Leave Type:</b> Halfday with Pay
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<b>Date File:</b> <?php echo $date_file; ?>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">				
						<label class="control-label"><span class="glyphicon glyphicon-file" style="color:#2E86C1;"></span> Type of Leave:&nbsp;<span class="red-asterisk">*</span></label>
						<select class="form-control" name="update_halfday_leave_type_leave" required="required">
							<option value=""></option>
							<option value="Vacation Leave" <?php if ($leave_type == "Vacation Leave") { echo "selected='selected'"; } ?> >Vacation Leave</option>
							<option value="Sick Leave" <?php if ($leave_type == "Sick Leave") { echo "selected='selected'"; } ?> >Sick Leave</option>
							<option value="Emergency Leave" <?php if ($leave_type == "Emergency Leave") { echo "selected='selected'"; } ?>>Emergency Leave</option>
							<option value="Others" <?php if ($leave_type == "Others") { echo "selected='selected'"; } ?>>Others</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">				
						<label class="control-label"><span class="glyphicon glyphicon-file" style="color:#2E86C1;"></span> Period:&nbsp;<span class="red-asterisk">*</span></label>
						<select class="form-control" name="update_halfday_leave_period" required="required">
							<option value=""></option>
							<option value="Morning" <?php if ($file_leave_type == "Morning Halfday Leave with pay") { echo "selected='selected'";} ?> >Morning</option>
							<option value="Afternoon" <?php if ($file_leave_type == "Afternoon Halfday Leave with pay") { echo "selected='selected'";} ?> >Afternoon</option>
						</select>
					</div>
				</div>

		
				<div class="form-group">
					<div class="col-sm-12">				
						<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Date:&nbsp;<span class="red-asterisk">*</span></label>
						<input type="text"  name="update_halfday_leave_date" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only"  value="<?php echo $date_from; ?>" class="form-control" placeholder="Input Date" required="required">
					</div>
				</div>


				<div class="form-group">
					<div class="col-sm-12">
						<label class="control-label"><span class="glyphicon glyphicon-map-marker" style="color:#2E86C1;"></span> Remarks:&nbsp;<span class="red-asterisk">*</span></label>
						<textarea class="form-control" name="update_halfday_remarks_Leave" placeholder="Input Remarks" required="required"><?php echo $remarks; ?></textarea>
					</div>
				</div>

				

				<div class="form-group">
					<div style="text-align:center;">
						<input type="submit" id="update_file_leave" value="File Leave" class="btn btn-success"/>
					</div>
				</div>

		
			</form>

			<script>
				$(document).ready(function(){
					//alert("HELLO WORLD!");
						$("input[name='update_halfday_leave_date']").dcalendarpicker(); // dateTo_Leave
						//$("input[name='update_formal_dateTo_Leave']").dcalendarpicker();
				});


				 $("input[id='date_only']").keydown(function (e) {
			      //  return false;
			        if(e.keyCode != 116) {
			            return false;
			        }
			      });

			        // onpaste
			     $("input[id='date_only").on("paste", function(){
			          return false;
			     });

			     $("input[id='update_file_leave']").on("click",function(){
			     	//alert("HELLO WORLD!");

			     	$("#update_fileHalfdaylLeave_Form" ).submit(function(event) {          
		              event.preventDefault();
		             
		            });

			     	$("select[name='update_halfday_leave_type_leave']").attr("required","required");
			     	$("select[name='update_halfday_leave_period']").attr("required","required");
			     	$("input[name='update_halfday_leave_date']").attr("required","required");
			     	$("textarea[name='update_halfday_remarks_Leave']").attr("required","required");

			     	var leave_type = $("select[name='update_halfday_leave_type_leave']").val();
			     	var period = $("select[name='update_halfday_leave_period']").val();
			     	var date = $("input[name='update_halfday_leave_date']").val();
			     	var remarks = $("textarea[name='update_halfday_remarks_Leave']").val();

			     	if (leave_type != "" && period != "" && date != "" && remarks != ""){

			     		$("#update_fileHalfdaylLeave_Form").append("<input type='hidden' value='<?php echo $leave_id; ?>' name='leave_id' />");
			     		$("#update_fileHalfdaylLeave_Form").attr("action","php script/update_file_halfday_leave.php");
			     		$("#update_fileHalfdaylLeave_Form" ).unbind().submit();     
			     		//alert("HELLO WORLD!");
			     	}

			     });


			</script>
<?php
		} // end of else if
	} // end of else

} // end of if
else {
	header("Location:../MainForm.php");
}

?>