<?php
include "../class/connect.php";
include "../class/BIR_Contribution.php";



if (isset($_POST["adjustGrossIncome"]) && isset($_POST["taxCode"])){
	$adjustGrossIncome = $_POST["adjustGrossIncome"];
//	$emp_id = $_POST["emp_id"];
	$taxCode = $_POST["taxCode"];

	$bir_contrib_class = new BIR_Contribution;

	//echo $adjustGrossIncome . " " . $emp_id . " " . $taxCode;

	// ibig sabihin wlang tax
	if ($taxCode == ""){
		$tax = 0;
	}

	// ibig sabihin with tax
	else {

		if ($taxCode == "S" || $taxCode == "ME") {
			$status = "S/ME";
		}
		else if ($taxCode == "S1" || $taxCode == "ME1") {
			$status = "ME1/S1";
		}

		else if ($taxCode == "S2" || $taxCode == "ME2") {
			$status = "ME2/S2";
		}

		else if ($taxCode == "S3" || $taxCode == "ME3") {
			$status = "ME3/S3";
		}

		else if ($taxCode == "S4" || $taxCode == "ME4") {
			$status = "ME4/S4";
		}

		$tax = round($bir_contrib_class->getTax($status,$adjustGrossIncome),2);
	}

	echo $tax;

	//$tax = $bir_contrib_class->getTax($status,$taxableIncome);

}

else {
	header("Location:../MainForm.php");
}


?>