<?php
session_start();
include "../class/connect.php";
include "../class/201_files_class.php";

if (isset($_POST["201_files_id"])){
	$files201_id = $_POST["201_files_id"];

	$files201_class = new files201_class;

	$row = $files201_class->getInfoById($files201_id);

	$file_name = $row->file_name;
	$file_path = $row->file_path;

	// for unlink
	unlink("../".$file_path);

	// for deletion
	$files201_class->deleteFiles201Images($files201_id);

	$_SESSION["delete_files201_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The Image for <b>$file_name</b> is successfully deleted.</center>";

	header("Location:../view_emp_profile.php");



	


}
else {
	header("Location:../MainForm.php");
}


?>