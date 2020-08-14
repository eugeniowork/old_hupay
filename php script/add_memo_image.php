<?php
session_start();
include "../class/connect.php";
include "../class/memorandum_class.php";
include "../class/image_class.php";

if (isset($_FILES['memo_upload_img']['name']) && isset($_POST["memo_id"])){

	$memo_id = $_POST["memo_id"];
	$image_class = new Image;


	//echo $count;
	//echo $count;




	$memo_class = new Memorandum;
	
	$naming_count = 0;
	if ($memo_class->memoImgCount($memo_id) != 0){
		$naming_count = $memo_class->memoImgLastId($memo_id);
	} // $recipient,$emp_id,$dept_id,$memoFrom,$subject,$content,$dateCreated)

	$memo_last_id = $memo_class->memoLastId();

	//$num_files = 0;
	//if (isset($_FILES['memo_upload_img']['name'])) {
	$num_files = count($_FILES['memo_upload_img']['name']);
	//}

	//echo $num_files;

	$counter = 0;
	
	do {

		$counter++;



		for($i=0; $i<$num_files; $i++) {

			$naming_count++; // for increminting


		  //Get the temp file path
			$tmpFilePath = $_FILES['memo_upload_img']['tmp_name'][$i];
			$base_name = basename($_FILES["memo_upload_img"]["name"][$i]);
			$file_type = pathinfo($base_name,PATHINFO_EXTENSION);
			$file_name =  $memo_id . "_".$naming_count . "." . $file_type;
			//$file_name =  $emp_fullName . "_". $naming_count;


			//echo $file_name . "<br/>";

			
			$path = "../img/memo_image/";
			$newFilePath = $path . $memo_id . "_". $naming_count;



			move_uploaded_file($tmpFilePath, $newFilePath);

			// for inserting to database
			$db_path = "img/memo_image/" . $file_name;

			$memo_class->insertMemoImages($memo_id,$db_path);

	        
			$image_class->resize('1000',$newFilePath,$newFilePath); // for resizing



			//echo $file_type;
		}
				

		

		//echo $_POST["to".$counter] . "<br/>";

		//echo $counter;
		
	}while($count > $counter);



	$_SESSION["add_memo_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> Memo image is sucessfully saved</center>";
	

	header("Location:../memorandum.php");








}

else {
	header("Location:../MainForm.php");
}


?>