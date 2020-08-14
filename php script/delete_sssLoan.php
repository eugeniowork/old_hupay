<?php
session_start();
include "../class/connect.php";
include "../class/sss_loan_class.php";
include "../class/emp_information.php";


if (isset($_POST["delete_sssLoanId"])){
	$sss_loan_id = $_POST["delete_sssLoanId"];
	
	$sss_loan_class = new SSSLoan;
	$emp_info_class = new EmployeeInformation;

	$row = $sss_loan_class->getInfoBySSSLoanId($sss_loan_id);

	$emp_id = $row->emp_id;

	$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);

	$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

	$sss_loan_class->deleteSSSLoan($sss_loan_id);

	$_SESSION["success_delete_sss_loan"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> <b>SSS Loan</b> of <b>$fullName</b> is successfully deleted.</center>";

	header("Location:../sss_loan.php");


}

else {
	header("Location:../MainForm.php");
}

?>