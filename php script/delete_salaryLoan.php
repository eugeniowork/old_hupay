<?php
session_start();
include "../class/connect.php";
include "../class/salary_loan_class.php";
include "../class/emp_information.php";


if (isset($_POST["delete_salaryLoanId"])){
	$salary_loan_id = $_POST["delete_salaryLoanId"];
	
	$salary_loan_class = new SalaryLoan;
	$emp_info_class = new EmployeeInformation;

	$row = $salary_loan_class->getInfoBySalaryLoanId($salary_loan_id);

	$emp_id = $row->emp_id;

	$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);

	$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

	$salary_loan_class->deleteSalaryLoan($salary_loan_id);

	$_SESSION["success_delete_salary_loan"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> <b>Salary Loan</b> of <b>$fullName</b> is successfully deleted.</center>";

	header("Location:../salary_loan.php");


}

else {
	header("Location:../MainForm.php");
}

?>