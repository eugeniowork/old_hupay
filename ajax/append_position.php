<?php
include "../class/connect.php";
include "../class/position_class.php";

if (isset($_POST["department_id"])){
	$dept_id = $_POST["department_id"];
	$position_class = new Position;
	$position_class->getPositionInfo($dept_id);
}
else {
	header("Location:../Mainform.php");
}

?>