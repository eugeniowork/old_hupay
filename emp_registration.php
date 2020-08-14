<?php
session_start();
if (!isset($_SESSION["id"])){
	header("Location:index.php");
}
include "class/connect.php"; // fixed class
include "class/position_class.php"; // fixed class
include "class/attendance_notifications.php"; // fixed class
include "class/date.php";
include "class/versioning_class.php"; // fixed class
include "class/payroll_notif_class.php"; // fixed class
include "class/events_notifications.php"; // fixed class
include "class/message_class.php"; // fixed class
include "class/cashbond_class.php"; // fixed class
include "class/salary_loan_class.php"; // fixed class
include "class/leave_class.php"; // fixed class
include "class/company_class.php"; // fixed class
include "class/memorandum_class.php"; // fixed class
include "class/attendance_overtime.php"; // fixed class
include "class/attendance_notif.php"; // fixed class

include "class/role.php";
include "class/working_hours_class.php";
include "class/working_days_class.php";
// for universal variables
$id = $_SESSION["id"];

// for session
$_SESSION["active_dashboard"] = null;
$_SESSION["active_sub_registration"] = "active-sub-menu";
$_SESSION["active_sub_employee_list"] = null;
$_SESSION["active_sub_user_authentication"] = null;
$_SESSION["active_sub_messaging_create"] = null;
$_SESSION["active_sub_messaging_inbox"] = null;
$_SESSION["active_atm_no"] = null;
$_SESSION["active_working_hours"] = null;
$_SESSION["active_memorandum"] = null;
$_SESSION["active_minimum_wage"] = null;
$_SESSION["active_biometrics"] = null;
$_SESSION["active_department"] = null;
$_SESSION["active_position"] = null;
$_SESSION["active_sub_sss"] = null;
$_SESSION["active_sub_bir"] = null;
$_SESSION["active_sub_pagibig"] = null;
$_SESSION["active_sub_philhealth"] = null;
$_SESSION["active_holiday"] = null;
$_SESSION["active_events"] = null;
$_SESSION["active_sub_upload_attendance"] = null;
$_SESSION["active_sub_view_attendance"] = null;
$_SESSION["active_sub_attendance_list"] = null;
$_SESSION["active_sub_sub_attendance_list"] = null;
$_SESSION["active_sub_file_overtime"] = null;
$_SESSION["active_sub_ot_list"] = null;
$_SESSION["active_sub_attendance_updates"] = null;
$_SESSION["active_sub_add_attendance"] = null;
$_SESSION["active_leaves"] = null;
$_SESSION["active_sub_pagibig_loan"] = null;
$_SESSION["active_sub_sss_loan"] = null;
$_SESSION["active_sub_salary_loan"] = null;
$_SESSION["active_sub_create_salary"] = null;
$_SESSION["active_sub_view_payroll_info"] = null;
$_SESSION["active_sub_my_payslip"] = null;
$_SESSION["active_my_payslip"] = null;
$_SESSION["active_simkimban"] = null;
$_SESSION["active_cashbond"] = null;
$_SESSION["active_year_total_deduction"] = null;
$_SESSION["active_salary_information"] = null;
$_SESSION["active_audit_trail"] = null;
$_SESSION["active_sub_loan_adjustment"] = null;
$_SESSION["active_sub_simkimban_adjustment"] = null;
$_SESSION["active_sub_payroll"] = null;
$_SESSION["active_sub_payroll_adjust"] = null;

// this area is for null of session
$_SESSION["view_emp_id"] = null; // sa view emp info
$_SESSION["update_emp_id"] = null; // sa update emp info
?>


<?php
	// for security browsing purpose
	if ($_SESSION["role"] == 4) {
		header("Location:Mainform.php");
	}

	
	include "class/emp_information.php";
	$emp_info = new EmployeeInformation;


	$position_class = new Position;
	$date_class = new date;
	$cashbond_class = new Cashbond;
	$salary_loan_class = new SalaryLoan;
	$leave_class = new Leave;
	$memorandum_class = new Memorandum;
	$attendance_ot_class = new Attendance_Overtime;
