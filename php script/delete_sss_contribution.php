<?php
session_start();
include "../class/connect.php";
include "../class/SSS_Contribution.php";

if (isset($_SESSION["delete_philhealth_contribution"])){
	$sss_contrib_id = $_SESSION["delete_philhealth_contribution"];

	$ss_contrib_class = new SSS_Contribution;
	$ss_contrib_class->deleteContributionInfo($sss_contrib_id);

	$_SESSION["success_msg_del_sss_contrib"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The <b>Contribution</b> is successfully deleted</center>";
	header("Location:../sssContributionTable.php");

}

else {
	header("Location:../MainForm.php");
}

?>