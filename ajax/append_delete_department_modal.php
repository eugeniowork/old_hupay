<?php
session_start();
include "../class/connect.php";
include "../class/department.php";

if (isset($_POST["department_id"])) {
	$dept_id = $_POST["department_id"];
	$department_class = new Department;
	// check if position id exist bka kc inedit sa inspect element eh
	if ($department_class->existDepartmentById($dept_id) != 0){
		$_SESSION["dept_del_id"] = $dept_id;

	?>

	<form class="" action="" method="post">
		<div class="container-fluid">
			<div style="text-align:center;">					
				<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to delete the <b><?php echo $department_class->getDepartmentValue($dept_id)->Department; ?> Department?</b></span>
			</div>						
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
