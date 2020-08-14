<?php
	session_start();
	include "../class/connect.php";
	include "../class/emp_loan_class.php";

	
	if (isset($_POST["update_amount"]) && isset($_POST["update_loan_type"]) && isset($_POST["update_program"]) && isset($_POST["update_purpose"])){
		$amount = $_POST["update_amount"];
		$loan_type = $_POST["update_loan_type"];
		$program = $_POST["update_program"];
		$purpose = $_POST["update_purpose"];
		$file_loan_id = $_GET["file_loan_id"];
		//$emp_id = ;
		// reference format RF020190625-1

		$emp_loan_class = new EmployeeLoan;

		$row = $emp_loan_class->getFileLoanInfoById($file_loan_id);
		$ref_no = $row->ref_no;


		$emp_loan_class->updateFileLoanById($file_loan_id,$amount,$loan_type,$program,$purpose);



		$_SESSION["file_success_emp_loan"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> You successfully updated your file loan for reference number <b>$ref_no</b>.</center>";


		//echo $amount . " " . $loan_type	 . " " . $purpose;
		header("Location:../file_loan.php");
	}

	else {
		header("Location:../MainForm.php");
	}



?>