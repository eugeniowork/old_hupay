<?php
session_start();
include "../class/connect.php";
include "../class/201_files_class.php";


if (isset($_POST["description"]) && isset($_POST["files201_id"])){
	$description = $_POST["description"];
	$files201_id = $_POST["files201_id"];

	$files201_class = new files201_class;

	if ($files201_class->noChangesUpdateDescription($files201_id,$description) == 1){
		$_SESSION["update_files201_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No changes was made, No updates were taken</center>";
	}

	// if success
	else {

		$row = $files201_class->getInfoById($files201_id);

		$old_description = $row->file_name; // for getting old description

		$files201_class->updateFiles201Description($files201_id,$description);

		$_SESSION["update_files201_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The Description of <b>$old_description</b> is successfully updated to <b>$description</b>.</center>";
	}

	header("Location:../view_emp_profile.php");

}
else {
	header("Location:../MainForm.php");
}


?>