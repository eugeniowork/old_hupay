<?php
session_start();
include "class/connect.php";
include "class/memorandum_class.php";
include "class/emp_information.php";
include "class/position_class.php";
include "class/date.php";
include "class/department.php";



if ($_SESSION["role"] != 1 &&  $_SESSION["role"] != 2 &&  $_SESSION["role"] != 3){
	header("Location:MainForm.php");
}


if (isset($_GET["memo_id"])){
	$memo_id = $_GET["memo_id"];

	//echo $memo_id;

	$memo_class = new Memorandum;
    $row = $memo_class->getMemoInfoById($memo_id);

    $emp_info_class = new EmployeeInformation;
    $row_memo_from = $emp_info_class->getEmpInfoByRow($row->memoFrom);

    $position_class = new Position;
    $row_position = $position_class->getPositionById($row_memo_from->position_id);

    $date_class = new date;
    $department_class = new Department;

    


    /*
    $recipient = ""; // for initialization value
    if ($row->recipient == "Specific Employee"){
       $emp_id = $row->emp_id;
       $row_emp = $emp_info_class->getEmpInfoByRow($emp_id);

       $recipient = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

       if ($row_emp->Middlename == ""){
          $recipient = $row_emp->Lastname . ", " . $row_emp->Firstname;
       }
    }

    else if ($row->recipient == "Department"){
      $dept_id = $row->dept_id;
      $row_dept = $department_class->getDepartmentValue($dept_id);
      $recipient = $row_dept->Department;
    }

    else {
        $recipient = "All";
    }
    */

   

  
   //$fileName = $recipient . "_" . $row->Subject . ".doc";



   header("Cache-Control: ");// leave blank to avoid IE errors
   header("Pragma: ");// leave blank to avoid IE errors
   header('Content-Description: File Transfer');
   header("Content-type: application/vnd.ms-word");
   header("Content-Disposition: attachment;Filename=memorandum.doc"); 
   
   //$image_path = 'http://192.168.2.125/hupay_testing_area/img/logo/lloyds_report_logo.jpeg';
   $image_path = 'http://philip-pc:91/img/logo/lloyds_report_logo.jpeg';
   /*$newheight = 100;
   
   list($originalwidth, $originalheight) = getimagesize($image_path);
   $ratio = $originalheight / $newheight;
   $newwidth = $originalwidth / $ratio; */
?>
<html>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">

	<body>
		  <center>
        <div>
          <img src='<?php echo $image_path; ?>' height="80" width="240"/>
        </div>
        <div>
          <b style="font-size:12px">1255 Cardona St. Rizal Village, Makati City</b>
        </div>
        <div>
          <b style="font-size:12px">897 66 44 - 46 / 897 62 76 - 77</b>
        </div>
      </center>

      <br/>
      <br/>

      <h4><u>MEMORANDUM</u></h4>

      <br/>


      <div>
        <span style="float:left;">TO: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <span class="float:left;"><?php echo $memo_class->getMultipleMemoInfo($memo_id); ?></span>
      </div>

      <br/>

       <div>
        <span style="float:left;">FROM: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <span class="float:left;"><?php echo $row_position->Position; ?></span>
      </div>

      <br/>

      <div>
        <span style="float:left;">ISSUE DATE: &nbsp;&nbsp;&nbsp;&nbsp;</span>
        <span class="float:left;"><?php echo $date_class->dateFormat($row->DateCreated); ?></span>
      </div>

      <br/>

      <div>
        <span style="float:left;">SUBJECT: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <span class="float:left;"><b><?php echo $row->Subject; ?></b></span>
      </div>

      <div style="color:#357ca5;border-bottom:1px solid #000000">&nbsp;</div>

      <br/>
      <div>
          <?php echo $row->Content; ?>
      </div>


	</body>
</html>

<?php
}

else {
	header("Location:MainForm.php");
}



?>