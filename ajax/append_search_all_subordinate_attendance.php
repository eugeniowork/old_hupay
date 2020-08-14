<?php
session_start();
include "../class/connect.php";
include "../class/date.php";
include "../class/time_in_time_out.php";
include "../class/emp_information.php";



if (isset($_POST["dateFrom"]) && isset($_POST["dateTo"])) {


$date_class = new date;
$attendance_class = new Attendance;
$emp_info_class = new EmployeeInformation;


$dateFrom = $date_class->dateDefaultDb($_POST["dateFrom"]);
$dateTo = $date_class->dateDefaultDb($_POST["dateTo"]);

	


?>

	<div class="col-sm-12" style="margin-top:50px;border:1px solid #BDBDBD;">
		<div style="margin-left:-15px;margin-right:-15px;background-color:  #1abc9c ;margin-bottom:10px;">
			<label class="control-label" style="color:#fff;">&nbsp;<b>Search Result - Date From : <?php echo $date_class->dateFormat($_POST["dateFrom"]); ?> - Date To : <?php echo $date_class->dateFormat($_POST["dateTo"]); ?></b></label>
		</div>
		<table id="attendance_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
			<thead>
				<tr>
					<th class=""><center><span class="glyphicon glyphicon-user" style="color:#186a3b"></span> Employee Name</center></th>
					<th class="no-sort"><center><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Date</center></th>
					<th class="no-sort"><center><span class="glyphicon glyphicon-time" style="color:#186a3b"></span> Time In</center></th>
					<th class="no-sort"><center><span class="glyphicon glyphicon-time" style="color:#186a3b"></span> Time Out</center></th>
				</tr>
			</thead>
			<tbody>	
				<?php
					$attendance_class->getAllSubordinateAttendanceToTable($dateFrom,$dateTo,$_SESSION["id"]);

				?>
			</tbody>
		</table>
		<br/>

		<div class="pull-right" id="print_search_attendance_list" style="cursor:pointer;margin-bottom:10px;">
			<?php
				$from = date_format(date_create($dateFrom), 'F d');
				$to = date_format(date_create($dateTo), 'F d, Y');
			?>
 			<span class="glyphicon glyphicon-print" style="color: #2c3e50"></span> <b style="color:#158cba">Print Attendance Reports <?php echo $from . " - " . $to;?></b>
 		</div>

 		<form id="form_print_search_attendance_list" method="post" action="">

		</form>

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


			// for Update attendance
	       $("div[id='print_search_attendance_list']").on("click",function() {
	       		var dateFrom = "<?php echo $dateFrom; ?>";
	       		var dateTo = "<?php echo $dateTo; ?>";

	       		
		        //window.location = "print_search_attendance_list.php";
		        $("#form_print_search_attendance_list").html('<input type="hidden" name="dateFrom" value="'+dateFrom+'"/><input type="hidden" name="dateTo" value="'+dateTo+'"/>');
		        $("#form_print_search_attendance_list").attr("action","print_search_attendance_list.php");
		        $("#form_print_search_attendance_list").submit();
		    });

		});

	</script>

<?php
} // end of if

else {
	header("Location:../MainForm.php");
}

?>



