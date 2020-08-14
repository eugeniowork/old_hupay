<?php
session_start();
include "../class/connect.php";
include "../class/memorandum_class.php";
include "../class/emp_information.php";
include "../class/date.php";
include "../class/department.php";


if (isset($_POST["memo_id"])){
	$memo_id = $_POST["memo_id"];
	$emp_id = $_SESSION["id"];

	$memorandum_class = new Memorandum;
	$emp_info_class = new EmployeeInformation;
	$date_class = new date;
	$department_class = new Department;

	if ($memorandum_class->checkExistMemo($memo_id) == 1){

		$row = $memorandum_class->getMemoInfoById($memo_id);


?>

	<!-- for plug ins design para mahaba ung textarea-->
	<style>
			.jqte_editor {
				height:350px;
			}
	</style>
	
	<form class="form-horizontal" action="" id="form_updateMemo" method="post">
							
		<div class="form-group">
			<div class="col-md-12">
				<?php

					$disabled = "";
					if ($memorandum_class->checkMemoForAll($memo_id) == 1){
						$disabled = "disabled='disabled'";
					}

				?>
				<button type="button" class="btn btn-primary btn-sm pull-right" id="add_recipient" <?php echo $disabled; ?> >Add Recipient</button>
			</div>
		</div>
		<?php
			$memorandum_class->getRecipientMultipleMemo($memo_id);

		?>	 	
 		<!--<div class="form-group">
 			<label class="control-label col-sm-3 col-sm-offset-1"><b>Recipient:</b></label>
 			
 			<label class="radio-inline"><input required="required" <?php if($row->recipient =="All") { echo "checked=checked"; }?> type="radio" value="All" name="update_optRecipient">All</label>
			<label class="radio-inline"><input required="required" <?php if($row->recipient =="Department") { echo "checked=checked"; }?> type="radio" value="Department" name="update_optRecipient">Department</label>
			<label class="radio-inline"><input required="required" <?php if($row->recipient =="Specific Employee") { echo "checked=checked"; }?> type="radio" value="Specific Employee" name="update_optRecipient">Specific Employee</label>

		</div>

 		<div class="form-group">
				<label class="control-label col-sm-3 col-sm-offset-1" style=""><b>To:</b></label>
				<div class="col-sm-6 txt-pagibig-loan" style="margin-right:-15px;">
					<?php 
						$disabledTo = "disabled=disabled";
						$to = "";
						$choose = "";
						$to_id = "";

						if ($row->recipient == "Specific Employee"){
							$disabledTo = "";
							$row_emp = $emp_info_class->getEmpInfoByRow($row->emp_id);
							$to = $row_emp->Lastname . ', ' . $row_emp->Firstname . ' ' . $row_emp->Middlename;
							if ($row_emp->Middlename == ""){
								$to = $row_emp->Lastname . ', ' . $row_emp->Firstname;
							 }

							 $choose = "<a href='#' id='choose_employee_memo'>Choose</a>";
							 $to_id = "<input type='hidden' name='updateEmpId' value='".$row->emp_id."' />";
						} // end of if
						else if ($row->recipient == "Department"){
							$disabledTo = "";
							$row_dept = $department_class->getDepartmentValue($row->dept_id);
							$to = $row_dept->Department;
							$choose = "<a href='#' id='choose_department_memo'>Choose</a>";
							$to_id = "<input type='hidden' name='updateDeptId' value='".$row->dept_id."' />";
						}

					?>
					<input type="text" class="form-control" <?php echo $disabledTo; ?>  value="<?php echo $to;  ?>" name="update_to" placeholder="" id="input_payroll" required="required" autocomplete="off"/>
				
					
				</div>
				<label class="col-sm-1 control-label"><div id="choose"><?php echo $choose; ?></div></label>
				
			</div>
			-->
			
							 				
			</div>
			<div class="form-group">
				<label class="control-label col-sm-3 col-sm-offset-1"><b>From:</b></label>
				<div class="col-sm-6 txt-pagibig-loan">
					<?php

						$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);

						$fullName = $row_emp->Lastname . ', ' . $row_emp->Firstname . ' ' . $row_emp->Middlename;
						if ($row_emp->Middlename == ""){
							$fullName = $row_emp->Lastname . ', ' . $row_emp->Firstname;
						 }


					?>
					<input type="text" class="form-control" id="input_payroll" placeholder="From ..." name="memoFrom" value="<?php echo $fullName; ?>" required="required"/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-3 col-sm-offset-1"><b>Subject:</b></label>
				<div class="col-sm-6 txt-pagibig-loan">
					<input type="text" class="form-control" value="<?php echo $row->Subject; ?>" placeholder="Subject ..." id="" name="update_subject" required="required"/>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-3 col-sm-offset-1"><b>Content:</b></label>
				<div class="col-sm-12">
					<textarea rows="13" class="form-control" placeholder="Write Content ..." id="" name="update_content" required="required"><?php echo htmlspecialchars($row->Content); ?></textarea>
				</div>
			</div>



			<div id="to_div">
				<?php echo $to_id; ?>

			</div>


			<div class="form-group">
				<div class="col-sm-offset-6">
					<input type="submit" value="Update" id="updateMemo" class="btn btn-primary btn-sm"/>
				</div>
			</div>

			<div class="form-group">
				<div id="error_msg">
				</div>
			</div>
			
		</form>


		

		<script>
			$(document).ready(function(){

				

				// for global count memo
 				var memoRecipientCount = <?php echo $memorandum_class->getMultipleMemoCount($memo_id);  ?>;
 				var dept_id_count = 0;
 				var emp_id_count = 0;

				// optRecipient
				//  input_payroll
				$("input[id='input_payroll']").keydown(function (e) {
				//  return false;
				if(e.keyCode != 116) {
				    return false;
				}
				});

				// onpaste
				$("input[id='input_payroll").on("paste", function(){
				   return false;
				});


				//$("input[name='update_effectiveDate']").dcalendarpicker(); //

				$("textarea[name='update_content']").jqte();
	
				// settings of status
				var jqteStatus = true;
				$("textarea[name='update_content']").click(function()
				{
					jqteStatus = jqteStatus ? false : true;
					$("textarea[name='update_content']").jqte({"status" : jqteStatus})
				});

				



				// update_optRecipient

				<?php

				$count =$memorandum_class->getMultipleMemoCount($memo_id);
				$counter = 0;
				do {
					$counter++;
				?>

					   // for choose department in memo choose_department_memo
			      	$("div[id='choose<?php echo $counter; ?>']").on("click","a[id='choose_department_memo']",function () {
			          $("#dept_list_modal").modal("show");
			          dept_id_count = $(this).closest("div").attr("id").slice(6,7);
			      	});


			       // for choosing memo specific employee choose_employee_memo
				    $("div[id='choose<?php echo $counter; ?>']").on("click","a[id='choose_employee_memo']",function () {
				        $("#emp_list_modal").modal("show");
				        emp_id_count = $(this).closest("div").attr("id").slice(6,7);
				    });

				    $("button[id='remove_recipient<?php echo $counter; ?>']").on("click",function(){
				    	//alert("Hello World!");
				    //alert($(this).attr("id").slice(16,17));
			          $("div[id='update_recipient_mother_div"+$(this).attr("id").slice(16,17)+"']").remove();
			          //alert(memoRecipientCount);
			          //  $("recipient_mother_div"+memoRecipientCount)

			       });


					$("input[name='update_optRecipient<?php echo $counter; ?>']").on("click",function () {
					var recipientType = $(this).val();
					//alert(recipientType);
					//var update_memo_emp_id = "";
					//var update_memo_dept_id = "";

		          // success
		          if (recipientType == "All"|| recipientType == "Specific Employee" || recipientType == "Department"){
		              if (recipientType == "Specific Employee") {
		                  $("input[name='update_to<?php echo $counter; ?>']").removeAttr("disabled");
		                  $("div[id='choose<?php echo $counter; ?>']").html("<a href='#' id='choose_employee_memo'>Choose</a>");
		                  $("input[name='update_to<?php echo $counter; ?>']").attr("required","required");
		                  $("input[name='update_to<?php echo $counter; ?>']").val("");
		                  $("input[name='update_to<?php echo $counter; ?>']").attr("placeholder","Employee ..");

		                  $("button[id='add_recipient']").removeAttr("disabled");
		              }

		              if (recipientType == "All"){
		                $("input[name='update_to<?php echo $counter; ?>']").attr("disabled","disabled");
		                $("input[name='update_to<?php echo $counter; ?>']").removeAttr("required");
		                 $("div[id='choose<?php echo $counter; ?>']").html("");
		                 $("input[name='update_to<?php echo $counter; ?>']").val("");
		                 $("input[name='update_to<?php echo $counter; ?>']").attr("placeholder","");

		                  $("button[id='add_recipient']").attr("disabled","disabled");

             			  $("#update_div_recipient").html("");
		                // for resetting global variable value
		              

		              }


		              if (recipientType == "Department"){
		                $("input[name='update_to<?php echo $counter; ?>']").removeAttr("disabled");
		                $("input[name='update_to<?php echo $counter; ?>']").attr("required","required");
		                $("div[id='choose<?php echo $counter; ?>']").html("<a href='#' id='choose_department_memo'>Choose</a>");
		                $("input[name='update_to<?php echo $counter; ?>']").attr("placeholder","Department ..");
		                $("input[name='update_to<?php echo $counter; ?>']").val("");

		                $("button[id='add_recipient']").removeAttr("disabled");
		                // for resetting global variable value
		                

		              }
		          }

				});
				<?php


					
				}while($count >= $counter);


				?>

				


			   // for txt only
			    $(document).on('keypress', 'input[name="update_subject"]', function (event) {


			        var regex = new RegExp("^[<>/]+$");
			        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

			        if (regex.test(key)) {
			            event.preventDefault();
			            return false;
			        }
			    });



			        // for security purpose return false
			     $("input[name='update_subject").on("paste", function(){
			    
			          return false;
			     });


			       // for handling security in contactNo
				    $("input[name='update_subject']").on('input', function(){

				       if ($(this).attr("maxlength") != 100){
				            if ($(this).val().length > 100){
				                $(this).val($(this).val().slice(0,-1));
				            }
				           $(this).attr("maxlength","100");
				       }

				   });


				 



			    $("#attendance_list").on("click","a[id='chooseEmployee']", function(){
			    	//alert("Hello Worldss!");	
			    	$("a[id='chooseEmployee']").on("click",function () {
						 // empName
			        var datastring = "emp_id="+$(this).closest("tr").attr("id");

			        //alert("Hello World!");
			        //alert("Hello World!");

			        var emp_id = $(this).closest("tr").attr("id");

			        // datastring
			          $.ajax({
			                type: "POST",
			                url: "ajax/append_emp_name.php",
			                data: datastring,
			                cache: false,
			               // datatype: "php",
			                success: function (data) {

			                  $("#emp_list_modal").modal("hide");
			                 // $("#updateFormModal").modal("show");
			                    
			                  // if has an error
			                  if (data == "Error" || data == 1){
			                     $("#errorModal").modal("show");
			                  }
			                  // if success
			                  else {
			                  	//alert("Hello World!");
			                    $("input[name='update_to"+emp_id_count+"']").val(data);
			                    //$("div[id='to_div']").html("<input type='hidden' name='updateEmpId' value='"+emp_id+"' />");
			                    
			                   // $("#atm_record_modal_body").html(data);
			                   // $("#updateATM_modal").modal("show");
			                   
			                  }
			                 
			                }
			           }); 
				    });
		    	})


				    // for choosing employee name dynamic chooseEmployee for memorandum
			     


				    // department_list
					// for choosing employee name dynamic chooseDepartment for memorandum
				   //  $("a[id='chooseDepartment']").on("click",function () {
			     	  $("#department_list").on("click","a[id='chooseDepartment']", function(){
				        // empName
				        var datastring = "dept_id="+$(this).closest("tr").attr("id");

				        var dept_id = $(this).closest("tr").attr("id");


				        // datastring
				          $.ajax({
				                type: "POST",
				                url: "ajax/append_department_value.php",
				                data: datastring,
				                cache: false,
				               // datatype: "php",
				                success: function (data) {

				                  $("#dept_list_modal").modal("hide");
				                    
				                  // if has an error
				                  if (data == "Error" || data == 1){
				                     $("#errorModal").modal("show");
				                  }
				                  // if success
				                  else {
				                     $("input[name='update_to"+dept_id_count+"']").val(data);
				                    // $("div[id='to_div']").html("<input type='hidden' name='updateDeptId' value='"+dept_id+"' />");
				                    
				                   // $("#atm_record_modal_body").html(data);
				                   // $("#updateATM_modal").modal("show");
				                   
				                  }
				                 
				                }
				           }); 
				    });



			

					$("button[id='add_recipient']").on("click",function(){

					 //alert(memoRecipientCount);
				      memoRecipientCount++;

				      //var div = '</div>';
				      var labelRecipient = '<label class="control-label col-sm-3 col-sm-offset-1"><b>Recipient:</b></label>';
				     // var allOption = '<label class="radio-inline"><input required="required" type="radio" value="All" name="optRecipient'+memoRecipientCount+'">All</label>';
				      var deptOption = '<label class="radio-inline"><input required="required" type="radio" value="Department" name="update_optRecipient'+memoRecipientCount+'">Department</label>';
				      var specificOption = '<label class="radio-inline"><input required="required" type="radio" value="Specific Employee" name="update_optRecipient'+memoRecipientCount+'">Specific Employee</label>&nbsp;&nbsp;<button type="button" class="btn btn-danger btn-sm" id="remove_recipient'+memoRecipientCount+'">-</button>';
				      var labelTo = '<label class="control-label col-sm-3 col-sm-offset-1"><b>To:</b></label>';
				      var divTxt = '<div class="col-sm-6 txt-pagibig-loan" style="margin-right:-15px;">';
				      var txt = '<input type="text" class="form-control" name="update_to'+memoRecipientCount+'" placeholder="" id="input_payroll" required="required" disabled="disabled" autocomplete="off"/>';
				      var endDivTxt = '</div>';
				      var labelChoose = '<label class="col-sm-1 control-label"><div id="choose'+memoRecipientCount+'"></div></label>';

				      $("#update_div_recipient").append('<div id="update_recipient_mother_div'+memoRecipientCount+'"><div class="form-group">'+labelRecipient+deptOption+specificOption+'</div><div class="form-group">'+labelTo+divTxt+txt+endDivTxt+labelChoose+'</div></div>');
				    

				      // for function
				      $("input[name='update_optRecipient"+memoRecipientCount+"']").on("click",function () {
				          var recipientType = $(this).val();
				          //alert("Hello World!");
				          //alert(recipientType);

				         // alert(recipientType);
				          //alert(memoRecipientCount);

				          // success
				          if (recipientType == "Specific Employee" || recipientType == "Department"){
				              if (recipientType == "Specific Employee") {
				              	  //alert("Hello World!");
				              	  //alert($(this).attr("name").slice(19,20));
				                  $("input[name='update_to"+$(this).attr("name").slice(19,20)+"']").removeAttr("disabled");
				                  $("div[id='choose"+$(this).attr("name").slice(19,20)+"']").html("<a href='#' id='choose_employee_memo'>Choose</a>");
				                  $("input[name='update_to"+$(this).attr("name").slice(19,20)+"']").attr("required","required");
				                  $("input[name='update_to"+$(this).attr("name").slice(19,20)+"']").val("");
				                  $("input[name='update_to"+$(this).attr("name").slice(19,20)+"']").attr("placeholder","Employee ..");
				              }

				            

				              if (recipientType == "Department"){
				                $("input[name='update_to"+$(this).attr("name").slice(19,20)+"']").removeAttr("disabled");
				                $("input[name='update_to"+$(this).attr("name").slice(19,20)+"']").attr("required","required");
				                $("div[id='choose"+$(this).attr("name").slice(19,20)+"']").html("<a href='#' id='choose_department_memo'>Choose</a>");
				                $("input[name='update_to"+$(this).attr("name").slice(19,20)+"']").attr("placeholder","Department ..");
				                $("input[name='update_to"+$(this).attr("name").slice(19,20)+"']").val("");


				                // for resetting global variable value
				               
				              }
				          }   

				      });

				  
				      $("div[id='choose"+memoRecipientCount+"']").on("click","a[id='choose_department_memo']",function () {
				          $("#dept_list_modal").modal("show");

				          dept_id_count = $(this).closest("div").attr("id").slice(6,7);
				         // alert(id_count);

				      }); 


				       $("div[id='choose"+memoRecipientCount+"']").on("click","a[id='choose_employee_memo']",function () {
				        $("#emp_list_modal").modal("show");

				       // alert(emp_id_count);
				         emp_id_count = $(this).closest("div").attr("id").slice(6,7);
				       //  alert(emp_id_count);
				    });


				       // for removing recipient
				       $("button[id='remove_recipient"+memoRecipientCount+"']").on("click",function(){

				         

				         $("div[id='recipient_mother_div"+$(this).attr("id").slice(16,17)+"']").remove();
				          //alert(memoRecipientCount);
				          //  $("recipient_mother_div"+memoRecipientCount)



				       });


				    

					


					


					$("button[id='remove_recipient"+memoRecipientCount+"']").on("click",function(){

         

			        	 $("div[id='update_recipient_mother_div"+$(this).attr("id").slice(16,17)+"']").remove();
			          //alert(memoRecipientCount);
			          //  $("recipient_mother_div"+memoRecipientCount)



	       			});


		  	  });


			  // updateMemo
		      $("input[id='updateMemo']").on("click",function () {

		        // for backing its required field
		       // $("input[name='update_effectiveDate']").attr("required","required");

		         // for backing its required field
		        $("input[name='update_optRecipient1']").attr("required","required");

		        if ($("input[name='update_optRecipient1']:checked").val() == "Specific Employee" || $("input[name='update_optRecipient1']:checked").val() == "Department"){
		            $("input[name='update_to1']").attr("required","required");
		        }

		        $("input[name='update_subject']").attr("required","required");
		        $("input[name='update_content']").attr("required","required");
		        
		        
		        if ($("input[name='update_optRecipient1']:checked").val() == "All") {
		           if ($("input[name='update_subject']").val() != "" && $("input[name='update_content']").val() != ""){
		              // alert(global_emp_id);
		             //  $("input[name='empName']").after("<input type='hidden' name='empId' value='"+add_simkimban_emp_id+"' />");
		              //$("input[name='update_to']").after("<input type='hidden' name='update_memoId' value='"+update_memo_id+"' />");
	             	  $("#form_updateMemo").append("<input type='hidden' name='memo_id' value='<?php echo $memo_id; ?>' />");
		              $("#form_updateMemo").append("<input type='hidden' name='count' value='"+memoRecipientCount+"' />");
		              $("#form_updateMemo").attr("action","php script/update_memo.php");
		             // alert("Ready For Submition");
		           }
		        }

		        if ($("input[name='update_optRecipient1']:checked").val() == "Specific Employee") {
		             if ($("input[name='update_to1']").val() != ""  && $("input[name='update_subject']").val() != "" && $("input[name='update_content']").val() != ""){
		              // alert(global_emp_id);
		              $("#form_updateMemo").append("<input type='hidden' name='memo_id' value='<?php echo $memo_id; ?>' />");
		               $("#form_updateMemo").append("<input type='hidden' name='count' value='"+memoRecipientCount+"' />");
		               //$("input[name='update_to']").after("<input type='hidden' name='update_memoId' value='"+update_memo_id+"' />");
		               $("#form_updateMemo").attr("action","php script/update_memo.php");
		             // alert("Ready For Submition");
		           }

		        }


		        // if 
		         if ($("input[name='update_optRecipient1']:checked").val() == "Department") {
		             if ($("input[name='update_to1']").val() != ""  && $("input[name='update_subject']").val() != "" && $("input[name='update_content']").val() != ""){
		              // $("input[name='update_to']").after("<input type='hidden' name='update_memoId' value='"+update_memo_id+"' />");
		              	$("#form_updateMemo").append("<input type='hidden' name='memo_id' value='<?php echo $memo_id; ?>' />");
		               $("#form_updateMemo").append("<input type='hidden' name='count' value='"+memoRecipientCount+"' />");
		               $("#form_updateMemo").attr("action","php script/update_memo.php");
		            }

		        } 


		        // empName
		        /*if ($("input[name='update_subject']").val() != "" && $("input[name='update_content']").val() != ""){
		            // alert(global_emp_id);
		             $("input[name='update_empName']").after("<input type='hidden' name='update_memoId' value='"+update_memo_id+"' />");
		             $("#form_updateMemo").attr("action","php script/update_memo.php");
		        }*/
		                

		    });


	




				    

		    });

	





		</script>




<?php
	} // end of if
	else {
		echo "Error";
	}

}

else {
	header("Location:../MainForm.php");
}


?>