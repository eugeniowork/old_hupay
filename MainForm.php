<?php
session_start();
if (!isset($_SESSION["id"])){
	header("Location:index.php");
}
include "class/connect.php"; // fixed class
include "class/position_class.php"; // fixed class
include "class/attendance_notifications.php"; // fixed class
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

include "class/holiday_class.php";
include "class/events.php";
include "class/money.php";
include "class/pagibig_loan_class.php";
include "class/sss_loan_class.php";
include "class/simkimban_class.php";
include "class/cashbond_class.php";
include "class/cut_off.php";
include "class/Payroll.php";
include "class/allowance_class.php";
include "class/working_hours_class.php";
include "class/working_days_class.php";
include "class/time_in_time_out.php";



// for session
$_SESSION["active_dashboard"] = "background-color:#1d8348";
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
$_SESSION["active_cashbond"] = null;
$_SESSION["active_year_total_deduction"] = null;
$_SESSION["active_salary_information"] = null;
$_SESSION["active_audit_trail"] = null;
$_SESSION["active_sub_loan_adjustment"] = null;
$_SESSION["active_sub_simkimban_adjustment"] = null;
$_SESSION["active_sub_payroll"] = null;
$_SESSION["active_sub_payroll_adjust"] = null;


// for universal variables
$id = $_SESSION["id"];

// this area is for null of session
$_SESSION["view_emp_id"] = null; // sa view emp info
$_SESSION["update_emp_id"] = null; // sa update emp info

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

$date_hired = date_format(date_create($row->DateHired),"m-d");
$year_date_hired = date_format(date_create($row->DateHired),"Y");


$leave_class->refereshLeaveCountAnniversary($id,$date_hired,$year_date_hired); // for refreshing


// for payroll
if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3){
	$cut_off_class = new CutOff;
	$payroll_class = new Payroll;


	$cutoffPeriod = $cut_off_class->getCutOffPeriodLatest();

	if ($payroll_class->existGeneratePayrollcutOff($cutoffPeriod) == 0){
		$payroll_class->insertGeneratePayroll($cutoffPeriod,date("Y-m-d"));
	}
}


?>

