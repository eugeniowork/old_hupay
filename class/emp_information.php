<?php

class EmployeeInformation extends Connect_db{

	// for specific person log in
	public function getEmpInfoByRow($id){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_employee_info WHERE emp_id='$id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;
	}

	// for specific person log in
	public function getEmpInfoByBioId($bio_id){
		$connect = $this->connect();
		
		$bio_id = mysqli_real_escape_string($connect,$bio_id);

		$select_qry = "SELECT * FROM tb_employee_info WHERE bio_id='$bio_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;
	}


	// for getting all employee with bio id
	public function getAllEmpIdWithoutBioId(){
		$connect = $this->connect();
		
		$select_qry = "SELECT * FROM tb_employee_info WHERE no_bio = '1' AND ActiveStatus = '1' AND (role_id != '1' OR dept_id != '1') ORDER BY Lastname ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){
					
				$fullName = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
				if ($row->Middlename == ""){
					$fullName = $row->Lastname . ", " . $row->Firstname;
				}

				echo "<tr id='".$row->emp_id."'>";
					echo "<td>".$fullName."</td>";
					echo "<td>";
						echo "<div style='color:#158cba;cursor:pointer' id='add_attendance_no_bio'><span class='glyphicon glyphicon-plus' style='color:#1e8449'></span> Add Attendance</div>";
					echo "</td>";
				echo "</tr>";
			}
		}
	}


	public function getAllEmployeesNameToTable(){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_employee_info";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				/*if ($row->Gender == "Male") {
					$pronoun = "his";
				}

				if ($row->Gender == "Female") {
					$pronoun = "her";
				}*/
				// for admin
				if (($row->role_id != 1 || $row->dept_id != 1) && $row->ActiveStatus == 1) {
					echo "<tr id='".$row->emp_id."' style='text-align:center;'>";
						echo "<td><a href='#' id='chooseEmployee' title='Choose ".$row->Lastname .", " . $row->Firstname . " " . $row->Middlename."'>" . $row->Lastname .", " . $row->Firstname . " " . $row->Middlename . "</a></td>";
					echo "</tr>";
				}
			}
		}
	}


	


	// for inserting information
	public function insertEmployee($lname,$fname,$mname,
									$address,$role,$department,$position,$head_emp_id,
									$birthdate,$gender,$contactNo,$email,$civilStatus,
									$profileImage,$profilePath,$username,
									$password,$sssNo,$pagibigNo,$tinNo,$philhealthNo,$salary,$dateHired,$workingHours,$company_id,
									$employment_type_stat,$education_attain,$working_days_id,$dateCreated){

		$connect = $this->connect();
		
		//$biod_id = mysqli_real_escape_string($connect,$bio_id);
		$lname = mysqli_real_escape_string($connect,$lname);
		$fname = mysqli_real_escape_string($connect,$fname);
		$mname = mysqli_real_escape_string($connect,$mname);
		$address = mysqli_real_escape_string($connect,$address);
		$role = mysqli_real_escape_string($connect,$role);
		$department = mysqli_real_escape_string($connect,$department);
		
		$position = mysqli_real_escape_string($connect,$position);
		$head_emp_id = mysqli_real_escape_string($connect,$head_emp_id);
		$birthdate = mysqli_real_escape_string($connect,$birthdate);
		$gender = mysqli_real_escape_string($connect,$gender);
		$contactNo = mysqli_real_escape_string($connect,$contactNo);
		$email = mysqli_real_escape_string($connect,$email);
		$civilStatus = mysqli_real_escape_string($connect,$civilStatus);
		$profileImage = mysqli_real_escape_string($connect,$profileImage);
		$profilePath = mysqli_real_escape_string($connect,$profilePath);
		$username = mysqli_real_escape_string($connect,$username);
		$password = mysqli_real_escape_string($connect,$password);
		$sssNo = mysqli_real_escape_string($connect,$sssNo);
		$pagibigNo = mysqli_real_escape_string($connect,$pagibigNo);
		$tinNo = mysqli_real_escape_string($connect,$tinNo);
		$philhealthNo = mysqli_real_escape_string($connect,$philhealthNo);
		$salary = mysqli_real_escape_string($connect,$salary);
		$dateHired = mysqli_real_escape_string($connect,$dateHired);
		$workingHours = mysqli_real_escape_string($connect,$workingHours);
		$company_id = mysqli_real_escape_string($connect,$company_id);
		$employment_type_stat = mysqli_real_escape_string($connect,$employment_type_stat);
		$education_attain = mysqli_real_escape_string($connect,$education_attain);
		$working_days_id = mysqli_real_escape_string($connect,$working_days_id);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_employee_info (emp_id,bio_id,Lastname,Firstname,
													Middlename,Address,role_id,dept_id,
													position_id,head_emp_id,Birthdate,Gender,ContactNo,EmailAddress,
													CivilStatus,ProfileImage,ProfilePath,Username,
													Password,SSS_No,PagibigNo,
													TinNo,PhilhealthNo,Salary,DateHired,working_hours_id,ActiveStatus,company_id,employment_type,highest_educational_attain,working_days_id,DateCreated)
											VALUES ('','','$lname','$fname',
													'$mname','$address','$role','$department',
													'$position','$head_emp_id','$birthdate','$gender',
													'$contactNo','$email','$civilStatus','$profileImage','$profilePath',
													'$username','$password','$sssNo',
													'$pagibigNo','$tinNo','$philhealthNo',
													'$salary','$dateHired','$workingHours','1','$company_id','$employment_type_stat'
													,'$education_attain','$working_days_id','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);

	}

	// function check if the bio id is exist
	public function checkExistBioId($bio_id){
		$connect = $this->connect();

		$bio_id = mysqli_real_escape_string($connect,$bio_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE bio_id = '$bio_id'"));
		return $num_rows;
	}


	// if the inputed bio id is the current id 
	public function sameBioId($emp_id,$bio_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$bio_id = mysqli_real_escape_string($connect,$bio_id);		

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE bio_id = '$bio_id' AND emp_id='$emp_id'"));
		return $num_rows;
	}


	// for update bio ID success
	public function updateBioId($emp_id,$bio_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$bio_id = mysqli_real_escape_string($connect,$bio_id);

		$update_qry = "UPDATE tb_employee_info SET bio_id = '$bio_id', no_bio = '0' WHERE  emp_id='$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for update bio ID success
	public function createBioId($emp_id,$bio_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$bio_id = mysqli_real_escape_string($connect,$bio_id);

		$update_qry = "UPDATE tb_employee_info SET bio_id = '$bio_id', no_bio = '1' WHERE emp_id='$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// function check if the username is exist
	public function checkExistUsername($username){
		$connect = $this->connect();

		$username = mysqli_real_escape_string($connect,$username);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE Username = '$username'"));
		return $num_rows;
	}


	// for checking if the user id is exist in the database
	public function checkExistEmpId($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE emp_id = '$emp_id'"));
		return $num_rows;
	}


	// for creating payroll for security purpose
	public function checkEmpName($emp_id,$emp_name){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$emp_name = mysqli_real_escape_string($connect,$emp_name);

		$existData = 0;

		$select_qry = "SELECT * FROM tb_employee_info";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$emp_id_db = $row->emp_id;
				$emp_name_db = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
				
				if ($emp_id == $emp_id_db && $emp_name == $emp_name_db){
					$existData = 1;
				}


			}

		}

		return $existData;


	}


	// for getting the last id in database
	public function empLastId(){
		$connect = $this->connect();
		$select_last_id_qry = "SELECT * FROM tb_employee_info ORDER BY emp_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->emp_id;
		return $last_id;
	}


	// for getting the info to table
	public function getEmpInfoToTable(){
		$connect = $this->connect();

		$user_id = $_SESSION["id"];

		//echo $user_id;

		$select_qry = "SELECT * FROM tb_employee_info ORDER BY Lastname ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){
				$select_position_qry = "SELECT * FROM tb_position WHERE position_id = '$row->position_id'";
				$result_position = mysqli_query($connect,$select_position_qry);
				$row_position = mysqli_fetch_object($result_position);
				$position_val = $row_position->Position;

				// for active or inactive
				$active_stat = $row->ActiveStatus;
				// if active
				$active_value_action = "";
				if ($active_stat == 1){
					// value is dynamic
					$active_value_action = "Inactive";
				}

				// if inactive
				if ($active_stat == 0){
					// value is dynamic
					$active_value_action = "&nbsp;&nbsp;Active&nbsp;&nbsp;";
				}

				$active_value = "";
				if ($active_stat == 1){
					// value is dynamic
					$active_value = "Active";
				}

				if ($active_stat == 0){
					// value is dynamic
					$active_value = "Inactive";
					if ($row->resignation_date != ""){
						$active_value .= "<br/>";
						$active_value .= "<small><span class='color-gray'><i>".date_format(date_create($row->resignation_date),"F d, Y")."</i></span></small>";
					}
				}


				// optional fields, so ang mangyayare titignan kung nafill upan na to pra macheck ung pending fields na hindi pa nafifill upan
				$middleName = $row->Middlename;
				$contactNo = $row->ContactNo;
				$emailAddress = $row->EmailAddress;
				$sssNo = $row->SSS_No;
				$pagibigNo = $row->PagibigNo;
				$tinNo = $row->TinNo;
				$philhealthNo = $row->PhilhealthNo;

				$unfillUp_fields = 0;
				if ($middleName == ""){
					$unfillUp_fields++;
				}

				if ($contactNo == ""){
					$unfillUp_fields++;
				}

				if ($emailAddress == ""){
					$unfillUp_fields++;
				}

				if ($sssNo == ""){
					$unfillUp_fields++;
				}

				if ($pagibigNo == ""){
					$unfillUp_fields++;
				}

				if ($tinNo == ""){
					$unfillUp_fields++;
				}

				if ($philhealthNo == ""){
					$unfillUp_fields++;
				}


				$unfill_info = "<span class='glyphicon glyphicon-ok' style='color:#1d8348;'></span>";
				if ($unfillUp_fields != 0){
					$unfill_info = "<label class='label label-danger' id='unfill_fields'>".$unfillUp_fields."</label><span>";
				}


				$withAtm = '<span class="glyphicon glyphicon-remove" style="color:#CB4335"></span>';
				if ($row->WithAtm == 1) {
					$withAtm = '<span class="glyphicon glyphicon-ok" style="color:#196F3D"></span>';
				}

				$select_company_qry = "SELECT * FROM tb_company WHERE company_id = '$row->company_id'";
				$result_company = mysqli_query($connect,$select_company_qry);
				$row_company = mysqli_fetch_object($result_company);


				// emp_id = 1 for ADMIN
				if ($row->role_id != 1 || $row->dept_id != 1){


					// for not miss yvette
					if ($user_id != 21){



						// for admin and hr purpose full control
						//if ($_SESSION["role"] == 1 || $_SESSION["role"] == 2){
							echo "<tr id=".$row->emp_id.">";
								echo "<td><img id='profile_pic_table' class='logo-company-image-table' src='". $row_company->logo_source . "'/></td>";
								echo "<td><img id='profile_pic_table' class='profile-image-table' src='". $row->ProfilePath . "'/></td>";
								echo "<td>".$unfill_info."</td>";
								echo "<td>".$withAtm."</td>";
								echo "<td>".$row->Lastname. ", " .  $row->Firstname. " " .$row->Middlename . "</td>";
								echo "<td id='readmoreValue' style='font-size:small;'>".htmlspecialchars($row->Address)."</td>";
								echo "<td style='font-size:small;'>".$position_val."</td>";
								echo "<td>".$active_value."</td>";
								echo "<td>";

									if ($row->ActiveStatus == 1){

										echo "<a href='#' id='edit_emp_info' class='action-a' title='Edit ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." Employee Info'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span></a>";
										echo "<span> | </span>";
										echo "<a href='#' id='status_emp_info' class='action-a' title='Make ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." $active_value_action Employee'><span class='glyphicon glyphicon-stats' style='color:#357ca5'></span></a>";
										echo "<span> | </span>";
										echo "<a href='#' id='view_emp_profile' class='action-a' title='View ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." Employee Info'><span class='glyphicon glyphicon-eye-open' style='color:#186a3b'></span></a>";
										echo "<span> | </span>";
										echo "<a href='#' id='upload_201_file' class='action-a' title='Upload ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." 201 File'><span class='glyphicon glyphicon-upload' style='color:#dc7633'></span></a>";
										echo "<span> | </span>";
										echo "<a href='#' id='print_emp_info' class='action-a' title='Print ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." Info'><span class='glyphicon glyphicon-print' style='color: #2c3e50 '></span></a>";
										echo "<span> | </span>";
										echo "<a href='#' id='view_emp_history_info' class='action-a' title='View LFC Employment History of ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname."'><span class='glyphicon glyphicon-briefcase' style='color: #cb4335 '></span></a>";
										echo "<span> | </span>";
										echo "<a href='#' id='update_atm_record' class='action-a' title='update ATM records of ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname."'><span class='glyphicon glyphicon-credit-card' style='color: #186a3b '></span></a>";
										echo "<span> | </span>";
										echo "<a href='#' id='add_increase_info' class='action-a' title='Add Increase Information of ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname."'><span class='glyphicon glyphicon-ruble' style='color: #357ca5 '></span></a>";

										if ($_SESSION["role"] == 1){ // admin lang pde
											echo "<span> | </span>";


											if ($row->generated_code == ""){

												echo "<a href='#' id='generate_code' class='action-a' title='Generate Code for Change Password of ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname."'><span class='glyphicon glyphicon-lock' style='color: #357ca5 '></span></a>";
											}

											else {
												echo "<a href='#' id='view_generated_code' class='action-a' title='View Generate Code for Change Password of ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname."'><span class='glyphicon glyphicon-lock' style='color: #186a3b '></span></a>";
											}
										}
									}

									// if inactive
									else {
										//echo "<a href='#' id='edit_emp_info' class='action-a' title='Edit ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." Employee Info'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span></a>";
										//echo "<span> | </span>";
										echo "<a href='#' id='status_emp_info' class='action-a' title='Make ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." $active_value_action Employee'><span class='glyphicon glyphicon-stats' style='color:#357ca5'></span></a>";
										echo "<span> | </span>";
										echo "<a href='#' id='view_emp_profile' class='action-a' title='View ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." Employee Info'><span class='glyphicon glyphicon-eye-open' style='color:#186a3b'></span></a>";
										echo "<span> | </span>";
										//echo "<a href='#' id='upload_201_file' class='action-a' title='Upload ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." 201 File'><span class='glyphicon glyphicon-upload' style='color:#dc7633'></span></a>";
										//echo "<span> | </span>";
										echo "<a href='#' id='print_emp_info' class='action-a' title='Print ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." Info'><span class='glyphicon glyphicon-print' style='color: #2c3e50 '></span></a>";
										echo "<span> | </span>";
										echo "<a href='#' id='view_emp_history_info' class='action-a' title='View LFC Employment History of ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname."'><span class='glyphicon glyphicon-briefcase' style='color: #cb4335 '></span></a>";
										//echo "<span> | </span>";
										//echo "<a href='#' id='update_atm_record' class='action-a' title='update ATM records of ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname."'><span class='glyphicon glyphicon-credit-card' style='color: #186a3b '></span></a>";
										//echo "<span> | </span>";
										//echo "<a href='#' id='add_increase_info' class='action-a' title='Add Increase Information of ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname."'><span class='glyphicon glyphicon-ruble' style='color: #357ca5 '></span></a>";
									}
								echo "</td>";
							echo "</tr>";
						}


						else {
							echo "<tr id=".$row->emp_id.">";
								echo "<td><img id='profile_pic_table' class='logo-company-image-table' src='". $row_company->logo_source . "'/></td>";
								echo "<td><img id='profile_pic_table' class='profile-image-table' src='". $row->ProfilePath . "'/></td>";
								echo "<td>".$unfill_info."</td>";
								echo "<td>".$withAtm."</td>";
								echo "<td>".$row->Lastname. ", " .  $row->Firstname. " " .$row->Middlename . "</td>";
								echo "<td id='readmoreValue' style='font-size:small;'>".htmlspecialchars($row->Address)."</td>";
								echo "<td style='font-size:small;'>".$position_val."</td>";
								echo "<td>".$active_value."</td>";
								echo "<td>";
									//echo "<a href='#' id='edit_emp_info' class='action-a' title='Edit ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." Employee Info'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span></a>";
									// e/cho "<span> | </span>";
									//echo "<a href='#' id='status_emp_info' class='action-a' title='Make ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." $active_value_action Employee'><span class='glyphicon glyphicon-stats' style='color:#357ca5'></span></a>";
									//echo "<span> | </span>";
									echo "<a href='#' id='view_emp_profile' class='action-a' title='View ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." Employee Info'><span class='glyphicon glyphicon-eye-open' style='color:#186a3b'></span></a>";
									echo "<span> | </span>";
									//echo "<a href='#' id='upload_201_file' class='action-a' title='Upload ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." 201 File'><span class='glyphicon glyphicon-upload' style='color:#dc7633'></span></a>";
									//echo "<span> | </span>";
									echo "<a href='#' id='print_emp_info' class='action-a' title='Print ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." Info'><span class='glyphicon glyphicon-print' style='color: #2c3e50 '></span></a>";
									echo "<span> | </span>";
									echo "<a href='#' id='view_emp_history_info' class='action-a' title='View LFC Employment History of ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname."'><span class='glyphicon glyphicon-briefcase' style='color: #cb4335 '></span></a>";
									//echo "<span> | </span>";
									//echo "<a href='#' id='update_atm_record' class='action-a' title='update ATM records of ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname."'><span class='glyphicon glyphicon-credit-card' style='color: #186a3b '></span></a>";
									//echo "<span> | </span>";
									//echo "<a href='#' id='add_increase_info' class='action-a' title='Add Increase Information of ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname."'><span class='glyphicon glyphicon-ruble' style='color: #357ca5 '></span></a>";
								echo "</td>";
							echo "</tr>";
						}
					//}

					// for payroll not full control
					/*if ($_SESSION["role"] == 3){
						echo "<tr id=".$row->emp_id.">";
							echo "<td><img id='profile_pic_table' class='profile-image-table' src='". $row->ProfilePath . "'/></td>";
							echo "<td>".$unfill_info."</td>";
							echo "<td>".$withAtm."</td>";
							echo "<td>".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname.  "</td>";
							echo "<td>".htmlspecialchars($row->Address)."</td>";
							echo "<td>".$position_val."</td>";
							echo "<td>".$active_value."</td>";
							echo "<td><center>";
								echo "<a href='#' id='edit_emp_info' class='action-a' title='Edit ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." Employee Info'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> Edit</a>";
							echo "</center></td>";
						echo "</tr>";
					}*/
				}
			}

		}

	} // end of function



	// for getting the info to table
	public function getEmployeeSalaryInformationToTable(){
		$connect = $this->connect();


		$select_qry = "SELECT * FROM tb_employee_info WHERE ActiveStatus = '1' ORDER BY Lastname ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){
				
				echo "<tr>";
					echo "<td>".$row->Lastname . ", " . $row->Firstname . " " . $row->Middlename."</td>";
					echo "<td>".number_format($row->Salary,2)."</td>";

					$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_emp_allowance WHERE emp_id = '$row->emp_id'"));

					$total_allowance = 0;
					if ($num_rows == 0){
						echo "<td>No allowance</td>";
					}

					else {
						echo "<td>";
						echo "<table class='table table-border'>
								<thead>
									<tr>
										<th>Allowance Type</th>
										<th>Value</th>
									</tr>
								</thead>
								<tbody>";
						$select_qry_allowance = "SELECT * FROM tb_emp_allowance WHERE emp_id = '$row->emp_id'";
						if ($result_allowance = mysqli_query($connect,$select_qry_allowance)) {
							while($row_allowance = mysqli_fetch_object($result_allowance)){

								$total_allowance += $row_allowance->AllowanceValue;
								echo "<tr>";
									echo "<td>".$row_allowance->AllowanceType."</td>";
									echo "<td>".number_format($row_allowance->AllowanceValue,2)."</td>";
								echo "</tr>";

									

							
							}
						}
						echo "</tbody>
							</table>";
						echo "</td>";

						
					}

					echo "<td>".number_format($row->Salary + $total_allowance,2)."</td>";



				echo "</tr>";
			}

		}

	} // end of function

	// check if contact number is exist
	public function contactNoExist($contact_num){
		$connect = $this->connect();

		$contact_num = mysqli_real_escape_string($connect,$contact_num);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE ContactNo='$contact_num'"));
		return $num_rows;
	}


	// check if email is exist
	public function emaillAddExist($email_address){
		$connect = $this->connect();

		$email_address = mysqli_real_escape_string($connect,$email_address);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE EmailAddress='$email_address'"));
		return $num_rows;
	}

	// check if sss no is exist
	public function sssNoExist($sssNo){
		$connect = $this->connect();

		$sssNo = mysqli_real_escape_string($connect,$sssNo);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE SSS_No='$sssNo'"));
		return $num_rows;
	}


	// check if sss no is exist
	public function pagibigNoExist($pagibigNo){
		$connect = $this->connect();

		$pagibigNo = mysqli_real_escape_string($connect,$pagibigNo);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE PagibigNo='$pagibigNo'"));
		return $num_rows;
	}

	// check if tin no is exist
	public function tinNoExist($tinNo){
		$connect = $this->connect();

		$tinNo = mysqli_real_escape_string($connect,$tinNo);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE TinNo='$tinNo'"));
		return $num_rows;
	}


	// check if tin no is exist
	public function philhealthNoExist($philhealthNo){
		$connect = $this->connect();
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE PhilhealthNo='$philhealthNo'"));
		return $num_rows;
	}



	// for updating emp information
	public function updateEmployee($lname,$fname,$mname,
									$address,$role,$department,$position,
									$birthdate,$gender,$contactNo,$email,
									$profileImage,$profilePath,$username,
									$password,$sssNo,$pagibigNo,$tinNo,$philhealthNo,$salary,$emp_id){

		$connect = $this->connect();
		
		//$biod_id = mysqli_real_escape_string($connect,$bio_id);
		$lname = mysqli_real_escape_string($connect,$lname);
		$fname = mysqli_real_escape_string($connect,$fname);
		$mname = mysqli_real_escape_string($connect,$mname);
		$address = mysqli_real_escape_string($connect,$address);
		$role = mysqli_real_escape_string($connect,$role);
		$department = mysqli_real_escape_string($connect,$department);
		$position = mysqli_real_escape_string($connect,$position);
		$birthdate = mysqli_real_escape_string($connect,$birthdate);
		$gender = mysqli_real_escape_string($connect,$gender);
		$contactNo = mysqli_real_escape_string($connect,$contactNo);
		$email = mysqli_real_escape_string($connect,$email);
		$profileImage = mysqli_real_escape_string($connect,$profileImage);
		$profilePath = mysqli_real_escape_string($connect,$profilePath);
		$username = mysqli_real_escape_string($connect,$username);
		$password = mysqli_real_escape_string($connect,$password);
		$sssNo = mysqli_real_escape_string($connect,$sssNo);
		$pagibigNo = mysqli_real_escape_string($connect,$pagibigNo);
		$tinNo = mysqli_real_escape_string($connect,$tinNo);
		$philhealthNo = mysqli_real_escape_string($connect,$philhealthNo);
		$salary = mysqli_real_escape_string($connect,$salary);

		$update_qry = "UPDATE tb_employee_info SET Lastname = '$lname', Firstname = '$fname',Middlename = '$mname',
													Address = '$address' , role_id = '$role' , dept_id = '$department',
													position_id = '$position' , Birthdate = '$birthdate', Gender = '$gender',
													ContactNo = '$contactNo',EmailAddress = '$email',Salary = '$salary',
													SSS_No = '$sssNo', PagibigNo = '$pagibigNo',TinNo = '$tinNo', PhilhealthNo = '$philhealthNo' WHERE emp_id = '$emp_id'";

		$sql = mysqli_query($connect,$update_qry);

	}


	// for change profile pic
	public function updateProfilePic($imageName,$path,$emp_id){
		$connect = $this->connect();

		$imageName = mysqli_real_escape_string($connect,$imageName);
		$path = mysqli_real_escape_string($connect,$path);
		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$emp_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		// for update query
		$update_qry = "UPDATE tb_employee_info SET ProfileImage = '$imageName', ProfilePath = '$path' WHERE emp_id = '$emp_id'";
		$sql = mysqli_query($connect,$update_qry);

	}


	// for checking when updating basic info is no updates
	public function sameBasicInfo($emp_id,$lastName,$firstName,$middleName,$civilStatus,$address,$birthdate,$gender,$contactNo,$emailAddress){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$lastName = mysqli_real_escape_string($connect,$lastName);
		$firstName = mysqli_real_escape_string($connect,$firstName);
		$middleName = mysqli_real_escape_string($connect,$middleName);
		$civilStatus = mysqli_real_escape_string($connect,$civilStatus);
		$address = mysqli_real_escape_string($connect,$address);
		$birthdate = mysqli_real_escape_string($connect,$birthdate);
		$gender = mysqli_real_escape_string($connect,$gender);
		$contactNo = mysqli_real_escape_string($connect,$contactNo);
		$emailAddress = mysqli_real_escape_string($connect,$emailAddress);


		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE Lastname='$lastName' AND Firstname = '$firstName' AND Middlename = '$middleName' 
															AND CivilStatus = '$civilStatus' AND Address = '$address' AND Birthdate = '$birthdate' 
															AND Gender = '$gender' AND ContactNo = '$contactNo' AND EmailAddress = '$emailAddress' AND emp_id = '$emp_id'"));

		return $num_rows;
	}


	// for update basic information
	public function updateBasicInfo($emp_id,$lastName,$firstName,$middleName,$civilStatus,$address,$birthdate,$gender,$contactNo,$emailAddress){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$lastName = mysqli_real_escape_string($connect,$lastName);
		$firstName = mysqli_real_escape_string($connect,$firstName);
		$middleName = mysqli_real_escape_string($connect,$middleName);
		$civilStatus = mysqli_real_escape_string($connect,$civilStatus);
		$address = mysqli_real_escape_string($connect,$address);
		$birthdate = mysqli_real_escape_string($connect,$birthdate);
		$gender = mysqli_real_escape_string($connect,$gender);
		$contactNo = mysqli_real_escape_string($connect,$contactNo);
		$emailAddress = mysqli_real_escape_string($connect,$emailAddress);

		$update_qry = "UPDATE tb_employee_info SET Lastname='$lastName', Firstname = '$firstName', Middlename = '$middleName' ,
														 CivilStatus = '$civilStatus', Address = '$address', Birthdate = '$birthdate',
														 Gender = '$gender', ContactNo = '$contactNo', EmailAddress = '$emailAddress' WHERE emp_id = '$emp_id'";
		$sql = mysqli_query($connect,$update_qry);												 

	}


	// for update company information
	public function updateCompanyInfo($emp_id,$department,$position,$salary,$working_hours_id,$head_emp_id,$company_id,$employment_type_stat,$working_days_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$department = mysqli_real_escape_string($connect,$department);
		$position = mysqli_real_escape_string($connect,$position);
		$salary = mysqli_real_escape_string($connect,$salary);
		//$dateHired = mysqli_real_escape_string($connect,$dateHired);
		$working_hours_id = mysqli_real_escape_string($connect,$working_hours_id);
		$head_emp_id = mysqli_real_escape_string($connect,$head_emp_id);
		$company_id = mysqli_real_escape_string($connect,$company_id);
		$employment_type_stat = mysqli_real_escape_string($connect,$employment_type_stat);
		$working_days_id = mysqli_real_escape_string($connect,$working_days_id);

		// for getting the dept_id of position for security issue
		$select_qry = "SELECT * FROM tb_position WHERE position_id = '$position'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		$dept_id = $row->dept_id;
		$update_qry = "UPDATE tb_employee_info SET dept_id='$dept_id', position_id='$position', Salary = '$salary', working_hours_id = '$working_hours_id', head_emp_id = '$head_emp_id', company_id = '$company_id', employment_type = '$employment_type_stat', working_days_id = '$working_days_id' WHERE emp_id='$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// checking when updating company information
	public function sameCompanyInfo($emp_id,$dept_id,$position_id,$salary,$dateHired,$working_hours_id,$head_emp_id,$company_id,$employment_type_stat,$working_days_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$dept_id = mysqli_real_escape_string($connect,$dept_id);
		$position_id = mysqli_real_escape_string($connect,$position_id);
		$salary = mysqli_real_escape_string($connect,$salary);
		$dateHired = mysqli_real_escape_string($connect,$dateHired);
		$working_hours_id = mysqli_real_escape_string($connect,$working_hours_id);
		$head_emp_id = mysqli_real_escape_string($connect,$head_emp_id);
		$company_id = mysqli_real_escape_string($connect,$company_id);
		$employment_type_stat = mysqli_real_escape_string($connect,$employment_type_stat);
		$working_days_id = mysqli_real_escape_string($connect,$working_days_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE dept_id='$dept_id' AND position_id = '$position_id' AND Salary = '$salary' AND emp_id = '$emp_id' AND DateHired = '$dateHired' AND working_hours_id = '$working_hours_id' AND head_emp_id ='$head_emp_id' AND company_id = '$company_id' AND employment_type = '$employment_type_stat' AND working_days_id = '$working_days_id'"));

		return $num_rows;
	}


	// checking when updating company information
	public function sameGovtInfo($emp_id,$sssNo,$pagibigNo,$tinNo,$philhealthNo){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$sssNo = mysqli_real_escape_string($connect,$sssNo);
		$pagibigNo = mysqli_real_escape_string($connect,$pagibigNo);
		$tinNo = mysqli_real_escape_string($connect,$tinNo);
		$philhealthNo = mysqli_real_escape_string($connect,$philhealthNo);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE SSS_No='$sssNo' AND PagibigNo = '$pagibigNo' AND tinNo = '$tinNo' AND PhilhealthNo = '$philhealthNo' AND emp_id = '$emp_id'"));

		return $num_rows;
	}

	// for update govt information
	public function updateGovtInfo($emp_id,$sssNo,$pagibigNo,$tinNo,$philhealthNo){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$sssNo = mysqli_real_escape_string($connect,$sssNo);
		$pagibigNo = mysqli_real_escape_string($connect,$pagibigNo);
		$tinNo = mysqli_real_escape_string($connect,$tinNo);
		$philhealthNo = mysqli_real_escape_string($connect,$philhealthNo);

		$update_qry = "UPDATE tb_employee_info SET SSS_No='$sssNo', PagibigNo='$pagibigNo', TinNo = '$tinNo', PhilhealthNo='$philhealthNo' WHERE emp_id='$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// checking when updating company information
	public function sameRoleInfo($emp_id,$role){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$role = mysqli_real_escape_string($connect,$role);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE role_id='$role' AND emp_id = '$emp_id'"));

		return $num_rows;
	}

	// for update account information
	public function updateAccountInfo($emp_id,$role){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$role = mysqli_real_escape_string($connect,$role);

		$update_qry = "UPDATE tb_employee_info SET role_id='$role' WHERE emp_id='$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for active status of employee
	public function updateActiveStatus($emp_id,$activeStatus,$resignation_date){
	//public function updateActiveStatus($emp_id,$activeStatus){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$activeStatus = mysqli_real_escape_string($connect,$activeStatus);
		$resignation_date = mysqli_real_escape_string($connect,$resignation_date);
		
		$select_qry = "UPDATE tb_employee_info SET ActiveStatus = '$activeStatus', resignation_date = '$resignation_date' WHERE emp_id = '$emp_id'";
		$sql = mysqli_query($connect,$select_qry);
		//$row = mysqli_query
	}


	// for getting the info to table used for biometrics ID
	public function getEmpInfoToTableBioReg(){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_employee_info ORDER BY Firstname ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){
				$select_position_qry = "SELECT * FROM tb_position WHERE position_id = '$row->position_id'";
				$result_position = mysqli_query($connect,$select_position_qry);
				$row_position = mysqli_fetch_object($result_position);
				$position_val = $row_position->Position;


				$select_dept_qry = "SELECT * FROM tb_department WHERE dept_id = '$row->dept_id'";
				$result_dept = mysqli_query($connect,$select_dept_qry);
				$row_dept = mysqli_fetch_object($result_dept);
				$dept_val = $row_dept->Department;

				$bio_id = $row->bio_id;
				if ($bio_id == 0){
					$bio_id = "No Bio Id Yet";
				}


				// emp_id = 1 for ADMIN
				if ($row->role_id != 1){
					echo "<tr id=".$row->emp_id.">";
						echo "<td>".$bio_id. "</td>";
						echo "<td>".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname. "</td>";
						echo "<td>".$dept_val."</td>";
						echo "<td>".$position_val."</td>";
						echo "<td>";
							echo "<a href='#' id='update_bio_id' class='action-a' title='Update Bio ID of ".$row->Firstname. " " .  $row->Middlename. " " .$row->Lastname." Employee Info'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> Update</a>";		
							echo " | ";
							echo "<a href='#' id='create_bio_id'><span class='glyphicon glyphicon-edit' style='color:#566573;'></span> Create</a>";
						echo "</td>";
					echo "</tr>";
				}
			}

		}

	} // end of function


	// for getting all user with role id with 2 and 1 of course the admin for notifications
	public function getEmpIdByRoleId(){
		$connect = $this->connect();

		$emp_id_values = "";
		$count = "";

		$select_qry = "SELECT * FROM tb_employee_info WHERE role_id = '1' OR role_id = '2'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){
				
				if ($emp_id_values == ""){
					$emp_id_values = $row->emp_id;
				}

				else {
					$emp_id_values = $emp_id_values . "#" . $row->emp_id;
				}

				$count++;


			}
		}

		return $emp_id_values;
	}



	// for getting all employee with role of 1
	public function getEmpIdRoleAdmin(){
		$connect = $this->connect();

		$emp_id_values = "";
		$count = "";

		$select_qry = "SELECT * FROM tb_employee_info WHERE role_id = '1' AND ActiveStatus = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){
				
				if ($emp_id_values == ""){
					$emp_id_values = $row->emp_id;
				}

				else {
					$emp_id_values = $emp_id_values . "#" . $row->emp_id;
				}

				$count++;


			}
		}

		return $emp_id_values;
	}



	// for getting employee count
	public function getAdminCount(){
		$connect = $this->connect();

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE role_id = '1' AND ActiveStatus = '1'"));

		return $num_rows;
	}


	// for getting all user with role id with 2 and 1 of course the admin for notifications
	public function getEmpIdByNotification($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);


		$row_emp = $this->getEmpInfoByRow($emp_id); // getting info by function

		$head_emp_id = $row_emp->head_emp_id;


		$emp_id_values = "";
		$count = "";

		// ibig sabihin wlang head so diretso kay mam rita
		if ($head_emp_id == 0) {
			$select_qry = "SELECT * FROM tb_employee_info WHERE role_id = '1' OR role_id = '2'";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){
					
					if ($emp_id_values == ""){
						$emp_id_values = $row->emp_id;
					}

					else {
						$emp_id_values = $emp_id_values . "#" . $row->emp_id;
					}

					$count++;


				}
			}
		}

		// if my head
		else {
			// insert to head first
			$select_qry = "SELECT * FROM tb_employee_info WHERE role_id = '1'";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){
					
					if ($emp_id_values == ""){
						$emp_id_values = $row->emp_id;
					}

					else {
						$emp_id_values = $emp_id_values . "#" . $row->emp_id;
					}

					$count++;


				}
			}

			$emp_id_values = $emp_id_values . "#" . $head_emp_id; // dagdag ung emp_id
		}

		return $emp_id_values;
	}

	// for getting all user with role id with 2 and 1 of course the admin for notifications
	public function getEmpIdByNotificationCount($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$row_emp = $this->getEmpInfoByRow($emp_id); // getting info by function

		$head_emp_id = $row_emp->head_emp_id;

		$count = 0;

		// ibig sabihin wlang head so diretso kay mam rita or sa mga hr
		if ($head_emp_id == 0) {
			$select_qry = "SELECT * FROM tb_employee_info WHERE role_id = '1' OR role_id = '2'";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){				
					$count++;
				}
			}
		}

		// if meron head
		else {

			$select_qry = "SELECT * FROM tb_employee_info WHERE role_id = '1'";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){				
					$count++;
				}
			}
			$count++; // for adding count only of head
		}

		return $count;
	}


	// for getting all user with role id with 2 and 1 of course the admin for notifications
	public function getEmpIdByRoleIdCount(){
		$connect = $this->connect();

		$count = 0;

		$select_qry = "SELECT * FROM tb_employee_info WHERE role_id = '1' OR role_id = '2'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){				
				$count++;
			}
		}

		return $count;
	}



	// for selecting all fields with unfill up
	public function getUnfillUpFields($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$select_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$emp_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		$middleName = $row->Middlename;
		$contactNo = $row->ContactNo;
		$emailAddress = $row->EmailAddress;
		$sssNo = $row->SSS_No;
		$pagibigNo = $row->PagibigNo;
		$tinNo = $row->TinNo;
		$philhealthNo = $row->PhilhealthNo;

		$unfillUp_fields = "";
		if ($middleName == ""){
			$unfillUp_fields = "<center>Middlename</center>";
		}

		if ($contactNo == ""){
			$unfillUp_fields = $unfillUp_fields . "<center>Contact Number</center>";
		}

		if ($emailAddress == ""){
			$unfillUp_fields = $unfillUp_fields . "<center>Email Address</center>";
		}

		if ($sssNo == ""){
			$unfillUp_fields = $unfillUp_fields . "<center>SSS No</center>";
		}

		if ($pagibigNo == ""){
			$unfillUp_fields = $unfillUp_fields . "<center>Pag-ibig No</center>";
		}

		if ($tinNo == ""){
			$unfillUp_fields = $unfillUp_fields . "<center>Tin No</center>";
		}

		if ($philhealthNo == ""){
			$unfillUp_fields = $unfillUp_fields . "<center>Philhealth No</center>";
		}

		return $unfillUp_fields;


	}

	// for changing password
	public function changePassword($emp_id,$newPassword){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$newPassword = mysqli_real_escape_string($connect,$newPassword);

		$update_qry = "UPDATE tb_employee_info SET Password = '$newPassword' WHERE emp_id = '$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for updating atm status
	public function updateATMstatus($emp_id,$atmAccountNo){
		$connect = $this->connect();


		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$atmAccountNo = mysqli_real_escape_string($connect,$atmAccountNo);

		$atmStatus = $this->getEmpInfoByRow($emp_id)->WithAtm;


		$newAtmStatus = 0;
		if ($atmStatus == 0){
			$newAtmStatus = 1;
		}


		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$update_qry = "UPDATE tb_employee_info SET WithAtm = '$newAtmStatus', atmAccountNumber = '$atmAccountNo' WHERE emp_id = '$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}




	// for getting all active emp id
	public function getEmpIdAllActiveEmp(){
		$connect = $this->connect();




		$all_emp_id = "";
		$select_qry = "SELECT * FROM tb_employee_info WHERE ActiveStatus = '1' AND (role_id != '1' OR dept_id != '1') ORDER BY Lastname ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				if ($all_emp_id == "") {
					$all_emp_id = $row->emp_id;
				}

				else {
					$all_emp_id = $all_emp_id . "#" . $row->emp_id;
				}

			}
		}


		return $all_emp_id;


	}


	// for getting the count of all emp_id with active status
	// for getting all active emp id
	public function getCountActiveEmp(){
		$connect = $this->connect();
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE ActiveStatus = '1' AND (role_id != '1' OR dept_id != '1') ORDER BY Lastname ASC"));
		return $num_rows;


	}


	// for getting all username and password to table
	public function getUsernamePasswordInfoToTable(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_employee_info ORDER BY Lastname ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$fullName = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
				if ($row->Middlename == ""){
					$fullName = $row->Lastname . ", " . $row->Firstname;
				}

				$password_length = strlen($row->Password);
				$counter = 0;
				$password_asterisk = ""; // for initiazation value
				do {
					if ($password_asterisk == ""){
						$password_asterisk = "*";
					}
					else {
						$password_asterisk = $password_asterisk . "*";
					}
					
					$counter++;
				}while($counter < $password_length);

				echo "<tr id='".$row->emp_id."'>";
					echo "<td>" . $fullName . "</td>";
					echo "<td>" . $row->Username. "</td>";
					echo "<td><span id='password".$row->emp_id."'>$password_asterisk</span><button type='button' title='click me to show password' id='show_password' class='btn btn-primary btn-sm pull-right' style='padding:2px;'><span class='glyphicon glyphicon-eye-open'><span></button></td>";
				echo "</tr>";
			}
		}
	}


	// for searching employee information
	/*public function searchEmployeeName($search){
		$connect = $this->connect();

		$search = mysqli_real_escape_string($connect,$search);

		$json = false;
		$select_qry = "SELECT * FROM tb_employee_info WHERE Lastname LIKE '%$search%' OR Firstname LIKE '%$search%' OR Middlename LIKE '%$search%'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$name = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;

				if ($row->Middlename == ""){
					$name = $row->Lastname . ", " . $row->Firstname;
				}

				$json[] =
			        //'name' => $name
			        $name;


			}
		}

		echo json_encode($json);

	}*/


	public function searchEmployeeOnly($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$array= array();
		$select_qry = "SELECT * FROM tb_employee_info WHERE ActiveStatus = '1' AND (role_id != '1' OR dept_id != '1') AND emp_id != '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$name = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;

				if ($row->Middlename == ""){
					$name = $row->Lastname . ", " . $row->Firstname;
				}

				$array[] =
			        //'name' => $name
			        $name;

			}
		}

		return json_encode($array) . ";";

	}


	public function searchEmployeeName(){
		$connect = $this->connect();

		//$search = mysqli_real_escape_string($connect,$search);

		$array= array();
		$select_qry = "SELECT * FROM tb_employee_info WHERE(role_id != '1' OR dept_id != '1')"; //AND ActiveStatus = '1'
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$name = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;

				if ($row->Middlename == ""){
					$name = $row->Lastname . ", " . $row->Firstname;
				}

				$array[] =
			        //'name' => $name
			        $name;

			}
		}

		return json_encode($array) . ";";

	}



	public function searchAdminAndPayrollAdmin(){
		$connect = $this->connect();

		//$search = mysqli_real_escape_string($connect,$search);

		$array= array();
		$select_qry = "SELECT * FROM tb_employee_info WHERE ActiveStatus = '1' AND (role_id != '1' OR dept_id != '1')";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$name = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;

				if ($row->Middlename == ""){
					$name = $row->Lastname . ", " . $row->Firstname;
				}

				$array[] =
			        //'name' => $name
			        $name;

			}
		}

		return json_encode($array) . ";";

	}


	// for checking if minessage nya ung sarili nya
	public function checkMessageSelf($emp_id,$emp_name){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$emp_name = mysqli_real_escape_string($connect,$emp_name);

		$existData = 0;

		$select_qry = "SELECT * FROM tb_employee_info";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$emp_id_db = $row->emp_id;
				$emp_name_db = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;

				if ($row->Middlename == ""){
					$emp_name_db = $row->Lastname . ", " . $row->Firstname;
				}
				
				if ($emp_id == $emp_id_db && $emp_name == $emp_name_db){
					$existData = 1;
				}


			}

		}

		return $existData;

	}

	// kapag ni isa wlang equal ibig sabihin d exist
	public function checkExistEmployeeName($emp_name){
		$connect = $this->connect();

		$emp_name = mysqli_real_escape_string($connect,$emp_name);

		$has_error = 1;
		$select_qry = "SELECT * FROM tb_employee_info WHERE ActiveStatus = '1' AND (role_id != '1' OR dept_id != '1')";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$name = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
				if ($row->Middlename == ""){
					$name = $row->Lastname . ", " . $row->Firstname;
				}

				if ($emp_name == $name){
					$has_error = 0;
				}	

			}
		}

		return $has_error;

	}



	// kapag ni isa wlang equal ibig sabihin d exist
	public function checkExistEmployeeNameByRole($emp_name,$role){
		$connect = $this->connect();

		$emp_name = mysqli_real_escape_string($connect,$emp_name);
		$role = mysqli_real_escape_string($connect,$role);


		$has_error = 1;

		// ibig sabihin admin at hr admin
		if ($role == 1 || $role == 2){
			$select_qry = "SELECT * FROM tb_employee_info WHERE ActiveStatus = '1' AND (role_id != '1' OR dept_id != '1')";
		}

		// ibig sabihin payroll admin ay normal user
		else {
			$select_qry = "SELECT * FROM tb_employee_info WHERE ActiveStatus = '1' AND role_id != '4' AND role_id != '3' AND (role_id != '1' OR dept_id != '1')";
		}

		
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$name = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
				if ($row->Middlename == ""){
					$name = $row->Lastname . ", " . $row->Firstname;
				}

				if ($emp_name == $name){
					$has_error = 0;
				}	

			}
		}

		return $has_error;

	}



	// take the emp id of the current employee name
	public function getEmpIdByEmployeeName($emp_name){
		$connect = $this->connect();

		$emp_name = mysqli_real_escape_string($connect,$emp_name);

		$emp_id = 0;
		$select_qry = "SELECT * FROM tb_employee_info WHERE ActiveStatus = '1' AND (role_id != '1' OR dept_id != '1')";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$name = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
				if ($row->Middlename == ""){
					$name = $row->Lastname . ", " . $row->Firstname;
				}

				if ($emp_name == $name){
					$emp_id = $row->emp_id;
				}	

			}
		}

		return $emp_id;

	}


	// for getting head_emp_name by head_emp_id
	public function geyHeadsNameByHeadEmpId($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$emp_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);


		$name = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
		if ($row->Middlename == ""){
			$name = $row->Lastname . ", " . $row->Firstname;
		}

		return $name;
	}


	// check if the your are head of a certain employee
	public function alreadyHead($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE head_emp_id = '$emp_id'"));
		return $num_rows;

	}


	// for showing all employee with atm
	public function getAtmAccountNoListToTable(){
		$connect = $this->connect();

		$user_id = $_SESSION["id"];

		$select_qry = "SELECT * FROM tb_employee_info WHERE WithAtm = '1' ORDER BY Lastname ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$empName = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
				if ($row->Middlename == ""){
					$empName = $row->Lastname . ", " . $row->Firstname;
				}

				// for not miss yvette
				if ($user_id != 21){

					echo "<tr id='".$row->emp_id."'>";
						echo "<td>" .$empName. "</td>";
						echo "<td>". $row->atmAccountNumber . "</td>";
						echo "<td>";
						 echo "<div id='edit_atm_info' style='cursor:pointer;color:#158cba'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> Edit</div>";
						echo "</td>"; 
					echo "</tr>";
				}

				else {
					echo "<tr id='".$row->emp_id."'>";
						echo "<td>" .$empName. "</td>";
						echo "<td>". $row->atmAccountNumber . "</td>";
						echo "<td>";
						 //echo "<div id='edit_atm_info' style='cursor:pointer;color:#158cba'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> Edit</div>";
							echo "No action";
						echo "</td>"; 
					echo "</tr>";
				}
			}
		}
	}


	// for checking if no changes was apply
	public function checkNoChangesUpdateAtmAccountNo($emp_id,$atmAccountNumber){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$atmAccountNumber = mysqli_real_escape_string($connect,$atmAccountNumber);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE emp_id = '$emp_id' AND atmAccountNumber ='$atmAccountNumber'"));
		return $num_rows;
	}


	public function updateAtmAccountNo($emp_id,$atmAccountNo){
		$connect = $this->connect();


		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$atmAccountNo = mysqli_real_escape_string($connect,$atmAccountNo);


		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$update_qry = "UPDATE tb_employee_info SET atmAccountNumber = '$atmAccountNo' WHERE emp_id = '$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}



	// for printing reports for atm
	public function printAtmAccountNoReports(){
			$connect = $this->connect();


		include "excel-report/PHPExcel/Classes/PHPExcel.php";

		$filename = "atm_account_number_reports";
		$objPHPExcel = new PHPExcel();
		/*********************Add column headings START**********************/
		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A1', 'Employee Name')
					->setCellValue('B1', 'ATM Account Number');
					//->setCellValue('D1', 'Total count of login')
					//->setCellValue('E1', 'Facebook Login');
		/*********************Add column headings END**********************/
		
		// You can add this block in loop to put all ur entries.Remember to change cell index i.e "A2,A3,A4" dynamically 
		/*********************Add data entries START**********************/
		
		$count = 1;
		$select_qry = "SELECT * FROM tb_employee_info WHERE WithAtm = '1' ORDER BY Lastname ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$empName = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;


				$count++;
				$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A'.$count, $empName)
					->setCellValue('B'.$count, $row->atmAccountNumber);
			}
		}

		
					//->setCellValue('D2', '5')
				//	->setCellValue('E2', 'No');
		/*********************Add data entries END**********************/
		
		/*********************Autoresize column width depending upon contents START**********************/
        foreach(range('A','B') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}
		/*********************Autoresize column width depending upon contents END***********************/
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true); //Make heading font bold
		
		/*********************Add color to heading START**********************/
		$objPHPExcel->getActiveSheet()
					->getStyle('A1:B1')
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('abb2b9');
		/*********************Add color to heading END***********************/
		
		$objPHPExcel->getActiveSheet()->setTitle('atm_account_number_reports'); //give title to sheet
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;Filename=$filename.xls");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	
	}



	// for reports employee list in excel
	public function printEmployeeListReports(){
			$connect = $this->connect();


		include "excel-report/PHPExcel/Classes/PHPExcel.php";

		$filename = "employee_list_reports";
		$objPHPExcel = new PHPExcel();
		/*********************Add column headings START**********************/
		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A1', 'Status')
					->setCellValue('B1', 'Employee Name')
					->setCellValue('C1', 'Address')
					->setCellValue('D1', 'Contact No.')
					->setCellValue('E1', 'Birthdate')
					->setCellValue('F1', 'Department')
					->setCellValue('G1', 'Position')
					->setCellValue('H1', 'Civil Status')
					->setCellValue('I1', 'SSS No.')
					->setCellValue('J1', 'Pagibig No.')
					->setCellValue('K1', 'Philhealth No.')
					->setCellValue('L1', 'TIN No.')
					->setCellValue('M1', 'Salary')
					->setCellValue('N1', 'Total Allowance')
					->setCellValue('O1', 'Date Hired')
					->setCellValue('P1', 'Dependent Name');
					//->setCellValue('D1', 'Total count of login')
					//->setCellValue('E1', 'Facebook Login');
		/*********************Add column headings END**********************/
		
		// You can add this block in loop to put all ur entries.Remember to change cell index i.e "A2,A3,A4" dynamically 
		/*********************Add data entries START**********************/
		
		$count = 1;
		$status  = "";
		$select_qry = "SELECT * FROM tb_employee_info ORDER BY Lastname ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$status = "Active";

				if ($row->ActiveStatus == 0){
					$status = "Inactive";
				}


				$empName = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;

				$select_position_qry = "SELECT * FROM tb_position WHERE position_id = '$row->position_id'";
				$result_position = mysqli_query($connect,$select_position_qry);
				$row_position = mysqli_fetch_object($result_position);
				$position_val = $row_position->Position;


				$select_dept_qry = "SELECT * FROM tb_department WHERE dept_id = '$row->dept_id'";
				$result_dept = mysqli_query($connect,$select_dept_qry);
				$row_dept = mysqli_fetch_object($result_dept);
				$dept_val = $row_dept->Department;


				$allowance = 0;

				$has_allowance = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_emp_allowance WHERE emp_id = '$row->emp_id'"));

				if ($has_allowance != 0){
					$select_allowance_qry = "SELECT * FROM tb_emp_allowance  WHERE emp_id = '$row->emp_id'";
					if ($result_allowance = mysqli_query($connect,$select_allowance_qry)){
						while($row_allowance = mysqli_fetch_object($result_allowance)){

							$allowance += $row_allowance->AllowanceValue;
						}
					}
				}


				$dependent_name = "";

				$has_dependent = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_dependent WHERE emp_id = '$row->emp_id'"));

				if ($has_dependent != 0){
					$select_dependent_qry = "SELECT * FROM tb_dependent  WHERE emp_id = '$row->emp_id'";
					if ($result_dependent = mysqli_query($connect,$select_dependent_qry)){
						while($row_dependent = mysqli_fetch_object($result_dependent)){

							if ($dependent_name == ""){
								$dependent_name .= $row_dependent->Fullname;
							}
							else {
								$dependent_name .= "," . $row_dependent->Fullname;
							}
						}
					}
				}



				$count++;
				$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A'.$count, $status)
					->setCellValue('B'.$count, $empName)
					->setCellValue('C'.$count, $row->Address)
					->setCellValue('D'.$count, $row->ContactNo)
					->setCellValue('E'.$count, $row->Birthdate)
					->setCellValue('F'.$count, $dept_val)
					->setCellValue('G'.$count, $position_val)
					->setCellValue('H'.$count, $row->CivilStatus)
					->setCellValue('I'.$count, $row->SSS_No)
					->setCellValue('J'.$count, $row->PagibigNo)
					->setCellValue('K'.$count, $row->PhilhealthNo)
					->setCellValue('L'.$count, $row->TinNo)
					->setCellValue('M'.$count, $row->Salary)
					->setCellValue('N'.$count, $allowance)
					->setCellValue('O'.$count, $row->DateHired)
					->setCellValue('P'.$count, $dependent_name);
			}
		}

		
					//->setCellValue('D2', '5')
				//	->setCellValue('E2', 'No');
		/*********************Add data entries END**********************/
		
		/*********************Autoresize column width depending upon contents START**********************/
        foreach(range('A','P') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}
		/*********************Autoresize column width depending upon contents END***********************/
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true); //Make heading font bold
		
		/*********************Add color to heading START**********************/
		$objPHPExcel->getActiveSheet()
					->getStyle('A1:P1')
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('abb2b9');
		/*********************Add color to heading END***********************/
		
		$objPHPExcel->getActiveSheet()->setTitle('atm_account_number_reports'); //give title to sheet
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;Filename=$filename.xls");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	
	}



	// for adding to increase table
	public function insertIncreaseSalary($emp_id,$old_salary,$new_salary,$date_increase){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$old_salary = mysqli_real_escape_string($connect,$old_salary);
		$new_salary = mysqli_real_escape_string($connect,$new_salary);
		$date_increase = mysqli_real_escape_string($connect,$date_increase);

		$insert_qry = "INSERT INTO tb_increase_salary (emp_id,old_salary,new_salary,date_increase) VALUES
						('$emp_id','$old_salary','$new_salary','$date_increase')";

		$sql = mysqli_query($connect,$insert_qry);
	}


	// for update salary
	public function updateSalary($emp_id,$new_salary){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$new_salary = mysqli_real_escape_string($connect,$new_salary);

		$update_qry = "UPDATE tb_employee_info SET Salary = '$new_salary' WHERE emp_id = '$emp_id'";

		$sql = mysqli_query($connect,$update_qry);
	}


	// for get increase info by id
	public function getIncreaseInfoById($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_increase_salary WHERE emp_id='$emp_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
	}


	// for checking if exist ung id sa tb_increase_salary
	public function checkExistIncreaseCutOff($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_increase_salary WHERE emp_id = '$emp_id'"));
		

		//echo $num_rows . "<br/>";

		$exist = 0;
		if ($num_rows != 0){

			$row = $this->getIncreaseInfoById($emp_id);


			$date_increase = date_format(date_create($row->date_increase), 'Y-m-d');

			date_default_timezone_set("Asia/Manila");
			//$date = date_create("1/1/1990");

			$dates = date("Y-m-d H:i:s");
			$date = date_create($dates);
			//date_sub($date, date_interval_create_from_date_string('15 hours'));

			// $current_date_time = date_format($date, 'Y-m-d H:i:s');
			$current_date_time = date_format($date, 'Y-m-d');

			//echo $current_date_time;
			$year = date("Y");

			// for cutoff
			$select_cutoff_qry = "SELECT * FROM tb_cut_off";
			if ($result_cutoff = mysqli_query($connect,$select_cutoff_qry)){
				while($row_cutoff = mysqli_fetch_object($result_cutoff)){
					//$date_to = $row_cutoff->dateTo . ", " .$year;

					$date_from = date_format(date_create($row_cutoff->dateFrom . ", " .$year),'Y-m-d');
					if (date_format(date_create($row_cutoff->dateFrom),'m-d') == "12-26"){
					//echo "wew";
					$prev_year = $year - 1;
					$date_from = $prev_year . "-" .date_format(date_create($row_cutoff->dateFrom),'m-d');
					//echo $date_from . "sad";
					//$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

				}
				$date_from = date_format(date_create($date_from),"Y-m-d");
					$date_to = date_format(date_create($row_cutoff->dateTo. ", " .$year),'Y-m-d');
					//$to =  date("Y-m-d",strtotime($date_to) + (86400 *5));
					//echo $to . "<br/>";


					$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

					
					if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
						$final_date_from = $date_from;
						$final_date_to = $date_to;
						$date_payroll = date_format(date_create($row_cutoff->datePayroll . ", " .$year),'Y-m-d');
					}


				}

			} // end of if


			if ($final_date_from <= $date_increase && $final_date_to >= $date_increase){
				$exist = 1;
			}



		}


		return $exist;



		//return $num_rows;
	}


	public function insertEmployeeEducation($emp_id,$type,$school_name,$course,$year_from,$year_to){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$type = mysqli_real_escape_string($connect,$type);
		$school_name = mysqli_real_escape_string($connect,$school_name);
		$course = mysqli_real_escape_string($connect,$course);
		$year_from = mysqli_real_escape_string($connect,$year_from);
		$year_to = mysqli_real_escape_string($connect,$year_to);

		$insert_qry = "INSERT INTO tb_emp_education_attain (emp_id,type,school_name,course,year_from,year_to) VALUES
						('$emp_id','$type','$school_name','$course','$year_from','$year_to')";

		$sql = mysqli_query($connect,$insert_qry);
	}


	// for delete events info
	public function deleteSchoolInformation($emp_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$delete_qry = "DELETE FROM tb_emp_education_attain WHERE emp_id='$emp_id'";
		$sql = mysqli_query($connect,$delete_qry);
	}


	// for delete events info
	public function deleteWorkExperience($emp_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$delete_qry = "DELETE FROM tb_emp_work_experience WHERE emp_id='$emp_id'";
		$sql = mysqli_query($connect,$delete_qry);
	}


	public function updateEmployeeEducation($emp_id,$type,$school_name,$course,$year_from,$year_to){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$type = mysqli_real_escape_string($connect,$type);
		$school_name = mysqli_real_escape_string($connect,$school_name);
		$course = mysqli_real_escape_string($connect,$course);
		$year_from = mysqli_real_escape_string($connect,$year_from);
		$year_to = mysqli_real_escape_string($connect,$year_to);

		$insert_qry = "UPDATE tb_emp_education_attain SET type = '$type', school_name = '$school_name' , course = '$course'
							 , year_from = '$year_from',year_to = '$year_to' WHERE emp_id = '$emp_id'";

		$sql = mysqli_query($connect,$insert_qry);
	}




	public function insertEmployeeWorkExperience($emp_id,$position,$company_name,$job_description,$year_from,$year_to){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$position = mysqli_real_escape_string($connect,$position);
		$company_name = mysqli_real_escape_string($connect,$company_name);
		$job_description = mysqli_real_escape_string($connect,$job_description);
		$year_from = mysqli_real_escape_string($connect,$year_from);
		$year_to = mysqli_real_escape_string($connect,$year_to);

		$insert_qry = "INSERT INTO tb_emp_work_experience (emp_id,position,company_name,job_description,year_from,year_to) VALUES
						('$emp_id','$position','$company_name','$job_description','$year_from','$year_to')";

		$sql = mysqli_query($connect,$insert_qry);
	}


	public function getSchoolInfoByUserId($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		//echo $emp_id;
		$is_first = 0;
		$select_qry = "SELECT * FROM tb_emp_education_attain WHERE emp_id='$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){



				//echo $row->type;
				if ($row->type == 0){
					echo '<div class="form-group">
							<div class="col-sm-12">
				              <label style="color: #27ae60 "><i>Secondary Information</i></label>
				             </div>
				            <div class="col-sm-8">
				              <label class="control-label"><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> School Name:&nbsp;<span class="red-asterisk">*</span></label>
				              <input type="text" name="school_name[]" class="form-control" value="'.$row->school_name.'" required="required"/>
				            </div>
				            <div class="col-sm-6">
				              <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>
				              <input type="text" name="year_from[]" class="form-control" value="'.$row->year_from.'" required="required" id="year_only" placeholder="year from"/>
				            </div>
				            <div class="col-sm-6">
				              <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>
				              <input type="text" id="year_only" name="year_to[]" value="'.$row->year_to.'" class="form-control" required="required" placeholder="year to"/>
				            </div>
				            <div class="col-sm-3" style="display: none">
					          <label class="control-label"><span class="glyphicon glyphicon-education" style="color:#2E86C1;"></span> Course:&nbsp;<span class="red-asterisk">*</span></label>
					          <textarea class="form-control" name="course[]"></textarea>
					        </div>
			          	</div>';
				}

				else {

					if ($is_first == 0){
						$is_first = 1;
						echo '<div class="form-group" style="border-bottom:1px solid #616a6b;padding-bottom:5px">
							<div class="col-sm-12">
				              <label style="color: #27ae60 "><i>Tertiary Information</i></label>
				             </div>
							<div class="col-sm-6">
					          <label class="control-label"><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> School Name:&nbsp;<span class="red-asterisk">*</span></label>
					          <input type="text" name="school_name[]" value="'.$row->school_name.'" class="form-control" required="required"/>
					        </div>
					        <div class="col-sm-6">
					          <label class="control-label"><span class="glyphicon glyphicon-education" style="color:#2E86C1;"></span> Course:&nbsp;<span class="red-asterisk">*</span></label>
					          <textarea class="form-control" name="course[]" reuired="required">'.$row->course.'</textarea>
					        </div>
					        <div class="col-sm-5">
					          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>
					          <input type="text" name="year_from[]" id="year_only" class="form-control" value="'.$row->year_from.'" required="required" placeholder="year from"/>
					        </div>
					        <div class="col-sm-5">
					          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>
					          <input type="text" name="year_to[]" id="year_only" class="form-control" value="'.$row->year_to.'" required="required" placeholder="year to"/>
					        </div>
					        <div class="col-md-2">
					          <button id="add_education_attain" class="btn btn-primary btn-sm" type="button" style="margin-top:30px"><span class="glyphicon glyphicon-plus"></span></button>
					        </div>
				        </div>';
			        }

			        else {

			        	echo '<div class="form-group">
							<div class="col-sm-6">
					          <label class="control-label"><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> School Name:&nbsp;<span class="red-asterisk">*</span></label>
					          <input type="text" name="school_name[]" class="form-control" required="required" value="'.$row->school_name.'"/>
					        </div>
					        <div class="col-sm-6">
					          <label class="control-label"><span class="glyphicon glyphicon-education" style="color:#2E86C1;"></span> Course:&nbsp;<span class="red-asterisk">*</span></label>
					          <textarea class="form-control" name="course[]" reuired="required">'.$row->course.'</textarea>
					        </div>
					        <div class="col-sm-5">
					          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>
					          <input type="text" name="year_from[]" id="year_only" class="form-control" value="'.$row->year_from.'" required="required" placeholder="year from"/>
					        </div>
					        <div class="col-sm-5">
					          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>
					          <input type="text" name="year_to[]" id="year_only" class="form-control" value="'.$row->year_to.'" required="required" placeholder="year to"/>
					        </div>
					        <div class="col-md-2">
					          <button id="remove_education_attain" class="btn btn-danger btn-sm" type="button" style="margin-top:30px"><span class="glyphicon glyphicon-remove"></span></button>
					        </div>
				        </div>';
			        }
				}

			}
		}
		


	}


	public function getWorkExperienceById($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_emp_work_experience WHERE emp_id = '$emp_id'"));
		
		if ($num_rows == 0){
			echo '<div class="form-group">
					<div class="col-md-12">
						<button class="btn btn-primary btn-xs pull-right" id="add_work_xp" type="button" style="margin-top:30px"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add
						</button>
					</div>
				</div>


				<div class="form-group">	
					<div class="col-sm-5">						
						<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Position&nbsp;<span class="red-asterisk">*</span></label>
						<input type="text" name="work_position[]" value="" id="txt_only" class="form-control" placeholder="Enter Position" required="required">
					</div>

					<div class="col-sm-7">						
						<label class="control-label"><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Company Name&nbsp;<span class="red-asterisk">*</span></label>
						<input type="text" name="company_name[]" value="" id="txt_only" value="" class="form-control" placeholder="Enter Company Name" required="required">
					</div>
					<div class="col-sm-8">						
						<label class="control-label"><span class="glyphicon glyphicon-info-sign" style="color:#2E86C1;"></span> Job Description</label>
						<textarea class="form-control" name="job_description[]" placeholder="Job Description" required="required"></textarea>
					</div>
					<div class="col-sm-4">
			          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>
			          <input type="text" id="year_only" name="work_year_from[]" value="" class="form-control" required="required" placeholder="year from"/>
			        </div>
			        <div class="col-sm-4">
			          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>
			          <input type="text" name="work_year_to[]" id="year_only" value="" class="form-control" required="required" placeholder="year to"/>
			        </div>
				</div>';
		}

		else {

			//echo $emp_id;
			$is_first = 0;
			$select_qry = "SELECT * FROM tb_emp_work_experience WHERE emp_id='$emp_id'";
			if ($result = mysqli_query($connect,$select_qry)){
				while ($row = mysqli_fetch_object($result)){

					if ($is_first == 0){
						$is_first = 1;

						echo '<div class="form-group">
							<div class="col-md-12">
								<button class="btn btn-primary btn-xs pull-right" id="add_work_xp" type="button" style="margin-top:30px"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add
								</button>
							</div>
						</div>


						<div class="form-group">	
							<div class="col-sm-5">						
								<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Position&nbsp;<span class="red-asterisk">*</span></label>
								<input type="text" name="work_position[]" value="'.$row->position.'" id="txt_only" class="form-control" placeholder="Enter Position" required="required">
							</div>

							<div class="col-sm-7">						
								<label class="control-label"><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Company Name&nbsp;<span class="red-asterisk">*</span></label>
								<input type="text" name="company_name[]" value="'.$row->company_name.'" id="txt_only" value="" class="form-control" placeholder="Enter Company Name" required="required">
							</div>
							<div class="col-sm-8">						
								<label class="control-label"><span class="glyphicon glyphicon-info-sign" style="color:#2E86C1;"></span> Job Description</label>
								<textarea class="form-control" name="job_description[]" placeholder="Job Description" required="required">'.$row->job_description.'</textarea>
							</div>
							<div class="col-sm-4">
					          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>
					          <input type="text" id="year_only" name="work_year_from[]" value="'.$row->year_from.'" class="form-control" required="required" placeholder="year from"/>
					        </div>
					        <div class="col-sm-4">
					          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>
					          <input type="text" name="work_year_to[]" id="year_only" value="'.$row->year_to.'" class="form-control" required="required" placeholder="year to"/>
					        </div>
						</div>';
					}

					else {
						echo '<div class="form-group">
							<div class="col-md-12">
								<button class="btn btn-danger btn-xs pull-right" id="remove_work_xp" type="button" style="margin-top:30px"><span class="glyphicon glyphicon-remove"></span>&nbsp;Remove
								</button>
							</div>
						</div>


						<div class="form-group">	
							<div class="col-sm-5">						
								<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Position&nbsp;<span class="red-asterisk">*</span></label>
								<input type="text" name="work_position[]" value="'.$row->position.'" id="txt_only" class="form-control" placeholder="Enter Position" required="required">
							</div>

							<div class="col-sm-7">						
								<label class="control-label"><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Company Name&nbsp;<span class="red-asterisk">*</span></label>
								<input type="text" name="company_name[]" value="'.$row->company_name.'" id="txt_only" value="" class="form-control" placeholder="Enter Company Name" required="required">
							</div>
							<div class="col-sm-8">						
								<label class="control-label"><span class="glyphicon glyphicon-info-sign" style="color:#2E86C1;"></span> Job Description</label>
								<textarea class="form-control" name="job_description[]" placeholder="Job Description" required="required">'.$row->job_description.'</textarea>
							</div>
							<div class="col-sm-4">
					          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>
					          <input type="text" id="year_only" name="work_year_from[]"  value="'.$row->year_from.'" class="form-control" required="required" placeholder="year from"/>
					        </div>
					        <div class="col-sm-4">
					          <label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>
					          <input type="text" name="work_year_to[]" id="year_only" value="'.$row->year_to.'" class="form-control" required="required" placeholder="year to"/>
					        </div>
						</div>';
					}	

				

				}
			}
		}
	}


	// for update bio ID success
	public function updateDucationalAttainment($emp_id,$education_attain){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$education_attain = mysqli_real_escape_string($connect,$education_attain);

		$update_qry = "UPDATE tb_employee_info SET highest_educational_attain = '$education_attain' WHERE  emp_id='$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	public function getViewSchoolInfoById($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		//echo $emp_id;
		$is_first = 0;
		$select_qry = "SELECT * FROM tb_emp_education_attain WHERE emp_id='$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				//echo "wew";

				//echo $row->type;
				if ($row->type == 0){

					//echo "wew";

					echo '<div class="col-xs-12 col-sm-12">
						<label style="color: #27ae60 "><i>Secondary Information</i></label> <br/>
		 				<b>'.$row->school_name.'</b> <br/>
		 				'.$row->year_from . " - " . $row->year_to.'
	 				</div>';

	 				
				}

				else {

					if ($is_first == 0){
						$is_first = 1;
						echo '<div class="col-xs-12 col-sm-12"  style="margin-top:15px">
							<label style="color: #27ae60 "><i>Tertiary Information</i></label> <br/>
			 				<b>'.$row->school_name.'</b> <br/>
			 				'.$row->course.' <br/>
			 				'.$row->year_from . " - " . $row->year_to.'
		 				</div>';
					}

					else {
						echo '<div class="col-xs-12 col-sm-12">
			 				<b>'.$row->school_name.'</b> <br/>
			 				'.$row->course.' <br/>
			 				'.$row->year_from . " - " . $row->year_to.'
		 				</div>';
					}

					
					
				}

			}
		}
		


	}


	public function getViewWorkExperienceById($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		//echo $emp_id;
		$select_qry = "SELECT * FROM tb_emp_work_experience WHERE emp_id='$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				//echo "wew";

				
				echo '<div class="col-xs-12 col-sm-12">
	 				<b>'.$row->position.'</b> <br/>
	 				<b>'.$row->company_name.'</b> <br/>
	 				'.$row->job_description.' <br/>
	 				'.$row->year_from . " - " . $row->year_to.'
					</div>';
					

					
					
				

			}
		}
	}



	// for getting all employee with role of 1
	public function getEmpIdRoleHR(){
		$connect = $this->connect();

		$emp_id_values = "";
		$count = "";

		$select_qry = "SELECT * FROM tb_employee_info WHERE role_id = '2' AND ActiveStatus = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){
				
				if ($emp_id_values == ""){
					$emp_id_values = $row->emp_id;
				}

				else {
					$emp_id_values = $emp_id_values . "#" . $row->emp_id;
				}

				$count++;


			}
		}

		return $emp_id_values;
	}



	// for getting employee count
	public function getHRCount(){
		$connect = $this->connect();

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE role_id = '2' AND ActiveStatus = '1'"));

		return $num_rows;
	}
	


	public function updatePasswordToHash(){
		$connect = $this->connect();
		
		$select_qry = "SELECT * FROM tb_employee_info WHERE emp_id <= '3'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){
					
				$hashed_password = password_hash($row->Password, PASSWORD_DEFAULT);


				if (password_verify($row->Password, $hashed_password)) {
				    // Success!
				    echo 'Password Matches';
				}else {
				    // Invalid credentials
				    echo 'Password Mismatch';
				}

				//echo $hashed_password . "<br/>";

				$update_qry = "UPDATE tb_employee_info SET Password = '$hashed_password' WHERE  emp_id='$row->emp_id'";
				$sql = mysqli_query($connect,$update_qry);
			}
		}
	}


	public function getEmpLeaveCount($emp_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_emp_leave WHERE emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$leave_array_explode =explode("," ,$row->leave_array);
				$leave_count_array_explode =explode("," ,$row->leave_count_array);

				$counter = 0;

				$count = count($leave_array_explode);

				do{

					$lt_id = $leave_array_explode[$counter];

					//echo $lt_id . " ";
					$row = $this->getLeaveTypeById($lt_id);



					echo "<tr>";
						echo "<td><small>".$row->name."</small></td>";
						echo "<td><small>".$leave_count_array_explode[$counter]."</small></td>";
					echo "</tr>";



					$counter++;
				}while($count > $counter);


			}

		}
	}


	public function getLeaveTypeById($lt_id){
		$connect = $this->connect();

		$lt_id = mysqli_real_escape_string($connect,$lt_id);

		$select_qry = "SELECT * FROM tb_leave_type WHERE lt_id = '$lt_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
	}


	// for inserting information
	public function insertPet($emp_id,$pet_type,$pet_name){

		$connect = $this->connect();
		
		//$biod_id = mysqli_real_escape_string($connect,$bio_id);
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$pet_type = mysqli_real_escape_string($connect,$pet_type);
		$pet_name = mysqli_real_escape_string($connect,$pet_name);
		//$date_created = mysqli_real_escape_string($connect,$date_created);

		$insert_qry = "INSERT INTO tb_pet_info (emp_id,pet_type,pet_name)
										VALUES ('$emp_id','$pet_type','$pet_name')";
		$sql = mysqli_query($connect,$insert_qry);

	}

	// checking when updating company information
	public function samePetInfo($emp_id,$pet_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$pet_id = mysqli_real_escape_string($connect,$pet_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_pet_info WHERE role_id='$role' AND emp_id = '$emp_id'"));

		return $num_rows;
	}

	public function getEmpPetByEmpId($emp_id){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_pet_info WHERE emp_id='$emp_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;
	}


	public function countPetInfo($emp_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_pet_info WHERE emp_id = '$emp_id'"));

		return $num_rows;
	}


	// for getting information by emp_id
	public function getAllPetInfo($emp_id){
		$connect = $this->connect();

		$counter = 0;

		$select_qry = "SELECT * FROM tb_pet_info WHERE emp_id='$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
				while ($row = mysqli_fetch_object($result)){
				$counter++;
				echo '<div>';
					echo  '<div class="col-sm-6">';
						echo '<label class="control-label">Pet Type &nbsp;<span class="red-asterisk">*</span></label><input type="text" value="'.$row->pet_type.'" class="form-control" id="txt_only" name="pet_type[]" placeholder="Enter Pet Type"/ required="required">';
					echo '</div>'; 
					echo '<div class="col-sm-5">';
						echo '<label class="control-label">Pet Name &nbsp;<span class="red-asterisk">*</span></label><input type="text" value="'.$row->pet_name.'" class="form-control" id="number_only" name="pet_name[]" placeholder="Enter Pet Name" required="required"/>';
					echo '</div>';
					echo '<div class="col-sm-1 remove-without-dependent">';
						echo '<a class="btn btn-danger btn-sm" title="Remove" id="remove_pet_info">&times;</a>';
					echo '</div>';
				echo '</div>';

				
			}
		}
	} // end of function


	// for delete events info
	public function deletePetInfo($emp_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$delete_qry = "DELETE FROM tb_pet_info WHERE emp_id='$emp_id'";
		$sql = mysqli_query($connect,$delete_qry);
	}


	public function insertEmpDefaultLeave($emp_id){

		$connect = $this->connect();
		
	
		
		$leave_array = "";
		$leave_count_array = "";


		$select_query = "SELECT * FROM `tb_leave_type` WHERE status = '1'";
		if ($result_a = mysqli_query($connect,$select_query)){
			while ($row_a = mysqli_fetch_object($result_a)) {



				if ($leave_array == ""){
					$leave_array = $row_a->lt_id; // 1
				}

				else {
					$leave_array .= ",". $row_a->lt_id;
				}

				if ($leave_count_array == ""){
					$leave_count_array = "0";
				}

				else {
					$leave_count_array .= ",". "0";
				}
				


				
				# code...
			}

			 

		}


		//echo $emp_id;
		//echo " ";
		//print_r($leave_array);
		//echo " ";
		//print_r($leave_count_array);
		//echo "<br/>";

	$insert_data_array = "INSERT INTO tb_emp_leave (emp_id,leave_array,leave_count_array)
											VALUES ('$emp_id','$leave_array','$leave_count_array')";

	$sql = mysqli_query($connect,$insert_data_array);


					



					
		    // Success! 
		

				

	}


	// for update bio ID success
	public function updateGeneratedCode($emp_id,$generated_code){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$generated_code = mysqli_real_escape_string($connect,$generated_code);

		$update_qry = "UPDATE tb_employee_info SET generated_code = '$generated_code'WHERE  emp_id='$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	public function getEmpInfoByUsername($username){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_employee_info WHERE username='$username'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;
	}
}


?>