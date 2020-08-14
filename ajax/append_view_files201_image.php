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
					
		<div class="modal-header" style="background-color:#1d8348;">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-eye-open' style='color:#fff'></span>&nbsp;<span id="view_header_files201_image"><?php  echo $row->file_name; ?></span></h5>
		</div> 
		<div class="modal-body" id="">
			<div class="container-fluid">
				<img src="<?php echo $row->file_path; ?>" alt="201 Files image" class="custom-img-files201"/>
			</div>
		</div> 
		<div class="modal-footer" style="padding:5px;">
			<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
		</div>
<?php
	} // end of else
}

else {
	header("Location:../MainForm.php");
}



?>