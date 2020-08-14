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

?>
	<div class="container-fluid">
		<form class="form-horizontal" action="" method="post" id="form_updateDescription_files201">
			<div class="form-group">
				<label>Description:</label>
				<input type="text" class="form-control" name="description" placeholder="Input Description" value="<?php echo $row->name; ?>" required="required"/>
			</div>
			<div class="form-group">
				<input type="submit" value="Save" id="save_files_201_btn" class="btn btn-primary pull-right"/>
			</div>
		</form>
	</div>
<?php
	} // end of else
}

else {
	header("Location:../MainForm.php");
}



?>