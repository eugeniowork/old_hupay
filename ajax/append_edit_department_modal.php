<?php
session_start();
include "../class/connect.php";
include "../class/department.php";

if (isset($_POST["department_id"])) {
	$dept_id = $_POST["department_id"];
	$department_class = new Department;
	// check if position id exist bka kc inedit sa inspect element eh
	if ($department_class->existDepartmentById($dept_id) != 0){
		$_SESSION["dept_id_update"] = $dept_id;

	?>
		<form class="form-horizontal" action="php script/update_department_script.php" method="post">
			<div class="container-fluid">								
				<label class="control-label"><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Department</label>
				<div class="input-group">
					<input type="text" id="department_txt" name="department" value="<?php echo $department_class->getDepartmentValue($dept_id)->Department;  ?>" autocomplete="off" placeholder="Enter Department" class="form-control" required="required">
				 	<span class="input-group-btn">
				    	<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-pencil"></span></button>
				    </span>
				</div>								
			</div>
		</form>

		<script>
			 $(document).ready(function(){

			 	 // department_txt
			     $("input[id='department_txt").on("paste", function(){
			    
			          return false;
			     });



			       // for txt only
			    $(document).on('keypress', 'input[id="department_txt"]', function (event) {


			        var regex = new RegExp("^[<>/?]+$");
			        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

			        if (regex.test(key)) {
			            event.preventDefault();
			            return false;
			        }
			    });



			      $("input[id='department_txt']").on('input', function(){

			       if ($(this).attr("maxlength") != 50){
			            if ($(this).val().length > 50){
			                $(this).val($(this).val().slice(0,-1));
			            }
			           $(this).attr("maxlength","50");
			       }

			   });

		 	 });


		</script>

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
