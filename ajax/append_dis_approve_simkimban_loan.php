<?php
include "../class/connect.php";
include "../class/simkimban_class.php";	



if (isset($_POST["simkimban_id"])){
	$simkimban_id = $_POST["simkimban_id"];

	$simkimban_class = new Simkimban;

	$row = $simkimban_class->getInfoBySimkimbaId($simkimban_id);

	$row_emp = $simkimban_class->getEmpInfoByRow($row->emp_id);


?>

<form class="form-horizontal" action="" method="post" id="disapprove_simkimban_loan">

	<!--<font><b>Note:</b> Used Military Time</font> -->
	<div class="form-group">
		<center><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span>&nbsp;Are you sure you want to disapprove file simkimban loan of <b><?php echo $row_emp->Firstname . " " . $row_emp->Lastname; ?></b> with Refrence No. <b><?php echo $row->ref_no; ?></b>?</center>
	</div>

</form>

<script>
	$(document).ready(function(){
		$("a[id='disapprove_yes_file_simkimban_loan']").on("click",function(){
			$("#disapprove_simkimban_loan").attr("action","php script/script_dis_approve_simkimban_loan.php");
			$("#disapprove_simkimban_loan").append("<input type='hidden' value='<?php echo $simkimban_id; ?>' name='simkimban_id'/>");
			$("#disapprove_simkimban_loan").submit();
		});
	});

</script>

<?php
	}

	else {
		header("Location:../MainForm.php");
	}
	
?>