<?php
session_start();
include "../class/connect.php";
include "../class/pagibig_loan_class.php";
include "../class/emp_information.php";
include "../class/date.php";
include "../class/money.php";


if (isset($_POST["pagibig_loan_id"])){
	$pagibig_loan_id = $_POST["pagibig_loan_id"];

	//echo $pagibig_loan_id;

	$pagibig_loan_class = new PagibigLoan;
	$emp_info_class = new EmployeeInformation;
	$date_class = new date;
	$money_class = new Money;

    if ($pagibig_loan_class->checkExistPagibigLoanUpdate($pagibig_loan_id) == 1){
		$row = $pagibig_loan_class->getInfoByPagibigLoanId($pagibig_loan_id);
		$row_emp = $emp_info_class->getEmpInfoByRow($row->emp_id);


		// values from db
		if ($row_emp->Middlename == ""){
			$full_name = $row_emp->Lastname . ", " . $row_emp->Firstname;
		}
		else {
			$full_name = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
		}

		$dateFrom = $date_class->dateDefault($row->dateFrom);
		$dateTo = $date_class->dateDefault($row->dateTo);
		$amountLoan = $row->amountLoan;
		$deduction = $row->deduction;
		$remainingBalance = $row->remainingBalance;

?>
		
		<form class="form-horizontal" action="" id="form_adjustPagibigLoan" method="post">
 			<div class="form-group">
				<label class="control-label col-sm-4"><b>Employee Name:</b></label>
				<div class="col-sm-6 txt-pagibig-loan" style="margin-right:-15px;">
					<input type="text" class="form-control" name="adjust_empName" value="<?php echo $full_name; ?>" placeholder="Employee Name ..." id="input_payroll" required="required"/>
				</div>
				<!-- <label class="col-sm-1 control-label"><a href="#" id="choose_employee_pagibig_loan">Choose</a></label> -->
			</div>
			
			<div class="form-group">
				<label class="control-label col-sm-3 col-sm-offset-1"><b>Date of Payment:</b></label>
				<div class="col-sm-6 txt-pagibig-loan">
					<input type="text" class="form-control" placeholder="Date of Payment ..." name="adjust_datePayment" required="required"/>
				</div>
			</div>


			<div class="form-group">
				<label class="control-label col-sm-4"><b>Outstanding Balance:</b></label>
				<div class="col-sm-6 txt-pagibig-loan">
					<input type="text" class="form-control" placeholder="Outstranding Balance ..." id="input_payroll" value="<?php echo $remainingBalance; ?>" name="adjust_remainingBalance" required="required"/>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-4"><b>Cash Payment:</b></label>
				<div class="col-sm-6 txt-pagibig-loan">
					<input type="text" class="form-control" placeholder="Cash Payment ..." id="float_only" value="" name="adjust_cashPayment" required="required"/>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-4"><b>New Outstanding Balance:</b></label>
				<div class="col-sm-6 txt-pagibig-loan">
					<input type="text" class="form-control" placeholder="New Outstanding Remaining Balance..." id="input_payroll" value="" name="adjust_newRemainingBalance" required="required"/>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-4"><b>Remarks:</b></label>
				<div class="col-sm-6 txt-pagibig-loan">
					<textarea class="form-control" placeholder="Remarks ..." id="" name="adjust_remarks" required="required" rows="4"/></textarea>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-6">
					<input type="submit" value="Adjust" id="adjustPagibigLoan" class="btn btn-primary btn-sm"/>
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


				$("input[name='adjust_datePayment']").dcalendarpicker(); //

			});

		</script>


		
<?php
	} // end of else if

	else {
		echo "Error";
	}
}

else {
	header("Location:../MainForm.php");
}

?>