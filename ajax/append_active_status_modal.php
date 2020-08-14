<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/date.php";

if (isset($_POST["emp_id"])) {
	$emp_id = $_POST["emp_id"];
	$_SESSION["update_emp_id"] = $emp_id; 

	// for num rows exist id
	$emp_info_class = new EmployeeInformation;
	if ($emp_info_class->checkExistEmpId($emp_id) == 1){
		$row = $emp_info_class->getEmpInfoByRow($emp_id);

	?>
		
		<form class="" action="" method="post">
			<div class="container-fluid">
				<div style="text-align:center;">					
					<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to make <b> <?php echo $row->Firstname . " " .$row->Middlename . " " . $row->Lastname; ?></b> in <b> <?php if ($row->ActiveStatus == 1) { echo "Inactive";} else { echo "Active";} ?></b> status?</span>
				</div>	

				<?php
					if ($row->ActiveStatus == 1) {

						echo "<br/>";
						echo "<label><b>Resignation/ Inactive Date: <span class='red-asterisk'>*</span></b></label>";
						echo "<input name='resignation_date' class='form-control' type='date' placeholder='Resignation Date'>";

						echo "<br/>";
						echo "<small><b>Note:</b> If you make an employee inactive, he/ she will no longer to log in his/ her account</small>";	
					}
				?>				
			</div>
		</form>

	<?php
	} // end of if
	else {
		echo "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is an error during getting of data</center>";
	}
}

else {
	header("Location:../Mainform.php");
}


?>