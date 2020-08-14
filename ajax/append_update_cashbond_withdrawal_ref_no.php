<?php
	
	include "../class/connect.php";
	include "../class/cashbond_class.php";
		
	if (isset($_POST["id"]) && isset($_POST["ref_no"])){

		$id = $_POST["id"];
		$ref_no	 = $_POST["ref_no"];


		$cashbond_class = new Cashbond;
		
		$cashbond_class->updateCashbondWithdrawalRefNo($id,$ref_no);

		echo $ref_no;

	}

	else {
		header("Location:../MainForm.php");
	}
	

?>