$attendance_notif_class = new AttendanceNotif;


	// for pending ot 
	$pending_file_ot_count = $attendance_ot_class->getOvertimePendingCount($_SESSION["role"],$_SESSION["id"]);
	$pending_file_attendance_request_count = $attendance_notif_class->attendanceNotifPendingCount($_SESSION["role"],$_SESSION["id"]);

	$row = $emp_info->getEmpInfoByRow($id);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Employee Registration</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <!-- for symbols -->
		<?php
			$company_class = new Company;
			$row_company = $company_class->getInfoByCompanyId($row->company_id);
			$logo_source = $row_company->logo_source;
		?>
		<link rel="shortcut icon" href="<?php echo $logo_source; ?>"> <!-- for logo -->

		<!-- css -->
		<link rel="stylesheet" href="css/lumen.min.css">
		<link rel="stylesheet" href="css/plug ins/calendar/datepicker.css">
		<!--<link rel="stylesheet" href="css/plug ins/calendar/dcalendar.picker.css"> -->
		<link rel="stylesheet" href="css/custom.css">
		<style type="text/css">
			.bs-example {
				font-family: sans-serif;
				position: relative;
				margin: 100px;
			}
			.typeahead, .tt-query, .tt-hint {
				border: 2px solid #CCCCCC;
				border-radius: 8px;
				/*font-size: 22px; /* Set input font size */
				/*height: 30px;*/
				line-height: 30px;
				outline: medium none;
				padding: 8px 12px;
				width: 145%;

				
			}
			.typeahead {
				background-color: #FFFFFF;
			}
			.typeahead:focus {
				border: 2px solid #0097CF;
			}
			.tt-query {
				box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
			}
			.tt-hint {
				color: #999999;
			}
			.tt-menu {
				background-color: #FFFFFF;
				border: 1px solid rgba(0, 0, 0, 0.2);
				border-radius: 8px;
				box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
				margin-top: 12px;
				padding: 8px 0;
				width: 300px;
			}
			.tt-suggestion {
				font-size: 15px;  /* Set suggestion dropdown font size */
				padding: 3px 12px;
			}
			.tt-suggestion:hover {
				cursor: pointer;
				background-color: #0097CF;
				color: #FFFFFF;
			}
			.tt-suggestion p {
				margin: 0;
			}
		</style>

		<!-- js -->
		<script src="js/jquery-1.12.2.min.js"></script>
		<script src="js/plug ins/typehead/typeahead.bundle.min.js"></script> 
		<script src="js/bootstrap.min.js"></script>
		<script src="js/plug ins/calendar/bootstrap-datepicker.js"></script>
		<!--<script src="js/plug ins/calendar/dcalendar.picker.js"></script> -->
		<script src="js/chevron.js"></script>
		<script src="js/string_uppercase.js"></script>
		<script src="js/numbers_only.js"></script>
		<script src="js/maxlength.js"></script>
		<script src="js/format.js"></script>
		<script src="js/notifications.js"></script>
		<script src="js/date_validation.js"></script>
		<script src="js/custom.js"></script>
		<script>
			$(document).ready(function(){
				//$("input[name='birthdate']").dcalendarpicker(); // 
				//$("input[name='dateHired']").dcalendarpicker();

				 

				$("input[name='birthdate']").datepicker();
				$("input[name='dateHired']").datepicker();

				<?php


					// for adding success
					if (isset($_SESSION["success_msg_registration"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["success_msg_registration"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["success_msg_registration"] = null;
					}


					// for adding error
					if (isset($_SESSION["error_msg_registration"])){
						echo '$(document).ready(function() {
							$("#error_modal_body").html("'.$_SESSION["error_msg_registration"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["error_msg_registration"] = null;
					}


					//<?php if (isset($_SESSION["error_msg_registration"])){ echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> ". "<span style='color:#CB4335'>".$_SESSION["error_msg_registration"] . "</span>"; $_SESSION["error_msg_registration"] = null;}
					//<?php if (isset($_SESSION["success_msg_registration"])){ echo "<span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> ". "<span style='color:#196F3D'>" .$_SESSION["success_msg_registration"] . "</span>"; $_SESSION["success_msg_registration"] = null;}


				?>

				 var emp_name_list = <?php echo $emp_info->searchEmployeeName(); ?>

			    // Constructing the suggestion engine
			    var emp_name_list = new Bloodhound({
			        datumTokenizer: Bloodhound.tokenizers.whitespace,
			        queryTokenizer: Bloodhound.tokenizers.whitespace,
			        local: emp_name_list
			    });
			    
			    // Initializing the typeahead
			    $('.typeahead').typeahead({
			        hint: true,
			        highlight: true, /* Enable substring highlighting */
			        minLength: 1 /* Specify minimum characters required for showing result */
			    },
			    {
			        name: 'emp_name',
			        source: emp_name_list
			    });



			    var has_add_pet = false;
			    var maximum_pet_count = 2;
			    var maximum_pet_counter = 1;

			    $("#add_emp_pet").on("click",function(){
			    	//alert("HELLO WORLD!");


			    	if (has_add_pet == false){

				    	var html = "";
				    	html += '<div class="form-group">';	
							html += '<div class="col-sm-4">';						
								html += '<label class="control-label"><span class="glyphicon glyphicon-piggy-bank" style="color:#2E86C1;"></span> Pet Type&nbsp;</label>';
								html += '<input type="text" name="pet_type[]" id="txt_only" value="" class="form-control" placeholder="Enter Pet Type (Dog/Cat/etc.)">';
							html += '</div>';
							html += '<div class="col-sm-4">';						
								html += '<label class="control-label"><span class="glyphicon glyphicon-piggy-bank" style="color:#2E86C1;"></span> Pet Name&nbsp;</label>';
								html += '<input type="text" name="pet_name[]" id="txt_only" value="" class="form-control" placeholder="Enter Pet Name">';
							html += '</div>';

							html += '<div class="col-sm-4">';						
								html += '<label class="control-label">&nbsp;</label>';
								html += '<button id="remove_emp_pet" type="button" class="btn btn-danger btn-sm" style="margin-top:30px;">Remove</button>';
							html += '</div>';
						html += '</div>';

						$("#append_pet_information").html(html);

						maximum_pet_counter++;

						if (maximum_pet_count == maximum_pet_counter){

							$(this).attr("disabled","disabled");
							has_add_pet = true;
						}
					}
			    });


			    $("#append_pet_information").on("click","button[id='remove_emp_pet']",function(){

			    	$(this).closest("div").parent("div").remove();
			    	maximum_pet_counter--;
			    	$("#add_emp_pet").removeAttr("disabled");
			    	has_add_pet = false;
			    });





			});
		</script>
	</head>
	<body>
		<?php

			include "layout/header.php";

		?>

		<div class="content">

			<!-- for menu directory at the top -->
			<div class="container-fluid">
				<div class="row" style="border-bottom:1px solid  #d5dbdb ">
					<ol class="breadcrumb">
						<li><a href="MainForm.php">&nbsp;&nbsp;<span class="glyphicon glyphicon-home"></span></a></li>
						<li class="" id="home_id">Employee Registration</li> 
					</ol>
				</div>
			</div>

			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-primary" style="margin-top:10px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-edit"></span> Employee Registration Form</center></div> 


							 <div class="panel-body">
							 	<fieldset>
							 		<!--<legend style="border-bottom:1px solid  #566573 ">
							 			<center><b>Employee Registration Form</b></center>
						 			</legend> -->
									<form class="form-horizontal" method="post" action="php script/registration_script.php" enctype="multipart/form-data">
										<div class="alert alert-success">																		
											<b><small><i>Fields with (<span style="color:#b03a2e;">*</span>) are required<br/>
											Contact Number Format:  Cellphone No (09123456789) , Landline No (1234567, with area code 123456789)</br>
											Gov't No Format: SSS No (Compose of 10 digits) , Pag-ibig No (Compose of 12 digits), Tin No (Compose of 9 digits), Philhealth No (Compose of 12 digits)</i></small></b>
										</div>
																				
										<fieldset style="border:1px solid #BDBDBD;background-color: #ebf5fb ">
											<h4 style="background-color:#1d8348;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-check"></span> BASIC INFORMATION</h4>
											<div class="col-sm-10 col-sm-offset-1" style="">

												<div class="form-group">	
													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Last Name&nbsp;<span class="red-asterisk">*</span></label>
														<input type="text" name="lastName" id="txt_only" value="<?php if (isset($_SESSION["emp_reg_lasname"])) { echo $_SESSION["emp_reg_lasname"]; $_SESSION["emp_reg_lasname"] = null; } ?>" class="form-control" placeholder="Enter Last Name" required="required">
													</div>

													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> First Name&nbsp;<span class="red-asterisk">*</span></label>
														<input type="text" name="firstName" id="txt_only" value="<?php if (isset($_SESSION["emp_reg_firstname"])) { echo $_SESSION["emp_reg_firstname"]; $_SESSION["emp_reg_firstname"] = null; } ?>" class="form-control" placeholder="Enter First Name" required="required">
													</div>
													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Middle Name</label>
														<input type="text" name="middleName" id="txt_only" value="<?php if (isset($_SESSION["emp_reg_middlename"])) { echo $_SESSION["emp_reg_middlename"]; $_SESSION["emp_reg_middlename"] = null; } ?>" class="form-control" placeholder="Enter Middle Name">
													</div>
												</div>

												<div class="form-group">
													<div class="col-sm-8">
														<label class="control-label"><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> Address&nbsp;<span class="red-asterisk">*</span></label>
														<textarea name="address" name="address" class="form-control" placeholder="Enter Address" required="required"><?php if (isset($_SESSION["emp_reg_address"])) { echo $_SESSION["emp_reg_address"]; $_SESSION["emp_reg_address"] = null; } ?></textarea>
													</div>

													<div class="col-sm-4">
														<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Civil Status&nbsp;<span class="red-asterisk">*</span></label>
														<select class="form-control" name="civil_status" required="required">
															<option value="">Select Civil Status</option>
															<option value="Single" <?php if (isset($_SESSION["emp_reg_civilStatus"])){ if ($_SESSION["emp_reg_civilStatus"] == "Single") { echo "selected=selected"; $_SESSION["emp_reg_civilStatus"] = null;}} ?> >Single</option>
															<option value="Married" <?php if (isset($_SESSION["emp_reg_civilStatus"])){ if ($_SESSION["emp_reg_civilStatus"] == "Married") { echo "selected=selected"; $_SESSION["emp_reg_civilStatus"] = null;}} ?>>Married</option>
														</select>
													</div> 

													
												</div>

												<div class="form-group">

													
													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Birthdate&nbsp;<span class="red-asterisk">*</span></label>
														<div>
															<input type="text" name="birthdate" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" value="<?php if (isset($_SESSION["emp_reg_birthdate"])) { echo $_SESSION["emp_reg_birthdate"]; $_SESSION["emp_reg_birthdate"] = null; } ?>" class="form-control" placeholder="Enter Birthdate" required="required">
														</div>
													</div>

													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Gender&nbsp;<span class="red-asterisk">*</span></label>
														<select class="form-control" name="gender" required="required">
															<option value="">Select Gender</option>
															<option value="Male" <?php if (isset($_SESSION["emp_reg_gender"])){ if ($_SESSION["emp_reg_gender"] == "Male") { echo "selected=selected"; $_SESSION["emp_reg_gender"] = null;}} ?> >Male</option>
															<option value="Female" <?php if (isset($_SESSION["emp_reg_gender"])){ if ($_SESSION["emp_reg_gender"] == "Female") { echo "selected=selected"; $_SESSION["emp_reg_gender"] = null;}} ?>>Female</option>
														</select>
													</div>

													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-phone-alt" style="color:#2E86C1;"></span> Contact No</label>
														<input type="text" name="contactNo" id="number_only" value="<?php if (isset($_SESSION["emp_reg_contactNo"])) { echo $_SESSION["emp_reg_contactNo"]; $_SESSION["emp_reg_contactNo"] = null; } ?>" maxlength="11" class="form-control" placeholder="Enter Contact No">
													</div>
													
												</div>

												<div class="form-group">
																																													
													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-envelope" style="color:#2E86C1;"></span> Email Address</label>
														<input type="email" id="email_address_txt" name="email_add" value="<?php if (isset($_SESSION["emp_reg_emailAdd"])) { echo $_SESSION["emp_reg_emailAdd"]; $_SESSION["emp_reg_emailAdd"] = null; } ?>" class="form-control" placeholder="Enter Email Address">
													</div>

												</div>
												
											</div>

										</fieldset>

										<fieldset style="border:1px solid #BDBDBD;background-color: #ebf5fb ">
											<h4 style="background-color:#e65c00;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-check"></span> PET INFORMATION</h4>
											<div class="col-sm-10 col-sm-offset-1" style="">
												<div class="form-group">
													<div class="col-md-12">
														<button class="btn btn-primary btn-xs pull-right" id="add_emp_pet" type="button" style="margin-top:30px"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add
														</button>
													</div>
												</div>
												<?php
														//if (!isset($_SESSION["emp_pet_info_count"])){
													?>
												<div class="form-group">	
													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-piggy-bank" style="color:#2E86C1;"></span> Pet Type&nbsp;</label>
														<input type="text" name="pet_type[]" id="txt_only" value="<?php if (isset($_SESSION["emp_pet_type0"])) { echo $_SESSION["emp_pet_type0"]; $_SESSION["emp_pet_type0"] = null; } ?>" class="form-control" placeholder="Enter Pet Type (Dog/Cat/etc.)">
													</div>
													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-piggy-bank" style="color:#2E86C1;"></span> Pet Name&nbsp;</label>
														<input type="text" name="pet_name[]" id="txt_only" value="<?php if (isset($_SESSION["emp_pet_name0"])) { echo $_SESSION["emp_pet_name0"]; $_SESSION["emp_pet_name0"] = null; } ?>" class="form-control" placeholder="Enter Pet Name">
													</div>
												</div>

												<?php
													//}
												?>
												<div id="append_pet_information">
														<?php
															if (isset($_SESSION["emp_pet_info_count"])){

																$count = $_SESSION["emp_pet_info_count"];
																$counter = 1;

																do {
														?>
															<div class="form-group">	
																<div class="col-sm-4">						
																	<label class="control-label"><span class="glyphicon glyphicon-piggy-bank" style="color:#2E86C1;"></span> Pet Type&nbsp;</label>
																	<input type="text" name="pet_type[]" id="txt_only" value="<?php if (isset($_SESSION["emp_pet_type".$counter])) { echo $_SESSION["emp_pet_type".$counter]; $_SESSION["emp_pet_type".$counter] = null; } ?>" class="form-control" placeholder="Enter Pet Type (Dog/Cat/etc.)">
																</div>
																<div class="col-sm-4">						
																	<label class="control-label"><span class="glyphicon glyphicon-piggy-bank" style="color:#2E86C1;"></span> Pet Name&nbsp;</label>
																	<input type="text" name="pet_name[]" id="txt_only" value="<?php if (isset($_SESSION["emp_pet_name".$counter])) { echo $_SESSION["emp_pet_name".$counter]; $_SESSION["emp_pet_name".$counter] = null; } ?>" class="form-control" placeholder="Enter Pet Name">
																</div>
															</div>
														<?php
																	$_SESSION["emp_pet_type".$counter] = null;
																	$_SESSION["emp_pet_name".$counter] = null;

																	$counter++;
																}while($counter < 2);

																$_SESSION["emp_pet_info_count"] = null;
															}
														?>
													</div>
											</div>
										</fieldset>


										<fieldset style="border:1px solid #BDBDBD;background-color: #ebf5fb ">
											<h4 style="background-color:#21618c ;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-check"></span> COMPANY INFORMATION</h4>
											<div class="col-sm-10 col-sm-offset-1">
												<div class="form-group">
													<div class="col-sm-4">						
															<label class="control-label"><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Department&nbsp;<span class="red-asterisk">*</span></label>
															<select class="form-control" name="department" required="required">
																<option value="">Select Department</option>
																<?php
																	include "class/department.php";
																	$department_class = new Department;
																	$department_class->getDepartmentInfo();															
																?>
															</select>
														</div>

														<div class="col-sm-4">						
															<label class="control-label"><span class="glyphicon glyphicon-tasks" style="color:#2E86C1;"></span> Position&nbsp;<span class="red-asterisk">*</span></label>
															<select class="form-control" name="position" required="required">
																<option value=""></option>
																<?php
																	if (isset($_SESSION["emp_reg_department"])){
																		
																		$position_class = new Position;
																		$position_class->getAllPosition($_SESSION["emp_reg_department"]);
																		$_SESSION["emp_reg_department"] = null;
																	}
																?>
															</select>
														</div>

														<div class="col-sm-4">
															<label class="control-label"><span class="glyphicon glyphicon-ruble" style="color:#2E86C1;"></span> Salary&nbsp;<span class="red-asterisk">*</span></label>
															<input type="text" name="salary" class="form-control" required="required" value="<?php if (isset($_SESSION["emp_reg_salary"])) { echo $_SESSION["emp_reg_salary"]; $_SESSION["emp_reg_salary"] = null; } ?>" placeholder="Enter Salary" id="number_only"/>
														</div>
												</div>	

												<div class="form-group">
													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Date Hired&nbsp;<span class="red-asterisk">*</span></label>
														<div>
															<input type="text" name="dateHired" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" value="<?php if (isset($_SESSION["emp_reg_dateHired"])) { echo $_SESSION["emp_reg_dateHired"]; $_SESSION["emp_reg_dateHired"] = null; } ?>" class="form-control" placeholder="Enter Date Hired" required="required">
														</div>
													</div>

													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Working Hours&nbsp;<span class="red-asterisk">*</span></label>
														<select class="form-control" name="workingHours" required="required">
															<option value="">Select Working Hours</option>
															<?php
																$working_hours_class = new WorkingHours;
																$working_hours_class->getWorkingHoursToRegistration();
																$_SESSION["emp_reg_workingHours"] = null;
																//if (isset($_SESSION["emp_reg_department"])){
																	
																//	$position_class = new Position;
																//	$position_class->getAllPosition($_SESSION["emp_reg_department"]);
																//	$_SESSION["emp_reg_department"] = null;
																//}
															?>
														</select>
													</div>

													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Immediate Head's Name&nbsp;</label>
														<input type="text" class="form-control typeahead tt-query" value="<?php if (isset($_SESSION['emp_reg_headsname'])) { echo $_SESSION['emp_reg_headsname']; $_SESSION['emp_reg_headsname'] = null; }  ?>" autocomplete="off" style="" name="headName" id="txt_only" />
													</div>

												</div> 

												<div class="form-group">
													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Company&nbsp;<span class="red-asterisk">*</span></label>
														<select name="company_id" class="form-control">
															<option value="">Select Company</option>
															<?php
													    		//$company_class = new Company;

																if (isset($_SESSION["emp_reg_company_id"])){

																	$company_class->selectCompanyToDropdown($_SESSION["emp_reg_company_id"]);
																	$_SESSION["emp_reg_company_id"] = null;
																}
																else {
													    			$company_class->getAllCompanyToDropdown();
													    		}
													    	?>	
														</select>
													</div>

													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Employment Type&nbsp;<span class="red-asterisk">*</span></label>
														<select name="employment_type" class="form-control">
															<option value="">Select Employment Type</option>
															<option value="OJT/Training" <?php if (isset($_SESSION["emp_reg_employment_type"])){ if ($_SESSION["emp_reg_employment_type"] == "OJT/Training") { echo "selected=selected"; $_SESSION["emp_reg_employment_type"] = null;}} ?>>OJT/Training</option>
															<option value="Provisional" <?php if (isset($_SESSION["emp_reg_employment_type"])){ if ($_SESSION["emp_reg_employment_type"] == "Provisional") { echo "selected=selected"; $_SESSION["emp_reg_employment_type"] = null;}} ?> >Probational</option>
															<option value="Regular" <?php if (isset($_SESSION["emp_reg_employment_type"])){ if ($_SESSION["emp_reg_employment_type"] == "Regular") { echo "selected=selected"; $_SESSION["emp_reg_employment_type"] = null;}} ?>>Regular</option>
														</select>
													</div>

													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Working Days&nbsp;<span class="red-asterisk">*</span></label>
														<select class="form-control" name="workingDays" required="required">
															<option value="">Select Working Days</option>
															<?php
																$working_days_class = new WorkingDays;
																$working_days_class->getWorkingDaysToRegistration();
																$_SESSION["emp_reg_workingDays"] = null;
																//if (isset($_SESSION["emp_reg_department"])){
																	
																//	$position_class = new Position;
																//	$position_class->getAllPosition($_SESSION["emp_reg_department"]);
																//	$_SESSION["emp_reg_department"] = null;
																//}
															?>
														</select>
													</div>

												</div>
											</div>
										</fieldset>


										<fieldset style="border:1px solid #BDBDBD;background-color: #ebf5fb ">
											<h4 style="background-color: #943126 ;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-check"></span> GOVERNMENT INFORMATION</h4>
												<div class="col-sm-10 col-sm-offset-1">
													<div class="form-group">	
														<div class="col-sm-4">						
															<label class="control-label"><img src="img/government images/SSS-Logo.jpg" class="government-logo" alt="SSS-Logo"/>&nbsp; SSS No.</label>
															<input type="text" name="sssNo" id="number_only" value="<?php if (isset($_SESSION["emp_reg_sss_no"])) { echo $_SESSION["emp_reg_sss_no"]; $_SESSION["emp_reg_sss_no"] = null; } ?>" class="form-control" placeholder="Enter SSS No.">
														</div>

														<div class="col-sm-4">						
															<label class="control-label"><img src="img/government images/pag-ibig-logo.jpg" class="government-logo" alt="Pag-big-Logo"/>&nbsp; Pag-ibig No.</label>
															<input type="text" name="pagibigNo" id="number_only" value="<?php if (isset($_SESSION["emp_reg_pag_ibig_no"])) { echo $_SESSION["emp_reg_pag_ibig_no"]; $_SESSION["emp_reg_pag_ibig_no"] = null; } ?>" class="form-control" placeholder="Enter Pag-ibig No.">
														</div>
														<div class="col-sm-4">						
															<label class="control-label"><img src="img/government images/bir-Logo.jpg" class="government-logo" alt="BIR-Logo"/>&nbsp; Tin No.</label>
															<input type="text" name="tinNo" id="number_only" value="<?php if (isset($_SESSION["emp_reg_tin_no"])) { echo $_SESSION["emp_reg_tin_no"]; $_SESSION["emp_reg_tin_no"] = null; } ?>" class="form-control" placeholder="Enter Tin No.">
														</div>
													</div>

													<div class="form-group">
														<div class="col-sm-4">						
															<label class="control-label"><img src="img/government images/philhealth-logo.jpg" class="government-logo" alt="Philhealth-Logo"/>&nbsp; Philhealth No.</label>
															<input type="text" name="philhealthNo" id="number_only" value="<?php if (isset($_SESSION["emp_reg_philhealt_no"])) { echo $_SESSION["emp_reg_philhealt_no"]; $_SESSION["emp_reg_philhealt_no"] = null; } ?>" class="form-control" placeholder="Enter Philhealth No.">
														</div>
													</div>


												</div>
										</fieldset>



										<fieldset style="border:1px solid #BDBDBD;background-color: #ebf5fb ">
											<h4 style="background-color:  #6eb4ff  ;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-education"></span> SCHOOL INFORMATION</h4>
												<div class="col-sm-10 col-sm-offset-1">
													<div class="form-group">	
														<div class="col-sm-4">						
															<label class="control-label"><span class="glyphicon glyphicon-education" style="color:#2E86C1;"></span>&nbsp;Highest Educational Attain&nbsp;<span class="red-asterisk">*</span></label>
															<select class="form-control" name="education_attain" required="required">
																<option value="">Select Highest Educational Attain</option>
																<option value="Secondary" <?php if (isset($_SESSION["emp_reg_educational_attain"])){ if ($_SESSION["emp_reg_educational_attain"] == "Secondary") { echo "selected=selected"; }} ?> >Secondary</option>
																<option value="Tertiary" <?php if (isset($_SESSION["emp_reg_educational_attain"])){ if ($_SESSION["emp_reg_educational_attain"] == "Tertiary") { echo "selected=selected"; }} ?>>Tertiary</option>
															</select>
														</div>

													</div>

													<div id="append_tertiary_education">
														<?php
															if (isset($_SESSION["emp_reg_educational_attain"])){

																if ($_SESSION["emp_reg_educational_attain"] == "Secondary"){
														?>
																	<div class="form-group">
																		<div class="col-sm-12">
															              <label style="color: #27ae60 "><i>Secondary Information</i></label>
															             </div>
															            <div class="col-sm-4">
															              <label class="control-label"><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> School Name:&nbsp;<span class="red-asterisk">*</span></label>
															              <input type="text" name="school_name[]" class="form-control" required="required" value='<?php echo $_SESSION["emp_reg_school_name"]; ?>'/>
															            </div>
															            <div class="col-sm-2">
															              <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>
															              <input type="text" name="year_from[]" class="form-control" required="required" id="year_only" value='<?php echo $_SESSION["emp_reg_year_from"]; ?>' placeholder="year from"/>
															            </div>
															            <div class="col-sm-2">
															              <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>
															              <input type="text" id="year_only" name="year_to[]" class="form-control" required="required" value='<?php echo $_SESSION["emp_reg_year_to"]; ?>' placeholder="year to"/>
															            </div>
														          	</div>
											          	<?php
											          				// for setting to null
											          				$_SESSION["emp_reg_school_name"] = null;
											          				$_SESSION["emp_reg_year_from"] = null;
											          				$_SESSION["emp_reg_year_to"] = null;

																} // end of if secondary
																if ($_SESSION["emp_reg_educational_attain"] == "Tertiary"){



														?>
																<div class="form-group">
																	<div class="col-sm-12">
														              <label style="color: #27ae60 "><i>Secondary Information</i></label>
														             </div>
														            <div class="col-sm-4">
														              <label class="control-label"><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> School Name:&nbsp;<span class="red-asterisk">*</span></label>
														              <input type="text" name="school_name[]" class="form-control" required="required" value='<?php echo $_SESSION["emp_reg_school_name1"]; ?>'/>
														            </div>
														            <div class="col-sm-2">
														              <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>
														              <input type="text" name="year_from[]" class="form-control" required="required" id="year_only" value='<?php echo $_SESSION["emp_reg_year_from1"]; ?>' placeholder="year from"/>
														            </div>
														            <div class="col-sm-2">
														              <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>
														              <input type="text" id="year_only" name="year_to[]" class="form-control" required="required" value='<?php echo $_SESSION["emp_reg_year_to1"]; ?>' placeholder="year to"/>
														            </div>
														            <div class="col-sm-3" style="display: none">
															          <label class="control-label"><span class="glyphicon glyphicon-education" style="color:#2E86C1;"></span> Course:&nbsp;<span class="red-asterisk">*</span></label>
															          <textarea class="form-control" name="course[]"><?php echo $_SESSION["emp_reg_course1"]; ?></textarea>
															        </div>
													          	</div>
														<?php
																	$count = $_SESSION["emp_reg_tertiary_count"];
																	$counter = 2;

																	do {
														?>

																<div class="form-group">

																	<?php

																		if ($counter == 2){
																	?>	

																	<div class="col-sm-12">
														              	<label style="color: #27ae60 "><i>Secondary Information</i></label>
														             </div>
																	<?php
																		}

																	?>

															       <div class="col-sm-4">
															          <label class="control-label"><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> School Name:&nbsp;<span class="red-asterisk">*</span></label>
															          <input type="text" value='<?php echo $_SESSION["emp_reg_school_name".$counter]; ?>' name="school_name[]" class="form-control" required="required"/>
															        </div>
															        <div class="col-sm-3">
															          <label class="control-label"><span class="glyphicon glyphicon-education" style="color:#2E86C1;"></span> Course:&nbsp;<span class="red-asterisk">*</span></label>
															          <textarea class="form-control" name="course[]" reuired="required"><?php echo $_SESSION["emp_reg_course".$counter]; ?></textarea>
															        </div>
															        <div class="col-sm-2">
															          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>
															          <input type="text" value='<?php echo $_SESSION["emp_reg_year_from".$counter]; ?>' name="year_from[]" id="year_only" class="form-control" required="required" placeholder="year from"/>
															        </div>
															        <div class="col-sm-2">
															          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>
															          <input type="text" name="year_to[]" id="year_only" value='<?php echo $_SESSION["emp_reg_year_to".$counter]; ?>' class="form-control" required="required" placeholder="year to"/>
															        </div>

															        <?php

															        	if ($counter == 1){
											        				?>
											        					<div class="col-md-1">
																          <button id="add_education_attain" class="btn btn-primary btn-sm" type="button" style="margin-top:30px"><span class="glyphicon glyphicon-plus"></span></button>
																        </div>
											        				<?php
															        	} // end of if
														        	
															        	else {
													        		?>
													        			<div class="col-md-1">
																          <button id="remove_education_attain" class="btn btn-danger btn-sm" type="button" style="margin-top:30px"><span class="glyphicon glyphicon-remove"></span></button>
																        </div>
													        		<?php
															        	}

															        ?>
															        


															      </div>
														<?php
																	$_SESSION["emp_reg_school_name".$counter] = null;
																	$_SESSION["emp_reg_course".$counter] = null;
																	$_SESSION["emp_reg_year_from".$counter] = null;
																	$_SESSION["emp_reg_year_to".$counter] = null;
																	$counter++;
																	}while($count >= $counter);
																} // end of if tertiary

																// set to null
																$_SESSION["emp_reg_educational_attain"] = null;
															}
														?>
													</div>


												</div>
										</fieldset>


										<fieldset style="border:1px solid #BDBDBD;background-color: #ebf5fb ">
											<h4 style="background-color:  #6eb4ff  ;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-briefcase"></span> EMPLOYEMENT INFORMATION</h4>
												<div class="col-sm-10 col-sm-offset-1">
													<div class="form-group">
														<div class="col-md-12">
															<button class="btn btn-primary btn-xs pull-right" id="add_work_xp" type="button" style="margin-top:30px"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add
															</button>
														</div>
													</div>


													<?php
														if (!isset($_SESSION["emp_reg_work_positon1"])){
													?>

													<div class="form-group">	
														<div class="col-sm-2">						
															<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Position&nbsp;<span class="red-asterisk">*</span></label>
															<input type="text" name="work_position[]" id="txt_only" class="form-control" placeholder="Enter Position" required="required">
														</div>

														<div class="col-sm-3">						
															<label class="control-label"><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Company Name&nbsp;<span class="red-asterisk">*</span></label>
															<input type="text" name="company_name[]" id="txt_only" value="" class="form-control" placeholder="Enter Company Name" required="required">
														</div>
														<div class="col-sm-3">						
															<label class="control-label"><span class="glyphicon glyphicon-info-sign" style="color:#2E86C1;"></span> Job Description</label>
															<textarea class="form-control" name="job_description[]" placeholder="Job Description" required="required"></textarea>
														</div>
														<div class="col-sm-2">
												          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>
												          <input type="text" id="year_only" name="work_year_from[]"  class="form-control" required="required" placeholder="year from"/>
												        </div>
												        <div class="col-sm-2">
												          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>
												          <input type="text" name="work_year_to[]" id="year_only" class="form-control" required="required" placeholder="year to"/>
												        </div>
													</div>
													<?php
														}
													?>
													<div id="append_work_experience">
														<?php
															if (isset($_SESSION["emp_reg_work_positon1"])){

																$count = $_SESSION["emp_reg_work_count"];
																$counter = 1;

																do {
														?>
															<div class="form-group">	
																<div class="col-sm-2">						
																	<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Position&nbsp;<span class="red-asterisk">*</span></label>
																	<input type="text" name="work_position[]" id="txt_only" class="form-control" placeholder="Enter Position" required="required" value='<?php echo $_SESSION["emp_reg_work_positon".$counter]; ?>'>
																</div>

																<div class="col-sm-3">						
																	<label class="control-label"><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Company Name&nbsp;<span class="red-asterisk">*</span></label>
																	<input type="text" name="company_name[]" id="txt_only"  class="form-control" placeholder="Enter Company Name" required="required" value='<?php echo $_SESSION["emp_reg_company_name".$counter]; ?>'>
																</div>
																<div class="col-sm-3">						
																	<label class="control-label"><span class="glyphicon glyphicon-info-sign" style="color:#2E86C1;"></span> Job Description</label>
																	<textarea class="form-control" name="job_description[]" placeholder="Job Description" required="required"><?php echo $_SESSION["emp_reg_job_description".$counter]; ?></textarea>
																</div>
																<div class="col-sm-2">
														          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>
														          <input type="text" id="year_only" name="work_year_from[]"  class="form-control" required="required" placeholder="year from" value='<?php echo $_SESSION["emp_reg_work_year_from".$counter]; ?>'/>
														        </div>
														        <div class="col-sm-2">
														          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>
														          <input type="text" name="work_year_to[]" id="year_only" class="form-control" required="required" placeholder="year to" value='<?php echo $_SESSION["emp_reg_work_year_to".$counter]; ?>'/>
														        </div>
															</div>
														<?php
																	$_SESSION["emp_reg_work_positon".$counter] = null;
																	$_SESSION["emp_reg_company_name".$counter] = null;
																	$_SESSION["emp_reg_job_description".$counter] = null;
																	$_SESSION["emp_reg_work_year_from".$counter] = null;
																	$_SESSION["emp_reg_work_year_to".$counter] = null;
																	$counter++;
																}while($count >= $counter);
															}
														?>
													</div>


												</div>
										</fieldset>


										<fieldset style="border:1px solid #BDBDBD;background-color: #ebf5fb ">
											<h4 style="background-color: #9a7d0a ;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-check"></span> USER ACCOUNT INFORMATION</h4>
											<div class="col-sm-10 col-sm-offset-1">
												<div class="form-group">
													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Username&nbsp;<span class="red-asterisk">*</span></label>
														<input type="text" id="account_info_txt" name="username" value="<?php if (isset($_SESSION["emp_reg_username"])) { echo $_SESSION["emp_reg_username"]; $_SESSION["emp_reg_username"] = null; } ?>" class="form-control" placeholder="Enter Username" required="required">
													</div>

													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-lock" style="color:#2E86C1;"></span> Password&nbsp;<span class="red-asterisk">*</span></label>
														<input type="password" id="account_info_txt" name="password" class="form-control" placeholder="Enter Password" required="required">
													</div>
													<div class="col-sm-4">						
														<label class="control-label"><span class="glyphicon glyphicon-lock" style="color:#2E86C1;"></span> Confirm Password&nbsp;<span class="red-asterisk">*</span></label>
														<input type="password" id="account_info_txt" name="confirmPassword" class="form-control" placeholder="Enter Confirm Password" required="required">
													</div>
												</div>

												<div class="form-group">
													<div class="col-sm-4">						
													    <label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Role&nbsp;<span class="red-asterisk">*</span></label>
														<select name="role" required="required" class="form-control">
															<option value=""></option>
															<?php																
																$row_class = new Role;
																$row_class->getAllRole();
															?>
														</select>
													</div>

												</div>
											</div>
										</fieldset>

										<div class="form-group" style="text-align:center;margin-top:10px;">
											<input type="submit" class="btn btn-primary btn-sm" value="REGISTER"/>
										</div>
									</form>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- end of container fluid -->

			<!--<div class="hr-payroll-system-footer">
				<strong>All Right Reserves 2017 | V1.0</strong>
			</div> -->
			
		</div> <!-- end of content -->
		
		

		<div id="change_profile_pic_modal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" style="width:400px">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-camera' style='color:#fff'></span>&nbsp;Change Profile Picture</h5>
					</div> 
					<div class="modal-body" id="">
						<center>
						
								<img src="" class="" id="profile_img"/>
						
							<input type="file" name="profile_pic_file" accept="image/*">
						</center>
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Change</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR ERROR MODAL -->
		<div id="errorModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#b03a2e;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-alert' style='color:#fff'></span>&nbsp;Error Notification</h5>
					</div> 
					<div class="modal-body" id="error_modal_body">
						
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="add_successModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Success Notification</h5>
					</div> 
					<div class="modal-body" id="success_modal_body_add">
						
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


	<!-- <script>
		var global_notif_count = <?php echo $attendance_notifications_class->checkExistNotif($id) ?>;
		//alert(global_notif_count);
		  var interval = setInterval(function(){ 

		      // clearInterval(interval);

		      //alert("HELLO WORLD!");
		      var datastring = "get=1&count="+global_notif_count;
		       $.ajax({
		        type: "POST",
		        url: "ajax/append_get_attendanance_notif.php",
		        data: datastring,
		        cache: false,
		       // datatype: "php",
		        success: function (data) {
		          //alert(data);

		         	if (data != "There is no new notif!"){

		         		data = data.split("#");

		         		global_notif_count = data[0];

		         		// to be load notify
		         		notifyMe(data[1],data[2]);  	

		         		// for ajax
		         	}

		        }
		    });
		    
		  }, 3000); //END OF TIMER//


		  // request permission on page load
		document.addEventListener('DOMContentLoaded', function () {
		  if (!Notification) {
		    alert('Desktop notifications not available in your browser. Try Chromium.'); 
		    return;
		  }

		  if (Notification.permission !== "granted")
		    Notification.requestPermission();
		});

		function notifyMe(path,employee_name) {

			//var path = path;


		  if (Notification.permission !== "granted")
		    Notification.requestPermission();
		  else {
		    var notification = new Notification('Attendance Notification', {
		      icon: 'img/logo/lloyds logo.png',
		      body: "A notif from " + employee_name,
		    });

		    notification.onclick = function () {
		      window.open("attendance_notification_page.php?"+path); // type=File Leave&id=32393      
		    };

		  }

		}
	</script> -->
	</body>
</html>