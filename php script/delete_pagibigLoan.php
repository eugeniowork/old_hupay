<?php
session_start();
include "../class/connect.php";
include "../class/pagibig_loan_class.php";
include "../class/emp_information.php";


if (isset($_POST["delete_pagibigLoanId"])){
	$pagibig_loan_id = $_POST["delete_pagibigLoanId"];
	
	$pagibig_loan_class = new PagibigLoan;
	$emp_info_class = new EmployeeInformation;

	$row = $pagibig_loan_class->getInfoByPagibigLoanId($pagibig_loan_id);

	$emp_id = $row->emp_id;

	$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);

	$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

	$pagibig_loan_class->deletePagibigLoan($pagibig_loan_id);

	$_SESSION["success_delete_pagibig_loan"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> <b>Pag-ibig Loan</b> of <b>$fullName</b> is successfully deleted.</center>";

	header("Location:../pagibig_loan.php");


}

else {
	header("Location:../MainForm.php");
}

?>