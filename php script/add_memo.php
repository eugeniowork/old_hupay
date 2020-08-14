<?php
session_start();
include "../class/connect.php";
include "../class/memorandum_class.php";
include "../class/emp_information.php";
include "../class/date.php";
include "../class/department.php";
include "../class/image_class.php";
include "../class/audit_trail_class.php";

if (isset($_POST["optRecipient1"]) && isset($_POST["memoFrom"]) && isset($_POST["subject"]) && isset($_POST["content"])){

	$date_class = new date;
	$emp_info_class = new EmployeeInformation;
	$department_class = new Department;
	$image_class = new Image;

	$count = $_POST["count"];

	//echo $count;
	//echo $count;


	$current_date = $date_class->getDate();
	$from = $_SESSION["id"];
	$subject = $_POST["subject"];
	$content = $_POST["content"];

	$memo_class = new Memorandum;
	$memo_class->insertMemo($from,$subject,$content,$current_date); // $recipient,$emp_id,$dept_id,$memoFrom,$subject,$content,$dateCreated)

	$memo_last_id = $memo_class->memoLastId();

	$num_files = 0;
	if (isset($_FILES['memo_upload_img']['name'])) {
		$num_files = count($_FILES['memo_upload_img']['name']);
	}


	//echo $num_files;

	$counter = 0;
	$naming_count = 0;
	do {

		$counter++;

		//if ()
		$emp_id = "0";
		$dept_id = "0";
		$typeRecipient =  $_POST["optRecipient".$counter];
		


		 if ($typeRecipient != "") {

			 if ($typeRecipient == "Specific Employee"){
				if (isset($_POST["to".$counter])){
					$emp_id = $emp_info_class->getEmpIdByEmployeeName($_POST["to".$counter]);
					//echo $emp_id . "<br/>";
				}

			}

			else if ($typeRecipient == "Department"){
				if (isset($_POST["to".$counter])){
					$dept_id = $department_class->getDeptIdByDepartment($_POST["to".$counter]);
					//echo $dept_id . "<br/>";
				}
			}

			/*echo $memo_last_id . "<br/>";
			echo $typeRecipient . "<br/>";
			echo $emp_id . "<br/>";
			echo $dept_id . "<br/>";
			echo $current_date . "<br/>";*/

			// for inserting to multiple memo
			$memo_class->insertMemoMultiple($memo_last_id,$typeRecipient,$emp_id,$dept_id,$current_date);


			$date_time_created = $date_class->getDateTime();

			if ($typeRecipient == "All"){

				$emp_count = $emp_info_class->getCountActiveEmp();

				$emp_id_values = explode("#", $emp_info_class->getEmpIdAllActiveEmp());


				$memo_id = $memo_class->lastId();

				$emp_counter = 0;
				do {

					$notif_type = $subject . " to All";

					$emp_id = $emp_id_values[$emp_counter];
					

					$memo_class->insertMemoNotif($memo_id,$from,$emp_id,$notif_type,$date_time_created);
					$emp_counter++;
				}while($emp_count > $emp_counter);


				// for uploading memo images
				// Loop through each file



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




			}


			else if ($typeRecipient == "Specific Employee"){
				if (isset($_POST["to".$counter])){
					$emp_id = $emp_info_class->getEmpIdByEmployeeName($_POST["to".$counter]);
					$notif_type = $subject;
					
					$memo_id = $memo_class->lastId();

					$memo_class->insertMemoNotif($memo_id,$from,$emp_id,$notif_type,$date_time_created);
					//echo $emp_id . "<br/>";

					for($i=0; $i<$num_files; $i++) {

						$naming_count++; // for increminting

						echo "wew";

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
				}

			}

			else if ($typeRecipient == "Department"){
				if (isset($_POST["to".$counter])){
					$dept_id = $department_class->getDeptIdByDepartment($_POST["to".$counter]);

					$emp_count = $department_class->AllActiveEmpIdLinkDeptIdCount($dept_id) - 1;


					$emp_id_values = explode("#", $department_class->getAllActiveEmpIdLinkDeptId($dept_id));

					//echo $department_class->getAllActiveEmpIdLinkDeptId($dept_id);

					$memo_id = $memo_class->lastId();

					$emp_counter = 0;
					do {

						$notif_type = $subject . " to "  .$_POST["to".$counter];

						$emp_id = $emp_id_values[$emp_counter];
						
						//echo $emp_id . "<br/>";

						//echo $memo_id . " " . $from . " " .  $emp_id . " " . $notif_type . " " . $date_time_created . "<br/>";

						//insertMemoNotif($memo_id,$from_emp_id,$to_emp_id,$notif_type,$date_created);

						$memo_class->insertMemoNotif($memo_id,$from,$emp_id,$notif_type,$date_time_created);
						$emp_counter++;
					}while($emp_count > $emp_counter);



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


					//echo $dept_id . "<br/>";
				}
			}




			// 

		}


		

		//echo $_POST["to".$counter] . "<br/>";

		//echo $counter;
		
	}while($count > $counter);




	$dateTime = $date_class->getDateTime();
	$audit_trail_class = new AuditTrail;
	$module = "Memorandum";
	$audit_trail_class->insertAuditTrail(0,0,$_SESSION["id"],$module,"Add memorandum about <b>".$subject."</b>",$dateTime);

	
	$_SESSION["add_memo_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The Memorandum of <b>$subject</b> is successfully save</center>";
	

	header("Location:../memorandum.php");

	//echo $_POST["optRecipient".$count];
	/*
	

	$typeRecipient = $_POST["optRecipient"];

	

	if ($typeRecipient == "Specific Employee"){
		if (isset($_POST["empId"])){
			$emp_id = $_POST["empId"];
		}

	}

	else if ($typeRecipient == "Department"){
		if (isset($_POST["deptId"])){
			$dept_id = $_POST["deptId"];
		}
	}


	//if (isset($_POST["empId"])){
	//	$emp_id = $_POST["empId"];
	//}

	//if (isset($_POST["deptId"])){
	//	$dept_id = $_POST["deptId"];
	//}


	$from = $_SESSION["id"];
	$subject = $_POST["subject"];
	$content = $_POST["content"];

	
	$current_date = $date_class->getDate();



	$memo_class = new Memorandum;
	$memo_class->insertMemo($typeRecipient,$emp_id,$dept_id,$from,$subject,$content,$current_date); // $recipient,$emp_id,$dept_id,$memoFrom,$subject,$content,$dateCreated)

	if ($typeRecipient == "All"){
		$_SESSION["add_memo_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The Memorandum of <b>$subject</b> is successfully send to <b>All Employee</b></center>";
	}

	else if ($typeRecipient == "Department"){
		$department_class = new Department;
		$row = $department_class->getDepartmentValue($dept_id);
		$department = $row->Department;
		$_SESSION["add_memo_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The Memorandum of <b>$subject</b> is successfully send to <b>$department Department</b></center>";
	}

	else {
		$emp_info_class = new EmployeeInformation;
		$row = $emp_info_class->getEmpInfoByRow($emp_id);
		$fullName = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
		$_SESSION["add_memo_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The Memorandum of <b>$subject</b> is successfully send to <b>$fullName</b></center>";
	}


	header("Location:../memorandum.php");
	*/







}

else {
	header("Location:../MainForm.php");
}


?>