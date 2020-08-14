<?php
session_start();
include "../class/connect.php";
include "../class/SSS_Contribution.php";
include "../class/date.php";

if (isset($_POST["sss_contrib_id"])) {
	$sss_contrib_id = $_POST["sss_contrib_id"];

	$sss_contrib_class = new SSS_Contribution;

	// if exist
	if ($sss_contrib_class->existContributionId($sss_contrib_id) == 0) {
		echo "Error";	
	}
	// if success
	else {
		$row = $sss_contrib_class->getInfoByContribId($sss_contrib_id);
		$_SESSION["delete_philhealth_contribution"] = $sss_contrib_id;

		if ($row->compensationFrom == "0"){
			$compensationFrom = "Php 0.00";
		}
		else {
			$compensationFrom = $sss_contrib_class->getMoney($row->compensationFrom);	
		}

	
?>
	<div class="container-fluid">
		<div style="text-align:center;">					
			<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to delete the <b><?php echo $compensationFrom ." - " . $sss_contrib_class->getMoney($row->compensationTo); ?></b> range of compensation with the contribution <b><?php echo $sss_contrib_class->getMoney($row->Contribution); ?></b>?</span>
		</div>						
	</div>


	<script>
		$(document).ready(function(){
			

	    });
	</script>

<?php
	} // end if else
}
else {
	header("Location:../Mainform.php");
}
?>