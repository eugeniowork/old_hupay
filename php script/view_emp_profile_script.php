<?php
	session_start();
	$_SESSION["view_emp_id"] = $_POST["emp_id"];
	header("Location:../view_emp_profile.php");
?>