<?php
include "../class/connect.php";
include "../class/memorandum_class.php";
include "../class/emp_information.php";
include "../class/position_class.php";
include "../class/date.php";
include "../class/department.php";


if (isset($_POST["memo_id"])){

	$memo_id = $_POST["memo_id"];

	$memo_class = new Memorandum;
	$emp_info_class = new EmployeeInformation;
	$dept_class = new Department;

	if ($memo_class->checkExistMemo($memo_id) == 0){
		echo "Error";
	}

	else {

		$row = $memo_class->getMemoInfoById($memo_id);
		$memoFrom_emp_id = $row->memoFrom;

		$row_memoFrom = $emp_info_class->getEmpInfoByRow($memoFrom_emp_id);

		$memoFrom = $row_memoFrom->Firstname . " " . $row_memoFrom->Middlename . " " . $row_memoFrom->Lastname;

		if ($row_memoFrom->Middlename == ""){
			$memoFrom = $row_memoFrom->Firstname . " " . $row_memoFrom->Lastname;
		}


		// for position of nagpadala ng memo
		$position_class = new Position;
		$memoFrom_position = $position_class->getPositionById($row_memoFrom->position_id)->Position;


		$date_class = new date;
		$date = $date_class->dateFormat($row->DateCreated);

?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-offset-1 col-sm-10">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="control-label col-sm-2"><b>To:</b></label>
						<div class="col-sm-10 memo-info">
							<p><?php 
									echo $memo_class->getMultipleMemoInfo($memo_id);
								?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2"><b>From:</b></label>
						<div class="col-sm-10 memo-info">
							<span><?php 
									echo $memoFrom;

								?></span> <br/>
							<span>
								<?php 
									echo $memoFrom_position;
								?>
							</span>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2"><b>Date:</b></label>
						<div class="col-sm-10 memo-info">
							<span><?php echo $date; ?></span>
						</div>
					</div>


					<div class="form-group">
						<label class="control-label col-sm-2"><b>Subject:</b></label>
						<div class="col-sm-10 memo-info">
							<span><?php echo $row->Subject; ?></span>
						</div>
					</div>


					<div class="form-group">
						<label class="control-label col-sm-2"><b>Content:</b></label>
						<div class="col-sm-10 memo-info">
							<span><?php echo nl2br(($row->Content)); ?></span>
						</div>
					</div>


					<div class="form-group">
						<?php

							$memo_class->getMemoImageProfile($memo_id);
						?>
					</div>

			 	</form>
		 	</div> <!-- end of col -->
		 </div> <!-- end of row -->
 	</div> <!-- end of container-fluid -->
<?php
	} // end of else

}

else {
	header("Location:../MainForm.php");
}


?>