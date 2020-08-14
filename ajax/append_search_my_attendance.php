<?php
session_start();
include "../class/connect.php";
include "../class/date.php";
include "../class/time_in_time_out.php";
include "../class/emp_information.php";


if (isset($_POST["searchOption"]) && isset($_POST["dateFrom"]) && isset($_POST["dateTo"])) {
	
	$date_class = new date;
	$attendance_class = new Attendance;
	$emp_info_class = new EmployeeInformation;

	$emp_id = $_SESSION["id"];
	$bio_id = $emp_info_class->getEmpInfoByRow($emp_id)->bio_id;



	//$attendance_class->attendanceTotTable($bio_id);



	$searchOption = $_POST["searchOption"];
	$dateFrom = $_POST["dateFrom"];
	$dateTo = $_POST["dateTo"];

	$searchResult = $searchOption;
	if ($searchOption == "Specific Date") {
		$searchResult = $searchOption  . " " . $date_class->dateFormat($dateFrom) . " - " .  $date_class->dateFormat($dateTo);
	}

	


?>

	<div class="col-sm-12" style="margin-top:50px;border:1px solid #BDBDBD;">
		<div style="margin-left:-15px;margin-right:-15px;background-color:  #1abc9c ;margin-bottom:10px;">
			<label class="control-label" style="color:#fff;">&nbsp;<b>Search Result - </b><?php echo $searchResult; ?></label>
		</div>
		<table id="attendance_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
			<thead>
				<tr>
					<th class="no-sort"><center><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Date</center></th>
					<th class="no-sort"><center><span class="glyphicon glyphicon-time" style="color:#186a3b"></span> Time In</center></th>
					<th class="no-sort"><center><span class="glyphicon glyphicon-time" style="color:#186a3b"></span> Time Out</center></th>
					<th  class="no-sort"><center><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</center></th>
				</tr>
			</thead>
			<tbody>	
			<?php

			    //
				if ($searchOption == "All") {
			        $attendance_class->attendanceTotTable($bio_id);
				}

				else if ($searchOption == "Current Cut off"){
					$attendance_class->getCurrentCutOff($bio_id);
				}


				else if ($searchOption == "Specific Date"){
					$dateFrom = $date_class->dateDefaultDb($_POST["dateFrom"]);
					$dateTo = $date_class->dateDefaultDb($_POST["dateTo"]);
					$attendance_class->getSpecificDate($bio_id,$dateFrom,$dateTo);
				}

			?>
			</tbody>
		</table>
	</div>

	<script>
		$(document).ready(function(){
			$('#attendance_list').dataTable( {
			     "order": [],
			    "columnDefs": [ {
			      "targets"  : 'no-sort',
			      "orderable": false,
			    }]
			});


			/*$("select[name='attendance_list_length']").change(function(){
				//alert("wew");
				$(this).val(); // for setting to 25
		 	
		 	});
			
			$("select[name='attendance_list_length']").trigger("change");
			*/


			// for Update attendance
		      /* $("a[id='edit_bio_id']").on("click", function () {
		         var datastring = "attendance_id="+$(this).closest("tr").attr("id");
		        // alert(datastring);
		         $("#modal_body_update_attendance").html("<center><div class='loader'></div>Loading Information</center>");
		          $.ajax({
		              type: "POST",
		              url: "ajax/append_update_attendance.php",
		              data: datastring,
		              cache: false,
		              success: function (data) {
		                // if has error 
		                if (data == "Error"){
		                  $("#update_errorModal").modal("show");
		                }
		                // if success
		                else {               
		                  $("#modal_body_update_attendance").html(data);
		                  $("#updateAttendanceModal").modal("show");
		                }
		                
		              }
		           });
		        
		      });*/

		});

	</script>


<?php
}

else {
	header("Location:../Mainform.php");
}

?>