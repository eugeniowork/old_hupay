<?php
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/history_position.php";

if (isset($_POST["emp_id"])){

	$emp_id = $_POST["emp_id"];

	$emp_info_class = new EmployeeInformation;

	// if edited in the inspect element
	if ($emp_info_class->checkExistEmpId($emp_id) == 0){
		echo "Error";
	}


	// if not edited and success
	else {
		$row = $emp_info_class->getEmpInfoByRow($emp_id);

?>
		
	<div class="container-fluid">
		<div class="col-sm-12">
			 <b style="color: #2874a6 "><img src="img/logo/lloyds logo.png" class="lloyds-logo-emp-history"/>&nbsp;Lloyds Financing Corp. Employment History</b><br/>
			 <b>Employee Name: </b><label class="control-label"><?php echo $row->Lastname . ", " . $row->Firstname . " " . $row->Firstname; ?></label>
		</div>
		<div class="col-sm-12">
			<table id="" class="table table-bordered table-hover" style="border:1px solid #BDBDBD;">
				<thead>
					<tr>
						<th class="no-sort"><span class="glyphicon glyphicon-blackboard" style="color:#186a3b"></span> <small>Department</small></th>
						<th class="no-sort"><span class="glyphicon glyphicon-tasks" style="color:#186a3b"></span> <small>Position</small></th>
						<th class="no-sort"><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> <small>Salary</small></th>
						<th class="no-sort"><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> <small>Date Hired/ Promote</small></th>												
					</tr>
				</thead>

				<tbody>	
					<?php
						$history_position_class = new HistoryPosition;
						$history_position_class->getHistoryEmploymentToTable($emp_id);
					?>
				</tbody>
			</table>
		</div>
	</div>
	

	<!--
	<div class="container-fluid">
	 	<div class="jumbotron">
	 		<div class="container-fluid">
	 				<div class="col-sm-12">
					    
					</div>
			</div>
	  	</div>
	</div>

-->


<?php

	} // end of else 


}

else {
	header("Location:../MainForm.php");
}

?>