$(document).ready(function(){


	$("a[id='attendance_notifications']").on("click", function () {
		//alert("Hello World!");

		// for making no notif
		$("#notif_attendance_count").html("");



		var div = $(this).closest("div").attr("class");
		//alert(div.attr("class"));

		if (div == "dropdown pull-right full-name"){
			$.ajax({
	            type: "POST",
	            url: "ajax/notifications_attendance.php",
	            //data: datastring,
	            cache: false,
	           // datatype: "php",
	            success: function (data) {
	            	//alert(data); 	   	
	            }
	        }); 
        }
	});


	// for clicking payroll notification 
    $("a[id='payroll_notifications']").on("click",function() {
       // alert("Hello World!"); notif_payroll_count
       // for making no notif
		$("#notif_payroll_count").html("");

		var div = $(this).closest("div").attr("class");
		//alert(div.attr("class"));

		if (div == "dropdown pull-right full-name"){
			$.ajax({
	            type: "POST",
	            url: "ajax/notifications_payroll.php",
	            //data: datastring,
	            cache: false,
	           // datatype: "php",
	            success: function (data) {
	            	//alert(data); 	   	
	            }
	        }); 
        }

    });

    // memo_notifications
    $("a[id='memo_notifications']").on("click",function() {
       // alert("Hello World!"); notif_payroll_count
       // for making no notif
		$("#notif_memo_count").html("");

		var div = $(this).closest("div").attr("class");
		//alert(div.attr("class"));

		if (div == "dropdown pull-right full-name"){
			$.ajax({
	            type: "POST",
	            url: "ajax/notifications_memu.php",
	            //data: datastring,
	            cache: false,
	           // datatype: "php",
	            success: function (data) {
	            	//alert(data); 	   	
	            }
	        }); 
        }

    });



    // for events notication
     $("a[id='events_notifications']").on("click",function() {
       // alert("Hello World!"); notif_payroll_count
       // for making no notif
		$("#notif_events_count").html("");

		var div = $(this).closest("div").attr("class");
		//alert(div.attr("class"));

		if (div == "dropdown pull-right full-name"){
			$.ajax({
	            type: "POST",
	            url: "ajax/notifications_events.php",
	            //data: datastring,
	            cache: false,
	           // datatype: "php",
	            success: function (data) {
	            	//alert(data); 	   	
	            }
	        }); 
        }

    });


    // for approval file salary loan
    $("li[id='file_salary_loan_notif']").on("click",function() {
    		
		var file_salary_loan_id = $(this).attr("class");
		
		//alert(file_salary_loan_id);
		window.location = "approve_file_salary_loan.php?id="+file_salary_loan_id;

 	});

 	 // approve file salary loan 
    $("li[id='approve_file_salary_loan']").on("click",function() {
    		
		window.location = "salary_loan.php";
 	});


 	// payroll_notif_sent
    $("li[id='payroll_notif']").on("click",function() {
    		
		var notifType = $(this).attr("class");
		//alert(notifType);

    	if (notifType == "Already Sent"){
    		window.location = "payroll_approval.php";
    	}

    	else {
    		window.location = "my_payslip.php";
    	}

 	});



 	// events_notif
 	$("li[id='events_notif']").on("click",function() {
		window.location = "php script/view_events_script.php?events_notif_id="+$(this).attr("class");
	});

});