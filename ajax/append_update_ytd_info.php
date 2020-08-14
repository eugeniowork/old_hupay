<?php
include "../class/connect.php";
include "../class/year_total_deduction_class.php";
include "../class/emp_information.php";
include "../class/minimum_wage_class.php";
// ytd_id

if (isset($_POST["ytd_id"])){
	$ytd_id = $_POST["ytd_id"];

	$ytd_class = new YearTotalDeduction;

	$emp_info_class = new EmployeeInformation;
	$min_wage_class = new MinimumWage;

	$min_wage = $min_wage_class->getMinimumWage();

	if ($ytd_class->checkExistYtd($ytd_id) == 1){
		$row = $ytd_class->getInfoByYtdId($ytd_id);
		$row_emp = $emp_info_class->getEmpInfoByRow($row->emp_id);


		// values from db
		if ($row_emp->Middlename == ""){
			$full_name = $row_emp->Lastname . ", " . $row_emp->Firstname;
		}
		else {
			$full_name = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
		}

		$ytdGross = $row->ytd_Gross;
		$ytdAllowance = $row->ytd_Allowance;
		$ytdTax = $row->ytd_Tax;
		$year = $row->Year;

		




?>
	<form class="form-horizontal" action="" id="form_updateYtd" method="post">
		<div class="form-group">
			<label class="control-label col-sm-3 col-sm-offset-1">Employee Name:</label>
			<div class="col-sm-6 txt-pagibig-loan" style="margin-right:-15px;">
				<input type="text" class="form-control" name="update_empName" value="<?php echo $full_name; ?>" placeholder="Employee Name ..." id="input_payroll" required="required"/>
			</div>
			<!-- <label class="col-sm-1 control-label"><a href="#" id="choose_employee_pagibig_loan">Choose</a></label> -->
		</div>
		<div class="form-group">
			<label class="control-label col-sm-3 col-sm-offset-1">YTD Gross:</label>
			<div class="col-sm-6 txt-pagibig-loan">
				<input type="text" class="form-control" placeholder="YTD Gross ..." id="float_only" value="<?php echo $ytdGross; ?>" name="update_ytdGross" required="required"/>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-3 col-sm-offset-1">YTD Allowance:</label>
			<div class="col-sm-6 txt-pagibig-loan">
				<input type="text" class="form-control" placeholder="YTD Allowance ..." id="float_only" value="<?php echo $ytdAllowance; ?>" name="update_ytdAllowance" required="required"/>
			</div>
		</div>




		<div class="form-group">
			<label class="control-label col-sm-3 col-sm-offset-1">YTD W/Tax:</label>
			<div class="col-sm-6 txt-pagibig-loan">
				<?php

				     // if taxable
					if ($row_emp->Salary > $min_wage) {
				?>
					<input type="text" class="form-control" placeholder="YTD W/ Tax ..." id="float_only" value="<?php echo $ytdTax; ?>" name="update_ytdTax" required="required" />
				<?php
					} // end of if

					// if minimum
					else {

				?>
					<input type="text" readonly="readonly" class="form-control" placeholder="YTD W/ Tax ..." id="input_payroll" value="<?php echo $ytdTax; ?>" name="update_ytdTax" required="required" />
				<?php
					} // end of else

				?>
				
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-3 col-sm-offset-1">Year:</label>
			<div class="col-sm-6 txt-pagibig-loan" style="margin-right:-15px;">
				<input type="text" class="form-control" name="update_year" value="<?php echo $year; ?>" placeholder="Year ..." id="input_payroll" required="required"/>
			</div>
			<!-- <label class="col-sm-1 control-label"><a href="#" id="choose_employee_pagibig_loan">Choose</a></label> -->
		</div>

		<div class="form-group">
			<div class="col-sm-offset-6">
				<input type="submit" value="Update" id="updateYTDinfo" class="btn btn-primary btn-sm"/>
			</div>
		</div>

		

		<div class="form-group">
			<div id="update_error_msg">
			</div>
		</div>
	</form>


	<script>
			$(document).ready(function(){
				//  input_payroll
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


				$("input[name='update_dateFrom']").dcalendarpicker(); //
				$("input[name='update_dateTo']").dcalendarpicker(); //


				// FOR DECIMAL POINT
				$("input[id='float_only']").keydown(function (e) {


				// for decimal pint
				if (e.keyCode == "190") {
				    if ($(this).val().replace(/[0-9]/g, "") == ".") {
				        return false;  
				    }
				}


				// Allow: backspace, delete, tab, escape, enter , F5
				if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116,190]) !== -1 ||
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
				$("input[id='float_only").on("paste", function(){
				  return false;
				});

			});

		</script>

<?php
	}
	else {
		echo "Error";
	}



}

else {
	header("../MainForm.php");
}

// checkExistYtd



?>