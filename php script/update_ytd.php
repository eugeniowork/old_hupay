<?php
session_start();
include "../class/connect.php";
include "../class/year_total_deduction_class.php";


if (isset($_POST["update_ytdId"])){
	$ytd_id = $_POST["update_ytdId"];

	$ytdGross = $_POST["update_ytdGross"];
	$ytdAllowance = $_POST["update_ytdAllowance"];
	$ytdTax = $_POST["update_ytdTax"];

	$ytd_class = new YearTotalDeduction;
	

	// if no changes
	if ($ytd_class->sameYtdInfo($ytd_id,$ytdGross,$ytdAllowance,$ytdTax) == 1){
		$_SESSION["update_ytd_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No changes was made, No updates were taken</center>";
	}

	// if success
	else {

		$ytd_class->updateYtd($ytd_id,$ytdGross,$ytdAllowance,$ytdTax);
		$_SESSION["update_ytd_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The YTD Information of <b>$empName</b> is successfully updated.</center>";
	}


	header("Location:../year_total_deduction.php");

}

else {
	header("Location:../MainForm.php");
}

?>