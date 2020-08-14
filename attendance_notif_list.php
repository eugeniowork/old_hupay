<?php
session_start();
if (!isset($_SESSION["id"])){
	header("Location:index.php");
}
include "class/connect.php"; // fixed class
include "class/position_class.php"; // fixed class
include "class/attendance_notifications.php"; // fixed class
include "class/date.php"; // fixed class
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


include "class/attendance_notif.php";
include "class/cut_off.php";
// for universal variables
$id = $_SESSION["id"];

// for session
$_SESSION["active_dashboard"] = null;
$_SESSION["active_sub_registration"] = null;
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
$_SESSION["active_sub_attendance_updates"] = "active-sub-menu";
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
	include "class/emp_information.php";
	$emp_info = new EmployeeInformation;

	$role = $_SESSION["role"];

	if ($emp_info->alreadyHead($id) == 0 && $role != 1 && $role != 2) {
		header("Location:MainForm.php");
	}

	/*if ($role != 1 && $role != 2) {
		header("Location:MainForm.php");
	}*/
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
		<title>Attendance Update Request</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <!-- for symbols -->
		<?php
			$company_class = new Company;
			$row_company = $company_class->getInfoByCompanyId($row->company_id);
			$logo_source = $row_company->logo_source;
		?>
		<link rel="shortcut icon" href="<?php echo $logo_source; ?>"> <!-- for logo -->

		<!-- css -->
		<link rel="stylesheet" href="css/lumen.min.css">
		<link rel="stylesheet" href="css/plug ins/calendar/dcalendar.picker.css">
		<link rel="stylesheet" href="css/plug ins/data_tables/dataTables.bootstrap.css">
		<link rel="stylesheet" href="css/custom.css">

		<!-- js -->
		<script src="js/jquery-1.12.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/plug ins/calendar/dcalendar.picker.js"></script>
		<script src="js/plug ins/data_tables/jquery.dataTables.js"></script>
		<script src="js/plug ins/data_tables/dataTables.bootstrap.js"></script>
		<script src="js/chevron.js"></script>
		<script src="js/readmore.js"></script>
		<script src="js/notifications.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script>
		<script src="js/modal.js"></script>
		<script>
			var oTable;
			var oSettings;
			$(document).ready(function(){
				//$('#attendance_list').DataTable();



				 oTable=$('#attendance_list').DataTable( {
			        "bSort": false,
			        "pagingType": "full_numbers",
			        "dom": 'T<"clear">lfrtip',
				});
			    oSettings = oTable.settings();

			    // for click enter button
				$("#modal_body_update_request_attendance").on("click", "button[id='enter']", function () {
					
					//alert("Hello World!");
					// 
					if ($("input[name='password']").val() == "") {
				 	 		$("#message").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please fill up your password.</center>");
				 	 }
				 	 	// if success ajax will be
			 	 	else {
			 	 		var datastring = "password="+$("input[name='password']").val();
			 	 		$("#message").html("<center><div class='loader'></div>Loading Information</center>");
			 	 		//alert("Hello World!");
			 	 		$.ajax({
				            type: "POST",
				            url: "ajax/approve_attendance_script.php",
				            data: datastring,
				            cache: false,
				           // datatype: "php",
				            success: function (data) {
				            	//alert(data);
				            	// if invalid password
				            	if (data == "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> You have entered an invalid password.</center>"){
				             		$("#message").html(data);

				             	}
				             	// if success
				             	else {
				             			
			             			var approve = $("button[id='enter']").html();
			             			//alert(approve);

				             		 oSettings[0]._iDisplayLength = oSettings[0].fnRecordsTotal();
									 oTable.draw();
				             		// if equal to approve
			             			$("#approve_request_list_form").attr("action","php script/multiple_approve_attendance_updates.php?approve="+approve);
				             		$("#approve_request_list_form").submit();
					             	
				             		
				             	}
				               // $('#update_modal_body').html(data);
				              //  $("#update_info_modal").modal("show");
				            }
				        });
			 	 	}

				});

				// if press was enter
				 $("#modal_body_update_request_attendance").on('keypress', "input[name='password']", function (e) {
				 	 if(e.which === 13){
				 	 	if ($("input[name='password']").val() == "") {
				 	 		$("#message").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please fill up your password.</center>");
				 	 	}
				 	 	// if success ajax will be
				 	 	else {
				 	 		var datastring = "password="+$("input[name='password']").val();
				 	 		$("#message").html("<center><div class='loader'></div>Loading Information</center>");
				 	 		$.ajax({
					            type: "POST",
					            url: "ajax/approve_attendance_script.php",
					            data: datastring,
					            cache: false,
					           // datatype: "php",
					            success: function (data) {
					            	// if invalid password
					            	if (data == "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> You have entered an invalid password.</center>"){
					             		$("#message").html(data);
					             	}
					             	// if success
					             	else {

					             		var approve = $("button[id='enter']").html();
			             				//alert(approve);

					             		oSettings[0]._iDisplayLength = oSettings[0].fnRecordsTotal();
									 	oTable.draw();
					             		// if equal to approve
					             		// if equal to approve
			             				$("#approve_request_list_form").attr("action","php script/multiple_approve_attendance_updates.php?approve="+approve);
				             			$("#approve_request_list_form").submit();
						             		
					             		
					             	}
					               // $('#update_modal_body').html(data);
					              //  $("#update_info_modal").modal("show");
					            }
					        });
				 	 	}
			 	 	}

				 });



				<?php

					// for updating emp info
					if (isset($_SESSION["success"])){
						echo '$(document).ready(function() {						
							$("#update_successModal").modal("show");
						});';
						$_SESSION["success"] = null;
					}


					// for updating emp info
					if (isset($_SESSION["success_disapprove"])){
						echo '$(document).ready(function() {						
							$("#update_successModal_disapprove").modal("show");
						});';
						$_SESSION["success_disapprove"] = null;
					}

				

				?>
				

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
				<div class="row" style="border-bottom:1px solid #BDBDBD;">
					<ol class="breadcrumb">
						<li><a href="MainForm.php">&nbsp;&nbsp;<span class="glyphicon glyphicon-home"></span></a></li>
						<li class="active" id="home_id">Attendance Request List</li> 
					</ol>
				</div>
			</div>

			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 content-div">
						<div class="thumbnail" style="border:1px solid #BDBDBD;">
							<div class="caption">
								
								<fieldset>
									<legend style="color:#357ca5;border-bottom:1px solid #BDBDBD"><span class="glyphicon glyphicon-list-alt"></span> Attendance Request List <!--- cut off: -->
										<?php
											//$cut_off_class = new CutOff;
											//$cut_off_class->getCutOffPeriod();

										?>
									</legend>
									<!-- <span><small><b><span class="glyphicon glyphicon-info-sign" style="color:#2E86C1;"></span> Note: If the position already used for creating an employee it can not be edit and delete.</b></small></span><br/><br/> -->
									<!--<div class="col-sm-10 col-sm-offset-1"> -->
										<form class="form-horizontal" id="approve_request_list_form" method="post">
											<table id="attendance_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
												<thead>
													<tr>
														<th class="no-sort"></th>
														<th class="no-sort"><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Employee Name</th>
														<th class="no-sort"><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Date</th>
														<th class="no-sort"><span class="glyphicon glyphicon-time" style="color:#186a3b"></span> Original Attendance</th>
														<th class="no-sort"><span class="glyphicon glyphicon-time" style="color:#186a3b"></span> Rquested Attendance</th>
														<th class="no-sort"><span class="glyphicon glyphicon-map-marker" style="color:#186a3b"></span> Remarks</th>
														<th  class="no-sort"><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</th>
													</tr>
												</thead>
												<tbody>	
												<?php
													// for showing own attendance only
													

													$emp_id = $_SESSION["id"];

													$attendance_notif_count = $attendance_notif_class->attendanceNotifToTableCount($emp_id,$role);
													
													$attendance_notif_class->attendanceNotifToTable($emp_id,$role);

												?>
												</tbody>
											</table>
										</form>

										<script>

											$(document).ready(function(){

												var attendance_notif_count = <?php echo $attendance_notif_count; ?>;

												var check_count = 0;

												var counter = 0;
												do {

													counter++
													$("table[id='attendance_list']").on("click","input[name='attendance_request"+counter+"']",function(){
														
														if ($(this).is(':checked')){
															check_count++;
															//alert(check_count);
														}
														else {
															check_count--;
															//alert(check_count);
														}

													});


												}while(attendance_notif_count >= counter);


												// for approving all attendance request
											    $("span[id='approve_attendance_updates']").on("click",function(){
											        //alert("Hello World!");
											        	
										        	if (check_count == 0){
										        		$("#error_modal_body").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Please check atleast 1 attendance updates</b></center>");
										        		$("#errorModal").modal("show");
										        	}

										        	else {
										        		//$("#approve_request_list_form").attr("action","php script/multiple_approve_attendance_updates.php");
										        		//$("#approve_request_list_form").submit();
										        		
										        		var datastring = "approve=approve";
										        		//alert(datastring);
										        		
										        		$.ajax({
											              type: "POST",
											              url: "ajax/append_script_update_multiple_attendance.php",
											              data: datastring,
											              cache: false,
											              success: function (data) {
											                // if has error 
											               //if (data == "Error") {
											                 // $("#update_errorModal").modal("show");
											               //}
											               // if success
											               //else {
											               //	alert(data);
											                  $("#modal_body_update_request_attendance").html(data);
											                 // alert(data);
											                  $("#updateRequestUpdateAttendanceModal").modal("show");
											             //  }
											               //alert(data);

											             }
											           });
														

										        	}


											    });


											    // for disapproving all attendance request
											    $("span[id='disapprove_attendance_updates']").on("click",function(){
											        //alert("Hello World!");
											        if (check_count == 0){
											        	$("#error_modal_body").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Please check atleast 1 attendance updates</b></center>");
										        		$("#errorModal").modal("show");
										        	}
										        	else {
										        		//$("#approve_request_list_form").attr("action","php script/multiple_approve_attendance_updates.php");
										        		//$("#approve_request_list_form").submit();
										        		var datastring = "approve=disapprove";
										        		$.ajax({
											              type: "POST",
											              url: "ajax/append_script_update_multiple_attendance.php",
											              data: datastring,
											              cache: false,
											              success: function (data) {
											               // if has error 
											               //if (data == "Error") {
											                 // $("#update_errorModal").modal("show");
											               //}
											               // if success
											               //else {
											                  $("#modal_body_update_request_attendance").html(data);
											                  $("#updateRequestUpdateAttendanceModal").modal("show");
											             //  }
											               //alert(data);

											             }
											           });
										        	}
											    });

											});
										</script>

										<br/>



										<?php

											if ($id != 21){
										?>

										
											<span class="glyphicon glyphicon-ok" style="color:#229954"></span> <span style="color:#158cba;cursor:pointer" id="approve_attendance_updates"><u>Approve</u></span>
											
											<span>|</span>
										
											<span class="glyphicon glyphicon-remove" style="color:#CB4335"></span> <span style="color:#158cba;cursor:pointer" id="disapprove_attendance_updates"><u>Disapprove</u></span>

										<?php

											}
										?>

										

									<!--</div> -->
								</fieldset>
							</div>
						</div> <!-- end of thumbnail -->
					</div>
				</div> <!-- end of row -->
			</div> <!-- end of container-fluid -->	

			
		</div> <!-- end of content -->

		<?php

			include "layout/footer.php";

		?>


		<!-- FOR ERROR MODAL IN  -->
		<div id="update_errorModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#b03a2e;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-warning-sign' style='color:#fff'></span>&nbsp;Request Update Attendance</h5>
					</div> 
					<div class="modal-body">
						<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is an error during getting of data</center>
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->



		<!-- for update bio id modal -->
		<div id="updateRequestUpdateAttendanceModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-user' style='color:#fff'></span>&nbsp;Request Update Attendance</h5>
					</div> 
					<div class="modal-body" id="modal_body_update_request_attendance" >
						
					</div> 
			 		
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="update_successModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-check' style='color:#fff'></span>&nbsp;Request Update Attendance Info</h5>
					</div> 
					<div class="modal-body">
						<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> Update Request of Time in Time Out is Successfully Approved.</center>
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="update_successModal_disapprove" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-check' style='color:#fff'></span>&nbsp;Request Update Attendance Info</h5>
					</div> 
					<div class="modal-body">
						<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> Update Request of Time in Time Out is Successfully Disapproved.</center>
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
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
						<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during getting of data, Please refresh the page</center>
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


	<!--<script>
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