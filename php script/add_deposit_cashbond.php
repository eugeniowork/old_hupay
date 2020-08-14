<?php
	session_start();
	include "../class/connect.php";
	include "../class/cashbond_class.php";
	include "../class/emp_information.php";
	include "../class/date.php";
	include "../class/audit_trail_class.php";
	
	//echo "HELLO WORLD!";	
	
	if (isset($_POST["cashbond_id"]) && isset($_POST["deposit"]) && isset($_POST["remarks"])){

		$cashbond_id = $_POST["cashbond_id"];
		$deposit = $_POST["deposit"];
		$remarks = $_POST["remarks"];

		$cashbond_class = new Cashbond;
		$emp_info_class = new EmployeeInformation;
		$date_class = new date;

		$row = $cashbond_class->getInfoByCashbondId($cashbond_id);
		$totalCashbond = $row->totalCashbond;

		$emp_id = $row->emp_id;

		$row_ch = $cashbond_class->getLastCashbondHistory($emp_id);
		$previous_ending_balance_amount = $row_ch->cashbond_balance;

		$date1 = $row_ch->posting_date;
		//echo $date1 . " ";
		$date1= date_create($date1);

		$date2 = date("Y-m-d");
		//echo $date2;
		$date2= date_create($date2);

		$percentage = .05;
		if ($previous_ending_balance_amount >= 30000){
			$percentage = .07;
		}


		//echo $row_ch->posting_date;
		$diff =date_diff($date1,$date2);
		$wew =  $diff->format("%R%a");
		$days = str_replace("+","",$wew);

		//echo $days;

		//echo $days;
		$interest = round(($days) * $previous_ending_balance_amount * ($percentage/360),2);

		// if approve we need to deduct and update the cashbond amount
		$cashbond_class->updateTotalCashbondApproveCashWithdrawal($row->emp_id,($totalCashbond + $deposit + $interest));


		// for inserting to cashbond history
		$cashbond_class->insertCashbondHistoryAfterWithdraw($row->emp_id,$deposit,$remarks,$interest,date("Y-m-d"),0,($totalCashbond + $deposit + $interest),'0',date("Y-m-d"));



		$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);

		$filer_name = $row_emp->Firstname . " " . $row_emp->Lastname;

		$_SESSION["success_approve_file_cashbond_withdrawal"] = "<center><span class='glyphicon glyphicon-ok' style='color: #229954 '></span> You successfully add deposit of <b>$filer_name</b> amounting <b>Php ".number_format($deposit,2)."</b></center>";


		$module = "Add Deposit";
		$task_description = "Add deposit amounting of Php " . $deposit . "";
		
		$dateTime = $date_class->getDateTime();
		$approver_id = $_SESSION["id"];

		// this facility is for audit trail
		$audit_trail_class = new AuditTrail;
		$audit_trail_class->insertAuditTrail($emp_id,$approver_id,'0',$module,$task_description,$dateTime);
		//insertAuditTrail($file_emp_id,$approve_emp_id,$involve_emp_id,$module,$task_description,$dateCreated)

		header("Location:../cashbond.php");

		//echo " " . $interest;


		//echo $cashbond_id . " " . $deposit . " " . $remarks;

		// first we need to do is to get the last cashbond

	}
	else {
		header("Locaton:../MainForm.php");
	}
?>