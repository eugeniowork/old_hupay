<?php
include "../class/connect.php";
include "../class/emp_loan_class.php";	


if (isset($_POST["file_loan_id"])){
	$file_loan_id = $_POST["file_loan_id"];

	$emp_loan_class = new EmployeeLoan;

	$row = $emp_loan_class->getFileLoanInfoById($file_loan_id);

	$row_emp = $emp_loan_class->getEmpInfoByRow($row->emp_id);


?>

<form class="form-horizontal" action="" method="post" id="disapprove_file_loan_form">

	<!--<font><b>Note:</b> Used Military Time</font> -->
	<div class="form-group">
		<center><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span>&nbsp;Are you sure you want to disapprove file salary loan of <b><?php echo $row_emp->Firstname . " " . $row_emp->Lastname; ?></b> with Refrence No. <b><?php echo $row->ref_no; ?></b>?</center>
	</div>

</form>

<script>
	$(document).ready(function(){
		$("a[id='disapprove_yes_fil_loan']").on("click",function(){
			$("#disapprove_file_loan_form").attr("action","php script/disapprove_file_loan.php");
			$("#disapprove_file_loan_form").append("<input type='hidden' value='<?php echo $file_loan_id; ?>' name='file_loan_id'/>");
			$("#disapprove_file_loan_form").submit();
		});
	});

</script>

<?php
	}

	else {
		header("Location:../MainForm.php");
	}
	
?>