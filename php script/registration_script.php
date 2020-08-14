<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/date.php";
include "../class/department.php";
include "../class/date_format.php";
include "../class/email_validation_format.php";
include "../class/role.php";
include "../class/position_class.php";
include "../class/history_position.php";
include "../class/year_total_deduction_class.php";
include "../class/cashbond_class.php";
include "../class/working_hours_class.php";
include "../class/company_class.php";
include "../class/audit_trail_class.php";

include "../class/working_days_class.php";

/* if(empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){ //catch file overload error...
        $postMax = ini_get('post_max_size'); //grab the size limits...
        //echo "<p style=\"color: #F00;\">\nPlease note files larger than {$postMax} will result in this error!<br>Please be advised this is not a limitation in the CMS, This is a limitation of the hosting server.<br>For various reasons they limit the max size of uploaded files, if you have access to the php ini file you can fix this by changing the post_max_size setting.<br> If you can't then please ask your host to increase the size limits, or use the FTP uploaded form</p>"; // echo out error and solutions...
       // addForm(); //bounce back to the just filled out form.
        $_SESSION["error_msg_registration"] = "The file size you have uploaded is larger than the maximum size limit of {$postMax}b";
        

}
else { */
	// if all has set and not edited by inspect element
if (isset($_POST["lastName"]) && isset($_POST["firstName"]) 
	&& isset($_POST["middleName"]) && isset($_POST["address"]) &&  isset($_POST["role"]) && isset($_POST["department"]) 
	&& isset($_POST["position"]) && isset($_POST["birthdate"]) && isset($_POST["gender"])
	&& isset($_POST["contactNo"]) && isset($_POST["email_add"])  && isset($_POST["salary"]) && isset($_POST["dateHired"]) && isset($_POST["workingHours"]) && isset($_POST["civil_status"]) && isset($_POST["sssNo"])
	&& isset($_POST["pagibigNo"]) && isset($_POST["tinNo"]) && isset($_POST["philhealthNo"]) && isset($_POST["username"])
	&& isset($_POST["password"]) && isset($_POST["confirmPassword"]) && isset($_POST["headName"]) && isset($_POST["company_id"])
	&& isset($_POST["employment_type"]) && isset($_POST["education_attain"])
	&& isset($_POST["work_position"][0]) && isset($_POST["company_name"][0]) && isset($_POST["job_description"][0]) 
	&& isset($_POST["work_year_from"][0]) && isset($_POST["work_year_to"][0]) 
	&& isset($_POST["workingDays"]) && isset($_POST["pet_type"][0]) && isset($_POST["pet_name"][0])) {




	// this variable is for error in regex
	$has_error_contactNo = 0;
	$count_contactNo = strlen($_POST["contactNo"]); // check the length

	// the contact number length must be 7 9 11
	// 11 - contact number
	// 9 - landline number with area code
	// 7 - landline number only

	if ($count_contactNo != 7 && $count_contactNo != 9 && $count_contactNo != 11) {
		$has_error_contactNo = 1;		
	}

		// if 11 cp number 09 cmula then follow by any 9 digits
	if ($count_contactNo == 11) {
		$regex = '/^[0]{1}[9]{1}[0-9]{9}$/i';

  		if (!preg_match($regex, $_POST["contactNo"])) {
  			$has_error_contactNo = 1;
  		}

	}

	//$bio_id = $_POST["bioId"];
	$lasname = $_POST["lastName"];
	$firstname = $_POST["firstName"];
	$middlename = $_POST["middleName"]; // optional
	$address = $_POST["address"];
	$role = $_POST["role"];
	$civilStatus = $_POST["civil_status"];

	//for PET

	//$pet_type = $_POST["pet_type"];
	//$pet_name = $_POST["pet_name"];

	// for getting the value of department by id
	$department_class = new Department;

	$position = $_POST["position"];
	$birthdate = $_POST["birthdate"];
	$department = $_POST["department"];

	// for date format
	$date_format_class = new DateFormat;
	



	$gender = $_POST["gender"];
	$contactNo = $_POST["contactNo"];
	$emailAdd = $_POST["email_add"];
	$salary = $_POST["salary"];
	
	$workingHours = $_POST["workingHours"];
	$headName = $_POST["headName"];
	$company_id = $_POST["company_id"];
	$employment_type = $_POST["employment_type"];
	$workingDays = $_POST["workingDays"];
	/*$withTax = 0;
	if (isset($_POST["withTax"])) {
		$withTax = 1;
	}*/



	// for checking if the inputed email is valid or not in a standard format as RFC 5322 Official Standard
	$email_class = new Email;
	$valid_email = $email_class->validateEmail($emailAdd);




	// for getting the last id in database of tb_employee_info to concatenate to the file name for unique purposes
	$emp_info_class = new EmployeeInformation;
	$emp_last_id = $emp_info_class->empLastId();

	// for image but is optional
	//$file_tmp_name = $_FILES["profileImage"]["tmp_name"];
	//$profileImage = basename($_FILES["profileImage"]["name"]);
	//$profileImageName = ++$emp_last_id ."_". $profileImage;
	//$file_type = pathinfo($profileImage,PATHINFO_EXTENSION);
	//$file_size = $_FILES["profileImage"]["size"];
	//$location = "../img/profile images/profile picture/" . $profileImageName; // for saving to the directory
	//$profilePath = "img/profile images/profile picture/" . $profileImageName; // for database purpose
	

	
	$sss_no = $_POST["sssNo"]; // optional
	$pag_ibig_no = $_POST["pagibigNo"]; // optional
	$tin_no = $_POST["tinNo"]; // optional
	$philhealt_no = $_POST["philhealthNo"]; // optional
	
	// $salary = '';
	$education_attain = $_POST["education_attain"];

	$username = $_POST["username"];
	$password = $_POST["password"];
	$confirmPassword = $_POST["confirmPassword"];


	//echo $birthdate;
	// for birthday
	$birthday_month = substr($birthdate,0,2);
	$birthday_day = substr(substr($birthdate, -7), 0,2);
	$birthday_year = substr($birthdate, -4);


	$datehired_month = substr($_POST["dateHired"],0,2);
	$datehired_day = substr(substr($_POST["dateHired"], -7), 0,2);
	$datehired_year = substr($_POST["dateHired"], -4);


	// /^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/

	$dateHired = $_POST["dateHired"];



	//echo $birthday_month;




	// for retrieval purpose to the form in case of there is an error
	// basic information
	//$_SESSION["emp_reg_bio_id"] = $bio_id;
	
	
	$_SESSION["emp_reg_lasname"] = $lasname;
	$_SESSION["emp_reg_firstname"] = $firstname;
	$_SESSION["emp_reg_middlename"] = $middlename;
	$_SESSION["emp_reg_address"] = $address;
	$_SESSION["emp_reg_role"] = $role;
	$_SESSION["emp_reg_department"] = $department;
	$_SESSION["emp_reg_position"] = $position;
	$_SESSION["emp_reg_birthdate"] = $birthdate;
	$_SESSION["emp_reg_gender"] = $gender;
	$_SESSION["emp_reg_contactNo"] = $contactNo;
	$_SESSION["emp_reg_emailAdd"] = $emailAdd;
	$_SESSION["emp_reg_civilStatus"] = $civilStatus;

	//PET Information

	//$_SESSION["emp_pet_type"] = $pet_type;
	//$_SESSION["emp_pet_name"] = $pet_name;


	$count = 1;
	$counter = 0;
	foreach ($_POST["pet_name"] as $row) {
		# code...
		$_SESSION["emp_pet_type".$counter] = $_POST["pet_type"][$counter];
		$_SESSION["emp_pet_name".$counter] = $_POST["pet_name"][$counter];
		$counter++;
	}

	$_SESSION["emp_pet_info_count"] = $counter;

	// government information
	$_SESSION["emp_reg_sss_no"] = $sss_no;
	$_SESSION["emp_reg_pag_ibig_no"] = $pag_ibig_no;
	$_SESSION["emp_reg_tin_no"] = $tin_no;
	$_SESSION["emp_reg_philhealt_no"] = $philhealt_no;
	$_SESSION["emp_reg_salary"] = $salary;
	$_SESSION["emp_reg_dateHired"] = $dateHired;
	$_SESSION["emp_reg_workingHours"] = $workingHours;
	$_SESSION["emp_reg_headsname"] = $headName;
	$_SESSION["emp_reg_company_id"] = $company_id;
	$_SESSION["emp_reg_employment_type"] = $employment_type;
	$_SESSION["emp_reg_workingDays"] = $workingDays;

	// for school information
	$_SESSION["emp_reg_educational_attain"] = $education_attain;

	//echo ;

	if ($education_attain == "Secondary"){
		$_SESSION["emp_reg_school_name"] = $_POST["school_name"][0];
		$_SESSION["emp_reg_year_from"] = $_POST["year_from"][0];
		$_SESSION["emp_reg_year_to"] = $_POST["year_to"][0];
	}

	if ($education_attain == "Tertiary"){
		
		// SCHOOL NAME
		$count = 1;
		$counter = 0;
		foreach($_POST["school_name"] as $row) {

			//echo $_POST["course"][$counter] . "<br/>";
			
			$_SESSION["emp_reg_school_name".$count] = $row;
			$_SESSION["emp_reg_course".$count] = $_POST["course"][$counter];
			$_SESSION["emp_reg_year_from".$count] = $_POST["year_from"][$counter];
			$_SESSION["emp_reg_year_to".$count] = $_POST["year_to"][$counter];

			$counter++;
			$count++;
		}

		$_SESSION["emp_reg_tertiary_count"] = $counter;

		//echo $counter;
	}


	// for work experience
	$count = 1;
	$counter = 0;
	foreach($_POST["work_position"] as $row) {

		//echo $_POST["course"][$counter] . "<br/>";		
		$_SESSION["emp_reg_work_positon".$count] = $row;
		$_SESSION["emp_reg_company_name".$count] = $_POST["company_name"][$counter];
		$_SESSION["emp_reg_job_description".$count] = $_POST["job_description"][$counter];
		$_SESSION["emp_reg_work_year_from".$count] = $_POST["work_year_from"][$counter];
		$_SESSION["emp_reg_work_year_to".$count] = $_POST["work_year_to"][$counter];

		$counter++;
		$count++;
	}

	$_SESSION["emp_reg_work_count"] = $counter;



	// account information
	$_SESSION["emp_reg_username"] = $username;
	




	// check if the bio id is exist must be unique
	//$emp_information = new EmployeeInformation;
	//$exist_bio_id = $emp_information->checkExistBioId($bio_id);

	// check if the username is exist must be unique
	$exist_username = $emp_info_class->checkExistUsername($username);


	// for role class
	$role_class = new Role;
	$position_class = new Position;

	$working_hours_class = new WorkingHours;
	$company_class = new Company;
	$working_days_class = new WorkingDays;



	// next validation if required fields has a value if not an error msg will appear
	if ($lasname == "" || $firstname == "" || $address == "" || $role == "" || $department == "" || $position == "" || $birthdate == "" || $gender == "" || $salary == "" || $dateHired == "" || $civilStatus == "" || $username == "" || $password == "" || $confirmPassword == ""){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving employee info, Information did not save.";
	}
	// first check if the uploaded images is jpeg,jpg,png
	//else if ($profileImage != "" && $file_type != "jpg" && $file_type != "png" && $file_type != "jpeg"){
	//	$_SESSION["error_msg_registration"] = "Sorry, only JPG, JPEG, & PNG files are allowed.";
	//}

	else if ($gender != "Male" && $gender != "Female"){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving employee info, Information did not save.";
	}


	else if ($civilStatus != "Single" && $civilStatus != "Married"){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving employee info, Information did not save.";
	}

	// if password and confirm password does not match
	else if ($password != $confirmPassword) {
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Password and confirm password does not match.";
	}

	// if exist username
	else if ($exist_username != ""){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The Username already exist.";
	}

	// if contact number is not valid
	else if ($contactNo != "" && $has_error_contactNo == 1){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The Contact Number is not match to the format.";
	}

	// if invalid email format
	else if ($emailAdd != "" && $valid_email == 0){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The Email Address is not valid.";
	}

	// if edited to the inspect element and change the value to string of position, role and department
	else if (!is_numeric($role) || !is_numeric($department) || !is_numeric($position)){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving employee info, Information did not save.";
	}


	// if edited in the inspect element and change the value in a number but is not exist in the 
		//tb_position, tb_department, and tb_role
	else if ($role_class->existRole($role) == 0 || $department_class->existDepartmentById($department) == 0 || $position_class->checkExistPositionId($position) == 0){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving employee info, Information did not save.";
	}


	// kapag 1 ung role id tapos kapag 1 din ung position id invalid request of saving information
	else if ($role == 1 || $position == 1 ){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving employee info, Information did not save.";
	}

	// if username is below 4 character
	else if (strlen($username) < 4){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The username length must be 4 and above character.";
	}

	// if password is below 8 character
	else if (strlen($password) < 8){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The password length must be 8 and above character.";
	}

	// if sss no length is not equal to 10
	else if ($sss_no != "" &&strlen($sss_no) != 10){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> SSS No. must composed of 10 digits.";
	}

	// if sss no length is not equal to 10
	else if ($pag_ibig_no != "" &&strlen($pag_ibig_no) != 12){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Pag-ibig No. must composed of 12 digits.";
	}

	else if ($tin_no != "" &&strlen($tin_no) != 9){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Tin No. must composed of 9 digits.";
	}

	else if ($philhealt_no != "" &&strlen($philhealt_no) != 12){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Philhealth No. must composed of 12 digits.";
	}

	// if exist contact number must be unique
	else if ($contactNo != "" && $emp_info_class->contactNoExist($contactNo) != 0){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Contact No is already exist.";
	}

	// if emaill add already exist
	else if ($emailAdd != "" && $emp_info_class->emaillAddExist($emailAdd) != 0){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Email Address is already exist.";
	}

	// if sss no  already exist
	else if ($sss_no != "" && $emp_info_class->sssNoExist($sss_no) != 0){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> SSS No is already exist.";
	}

	// if pagibig no already exist
	else if ($pag_ibig_no != "" &&$emp_info_class->pagibigNoExist($pag_ibig_no) != 0){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Pag-ibig No is already exist.";
	}

	// if tin no already exist
	else if ($tin_no != "" &&$emp_info_class->tinNoExist($tin_no) != 0){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Tin No is already exist.";
	}


	// if philhealth no already exist
	else if ($philhealt_no != "" && $emp_info_class->philhealthNoExist($philhealt_no) != 0){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Philhealth No is already exist.";
	}


	// for checking if edited in the inspect element the working_hours_id
	else if ($working_hours_class->checkExistWorkingHoursId($workingHours) == 0) {
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving employee info, Information did not save.";
	}


	else if ($working_days_class->checkExistWorkingDaysId($workingDays) == 0) {
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving employee info, Information did not save.";
	}


	else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$birthdate)) {
    	$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Birthdate</b> not match to the current format mm/dd/yyyy";
	}

	// for validating leap year
	else if ($birthday_year % 4 == 0 && $birthday_month == 2 && $birthday_day >= 30){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Birthdate</b> date";
	}

	// for validating leap year also
	else if ($birthday_year % 4 != 0 && $birthday_month == 2 && $birthday_day >= 29){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Birthdate</b> date";
	}

	// mga month na may 31
	else if (($birthday_month == 4 || $birthday_month == 6 || $birthday_month == 9 || $birthday_month == 11)
			&& $birthday_day  >= 31){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Birthdate</b> date";
	}

	else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["dateHired"])) {
    	$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Datehired</b> not match to the current format mm/dd/yyyy";
	}

	// for validating leap year
	else if ($datehired_year % 4 == 0 && $datehired_month == 2 && $datehired_day >= 30){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Datehired</b> date";
	}

	// for validating leap year also
	else if ($datehired_year % 4 != 0 && $datehired_month == 2 && $datehired_day >= 29){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Datehired</b> date";
	}

	// mga month na may 31
	else if (($datehired_month == 4 || $datehired_month == 6 || $datehired_month == 9 || $datehired_month == 11)
			&& $datehired_day  >= 31){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Datehired</b> date";
	}


	// if heads name is not exist
	else if ($headName != "" && $emp_info_class->checkExistEmployeeName($headName) == 1){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The <b>Immediate Head's Name $headName</b> is not exist in the employee list name";
	}

	// for checking if the company id is exist to the database
	else if ($company_class->checkExistCompanyId($company_id) == 0){
		$_SESSION["error_msg_registration"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving employee info, Information did not save.";
	}

	// if no error success
	else {
		
		$position_class->getPositionById($position);
		$dept_id = $position_class->getPositionById($position)->dept_id; // for department id according to position id for information ok purpose


		// for getting emp id by name for heads emp id purposes
		$head_emp_id = $emp_info_class->getEmpIdByEmployeeName($headName);


		//$_SESSION["success_msg_registration"] = "Employee $firstname $middlename $lasname is successfully registered";
		
		// set to null if success
			//$_SESSION["emp_reg_bio_id"] = null;
			$_SESSION["emp_reg_lasname"] = null;
			$_SESSION["emp_reg_firstname"] = null;
			$_SESSION["emp_reg_middlename"] = null;
			$_SESSION["emp_reg_address"] = null;
			$_SESSION["emp_reg_role"] = null;
			$_SESSION["emp_reg_department"] = null;
			$_SESSION["emp_reg_position"] = null;
			$_SESSION["emp_reg_birthdate"] = null;
			$_SESSION["emp_reg_gender"] = null;
			$_SESSION["emp_reg_contactNo"] = null;
			$_SESSION["emp_reg_emailAdd"] = null;
			$_SESSION["emp_reg_civilStatus"] = null;

			//

			//$_SESSION["emp_pet_type"] = null;
			//$_SESSION["emp_pet_name"] = null;
			$count = 1;
			$counter = 0;
			foreach ($_POST["pet_name"] as $row) {
				# code...
				$_SESSION["emp_pet_type".$counter] = null;
				$_SESSION["emp_pet_name".$counter] = null;
				$counter++;
			}

			$_SESSION["emp_pet_info_count"] = null;

			// government information
			$_SESSION["emp_reg_sss_no"] = null;
			$_SESSION["emp_reg_pag_ibig_no"] = null;
			$_SESSION["emp_reg_tin_no"] = null;
			$_SESSION["emp_reg_philhealt_no"] = null;
			$_SESSION["emp_reg_salary"] = null;
			$_SESSION["emp_reg_dateHired"] = null;
			$_SESSION["emp_reg_workingHours"] = null;
			$_SESSION["emp_reg_headsname"] = null;
			$_SESSION["emp_reg_company_id"] = null;

			$_SESSION["emp_reg_workingDays"] = null;
			//$_SESSION["emp_reg_withTax"] = null;

			$_SESSION["emp_reg_educational_attain"] = null;
			if ($education_attain == "Secondary"){
				$_SESSION["emp_reg_school_name"] = null;
				$_SESSION["emp_reg_year_from"] = null;
				$_SESSION["emp_reg_year_to"] = null;
			}

			if ($education_attain == "Tertiary"){
				
				// SCHOOL NAME
				$count = 1;
				//$counter = 0;
				foreach($_POST["school_name"] as $row) {
					
					$_SESSION["emp_reg_school_name".$count] = null;
					$_SESSION["emp_reg_course".$count] = null;
					$_SESSION["emp_reg_year_from".$count] = null;
					$_SESSION["emp_reg_year_to".$count] = null;

					//$counter++;
					$count++;
				}

				$_SESSION["emp_reg_tertiary_count"] = null;

				//echo $counter;
			}


			// for work experience
			$count = 1;
			//$counter = 0;
			foreach($_POST["work_position"] as $row) {

				//echo $_POST["course"][$counter] . "<br/>";
				
				$_SESSION["emp_reg_work_positon".$count] = null;
				$_SESSION["emp_reg_company_name".$count] = null;
				$_SESSION["emp_reg_job_description".$count] = null;
				$_SESSION["emp_reg_work_year_from".$count] = null;
				$_SESSION["emp_reg_work_year_to".$count] = null;

				//$counter++;
				$count++;
			}

			$_SESSION["emp_reg_work_count"] = null;

			// account information
			$_SESSION["emp_reg_username"] = null;


			$date = new date; // for date

			// since it is not required
			//if ($profileImage == "") {
			if ($gender == "Male"){
				$profileImageName = "male.jpg";
				$profilePath = "img/profile images/default/" . $profileImageName;
			}
			if ($gender == "Female"){
				$profileImageName = "female.jpg";
				$profilePath = "img/profile images/default/" . $profileImageName;
			}


			$employment_type_stat = 1;
			if ($employment_type == "Provisional"){
				$employment_type_stat = 0;
			}
			if ($employment_type == "OJT/Training"){
				$employment_type_stat = 2;
			}

			//}
			//else 
			//{
			//	move_uploaded_file($file_tmp_name,$location);
		//	}	


			
			$final_birthdate = $date_format_class->setDateFormat($birthdate);
			$final_date_hired = $date_format_class->setDateFormat($dateHired);

			$emp_info_class->insertEmployee($lasname,$firstname,$middlename,$address,$role,$dept_id,$position,$head_emp_id,$final_birthdate,$gender,$contactNo,$emailAdd,$civilStatus,$profileImageName,$profilePath,$username,password_hash($password, PASSWORD_DEFAULT),$sss_no,$pag_ibig_no,$tin_no,$philhealt_no,$salary,$final_date_hired,$workingHours,$company_id,$employment_type_stat,$education_attain,$workingDays,$date->getDate());
			


			// for adding to lfc employment history
			$history_position_class = new HistoryPosition;
			$history_position_class->insertHistoryPosition(++$emp_last_id,$dept_id,$position,$salary,$final_date_hired,$date->getDate()); // $emp_id,$dept_id,$position_id,$salary,$dateHired,$dateCreated)


			$counter = 0;
			foreach ($_POST["pet_name"] as $row) {
				# code...
				// for adding pet 
				$emp_info_class->insertPet($emp_last_id,$_POST["pet_type"][$counter],$_POST["pet_name"][$counter]);

				$counter++;
			}

			
			

			// for adding a year total deduction starting to 0
			$year = date("Y");
			$ytd_class = new YearTotalDeduction;
			$ytd_class->insertYTD($emp_last_id,'0','0','0',$year,$date->getDate());



			// for inserting cashbond
			$cashbond_class = new Cashbond;
			$cashbond = round($cashbond_class->cashbondNewEmpFormula($salary),2);
			$cashbond_class->insertCashbond($emp_last_id,$cashbond,$date->getDate());
			
			
			// for insert to employee education

			if ($education_attain == "Secondary"){
				

				$emp_info_class->insertEmployeeEducation($emp_last_id,0,$_POST["school_name"][0],"",$_POST["year_from"][0],$_POST["year_to"][0]);
			}

			if ($education_attain == "Tertiary"){
				
				// SCHOOL NAME
				$count = 1;
				$counter = 0;
				foreach($_POST["school_name"] as $row) {

					$type = 0;
					if ($counter > 0){
						$type = 1;
					}
					

					$emp_info_class->insertEmployeeEducation($emp_last_id,$type,$row,$_POST["course"][$counter],$_POST["year_from"][$counter],$_POST["year_to"][$counter]);

					$counter++;
					$count++;
				}

				$_SESSION["emp_reg_tertiary_count"] = $counter;

				//echo $counter;
			}


			$count = 1;
			$counter = 0;
			foreach($_POST["work_position"] as $row) {

				$emp_info_class->insertEmployeeWorkExperience($emp_last_id,$row,$_POST["company_name"][$counter],$_POST["job_description"][$counter],$_POST["work_year_from"][$counter],$_POST["work_year_to"][$counter]);

				$counter++;
				$count++;
			}


			// for inserting default leave
			$emp_info_class->insertEmpDefaultLeave($emp_last_id);


			$_SESSION["success_msg_registration"] = "<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> Employee <b>$firstname $middlename $lasname</b> is successfully registered.</center>";

			
			$dateTime = $date->getDateTime();
			$audit_trail_class = new AuditTrail;
			$module = "Employee Registration";
			$audit_trail_class->insertAuditTrail($emp_last_id,0,$_SESSION["id"],$module,"Add Employee",$dateTime);


		}
		
		header("Location:../emp_registration.php");
		
	} 

else {
	header("Location:../MainForm.php");
}
		

//} // end of else 

	//echo $_SESSION["error_msg_registration"];
	//echo $_SESSION["success_msg_registration"];


// if edited or if just browsing
?>