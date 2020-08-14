<?php
	session_start();
	include "../class/connect.php";
	include "../class/emp_loan_class.php";
	include "../class/payroll_notif_class.php";
	include "../class/emp_information.php";
	include "../class/date.php";
	
	if (isset($_POST["amount"]) && isset($_POST["loan_type"]) && isset($_POST["program"]) && isset($_POST["purpose"])){
		$amount = $_POST["amount"];
		$loan_type = $_POST["loan_type"];
		$program = $_POST["program"];
		$purpose = $_POST["purpose"];
		//$emp_id = ;
		// reference format RF020190625-1

		$emp_loan_class = new EmployeeLoan;
		$date_class = new date;

		$exist_count = $emp_loan_class->checkExistFile();

		$ref_no = "";
		if ($exist_count == 0){
			$ref_no = "RF0" . date("Ymd") . "-1";
		}

		else {
			$ref_no = "RF0" . date("Ymd") . "-" . ($emp_loan_class->lastID() + 1);
		}


		// for insert
		$emp_loan_class->insertFileLoan($_SESSION["id"],$ref_no,$amount,$loan_type,$program,$purpose);


		$loan_type_str = "Salary Loan";
		if ($loan_type == 2){
			$loan_type_str = "SIMKIMBAN";
		}

		else if ($loan_type == 2){
			$loan_type_str = "Employee Benifit Program Loan";
		}



		$_SESSION["file_success_emp_loan"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> You successfully file a loan for <b>$loan_type_str</b>.</center>";



		/*$notif_type = "File Loan";
		$readStatus = '0';

		// insert to payroll notification
		$payroll_notif_class = new PayrollNotif;
		$emp_info_class = new EmployeeInformation;
		$emp_values = explode("#",$emp_info_class->getEmpIdRoleAdmin());
		$admin_count = $emp_info_class->getAdminCount();
		$counter = 0;
		do {
			$emp_id = $emp_values[$counter];
			//echo $emp_id . "<br/>";
			$payroll_notif_class->insertPayrollNotif('0',$emp_id,'0','0','0',$notif_type,'',$readStatus,$date_class->getDateTime());
			$counter++;

			// for update agad notif
			$payroll_notif_class->updateRefNoLastPayrollNotif($ref_no);

		} while($admin_count > $counter);


		// insert naman sa HR ang role
		$emp_values = explode("#",$emp_info_class->getEmpIdRoleHR());
		$hr_count = $emp_info_class->getHRCount();
		$counter = 0;
		do {
			$emp_id = $emp_values[$counter];
			//echo $emp_id . "<br/>";
			$payroll_notif_class->insertPayrollNotif('0',$emp_id,'0','0','0',$notif_type,'',$readStatus,$date_class->getDateTime());
			$counter++;

			// for update agad notif
			$payroll_notif_class->updateRefNoLastPayrollNotif($ref_no);

		} while($hr_count > $counter);*/



		//echo $amount . " " . $loan_type	 . " " . $purpose;
		header("Location:../file_loan.php");
	}

	else {
		header("Location:../MainForm.php");
	}



?>