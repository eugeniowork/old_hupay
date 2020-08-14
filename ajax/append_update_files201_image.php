<?php
include "../class/connect.php";
include "../class/201_files_class.php";

if (isset($_POST["files201_id"])){
	$files201_id = $_POST["files201_id"];
	
	$files201_class = new files201_class;

	// check if not exist
	if ($files201_class->checkExistFiles201Id($files201_id) == 0){
		echo "Error";
	}

	// if success
	else {
		$row = $files201_class->getInfoById($files201_id);

		echo $row->file_name;

?>

<?php
	} // end of else
}

else {
	header("Location:../MainForm.php");
}



?>