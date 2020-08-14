<?php
session_start();
include "../class/connect.php";
include "../class/memorandum_class.php";
include "../class/emp_information.php";
include "../class/date.php";
include "../class/department.php";
include "../class/audit_trail_class.php";

if (isset($_POST["update_optRecipient1"]) && isset($_POST["update_subject"]) && isset($_POST["update_content"])){

	$from = $_SESSION["id"];
	$memo_id = $_POST["memo_id"];
	$subject = $_POST["update_subject"];
	$content = $_POST["update_content"];

	/*echo $memo_id . "<br/>";
	echo $from . "<br/>";
	echo $subject . "<br/>";
	echo $content . "<br/>";*/

	$memo_class = new Memorandum;
	$emp_info_class = new EmployeeInformation;
	$department_class = new Department;
	$date_class = new date;

	$current_date = $date_class->getDate();

	$count = $_POST["count"];

	//echo $count;

	$memo_class->updateMemo($memo_id,$from,$subject,$content,$current_date);


	// for deleting first all memos from multiple memo
	$memo_class->deleteMultipleMemo($memo_id);

	$counter = 0;
	do {
		//echo "Hello World!";

		$counter++;
		//echo $counter;
		//if ()
		$emp_id = "0";
		$dept_id = "0";
		if (isset($_POST["update_optRecipient".$counter])) {
			$typeRecipient =  $_POST["update_optRecipient".$counter];
			



			 if ($typeRecipient != "") {

				 if ($typeRecipient == "Specific Employee"){
					if (isset($_POST["update_to".$counter])){
						$emp_id = $emp_info_class->getEmpIdByEmployeeName($_POST["update_to".$counter]);
						//echo $emp_id . "<br/>";
					}

				}

				else if ($typeRecipient == "Department"){
					if (isset($_POST["update_to".$counter])){
						$dept_id = $department_class->getDeptIdByDepartment($_POST["update_to".$counter]);
						//echo $dept_id . "<br/>";
					}
				}

				//echo $memo_last_id . "<br/>";
				/*echo $memo_id . "<br/>";
				echo $typeRecipient . "<br/>";
				echo $emp_id . "<br/>";
				echo $dept_id . "<br/>";
				echo $current_date . "<br/>";
				*/

				// for inserting to multiple memo
				$memo_class->insertMemoMultiple($memo_id,$typeRecipient,$emp_id,$dept_id,$current_date);
			


				$memo_class->deleteMemoNotif($memo_id); // for delete memo notif


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

				}


				else if ($typeRecipient == "Specific Employee"){
					if (isset($_POST["update_to".$counter])){
						$emp_id = $emp_info_class->getEmpIdByEmployeeName($_POST["update_to".$counter]);
						$notif_type = $subject;
						
						$memo_id = $memo_class->lastId();

						$memo_class->insertMemoNotif($memo_id,$from,$emp_id,$notif_type,$date_time_created);
						//echo $emp_id . "<br/>";
					}

				}

				else if ($typeRecipient == "Department"){
					if (isset($_POST["update_to".$counter])){
						$dept_id = $department_class->getDeptIdByDepartment($_POST["update_to".$counter]);

						$emp_count = $department_class->AllActiveEmpIdLinkDeptIdCount($dept_id) - 1;


						$emp_id_values = explode("#", $department_class->getAllActiveEmpIdLinkDeptId($dept_id));

						//echo $department_class->getAllActiveEmpIdLinkDeptId($dept_id);

						$memo_id = $memo_class->lastId();

						$emp_counter = 0;
						do {

							$notif_type = $subject . " to "  .$_POST["update_to".$counter];

							$emp_id = $emp_id_values[$emp_counter];
							
							//echo $emp_id . "<br/>";

							//echo $memo_id . " " . $from . " " .  $emp_id . " " . $notif_type . " " . $date_time_created . "<br/>";

							//insertMemoNotif($memo_id,$from_emp_id,$to_emp_id,$notif_type,$date_created);

							$memo_class->insertMemoNotif($memo_id,$from,$emp_id,$notif_type,$date_time_created);
							$emp_counter++;
						}while($emp_count > $emp_counter);


						//echo $dept_id . "<br/>";
					}
				}



			}
		}



		//$counter++;
	}while($count >= $counter);


	$dateTime = $date_class->getDateTime();
	$audit_trail_class = new AuditTrail;
	$module = "Memorandum";
	$audit_trail_class->insertAuditTrail(0,0,$_SESSION["id"],$module,"Update memorandum about <b>".$subject."</b>",$dateTime);



	//echo $count;
	
	/*$date_class = new date;


	$emp_id = "";
	$dept_id = "";

	$memo_id = $_POST["update_memoId"];
	$typeRecipient = $_POST["update_optRecipient"];

	if ($typeRecipient == "Specific Employee"){
		if (isset($_POST["updateEmpId"])){
			$emp_id = $_POST["updateEmpId"];
		}

	}

	else if ($typeRecipient == "Department"){
		if (isset($_POST["updateDeptId"])){
			$dept_id = $_POST["updateDeptId"];
		}
	}

	$memoFrom_id = $_SESSION["id"];
	$subject = $_POST["update_subject"];
	$content = $_POST["update_content"];
	$dateCreated = $date_class->getDate();

	/*echo "Memo id:" . $memo_id. "<br/>";
	echo "Recipient: " . $typeRecipient ."<br/>";
	echo "Emp id: " . $emp_id ."<br/>";
	echo "Dept id: " . $dept_id ."<br/>";
	echo "Memo From: " . $memoFrom_id ."<br/>";
	echo "Subject: " . $subject ."<br/>";
	echo "Content: " . $content ."<br/>";
	echo "Date Created " . $dateCreated ."<br/>";*/



	
	

	//$memo_class = new Memorandum;


	// if no changes
	/*if ($memo_class->sameMemoInfo($memo_id,$typeRecipient,$emp_id,$dept_id,$memoFrom_id,$subject,$content) == 1){ 
		$_SESSION["update_memo_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No changes was made, No updates were taken</center>";
	}

	// if success
	else {
	

		$memoFrom = $_SESSION["id"];

		$memo_class->updateMemo($memo_id,$typeRecipient,$emp_id,$dept_id,$memoFrom_id,$subject,$content,$dateCreated);

		$row = $memo_class->getMemoInfoById($memo_id);

		$to = $row->recipient;

		if ($to == "All"){
			$empName = "All Employee";
		}

		else if ($to == "Specific Employee"){
			$emp_info_class = new EmployeeInformation;
			$row_emp = $emp_info_class->getEmpInfoByRow($row->emp_id);

			$empName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
		}

		else {
			$department_class = new Department;
			$row_dept = $department_class->getDepartmentValue($row->dept_id);
			$empName = $row_dept->Department . " Department";
		}

		$_SESSION["update_memo_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The Memo Information of <b>$empName</b> is successfully updated.</center>";
	//}

	//header("Location:../memorandum.php");
	*/

	$_SESSION["update_memo_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The Memo Information of <b>$subject</b> is successfully updated.</center>";
	header("Location:../memorandum.php");
}

else {
	header("Location:../MainForm.php");
}


?>