<!DOCTYPE html>
<html>
	<head>
		<title>Event Dashboard</title>
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
		<link rel="stylesheet" href="css/custom.css">

		<!-- js -->
		<script src="js/jquery-1.12.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/plug ins/calendar/dcalendar.picker.js"></script>
		<script src="js/chevron.js"></script>
		<script src="js/notifications.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script> 
		<script src="js/modal.js"></script>
		<script>
			$(document).ready(function(){
				
			});
		</script>
	</head>
	<body style="background-color:#ecf0f5;">
		
		<?php
			include "layout/header.php";
		?>

		<div class="content">

			<!-- for menu directory at the top -->
			<!--<div class="container-fluid">
				<div class="row" style="border-bottom:1px solid #BDBDBD;">
					<ol class="breadcrumb">
						<li><a href="MainForm.php">&nbsp;&nbsp;<span class="glyphicon glyphicon-home"></span></a></li>
						<li class="active" id="home_id">Dashboard</li> 
					</ol>
				</div>
			</div>-->


			<div class="container-fluid">
				<div class="row">


				<?php

					if ($id == 1){
				?>
					<div class="col-md-12" style="margin-top:10px">

					</div>
				<?php
					}

					if ($id != 1){
				?>		

				<div class="col-sm-4">
					<?php
						

					?>
					
					<div class="thumbnail" style="margin-top:10px;border:1px solid #BDBDBD;border-radius:0px;background-color:#1d8348">
						<div class="caption" style="padding:0px;color:#fff;">
							LOANS REMAINING BALANCE INFORMATION
						</div>
					</div>
				</div>
				<div class="col-sm-9">
					<div class="thumbnail" style="margin-top:-20px;border:1px solid #BDBDBD;border-radius:0px;">
						<div class="caption" style="padding:0px;">
							<div class="container-fluid">
								<div class="col-md-3">	
									<label class="control-label"><b>Pag-ibig Loan:</b></label>
									<div>
									<?php
										$pagibig_loan_class = new PagibigLoan;
										$money_class = new Money;

									

										if ($pagibig_loan_class->existPagibigLoan($id) == 1){

											$row_pagibigLoan = $pagibig_loan_class->getInfoByPagibigLoanEmpId($id);

											echo "Php " . $money_class->getMoney($row_pagibigLoan->remainingBalance);
										}

										else {
											echo "Php 0.00";
										}

									?>
									</div>
								</div>
								<div class="col-md-3">	
									<label class="control-label"><b>SSS Loan:</b></label>
									<div>
									<?php
										$sss_loan_class = new SSSLoan;
									
										if ($sss_loan_class->existSSSPendingLoan($id) > 0){
											$sss_loan_amount = $sss_loan_class->getInfoBySSSLoanEmpId($id);

											echo "Php " . number_format($sss_loan_amount,2);

											if ($sss_loan_class->existSSSPendingLoan($id) > 1){
												echo "&nbsp;<span data-placement='bottom' class='color-blue glyphicon glyphicon-eye-open' style='cursor:pointer' data-toggle='popover' title='Loan Information Balance' data-popover-content='#a1'></span>";
											}
										}

										else {
											echo "Php 0.00";
										}

									?>
									</div>
									<!-- Content for Popover #1 -->
									<div class="hidden" id="a1">
									  <div class="popover-heading">
									    This is the heading for #1
									  </div>

									  <div class="popover-body">
									  	
								  		
										    <table class="table-bordered table-striped" border="1" cellpadding="15">
											    <thead>
											      <tr>
											        <th style="padding: 5px;">Loan Type</th>
											        <th>Balance</th>
											      
											      </tr>
											    </thead>
											    <tbody>
											    	<?php

											    		$sss_loan_class->getInfoBySSSLoanEmpIdToDashboard($id);
											    	?>
											    </tbody>
										  	</table>
									  	
										  
									  </div>
									</div>
									<script>
										$(document).ready(function(){
										    $("[data-toggle=popover]").popover({
										        html : true,
										        content: function() {
										          var content = $(this).attr("data-popover-content");
										          return $(content).children(".popover-body").html();
										        },
										        title: function() {
										          var title = $(this).attr("data-popover-content");
										          return $(title).children(".popover-heading").html();
										        }
										    });   
										});
									</script>
								</div>
								<div class="col-md-3">	
									<label class="control-label"><b>Cash Advance:</b></label>
									<div>
									<?php
										$simkimban_class = new Simkimban;
										
										//echo $salary_loan_class->existSalaryLoan($id);
										if ($simkimban_class->existSimkimbanLoan($id) != 0 || $salary_loan_class->existSalaryLoan($id) != 0){
											
											//echo "wew";
											$simkimban_remaining_balance = 0;
											if ($simkimban_class->existSimkimbanLoan($id) != 0) {
												$simkimban_remaining_balance = $simkimban_class->getAllRemainingBalanceSimkimban($id);
											}
											//echo $simkimban_remaining_balance . "<br/>";
											$salary_loan_remaining_balance = 0;
											//echo $id;
											//echo $salary_loan_class->existSalaryLoan($id);
											if ($salary_loan_class->existSalaryLoan($id) != 0){
												$salary_loan_remaining_balance = $salary_loan_class->getAllSalaryLoan($id);
												//$salary_loan_remaining_balance = $row_salary_loan->remainingBalance;
											}
											//echo $salary_loan_remaining_balance ."<br/>";

											echo "Php " . $money_class->getMoney($salary_loan_remaining_balance + $simkimban_remaining_balance);

										}

										else {
											echo "Php 0.00";
										}
										

									?>
									</div>
								</div>
								
								<div class="col-md-3">	
									<label class="control-label"><b>Cashbond:</b></label>
									<div>
									<?php
										$cashbond_class = new Cashbond;

										

										if ($cashbond_class->checkExistCashBondByEmpId($id) != 0){
											$row_cashbond = $cashbond_class->getInfoByEmpId($id);
											echo "Php " . $money_class->getMoney($row_cashbond->totalCashbond);
										}

										else {
											echo "Php 0.00";
										}

										
										
										

									?>
									</div>
								</div>

								<div class="col-md-12" style="margin-top:15px">

								</div>


								<div class="col-md-3">	
									<label class="control-label"><b>Running Balance:</b></label>
									<div>
									<?php
										
										$cut_off_class = new CutOff;
										$holiday_class = new Holiday;
										$allowance_class = new Allowance;
										$working_hours_class = new WorkingHours;
										$attendance_class = new Attendance;
										$working_days_class = new WorkingDays;

										$row_wd = $working_days_class->getWorkingDaysInfoById($row->working_days_id);

										$day_from = $row_wd->day_from;
										$day_to = $row_wd->day_to;

										$working_days_count = $cut_off_class->getCutOffAttendanceDateCountToRunningBalance($day_from,$day_to);
										$holiday_cut_off_count = $holiday_class->holidayCutOffTotalCount();

										$allowance = $allowance_class->getAllowanceInfoToPayslip($row->emp_id);
										$salary = $row->Salary;


										$basicCutOffPay = round($salary / 2,2);
										$allowanceCutOffPay = round($allowance / 2,2);

										//echo $basicCutOffPay . " " . $allowanceCutOffPay . "<br/>";

										$basicCutOffPay = round($basicCutOffPay / 12,2);
										$allowanceCutOffPay = round($allowanceCutOffPay / 12,2);

										

										


										//echo $basicCutOffPay . " " . $allowanceCutOffPay . "<br/>";
										//echo $row->DateHired;

										//echo $basicCutOffPay . " " . " " . $working_days_count . " " . $allowanceCutOffPay . "<br/>";

										//echo $working_days_count  . " " . $holiday_cut_off_count . "<br/>";
										//echo " " . $allowance . " " . $salary;

										//echo $working_days_count . " " . $holiday_cut_off_count . " ";

										$daily_rate = round((($allowance + $salary) / 2) / ($working_days_count - $holiday_cut_off_count),2);

										//echo $daily_rate . " ";

										//echo  " " . $cut_off_rate;
										//$daily_rate = 

										
										
						


										$row_wh = $working_hours_class->getWorkingHoursInfoById($row->working_hours_id);
										

										$timeFrom = $row_wh->timeFrom;
										$timeTo = $row_wh->timeTo;

										


										$timeFrom = strtotime($timeFrom);
										$timeTo = strtotime($timeTo);

										//echo " " . $timeFrom . " " . $timeTo;

										$total_hours = (($timeTo - $timeFrom) / 3600) - 1;

										$hourly_rate = round($daily_rate / $total_hours,2);

										//echo  " " . $hourly_rate;


										//echo " " . $attendance_class->getTardiness($row->bio_id);


										
										//echo " " . $day_from . " " . $day_to;

										$present =  $attendance_class->getPresent($row->bio_id,$day_from,$day_to);

										if ($cut_off_class->checkIfHiredWithinCutOff($row->DateHired) == 1){

											$daily_basic_13_month_pay = round($basicCutOffPay / $working_days_count,2);
											$dayily_allowance_13_month_pay = round($allowanceCutOffPay / $working_days_count,2);

											$basicCutOffPay = round($daily_basic_13_month_pay * $present,2);
											$allowanceCutOffPay = round($dayily_allowance_13_month_pay * $present,2);

										}


										$tardiness = $attendance_class->getTardinessToRunningBalance($row->emp_id,$row->bio_id,$row_wh->timeFrom,$row_wh->timeTo,$day_from,$day_to,$hourly_rate,$daily_rate,$total_hours);	

										$incentives = $attendance_class->getIncentivesToRunningBalance($row->emp_id,$row->bio_id,$row_wh->timeFrom,$row_wh->timeTo,$day_from,$day_to,$daily_rate);

										//echo $daily_rate . " " . $incentives . " wew ";	

										
										$regularOTmin = round($attendance_ot_class->getOvertimeRegularOtRunningBalance($row->emp_id)/ 60,2);
										$regular_ot_rate = round($hourly_rate + round($hourly_rate * .25,2),2);
										$running_regular_ot = round($regular_ot_rate * $regularOTmin,2);

										$regHolidayOTmin = round($attendance_ot_class->getOvertimeRegularHolidayOtRunningBalance($row->emp_id)/ 60,2);
										$regHoliday_ot_rate = round($hourly_rate,2);
										$running_regHoliday_ot = round($regHoliday_ot_rate * $regHolidayOTmin,2); 

										// for special holiday ot
   										$specialHolidayOTmin = round($attendance_ot_class->getOvertimeSpecialHolidayOtRunningBalance($row->emp_id)/60,2); // 90/60 = 1.5
   										$specialHoliday_ot_rate = round($hourly_rate * .3,2);
   										$running_specialHoliday_ot_amount = round($specialHoliday_ot_rate * $specialHolidayOTmin,2);
										//echo $regHolidayOTmin . " ";


   										//
   										$rd_regularHolidayOTmin = round($attendance_ot_class->getOvertimeRDRegularHolidayOtToRunningBalance($row->emp_id)/60,2); // 90/60 = 1.5
   										$rdRegularHoliday_ot_rate = round($hourly_rate * 2.6,2);
										$running_rdRegularHoliday_ot_amount = round($rdRegularHoliday_ot_rate * $rd_regularHolidayOTmin,2); 
										//echo $running_specialHoliday_ot_amount . " ";
										//echo $regular_ot_rate;
										//echo $running_regular_ot;

										$rd_specialHolidayOTmin = round($attendance_ot_class->getOvertimeRDSpecialHolidayOtRunningBalance($row->emp_id)/60,2); // 90/60 = 1.5
										//echo $rd_specialHolidayOTmin;
										$rdSpecialHoliday_ot_rate = round($hourly_rate + ($hourly_rate * .6) ,2);
										$running_rdSpecialHoliday_ot_amount = round($rdSpecialHoliday_ot_rate * $rd_specialHolidayOTmin,2); 

										// getOvertimeRestdayOtRunningBalance
										$restdayOTmin = round($attendance_ot_class->getOvertimeRestdayOtRunningBalance($row->emp_id)/60,2); // 90/60 = 1.5
										$rd_ot_rate = round($hourly_rate + ($hourly_rate * .3),2);
										//echo $restdayOTmin . " ";
										$running_rd_ot_amount = round($rd_ot_rate * $restdayOTmin,2);

										//echo $running_rd_ot_amount . " ";
										
										//echo $running_rdSpecialHoliday_ot_amount;

										$running_balance = ($daily_rate * $present) + $running_regular_ot + $running_regHoliday_ot + $running_specialHoliday_ot_amount + $running_rdRegularHoliday_ot_amount + $running_rdSpecialHoliday_ot_amount + $basicCutOffPay + $allowanceCutOffPay;


										//echo $running_balance . " " . $tardiness;
										$running_balance -= $tardiness;

										$running_balance += $incentives;


										//echo $daily_rate . "<br/>";

										//echo $tardiness . "<br/>";
										echo number_format($running_balance,2) . "<br/>";


										// rest day ot
										// 

									?>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				<?php
					}
				?>

			<!-- for body -->
			<?php
				$events_class = new Events;
				$events_class->getAllEvents();

				echo '<div class="holiday-info">';
					$holiday_class = new Holiday;
					$holiday_class->regHolidayToEvent();

					$holiday_class = new Holiday;
					$holiday_class->specialNonWorkingDaysHolidayToEvent();
				echo '</div>';

			?>
				</div> <!-- end of row -->
			</div> <!-- end of container-fluid -->



			
		</div> <!-- end of content -->

		<!--<div class="footer">
			<img src="img/logo/lloyds logo.png" style="width:15px;"/> <strong><small>Copyright <span class="glyphicon glyphicon-copyright-mark"></span> 2017 | LFC HR & Payroll System | V1.0</small></strong>
		</div> -->

		
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