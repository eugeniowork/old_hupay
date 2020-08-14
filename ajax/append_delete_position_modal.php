<?php
session_start();
include "../class/connect.php";
include "../class/position_class.php";

if (isset($_POST["position_id"])) {
	$position_id = $_POST["position_id"];
	$position_class = new Position;

	// check if position id exist bka kc inedit sa inspect element eh
	if ($position_class->checkExistPositionId($position_id) != 0){
		$_SESSION["position_del_id"] = $position_id;

	?>

	<form class="" action="" method="post">
		<div class="container-fluid">
			<div style="text-align:center;">					
				<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to delete the <b><?php echo $position_class->getPositionById($position_id)->Position; ?> Position?</b></span>
			</div>						
		</div>
	</form>

	<?php
	} // end of if
	else {
		echo "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is an error during getting of data</center>";
	}
}

else {
	header("Location:../Mainform.php");
}



?>
