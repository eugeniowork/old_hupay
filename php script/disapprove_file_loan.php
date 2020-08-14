<?php
	session_start();
	include "../class/connect.php";
	include "../class/emp_loan_class.php";

	
	if (isset($_POST["file_loan_id"])){
		$file_loan_id = $_POST["file_loan_id"];

		//$emp_id = ;
		// reference format RF020190625-1

		$emp_loan_class = new EmployeeLoan;

		$row = $emp_loan_class->getFileLoanInfoById($file_loan_id);
		$ref_no = $row->ref_no;

		$row_emp = $emp_loan_class->getEmpInfoByRow($row->emp_id);

		$emp_loan_class->disapproveFileLoanById($file_loan_id);




		$_SESSION["file_success_emp_loan"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> You successfully disapprove file loan of ".$row_emp->Firstname . " " . $row_emp->Lastname." for reference number <b>$ref_no</b>.</center>";


		//echo $amount . " " . $loan_type	 . " " . $purpose;
		header("Location:../file_loan.php");
	}

	else {
		header("Location:../MainForm.php");
	}



?>