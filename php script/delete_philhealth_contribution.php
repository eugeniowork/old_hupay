<?php
session_start();
include "../class/connect.php";
include "../class/Philhealth_Contribution.php";

if (isset($_SESSION["delete_philhealth_contribution"])){
	$philhealth_contrib_id = $_SESSION["delete_philhealth_contribution"];

	$philhealth_contrib_class = new Philhealth_Contribution;
	$philhealth_contrib_class->deleteContributionInfo($philhealth_contrib_id);

	$_SESSION["success_msg_del_sss_contrib"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The <b>Contribution</b> is successfully deleted</center>";
	header("Location:../philhealthContributionTable.php");

}

else {
	header("Location:../MainForm.php");
}

?>