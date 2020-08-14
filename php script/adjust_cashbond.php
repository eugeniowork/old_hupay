<?php
session_start();
include "../class/connect.php";
include "../class/cashbond_class.php";
include "../class/date.php";


if (isset($_POST["cashbond_id"]) && isset($_POST["adjust"]) && isset($_POST["remarks"])){
	$cashbond_id = $_POST["cashbond_id"];
	$adjust	 = $_POST["adjust"];
	$remarks = $_POST["remarks"];

	$cashbond_class = new Cashbond;
	$date_class = new date;


	$row = $cashbond_class->getInfoByCashbondId($cashbond_id);

	$current_cashbond = $row->totalCashbond;

	
	$new_cashbond = $current_cashbond + $adjust;

	if ($new_cashbond < 0){
		$_SESSION["update_error_msg_cashbond"] = "<center><span class='glyphicon glyphicon-remove' style='color: #c0392b'></span>&nbsp;<b>New Current Value</b> must be not negative.</center>";
	}

	else {

		$emp_id = $row->emp_id;
		$cashbond_deposit = $adjust;

		// dito ung previous balance
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

		$posting_date = $date_class->getDate();
		$amount_withdraw = 0;
		$cashbond_balance = $new_cashbond;
		$interest_rate = 3;
		$dateCreated = $date_class->getDate();

		$cashbond_class->insertCashbondHistoryAfterWithdraw($emp_id,$cashbond_deposit,$remarks,$interest,$posting_date,$amount_withdraw,$cashbond_balance,$interest_rate,$dateCreated);
		
		$cashbond_class->updateTotalCashbondApproveCashWithdrawal($emp_id,$new_cashbond);


		$_SESSION["update_success_msg_cashbond"] = "<center><span class='glyphicon glyphicon-ok' style='color: #229954 '></span>&nbsp;<b>Cashbond</b> is successfully adjust.</center>";
	}



	header("Location:../cashbond.php");

	//echo $cashbond_id . " " . $adjust;

}
else {
	header("Location:../MainForm.php");
}

?>