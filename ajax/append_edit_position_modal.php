<?php
session_start();
include "../class/connect.php";
include "../class/position_class.php";

if (isset($_POST["position_id"])) {
	$position_id = $_POST["position_id"];
	$position_class = new Position;
	// check if position id exist bka kc inedit sa inspect element eh
	if ($position_class->checkExistPositionId($position_id) != 0){

		$_SESSION["position_id_update"] = $position_id;

	?>


	<form class="form-horizontal" action="php script/update_position_script.php" method="post">
		<div class="container-fluid">								
			<label class="control-label"><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Position</label>
			<div class="input-group">
				<input type="text" name="position" value="<?php echo $position_class->getPositionById($position_id)->Position; ?>" autocomplete="off" placeholder="Enter Department" class="form-control" required="required">
			 	<span class="input-group-btn">
			    	<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-pencil"></span></button>
			    </span>
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

