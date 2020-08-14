<?php
session_start();
include "../class/connect.php";
include "../class/leave_class.php";

if (isset($_POST["lt_id"])) {
	$lt_id = $_POST["lt_id"];
	$leave_class = new leave;


	$row = $leave_class->getLeaveTypeById($lt_id);

	$cannot_deleted =  $leave_class->checkExistEmpLeaveInfo($lt_id);
	if ($cannot_deleted == 1){

		echo "Error";
	}

	
	else if ($leave_class->checkExistLeaveType($lt_id) == 0){
			echo "Error";
	}

	else {

?>

	<form class="" action="" method="post">
		<div class="container-fluid">
			<div style="text-align:center;">					
				<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to delete the <b><?php echo $row->name; ?> Leave Type?</b></span>
			</div>						
		</div>
	</form>

	<script>
		$(document).ready(function(){
			$("#confirm_yes").on("click",function(){
				//alert("HELLO WORLD!");

				$(this).attr("disabled","disabled");

				var input = "<input type='hidden' value='<?php echo $lt_id; ?>' name='lt_id'/>"

				$(this).closest("div[id='ConfirmationModal']").find("form").append(input);


				$(this).closest("div[id='ConfirmationModal']").find("form").attr("action","php script/delete_leave_type.php");

				$(this).closest("div[id='ConfirmationModal']").find("form").submit();
			});
		});
	</script>

	<?php
	
	}
}

else {
	header("Location:../Mainform.php");
}




?>
