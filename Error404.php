<?php
	
	$base_url = "http://diamond/hupay";
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Error 404 Page</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <!-- for symbols -->
		<link rel="shortcut icon" href="img/logo/lloyds logo.png"> <!-- for logo -->

		<!-- css -->
		<link rel="stylesheet" href="<?php echo $base_url; ?>/css/lumen.min.css">
		<link rel="stylesheet" href="<?php echo $base_url; ?>/css/plug ins/calendar/dcalendar.picker.css">
		<link rel="stylesheet" href="<?php echo $base_url; ?>/css/custom.css">

		<!-- js -->
		<script src="<?php echo $base_url; ?>/js/jquery-1.12.2.min.js"></script>
		<script src="<?php echo $base_url; ?>/js/bootstrap.min.js"></script>
		<script src="<?php echo $base_url; ?>/js/plug ins/calendar/dcalendar.picker.js"></script>
		<script src="<?php echo $base_url; ?>/js/chevron.js"></script>
		<script src="<?php echo $base_url; ?>/js/notifications.js"></script>
		<script src="<?php echo $base_url; ?>/js/custom.js"></script>
		<script>
			$(document).ready(function(){
				
			});
		</script>
	</head>
	<body>
		<!-- for nav menu -->
		<nav class="navbar navbar-inverse navbar-fixed-top" style="background-color:#357ca5;">
			<div class="container-fluid">
				<div class="navbar-header">
				<!--
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapsemenu">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button> -->
				<a class="navbar-brand" href="MainForm.php">
					<img src="<?php echo $base_url; ?>/img/logo/lloyds logo.png" class="lloyds-logo"/>
				  	<!--<span style="color:rgba(255, 255, 255, 0.8);">&nbsp;HR & Payroll System</span>-->
			  		<span class="hupay-color">&nbsp;HuPay System</span>
				</a>
				</div>

				

			</div>	<!-- end of  div -->

		</nav> <!-- end of nav -->


		

		

		
			

		<!-- for body -->

		<div class="thumbnail col-sm-6 col-sm-offset-3" style="margin-top:100px;">
			<div class="caption">
				<div style="text-align:center">
					<h1><u>Error 404</u></h1>
				</div>

				 <div class="col-sm-3">
					<img src="<?php echo $base_url; ?>/img/error404.png" class="error404"/>
				</div> 

				<div class="col-sm-9">
					<h3><span class="label label-danger">OOPS! , The page you were looking for doesn't exist.</span></h3>
					<a href="<?php echo $base_url; ?>/MainForm.php" class="btn btn-success col-sm-4">Go back to homepage</a>
				</div>	
						
			</div>
		</div>
			


		


	</body>
</html>