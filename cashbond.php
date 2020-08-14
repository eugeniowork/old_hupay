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
include "class/salary_loan_class.php"; // fixed class
include "class/leave_class.php"; // fixed class
include "class/company_class.php"; // fixed class
include "class/memorandum_class.php"; // fixed class
include "class/attendance_overtime.php"; // fixed class
include "class/attendance_notif.php"; // fixed class

include "class/cashbond_class.php";
include "class/money.php";
include "class/simkimban_class.php";

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
$_SESSION["active_cashbond"] = "background-color:#1d8348";
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
	//if ($_SESSION["role"] == 2 || $_SESSION["role"] == 4) {
	//	header("Location:Mainform.php");
	//}
	include "class/emp_information.php";
	$emp_info = new EmployeeInformation;
	$position_class = new Position;
	$date_class = new date;
	$cashbond_class  = new Cashbond;
	$salary_loan_class = new SalaryLoan;
	$leave_class = new Leave;
	$memorandum_class = new Memorandum;
	$attendance_ot_class = new Attendance_Overtime;
	$attendance_notif_class = new AttendanceNotif;

	$simkimban_class = new Simkimban;


	// for pending ot 
	$pending_file_ot_count = $attendance_ot_class->getOvertimePendingCount($_SESSION["role"],$_SESSION["id"]);
	$pending_file_attendance_request_count = $attendance_notif_class->attendanceNotifPendingCount($_SESSION["role"],$_SESSION["id"]);
	
	$row = $emp_info->getEmpInfoByRow($id);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Cash Bond</title>
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
		<script src="js/hover.js"></script>
		<script src="js/string_uppercase.js"></script>
		<script src="js/numbers_only.js"></script>
		<script src="js/maxlength.js"></script>
		<script src="js/format.js"></script>
		<script src="js/readmore.js"></script>
		<script src="js/notifications.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script>
		<!--<script src="js/modal.js"></script> -->
		<script>
			$(document).ready(function(){
				$('#cashbond_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});

				//$('.modal-dialog').draggable();


				// cashbond_withdraw_history
				$('#cashbond_withdraw_history').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});

				// file_cashnond_withdrawal_list
				$('#file_cashnond_withdrawal_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});

				

				<?php
					if (isset($_SESSION["update_error_msg_cashbond"])){
						echo '$(document).ready(function() {
							$("#error_modal_body").html("'.$_SESSION["update_error_msg_cashbond"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["update_error_msg_cashbond"] = null;
					}

					// success in updating
					if (isset($_SESSION["update_success_msg_cashbond"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_cashbond").html("'.$_SESSION["update_success_msg_cashbond"].'");
							$("#successModal").modal("show");
						});';
						$_SESSION["update_success_msg_cashbond"] = null;
					}


					// for success in approving or disapproving file cashbond withdrawal
					if (isset($_SESSION["success_approve_file_cashbond_withdrawal"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_cashbond").html("'.$_SESSION["success_approve_file_cashbond_withdrawal"].'");
							$("#successModal").modal("show");
						});';
						$_SESSION["success_approve_file_cashbond_withdrawal"] = null;
					}


					// for success in approving or disapproving file cashbond withdrawal
					if (isset($_SESSION["success_crud_cashbond"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_cashbond").html("'.$_SESSION["success_crud_cashbond"].'");
							$("#successModal").modal("show");
						});';
						$_SESSION["success_crud_cashbond"] = null;
					}


					// success in filing cashbond withdrawal
					if (isset($_SESSION["success_file_cashbond_withdrawal"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_cashbond").html("'.$_SESSION["success_file_cashbond_withdrawal"].'");
							$("#successModal").modal("show");
						});';
						$_SESSION["success_file_cashbond_withdrawal"] = null;
					}



					// error in filing cashbond withdrawal 
					if (isset($_SESSION["error_file_cashbond_withdrawal"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_cashbond").html("'.$_SESSION["error_file_cashbond_withdrawal"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["error_file_cashbond_withdrawal"] = null;
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
						<li class="active" id="home_id">Cash Bond</li> 
					</ol>
				</div>
			</div>


			<?php
	 			
	 			$money_class = new Money;
	 		?>


			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">

						<?php
							if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3 || $_SESSION["role"] == 2){

						?>
						<div class="panel panel-primary" style="margin-top:10px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span> List of Cashbond of Employee</center></div> 


							 <div class="panel-body">
							 	<b>
									<div class="col-sm-12" style="border-radius:10px;background-color: #e5e8e8;margin-bottom:10px;padding:10px;text-align:center;">
										<small>
											<!--<span style='color:#186a3b;'>Icon Legends: </span> -->
											<span class='glyphicon glyphicon-pencil' style='color:#b7950b ;margin-left:5px;'></span> - Edit Salary Loan Info
											<span class='glyphicon glyphicon-eye-open' style='color:#2980b9 ;margin-left:5px;'></span> - View Cashbond Reports 
											<span class='glyphicon glyphicon-plus-sign' style='color: #717d7e  ;margin-left:5px;'></span> - Add Cashbond Deposit
											<span class='glyphicon glyphicon-adjust' style='color:#229954;margin-left:5px;'></span> - Adjust Cashbond Info 
										</small>

									</div>
								</b>
							 	<div class="col-sm-10 col-sm-offset-1">
							 		
							 		<div class="pull-right" id="print_cash_bond_reports" style="cursor:pointer;">
							 			<span class="glyphicon glyphicon-print" style="color: #2c3e50"></span> <b style="color:#158cba">Print Cashbond Reports</b>
							 		</div>
							 		<br/><br/>
							 		<table id="cashbond_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
										<thead>
											<tr> 	
												<th><span class="glyphicon glyphicon-user" style="color:#186a3b"></span> Employee Name</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Cashbond Value</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Total Cashbond</th>
												<th><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</th>
											</tr>
										</thead>
										<tbody>	
										<?php
											//$emp_info->getEmpInfoToTable();
											$cashbond_class->getCashbondInfoToTable();

										?>
										</tbody>
									</table>
							 	</div>


							</div>
						</div>

						<?php
							}
						?>


						<?php
							//if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3){

							if ($cashbond_class->checkExistCashBondByEmpId($id) == 1){

							$row_cashbond = $cashbond_class->getInfoByEmpId($id);
							//echo $row_cashbond->totalCashbond;
							$totalCashbond = $row_cashbond->totalCashbond;

						?>
						<div class="panel panel-primary" style="margin-top:10px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span> Cashbond Withdraw History</center></div> 


							 <div class="panel-body">
							 	<div class="col-sm-8 col-sm-offset-2">
							 		<i><span class="glyphicon glyphicon-info-sign" style="color: #2980b9 "></span>&nbsp;Note: Cashbond rate increase 2% from 3% become 5% per annum and upon reaching 30,000 and above rate also increase by 2% from 5% become 7%.</i>
							 		<br/>
							 		<?php
							 			if ($totalCashbond >= 5001){
							 		?>
							 		<div class="pull-right" id="file_cashbond_withdrawal" style="cursor:pointer;">
							 			<span class="glyphicon glyphicon-file" style="color:#229954;"></span> <b style="color:#158cba">File Cashbond Withdrawal</b>
							 		</div>
							 		<br/><br/>
							 		<?php
							 			}
							 		?>
							 		<table id="cashbond_withdraw_history" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
										<thead>
											<tr> 	
												<th><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Date File</th>
												<th><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Date Approve</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Amount Withdraw</th>
											</tr>
										</thead>
										<tbody>	
										<?php
											//$emp_info->getEmpInfoToTable();
											$cashbond_class->getOwnApproveFileCashbondHistory($id);

										?>
										</tbody>
									</table>
							 	</div>


							</div>
						</div>

						<?php
						} // end of if exist ung emoid sa cashbond
						?>

						<?php
							if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3){
						?>
						<div class="panel panel-primary" style="margin-top:10px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span> List of Filed Cashbond Withdrawal</center></div> 


							 <div class="panel-body">
							 	<div class="col-sm-10 col-sm-offset-1">
							 		
							 		<table id="file_cashnond_withdrawal_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
										<thead>
											<tr> 	
												<th><span class="glyphicon glyphicon-user" style="color:#186a3b"></span> Employee Name</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Amount Withdraw</th>
												<th><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Date File</th>
												<th><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</th>
											</tr>
										</thead>
										<tbody>	
										<?php
											//$emp_info->getEmpInfoToTable();
											$cashbond_class->getAllPendingFileCashbondWithdrawal();

										?>
										</tbody>
									</table>
							 	</div>


							</div>
						</div>
						<?php
							}
						?>

						<?php

							// for checking kung meron siyang pending na cashbond withdrawal
							$file_amount_withdraw = 0;
							if ($cashbond_class->checkExistFileCashbondWithdrawal($id) == 1){
								$row_file_cashbond_withdrawal = $cashbond_class->getLastestFileCashbondWithdrawal($id);
								$file_amount_withdraw = $row_file_cashbond_withdrawal->amount_withdraw;
						?>
							<div class="thumbnail col-md-6 col-md-offset-6">
								<div class="caption">
									<h4 style="border-bottom:1px solid #BDBDBD;color: #2980b9 "><span class="glyphicon glyphicon-stats"></span> Pending File Cashbond Withdrawal Information</h4>
										
									<div class="col-md-6 col-md-offset-3">
										<label><span class="glyphicon glyphicon-calendar" style="color: #229954 "></span> <b>Date File:</b> <?php echo $date_class->dateFormat($row_file_cashbond_withdrawal->dateCreated); ?></label>
									</div>

									<div class="col-md-6 col-md-offset-3">
										<label><span class="glyphicon glyphicon-ruble" style="color: #229954 "></span> <b>Amount Withdraw:</b> Php <?php echo $money_class->getMoney($row_file_cashbond_withdrawal->amount_withdraw); ?></label>
									</div>


									<div class="col-md-12">
										<span class="pull-right" id="cancel_file_cashbond_withdrawal" style="cursor:pointer;color: #2980b9 "><span class="glyphicon glyphicon-remove" style="color: #c0392b "></span>Cancel</span>
										<span class="pull-right">&nbsp;|&nbsp;</span>
										<span class="pull-right" id="edit_file_cashbond_withdrawal" style="cursor:pointer;color: #2980b9 "><span class="glyphicon glyphicon-pencil" style="color: #d4ac0d"></span>Edit</span>
									</div>


								</div>
							</div>

						<?php
							}
						?>


						<?php
							//}
						?>


						<!--
						<div class="panel panel-primary" style="margin-top:10px;"> 

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-file"></span> File </center></div> 


							 <div class="panel-body">
							 	<div class="col-sm-8 col-sm-offset-2">
							 		
							 	</div>


							</div>
						</div>
						-->


					</div>

			<!--<div class="hr-payroll-system-footer">
				<strong>All Right Reserves 2017 | V1.0</strong>
			</div> -->
			
		</div> <!-- end of content -->


		<?php

			include "layout/footer.php";

		?>


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

		<!-- FOR SUCCESS MODAL -->
		<div id="updateFormModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-pencil' style='color:#fff'></span>&nbsp;Update Notification</h5>
					</div> 
					<div class="modal-body" id="update_cashbondForm_body">						
					</div> 
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="adjustFormModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-pencil' style='color:#fff'></span>&nbsp;Adjust Form</h5>
					</div> 
					<div class="modal-body" id="adjust_cashbondForm_body">
						
					</div> 
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="addDepositModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-pencil' style='color:#fff'></span>&nbsp;Deposit Form</h5>
					</div> 
					<div class="modal-body" id="deposit_cashbondForm_body">
						
					</div> 
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="successModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-check' style='color:#fff'></span>&nbsp;Success Notification</h5>
					</div> 
					<div class="modal-body" id="success_modal_body_cashbond">
						
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<div id="fileCashwithdrawalModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header" style="background-color: #2980b9 ">
		        <button type="button" class="close" data-dismiss="modal" style="color:#fff">&times;</button>
		        <h4 class="modal-title" style="color:#fff"><span class="glyphicon glyphicon-edit"></span>&nbsp;File Cashwithdrawal</h4>
		      </div>
		      <div class="modal-body">
		      	<form class="form-horizontal" id="form_file_cashbond_withdrawal" method="post">
		      		<div class="container-fluid">
		      			<div class="form-group">
		      				<div class="col-md-12">
		      					<?php
		      						
		      					?>
		      					<b>Total Cashbond:</b><i> Php <?php echo $money_class->getMoney($totalCashbond); ?></i> <br/>
		      					<?php

		      						$total_salary_loan = $salary_loan_class->getAllSalaryLoan($id);
		      						$total_simkimban_loan = $simkimban_class->getAllRemainingBalanceSimkimban($id);


		      						$amount_can_withdraw = ($totalCashbond - 5000) - ($total_salary_loan + $total_simkimban_loan);

		      						if ($amount_can_withdraw < 0){
		      							$amount_can_withdraw = 0;
		      						}

		      					?>
		      					<b>Pending Loans Total Amount:</b><i> Php <?php echo number_format($total_salary_loan + $total_simkimban_loan,2); ?></i> <br/>

		      					<b>Available amount that can withdraw:</b><i> Php <?php echo number_format($amount_can_withdraw,2);?></i> 
		      				</div>
	      				</div>
		      			<div class="form-group">
		      				<label class="control-label col-md-4" style="margin-right:-20px;"><b style="color: #2471a3 ">Date:</b></label>
		      				<div class="col-md-5">
		      					<?php
		      						$current_date = $date_class->dateFormat($date_class->getDate());
		      					?>
		      					<input type="text" name="current_date" value="<?php echo $current_date; ?>" class="form-control" id="input_payroll"/>
	      					</div>
	      				</div>

	      				<div class="form-group">
		      				<label class="control-label col-md-4" style="margin-right:-20px;"><b style="color: #2471a3 ">Amount Withdraw:</b></label>
		      				<div class="col-md-5">
		      					
		      					<input type="text" name="amount_withdraw" value="" class="form-control" id="float_only"/>
	      					</div>
	      				</div>

	      				<div class="form-group">
	      					<div class="col-md-offset-4">
	      						<button type="button" class="btn btn-sm btn-success" id="btn_file_withdrawal">File Withdrawal</button>
      						</div>
      					</div>

      					<div class="form-group">
      						<div class="col-md-offset-4">
      							<label id="file_withdraw_message">&nbsp;</label>
  							</div>
  						</div>
	      			</div>
	        	</form>
		      </div>
		      
		    </div>

		  </div>
		</div>

		<div id="approveConfirmationModal" class="modal fade" role="dialog">
		  <div class="modal-dialog modal-sm">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header" style="background-color: #2980b9 ">
		        <button type="button" class="close" data-dismiss="modal" style="color:#fff">&times;</button>
		        <h4 class="modal-title" style="color:#fff"><span class="glyphicon glyphicon-edit"></span>&nbsp;Approve Confirmation</h4>
		      </div>
		      <div class="modal-body" id="approve_confirmation_body">


      		</div>
  		</div>
	  </div>
  </div>



	  <!-- FOR viewing cashbond history MODAL -->
		<div id="cashbondHistoryModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-user' style='color:#fff'></span>&nbsp;Employee Cashbond History</h5>
					</div> 
					<div class="modal-body" id="cashbond_history_body">
						
					</div> 
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR DELETE CONFIRMATION MODAL -->
		<div id="cancelConfirmationModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-remove' style='color:#fff'></span>&nbsp;Cancel File Withdrawal Notification</h5>
					</div> 
					<div class="modal-body" id="delete_modal_body">
						<center>Are you sure you want to cancel your file cashbond withdrawal with the amount of <b>Php <?php echo $money_class->getMoney($file_amount_withdraw); ?></b>?</center>
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="delete_modal_footer">
						<a href="#" class="btn btn-default" id="cancel_yes_cashbond_withdrawal">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->



		<div id="updatefileCashwithdrawalModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header" style="background-color: #2980b9 ">
		        <button type="button" class="close" data-dismiss="modal" style="color:#fff">&times;</button>
		        <h4 class="modal-title" style="color:#fff"><span class="glyphicon glyphicon-edit"></span>&nbsp;File Cashwithdrawal</h4>
		      </div>
		      <div class="modal-body" id="modal_body_file_cashbond_withdraw">
		      	
		      </div>
		      
		    </div>

		  </div>
		</div>


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