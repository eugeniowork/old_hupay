<?php

class Memorandum extends Connect_db{


	public function getMemoInfoById($memo_id){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_memorandum WHERE memo_id='$memo_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;

	}

	public function insertMemo($memoFrom,$subject,$content,$dateCreated){
		$connect = $this->connect();

		$memoFrom = mysqli_real_escape_string($connect,$memoFrom);
		$subject = mysqli_real_escape_string($connect,$subject);
		$content = mysqli_real_escape_string($connect,$content);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);


		$insert_qry = "INSERT INTO tb_memorandum (memo_id,memoFrom,Subject,Content,DateCreated) VALUES
												('','$memoFrom','$subject','$content','$dateCreated')";

		$sql = mysqli_query($connect,$insert_qry);

	}


	// for inserting memo to list
	public function insertMemoMultiple($memo_id,$recipient,$emp_id,$dept_id,$dateCreated){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);
		$recipient = mysqli_real_escape_string($connect,$recipient);
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$dept_id = mysqli_real_escape_string($connect,$dept_id);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_multiple_memo (multiple_memo_id,memo_id,recipient,emp_id,dept_id,dateCreated) 
					VALUES ('','$memo_id','$recipient','$emp_id','$dept_id','$dateCreated')";

		$sql = mysqli_query($connect,$insert_qry);

	}



	//for inserting to memo images
	public function insertMemoImages($memo_id,$image_path){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);
		$image_path = mysqli_real_escape_string($connect,$image_path);

		$insert_qry = "INSERT INTO tb_memo_images (memo_id,image_path) VALUES ('$memo_id','$image_path')";
		$sql = mysqli_query($connect,$insert_qry);

	}

	public function getMemoToTable(){
		$connect = $this->connect();

		$user_id = $_SESSION["id"];

		$select_qry = "SELECT * FROM tb_memorandum ORDER BY DateCreated DESC";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){


				$recipient = "";
				// for getting recipient value
				$select_multiple_memo_qry = "SELECT * FROM tb_multiple_memo WHERE memo_id = '$row->memo_id'";
				if ($result_multiple_memo = mysqli_query($connect,$select_multiple_memo_qry)){
					while ($row_multiple_memo = mysqli_fetch_object($result_multiple_memo)){


						if ($recipient == ""){
							if ($row_multiple_memo->recipient == "All"){
								$recipient = "All";
							}

							if ($row_multiple_memo->recipient == "Specific Employee"){
								$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row_multiple_memo->emp_id'";
								$result_emp = mysqli_query($connect,$select_emp_qry);
								$row_emp = mysqli_fetch_object($result_emp);
								$recipient = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
							}


							if ($row_multiple_memo->recipient == "Department"){
								$select_dept_qry = "SELECT * FROM tb_department WHERE dept_id = '$row_multiple_memo->dept_id'";
								$result_dept = mysqli_query($connect,$select_dept_qry);
								$row_dept = mysqli_fetch_object($result_dept);
								$recipient = $row_dept->Department;
							}
						}

						else {

							if ($row_multiple_memo->recipient == "Specific Employee"){
								$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row_multiple_memo->emp_id'";
								$result_emp = mysqli_query($connect,$select_emp_qry);
								$row_emp = mysqli_fetch_object($result_emp);
								$recipient =$recipient . " , " . $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
							}


							if ($row_multiple_memo->recipient == "Department"){
								$select_dept_qry = "SELECT * FROM tb_department WHERE dept_id = '$row_multiple_memo->dept_id'";
								$result_dept = mysqli_query($connect,$select_dept_qry);
								$row_dept = mysqli_fetch_object($result_dept);
								$recipient = $recipient . " , " . $row_dept->Department;
							}
						}
					}

				}




				//$recipient = $row_multiple_memo->recipient;

				



				$date_create = date_create($row->DateCreated);
				$date = date_format($date_create, 'F d, Y');


				if ($user_id != 21){

					echo "<tr id='".$row->memo_id."'>";
						echo "<td>".$row->Subject ."</td>";
						echo "<td>".$recipient."</td>";
						echo "<td>".$date."</td>";
						echo "<td id=''>".nl2br($row->Content)."</td>";
						echo "<td>";
							echo "<div style='cursor:pointer;float:left;' id='edit_memorandum' class='action-a'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span></div>";
							echo "<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>";
							echo "<a href='#' id='delete_memorandum' class='action-a'><span class='glyphicon glyphicon-trash' style='color:#515a5a'></span></a>";
							echo "<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>";
							echo "<a href='#' id='print_memorandum' class='action-a'><span class='glyphicon glyphicon-print' style='color:#2c3e50'></span></a>";
						
							if ($this->checkExistMemoImg($row->memo_id) != 0){
								echo "<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>";
								echo "<a href='#' id='view_memo_img' class='action-a'><span class='glyphicon glyphicon-picture' style='color: #2980b9 '></span></a>";
							}
							echo "<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>";
							echo "<a href='#' id='add_memo_img' class='action-a'><span class='glyphicon glyphicon-paperclip' style='color:#2c3e50'></span></a>";
						echo "</td>";

					echo "</tr>";
				}

				else {
					echo "<tr id='".$row->memo_id."'>";
						echo "<td>".$row->Subject ."</td>";
						echo "<td>".$recipient."</td>";
						echo "<td>".$date."</td>";
						echo "<td id=''>".nl2br($row->Content)."</td>";
						echo "<td>";
							//echo "<div style='cursor:pointer;float:left;' id='edit_memorandum' class='action-a'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span></div>";
							//echo "<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>";
							//echo "<a href='#' id='delete_memorandum' class='action-a'><span class='glyphicon glyphicon-trash' style='color:#515a5a'></span></a>";
							//echo "<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>";
							echo "<a href='#' id='print_memorandum' class='action-a'><span class='glyphicon glyphicon-print' style='color:#2c3e50'></span></a>";
						
							/*if ($this->checkExistMemoImg($row->memo_id) != 0){
								echo "<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>";
								echo "<a href='#' id='view_memo_img' class='action-a'><span class='glyphicon glyphicon-picture' style='color: #2980b9 '></span></a>";
							}*/
							//echo "<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>";
							//echo "<a href='#' id='add_memo_img' class='action-a'><span class='glyphicon glyphicon-paperclip' style='color:#2c3e50'></span></a>";
						echo "</td>";

					echo "</tr>";
				}
			}
		}

	}


	// check if exist memo
	public function checkExistMemo($memo_id){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_memorandum WHERE memo_id = '$memo_id'"));
		return $num_rows;
	}


	// check if exist memo
	public function checkExistMemoImg($memo_id){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_memo_images WHERE memo_id = '$memo_id'"));
		return $num_rows;
	}


	public function getMemoImgList($memo_id){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);

		$select_qry = "SELECT * FROM tb_memo_images WHERE memo_id = '$memo_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){


				 echo '<div class="col-md-offset-1 col-md-10 thumb" style="margin-bottom:10px">
						
				 	<div id="'.$row->memo_img_id.'">
				 		<button type="button" id="remove_memo_image" class="btn btn-danger pull-right btn-xs"><span class="glyphicon glyphicon-remove"></span></button>
				 	</div>

                    <img class="img-thumbnail"
                         src="'.$row->image_path.'"
                         alt="Another alt text">
	                
	            </div>';
			}
		}
	}


	// for removing 
	public function removeMemoImg($memo_img_id){
		$connect = $this->connect();

		$memo_img_id = mysqli_real_escape_string($connect,$memo_img_id);

		$delete_qry = "DELETE FROM tb_memo_images WHERE memo_img_id = '$memo_img_id'";
		$sql = mysqli_query($connect,$delete_qry);
	}

	public function getMemoImagesInfoById($memo_id){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_memo_images WHERE memo_img_id='$memo_img_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;

	}


	// for checking if no changes
	public function sameMemoInfo($memo_id,$typeRecipient,$emp_id,$dept_id,$memoFrom,$subject,$content){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);
		$typeRecipient = mysqli_real_escape_string($connect,$typeRecipient);
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$dept_id = mysqli_real_escape_string($connect,$dept_id);
		$memoFrom = mysqli_real_escape_string($connect,$memoFrom);
		$subject = mysqli_real_escape_string($connect,$subject);
		$content = mysqli_real_escape_string($connect,$content);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_memorandum WHERE recipient = '$typeRecipient' AND emp_id = '$emp_id' AND dept_id ='$dept_id'
													AND memoFrom = '$memoFrom' AND Subject = '$subject' AND Content = '$content' AND memo_id = '$memo_id'"));
		return $num_rows;

	}


	public function updateMemo($memo_id,$memoFrom,$subject,$content,$dateCreated){
		$connect = $this->connect();

		
		$memo_id = mysqli_real_escape_string($connect,$memo_id);
		$memoFrom = mysqli_real_escape_string($connect,$memoFrom);
		$subject = mysqli_real_escape_string($connect,$subject);
		$content = mysqli_real_escape_string($connect,$content);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$update_qry = "UPDATE tb_memorandum SET  memoFrom = '$memoFrom',Subject = '$subject' , Content = '$content',DateCreated = '$dateCreated'
						WHERE memo_id = '$memo_id'";

		$sql = mysqli_query($connect,$update_qry);


	}

	// for delete memo
	public function deleteMemo($memo_id){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);

		$delete_qry = "DELETE FROM tb_memorandum WHERE memo_id = '$memo_id'";
		$sql = mysqli_query($connect,$delete_qry);

		$delete_multiple_qry = "DELETE FROM tb_multiple_memo WHERE memo_id = '$memo_id'";
		$sql_multiple = mysqli_query($connect,$delete_multiple_qry);
	}


	// for deleting multiple memo
	public function deleteMultipleMemo($memo_id){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);

		$delete_qry = "DELETE FROM tb_multiple_memo WHERE memo_id = '$memo_id'";
		$sql = mysqli_query($connect,$delete_qry);

	}


	// for deleting multiple memo
	public function deleteMemoNotif($memo_id){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);

		$delete_qry = "DELETE FROM tb_memo_notif WHERE memo_id = '$memo_id'";
		$sql = mysqli_query($connect,$delete_qry);

	}




	// for getting all memo in the database 
	public function getAllMemoToTable($emp_id,$role_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$role_id = mysqli_real_escape_string($connect,$role_id);

		//$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_memorandum WHERE emp_id = '$emp_id'"));

		// if has data
		//if ($num_rows != 0){
			$select_qry = "SELECT * FROM tb_multiple_memo ORDER BY dateCreated DESC";
			if ($result = mysqli_query($connect,$select_qry)){
				while ($row = mysqli_fetch_object($result)){


					$select_user_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$emp_id'";
					$result_user = mysqli_query($connect,$select_user_emp_qry);
					$row_user = mysqli_fetch_object($result_user);

					$my_dept = $row_user->dept_id;


					$date_create = date_create($row->dateCreated);
					$date = date_format($date_create, 'F d, Y');

					$select_memo_qry = "SELECT * FROM tb_memorandum WHERE memo_id = '$row->memo_id'";
					$result_memo = mysqli_query($connect,$select_memo_qry);
					$row_memo = mysqli_fetch_object($result_memo);


					$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row_memo->memoFrom'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);


					$select_position_qry = "SELECT * FROM tb_position WHERE position_id = '$row_emp->position_id'";
					$result_position = mysqli_query($connect,$select_position_qry);
					$row_position = mysqli_fetch_object($result_position);


					//$memoFrom_position = $row_position->Position; 
					$memoFrom_position = "HR"; 

					//echo $row->dept_id . "<br/>";

					$category = "";
					if ($row->recipient == "All"){
						$category = "All";
					}

					else if ($row->recipient == "Specific Employee"){
						$category = "Own";

					}

					else {
						$select_dept_qry = "SELECT * FROM tb_department WHERE dept_id ='$row->dept_id'";
						$result_dept = mysqli_query($connect,$select_dept_qry);
						$row_dept = mysqli_fetch_object($result_dept);

						$category = $row_dept->Department . " Department";
					}


					// if to certain dept and the dept is not to the current user no display
					if ($row->recipient == "Department" && $my_dept == $row->dept_id) {
						echo "<tr id='".$row->memo_id."'>";
							echo "<td id='readmoreValueMemo'><a id='view_memo_info'><b>".$date." - ".$row_memo->Subject."</b>".$row_memo->Content."</a></td>";
							echo "<td>".$memoFrom_position."</td>";
							echo "<td>".$category."</td>";
							if ($role_id == 3){
								echo "<td>";
									echo "<a href='#' id='print_memorandum' class='action-a'><span class='glyphicon glyphicon-print' style='color:#2c3e50'></span></a>";
								echo "</td>";
							}
						echo "</tr>";
					}

					// if all and sa kanya
					if ($row->recipient == "All"){
						echo "<tr id='".$row->memo_id."'>"; // view_memo_info
							echo "<td id='readmoreValueMemo'><a id='view_memo_info'><b>".$date." - ".$row_memo->Subject."</b>".$row_memo->Content."</a></td>";
							echo "<td>".$memoFrom_position."</td>";
							echo "<td>".$category."</td>";
							if ($role_id == 3){
								echo "<td>";
									echo "<a href='#' id='print_memorandum' class='action-a'><span class='glyphicon glyphicon-print' style='color:#2c3e50'></span></a>";
								echo "</td>";
							}
						echo "</tr>";
					}

					// for 
					 if ($row->recipient == "Specific Employee" && $row->emp_id == $emp_id){
						echo "<tr id='".$row->memo_id."'>"; // view_memo_info
							echo "<td id='readmoreValueMemo'><a id='view_memo_info' style=''><b>".$date." - ".$row_memo->Subject."</b>".$row_memo->Content."</a></td>";
							echo "<td>".$memoFrom_position."</td>";
							echo "<td>".$category."</td>";
							if ($role_id == 3){
								echo "<td>";
									echo "<a href='#' id='print_memorandum' class='action-a'><span class='glyphicon glyphicon-print' style='color:#2c3e50'></span></a>";
								echo "</td>";
							}
						echo "</tr>";
					}

				}
			}
		//}

		// if has no data
		//else {
		//	echo "<tr>";
		//		echo "<td><center>There is no information</center></td>";
		//	echo "</tr>";
	//	}



	}



	public function getMemoImageProfile($memo_id){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);

		$select_qry = "SELECT * FROM tb_memo_images WHERE memo_id = '$memo_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){


				 echo '<div class="col-md-offset-1 col-md-10 thumb" style="margin-bottom:10px">
						
				 	

                    <img class="img-thumbnail"
                         src="'.$row->image_path.'"
                         alt="Another alt text">
	                
	            </div>';
			}
		}
	}


	// for getting all memo for all employee 
	public function getAllMemo(){
		$connect = $this->connect();


		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_memorandum WHERE recipient = 'All'"));

		// if has data
		if ($num_rows != 0){
			$select_qry = "SELECT * FROM tb_memorandum WHERE recipient = 'All'";
			if ($result = mysqli_query($connect,$select_qry)){
				while ($row = mysqli_fetch_object($result)){

					$date_create = date_create($row->DateCreated);
					$date = date_format($date_create, 'F d, Y');

					$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->memoFrom'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

					$memoFrom = $row_emp->Lastname . ", " . $row_emp->Firstname; 

					

					echo "<tr id='".$row->memo_id."'>";
						echo "<td><center><small><span class='glyphicon glyphicon-file'></span><span style='cursor:pointer;color:#2e86c1;' id='view_memo_info'> <b>Subject:</b> ".$row->Subject. " - <b> Date: </b>" . $date. " -  <b>From: </b>".$memoFrom."</span></small></center></td>";
					echo "</tr>";
				}
			}
		}

		// if has no data
		else {
			echo "<tr>";
				echo "<td><center>There is no information</center></td>";
			echo "</tr>";
		}

	}


	// for getting memo last id
	public function memoLastId(){
		$connect = $this->connect();
		$select_last_id_qry = "SELECT * FROM tb_memorandum ORDER BY memo_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->memo_id;
		return $last_id;
	}


	// for getting memo last id
	public function memoImgLastId($memo_id){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);

		$select_last_id_qry = "SELECT * FROM tb_memo_images WHERE memo_id = '$memo_id' ORDER BY memo_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->memo_id;
		return $last_id;
	}



	// for getting memo information at multiple memo
	public function getRecipientMultipleMemo($memo_id){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);


		if ($this->getMultipleMemoCount($memo_id) == 1) {
			$count = 1;
			$select_qry = "SELECT * FROM tb_multiple_memo WHERE memo_id = '$memo_id'";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){


					$checkAll = "";
					$checkDept = "";
					$checkEmp = "";

					$to = "";
					$disabled = 'disabled="disabled"';
					$choose = "";

					if ($row->recipient == "All") {
						$checkAll = "checked='checked'";
					}

					else if ($row->recipient == "Department") {

						$select_dept_qry = "SELECT * FROM tb_department WHERE dept_id = '$row->dept_id'";
						$result_dept = mysqli_query($connect,$select_dept_qry);
						$row_dept = mysqli_fetch_object($result_dept);
						$to = $row_dept->Department;
						$choose = "<a href='#' id='choose_department_memo'>Choose</a>";

						$checkDept = "checked='checked'";
						$disabled = "";
					}

					else if ($row->recipient == "Specific Employee") {

						$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
						$result_emp = mysqli_query($connect,$select_emp_qry);
						$row_emp = mysqli_fetch_object($result_emp);

						$to = $row_emp->Lastname . ', ' . $row_emp->Firstname . ' ' . $row_emp->Middlename;
						if ($row_emp->Middlename == ""){
							$to = $row_emp->Lastname . ', ' . $row_emp->Firstname;
						}
						$choose = "<a href='#' id='choose_employee_memo'>Choose</a>";

						$checkEmp = "checked='checked'";
						$disabled = "";
					}

					$remove_button = '&nbsp;&nbsp;<button type="button" class="btn btn-danger btn-sm" id="remove_recipient'.$count.'">-</button>';
					if ($count == 1){
						$remove_button = "";
					}


					$allOption = '<label class="radio-inline"><input required="required" '.$checkAll.' type="radio" value="All" name="update_optRecipient'.$count.'">All</label>';
					if ($count > 1){
						$allOption = "";
					}




					if ($count == 1) {
					echo '<div class="form-group">
	 						<label class="control-label col-sm-3 col-sm-offset-1"><b>Recipient:</b></label>
	 			
	 						'.$allOption.'
							<label class="radio-inline"><input required="required" '.$checkDept.'  type="radio" value="Department" name="update_optRecipient'.$count.'">Department</label>
							<label class="radio-inline"><input required="required" '.$checkEmp.' type="radio" value="Specific Employee" name="update_optRecipient'.$count.'">Specific Employee</label>
							'.$remove_button.'
						</div>';

					echo '<div class="form-group">
							<label class="control-label col-sm-3 col-sm-offset-1" style=""><b>To:</b></label>
							<div class="col-sm-6 txt-pagibig-loan" style="margin-right:-15px;">


							<input type="text" class="form-control" '.$disabled.' value="'.$to.'" name="update_to'.$count.'" placeholder="" id="input_payroll" required="required" autocomplete="off"/>


							</div>
							<label class="col-sm-1 control-label"><div id="choose'.$count.'">'.$choose.'</div></label>
						</div>';

						echo '<div id="update_div_recipient">';

						echo '</div>';

					}
					


					$count++;
				}
			}
		}

		else {
			$count = 1;
			$select_qry = "SELECT * FROM tb_multiple_memo WHERE memo_id = '$memo_id'";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){


					$checkAll = "";
					$checkDept = "";
					$checkEmp = "";

					$to = "";
					$disabled = 'disabled="disabled"';
					$choose = "";

					if ($row->recipient == "All") {
						$checkAll = "checked='checked'";
					}

					else if ($row->recipient == "Department") {

						$select_dept_qry = "SELECT * FROM tb_department WHERE dept_id = '$row->dept_id'";
						$result_dept = mysqli_query($connect,$select_dept_qry);
						$row_dept = mysqli_fetch_object($result_dept);
						$to = $row_dept->Department;
						$choose = "<a href='#' id='choose_department_memo'>Choose</a>";

						$checkDept = "checked='checked'";
						$disabled = "";
					}

					else if ($row->recipient == "Specific Employee") {

						$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
						$result_emp = mysqli_query($connect,$select_emp_qry);
						$row_emp = mysqli_fetch_object($result_emp);

						$to = $row_emp->Lastname . ', ' . $row_emp->Firstname . ' ' . $row_emp->Middlename;
						if ($row_emp->Middlename == ""){
							$to = $row_emp->Lastname . ', ' . $row_emp->Firstname;
						}
						$choose = "<a href='#' id='choose_employee_memo'>Choose</a>";

						$checkEmp = "checked='checked'";
						$disabled = "";
					}

					$remove_button = '&nbsp;&nbsp;<button type="button" class="btn btn-danger btn-sm" id="remove_recipient'.$count.'">-</button>';
					if ($count == 1){
						$remove_button = "";
					}


					$allOption = '<label class="radio-inline"><input required="required" '.$checkAll.' type="radio" value="All" name="update_optRecipient'.$count.'">All</label>';
					if ($count > 1){
						$allOption = "";
					}




					if ($count == 1) {
					echo '<div class="form-group">
	 						<label class="control-label col-sm-3 col-sm-offset-1"><b>Recipient:</b></label>
	 			
	 						'.$allOption.'
							<label class="radio-inline"><input required="required" '.$checkDept.'  type="radio" value="Department" name="update_optRecipient'.$count.'">Department</label>
							<label class="radio-inline"><input required="required" '.$checkEmp.' type="radio" value="Specific Employee" name="update_optRecipient'.$count.'">Specific Employee</label>
							'.$remove_button.'
						</div>';

					echo '<div class="form-group">
							<label class="control-label col-sm-3 col-sm-offset-1" style=""><b>To:</b></label>
							<div class="col-sm-6 txt-pagibig-loan" style="margin-right:-15px;">


							<input type="text" class="form-control" '.$disabled.' value="'.$to.'" name="update_to'.$count.'" placeholder="" id="input_payroll" required="required" autocomplete="off"/>


							</div>
							<label class="col-sm-1 control-label"><div id="choose'.$count.'">'.$choose.'</div></label>
						</div>';



					}
					else {
						
					echo '<div id="update_div_recipient">';
						echo '<div id="update_recipient_mother_div'.$count.'">';
							echo '<div class="form-group">
			 						<label class="control-label col-sm-3 col-sm-offset-1"><b>Recipient:</b></label>
			 			
			 						'.$allOption.'
									<label class="radio-inline"><input required="required" '.$checkDept.'  type="radio" value="Department" name="update_optRecipient'.$count.'">Department</label>
									<label class="radio-inline"><input required="required" '.$checkEmp.' type="radio" value="Specific Employee" name="update_optRecipient'.$count.'">Specific Employee</label>
									'.$remove_button.'
								</div>';

							echo '<div class="form-group">
									<label class="control-label col-sm-3 col-sm-offset-1" style=""><b>To:</b></label>
									<div class="col-sm-6 txt-pagibig-loan" style="margin-right:-15px;">


									<input type="text" class="form-control" '.$disabled.' value="'.$to.'" name="update_to'.$count.'" placeholder="" id="input_payroll" required="required" autocomplete="off"/>


									</div>
									<label class="col-sm-1 control-label"><div id="choose'.$count.'">'.$choose.'</div></label>
								</div>';
							echo '</div>';
					echo '</div>';
					}


					$count++;
				}
			}
		}



	}


	// for getting if the memo is for all
	public function checkMemoForAll($memo_id){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_multiple_memo WHERE memo_id = '$memo_id' AND recipient = 'All'"));
		return $num_rows;
	}

	// for check ilan ung naka cc sa current memo
	public function getMultipleMemoCount($memo_id){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_multiple_memo WHERE memo_id = '$memo_id'"));
		return $num_rows;
	}


	// for getting information for multiple memo
	public function getMultipleMemoInfo($memo_id){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id); 


		$count = 1;
		$all_recipient = "";
		$select_qry = "SELECT * FROM tb_multiple_memo WHERE memo_id = '$memo_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				if ($count == 1 && $row->recipient == "All"){
					$all_recipient =  "All";
				}
				else {

					if ($row->recipient == "Department") {
						$select_dept_qry = "SELECT * FROM tb_department WHERE dept_id = '$row->dept_id'";
						$result_dept = mysqli_query($connect,$select_dept_qry);
						$row_dept = mysqli_fetch_object($result_dept);
						$recipient = $row_dept->Department;		
					}

					if ($row->recipient == "Specific Employee") {
						$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
						$result_emp = mysqli_query($connect,$select_emp_qry);
						$row_emp = mysqli_fetch_object($result_emp);
						$recipient = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
					}
					if ($all_recipient == "") {
						$all_recipient = $recipient;
					}
					else {
						$all_recipient = $all_recipient . ", " . $recipient;
					}

				}
				$count++;
			}
		}

		return $all_recipient;
		

	}



	public function getAllMemoNotif($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id); 

		$select_qry = "SELECT * FROM tb_memo_notif WHERE to_emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){


				$select_from_qry = "SELECT * FROM tb_employee_info WHERE emp_id= '$row->from_emp_id'";
				$result_from = mysqli_query($connect,$select_from_qry);
				$row_from = mysqli_fetch_object($result_from);

				$profile_path = $row_from->ProfilePath;

				$fullName = $row_from->Firstname . " " . $row_from->Lastname;

				$dateCreated = date_format(date_create($row->dateCreated), 'F d, Y');

				$time = date_format(date_create($row->dateCreated), 'g:i A');


				echo '<li id="" class="">
						<div id="">
							<div class="container-fluid">
								<div clas="sm-2">
									<img src="'.$profile_path.'">
								</div>
								<div class="col-sm-10">
									 <b>'.$fullName.'</b> about <b>'.$row->notif_type.'</b> on
									<b>'.$dateCreated.'</b> at <b>'.$time.'</b>
								</div>
							</div>
						</div>
					</li>';
			}
		}



		
	}


	// for getting payroll notification where read status = 0
	public function memoImgCount($memo_id){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_memo_images WHERE memo_id = '$memo_id'"));

		return $num_rows;
	}


	// for getting payroll notification where read status = 0
	public function unreadMemoNotifCount($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_memo_notif WHERE to_emp_id = '$emp_id' AND readStatus = '0'"));

		return $num_rows;
	}

	// for getting the last id in database
	public function lastId(){
		$connect = $this->connect();
		$select_last_id_qry = "SELECT * FROM tb_memorandum ORDER BY memo_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->memo_id;
		return $last_id;
	}


	public function insertMemoNotif($memo_id,$from_emp_id,$to_emp_id,$notif_type,$date_created){
		$connect = $this->connect();

		$memo_id = mysqli_real_escape_string($connect,$memo_id);
		$from_emp_id = mysqli_real_escape_string($connect,$from_emp_id);
		$to_emp_id = mysqli_real_escape_string($connect,$to_emp_id);
		$notif_type = mysqli_real_escape_string($connect,$notif_type);
		$date_created = mysqli_real_escape_string($connect,$date_created);

		$insert_qry = "INSERT INTO tb_memo_notif (memo_id,from_emp_id,to_emp_id,notif_type,readStatus,dateCreated) 
					VALUES ('$memo_id','$from_emp_id','$to_emp_id','$notif_type','0','$date_created')";

		$sql = mysqli_query($connect,$insert_qry);

	}
}




?>