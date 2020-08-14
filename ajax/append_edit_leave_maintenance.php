<?php
include "../class/connect.php";
include "../class/leave_class.php";
	

	$leave_class = new Leave;

	if (isset($_POST["lt_id"])){

		$lt_id = $_POST["lt_id"];

		if ($leave_class->checkExistLeaveType($lt_id) == 0){
			echo "Error";
		}

		else {
			$row = $leave_class->getLeaveTypeById($lt_id);
			$lv_id = $row->lv_id;
			$no_days_to_file  = $row->no_days_to_file ;
			$name = $row->name;
			$count = round($row->count);
			$is_convertable_to_cash = $row->is_convertable_to_cash;
?>
<div class="container-fluid">
	<div class="row">
		<form class="form-horizontal" id="form_leave_maintenance">
			<div class="form-group">
				<div class="col-md-6">
					<label class="control-label">Name:</label>
					<input type="text" class="form-control" name="name" value="<?php echo $name; ?>" required="required"/>
				</div>
				<div class="col-md-6">

					<label class="control-label">Validation:</label>
					<select name="validation" class="form-control" required="required">
						<option value="">Please select</option>
						<?php
							$leave_class->getLeaveValidationToDropdown($lv_id,"Edit");
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-6">
					<label class="control-label">No. Of Days To Be File:</label>
					<?php

						$disabled = "";
						if ($no_days_to_file == ""){
							$disabled = "disabled='disabled'";
						}
					?>
					<input type="text" class="form-control" name="no_days_to_file" value="<?php echo $no_days_to_file; ?>" required="required" id="number_only" <?php echo $disabled; ?> />
				</div>
				<div class="col-md-6">
					<label class="control-label">Leave Count</label>
					<input type="text" class="form-control" name="leave_count" required="required" id="number_only" value="<?php echo $count; ?>"/>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-12">
					<div class="checkbox">
						<?php

							$checked = "";
							if ($is_convertable_to_cash == 1){
								$checked = "checked='checked'";
							}
						?>
					  <label><input name="is_convetable_to_cash" <?php echo $checked; ?> type="checkbox" value="">Is Convertable To Cash</label>
					</div>
				</div>
			</div>

			
			<div class="form-group">
				<div class="col-md-10">
					<label id="msg">&nbsp;</label>
				</div>
				<div class="col-md-2">
					<button class="pull-right btn-md btn btn-success" type="submit">Save</button>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
	$(document).ready(function(){
		//alert("HELL WORLD!");
		//$('[data-toggle="popover"]').popover(); 


		// for number only
	     $("input[id='number_only']").keydown(function (e) {
	        // Allow: backspace, delete, tab, escape, enter , F5
	        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116]) !== -1 ||
	             // Allow: Ctrl+A, Command+A
	            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
	             // Allow: home, end, left, right, down, up
	            (e.keyCode >= 35 && e.keyCode <= 40)) {
	                 // let it happen, don't do anything
	                 return;
	        }
	        // Ensure that it is a number and stop the keypress
	        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	            e.preventDefault();
	        }
	    });

	  	
	     $("input[id='number_only']").on('input', function(){
		       if ($(this).attr("maxlength") != 10){
		            if ($(this).val().length > 10){
		                $(this).val($(this).val().slice(0,-1));
		            }
		           $(this).attr("maxlength","10");
		       }

		   });

	     // for security purpose return false 
	     $("input[id='number_only").on("paste", function(){
	          return false;
	     }); 


		$("#form_leave_maintenance").submit(function(event) {          
          event.preventDefault();
         
        });

		var obj = $("input[name='no_days_to_file']");
		//disabledNoOfDays(obj);
		$("select[name='validation']").on("change",function(){


			var validation = $(this).val();
			var values = obj.val();
			disabledNoOfDays(obj);

			if (validation == 1 || validation == 2){

				enabledNoOfDays(obj,values);
			}

		});


        $("#form_leave_maintenance").on("click","button",function(){
        	//alert("HELLo world1");
        	var name = $("input[name='name']").val();
        	var validation = $("select[name='validation']").val();
        	var no_days_to_file = $("input[name='no_days_to_file']").val();
        	var leave_count = $("input[name='leave_count']").val();
        	var is_convetable_to_cash = 0;
			if ($("input[name='is_convetable_to_cash']").is(":checked")){
				is_convetable_to_cash = 1;
			}

        	if (name != "" && validation != "" && (((validation == 1 || validation == 2) && no_days_to_file != "") || ((validation == 3 || validation == 4) && no_days_to_file == "")) && leave_count != ""){

        		//alert("READY FOR SUBMITTION");
        		var datastring = "";
        		datastring += "name="+name;
        		datastring += "&validation="+validation;
        		datastring += "&no_days_to_file="+no_days_to_file;
        		datastring += "&leave_count="+leave_count;
        		datastring += "&is_convetable_to_cash="+is_convetable_to_cash;
        		datastring += "&lt_id="+"<?php echo $lt_id; ?>";

        		//alert(datastring);
        		$(this).attr("disabled","disabled");
        		$("#msg").html("<center><div class='loader' style='float:left'></div>Please wait ...</center>");
        		$.ajax({
		            type: "POST",
		            url: "php script/edit_leave_maintenance.php",
		            data: datastring,
		            cache: false,
		           // datatype: "php",
		            success: function (data) {
		            	if (data != "Success"){
		            		$("#msg").html(data);
		            		$("#form_leave_maintenance").find("button").removeAttr("disabled");
		            	}
		            	else {
		            		location.reload();
		            	}
		              //$("select[name='position']").html(data);
		               // $('#update_modal_body').html(data);
		              //  $("#update_info_modal").modal("show");
		            }
		        });
        	}
        });


        function disabledNoOfDays(obj){
        	obj.attr("disabled","disabled");
        	obj.val("");
        }

        function enabledNoOfDays(obj,values){
        	obj.val(values);
        	obj.removeAttr("disabled");
        	
        }

       
	   // alert("HELL WORLD!");
			
	});

</script>
<?php
		}
	}

	else {
		echo "No Data to Display!";
	}

?>