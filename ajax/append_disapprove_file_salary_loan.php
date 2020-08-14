<?php
session_start();
include "../class/connect.php";
include "../class/salary_loan_class.php";
include "../class/emp_information.php";

if(isset($_POST["file_salary_loan_id"])) {
	$file_salary_loan_id = $_POST["file_salary_loan_id"];

	$salary_loan_class = new SalaryLoan;
	// if edited in the inspect element and attendance notif id is not existed so error 
	if ($salary_loan_class->checkExistFileSalaryLoanId($file_salary_loan_id) == 0){
		echo "Error";
	}

	// if success
	else {

		$row = $salary_loan_class->getFileSalaryLoanById($file_salary_loan_id);

		$emp_info_class = new EmployeeInformation;

		$emp_id = $row->emp_id;

		$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);

		// values from db
		if ($row_emp->Middlename == ""){
			$full_name = $row_emp->Lastname . ", " . $row_emp->Firstname;
		}
		else {
			$full_name = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
		}
?>
	<form class="" action="" method="post" id="form_deleteSalaryLoan">
		<div class="container-fluid">
			<div style="text-align:center;">					
				<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to disapprove the <b>File Salary Loan</b> of <b><?php echo $full_name; ?></b>?</b></span>
			</div>						
		</div>
	</form>
<?php

	} // end of else
} // end of if
else {
	header("Location:../Mainform.php");
}

?>