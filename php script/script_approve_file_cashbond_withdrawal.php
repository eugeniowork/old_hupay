<?php
session_start();
include "../class/connect.php";
include "../class/cashbond_class.php";
include "../class/date.php";
include "../class/emp_information.php";
include "../class/money.php";
include "../class/audit_trail_class.php";

if (isset($_POST["file_cashbond_withdrawal_id"]) && isset($_POST["approve_stats"])){
	$file_cashbond_withdrawal_id = $_POST["file_cashbond_withdrawal_id"];
	$approve_stats = $_POST["approve_stats"];

	$cashbond_class = new Cashbond;
	$date_class = new date;
	$emp_info_class = new EmployeeInformation;
	$money_class = new Money;

	$row = $cashbond_class->getInfoByFileCashbondWithdrawalId($file_cashbond_withdrawal_id); // for getting information sa file ng cashbond withdrawal
	$row_cashbond = $cashbond_class->getInfoByEmpId($row->emp_id);

	$totalCashbond = $row_cashbond->totalCashbond - $row->amount_withdraw;


	$approver_id = $_SESSION["id"];
	
	$dateApprove = $date_class->getDate();
	//echo $file_cashbond_withdrawal_id . "<br/>";
	//echo $approve_stats;
	// if approve
	if ($approve_stats == "Approve"){

		$stats = '1';


		// for getting first the interest earn
		// kukunin natin lahat ng cashbond emp history or SOA
		$row_ch = $cashbond_class->getLastCashbondHistory($row->emp_id);
		$previous_ending_balance_amount = $row_ch->cashbond_balance;

		$date1 = $row_ch->posting_date;
		$date1= date_create($date1);

		$date2 = date("Y-m-d");
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
		$interest = round(($days) * $previous_ending_balance_amount * ($percentage/360),2);
		
		//echo $interest;

		$cashbond_class->approveFileCashbondWithdrawal($file_cashbond_withdrawal_id,$approver_id,$stats,$dateApprove);

		//$_SESSION["su"]

		// if approve we need to deduct and update the cashbond amount
		$cashbond_class->updateTotalCashbondApproveCashWithdrawal($row->emp_id,($totalCashbond + $interest));


		// for inserting to cashbond history
		$cashbond_class->insertCashbondHistoryAfterWithdraw($row->emp_id,'0','',$interest,$dateApprove,$row->amount_withdraw,($totalCashbond + $interest),'0',$dateApprove);
	}
	// if disapprove
	else {
		$stats = '2';
		$cashbond_class->approveFileCashbondWithdrawal($file_cashbond_withdrawal_id,$approver_id,$stats,$dateApprove);
	}
	
	$row_emp = $emp_info_class->getEmpInfoByRow($row->emp_id);

	$filer_name = $row_emp->Firstname . " " . $row_emp->Lastname;

	$_SESSION["success_approve_file_cashbond_withdrawal"] = "<center><span class='glyphicon glyphicon-ok' style='color: #229954 '></span> You successfully <b>".$_POST["approve_stats"]."</b> of <b>$filer_name</b> amounting <b>".$money_class->getMoney($row->amount_withdraw)."</b></center>";


	$amount_withdraw = $row->amount_withdraw;
	$module = "Approve File Cashbond Withdrawal";
	$task_description = $approve_stats." File Cashbond Withdrawal amounting of Php " . $amount_withdraw . "";
	
	$dateTime = $date_class->getDateTime();


	// this facility is for audit trail
	$audit_trail_class = new AuditTrail;
	$audit_trail_class->insertAuditTrail($row->emp_id,$approver_id,'0',$module,$task_description,$dateTime);
	//insertAuditTrail($file_emp_id,$approve_emp_id,$involve_emp_id,$module,$task_description,$dateCreated)

	header("Location:../cashbond.php");
}

else {
	header("Location:../MainForm.php");
}

?>