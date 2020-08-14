<?php
session_start();
include "../class/connect.php";
include "../class/leave_class.php";

if (isset($_POST["lt_id"])) {
	$lt_id = $_POST["lt_id"];
	$leave_class = new leave;


	$row = $leave_class->getLeaveTypeById($lt_id);


	$status = "<label class='label label-success'>Active</label>";

	if ($row->status == 1){
		$status = "<label class='label label-warning'>Inactive</label>";
		
	}

	
	if ($leave_class->checkExistLeaveType($lt_id) == 0){
			echo "Error";
	}

	else {

?>

	<form class="" action="" method="post">
		<div class="container-fluid">
			<div style="text-align:center;">					
				<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to make the Leave Type <b><?php echo $row->name; ?> in <?php echo $status; ?> status?</b></span>
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


				$(this).closest("div[id='ConfirmationModal']").find("form").attr("action","php script/active_leave_type.php");

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
