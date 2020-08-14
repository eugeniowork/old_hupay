$(document).ready(function(){
	//alert("HELLO WORLD!");

	$("#add_btn").on("click",function(){

		var datastring = "";
		datastring = "append=1";

		$.ajax({
            type: "POST",
            url: "ajax/append_add_leave_maintenance.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {
             
                $("#info_modal_size").attr("class","modal-dialog modal-md");
            	$("#info_modal_title").html("Add Leave Type");

            	$("#informationModal").modal("show");
            	$("#info_modal_body").html(data);
            }
        });


	});


	$("button[id='edit_leave_type']").on("click",function(){
		//alert("HELLO WORLD!");
		var lt_id = $(this).closest("tr").attr("id");

		var datastring = "lt_id="+lt_id;

		$.ajax({
            type: "POST",
            url: "ajax/append_edit_leave_maintenance.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {

            	if (data == "Error"){
            		$("#ErrorModal").modal("show");
            	}
            	else {

             
	                $("#info_modal_size").attr("class","modal-dialog modal-md");
	            	$("#info_modal_title").html("Edit Leave Type");

	            	$("#informationModal").modal("show");
	            	$("#info_modal_body").html(data);
            	}
            }
        });
	});


	$("button[id='delete_leave_type']").on("click",function(){
		var lt_id = $(this).closest("tr").attr("id");

		var datastring = "lt_id="+lt_id;


		// var icon = "&nbsp;<i class='fa fa-share-alt' color-white></i>";
	            
    	//alert(datastring);
        $.ajax({
            type: "POST",
            url: "ajax/append_delete_leave_maintenance.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {
            	if (data == "Error"){
            		$("#ErrorModal").modal("show");
            	}
            	else {	

            		var icon = "&nbsp;<span class='glyphicon glyphicon-trash color-white'></span>";
             
	                var modal_title = icon +"&nbsp;<span class='color-white'>Delete Confirmation</span>";
		            // for size
		            $("#confirmation_modal_size").attr("class","modal-dialog modal-sm");
		            $("#confirmation_modal_title").html(modal_title);

		            $("#ConfirmationModal").modal("show");
		            $("#confirmation_modal_body").html(data);
		        }
            }
        });
	});


	// 
	$("button[id='active_leave_type']").on("click",function(){
		var lt_id = $(this).closest("tr").attr("id");

		var datastring = "lt_id="+lt_id;


		// var icon = "&nbsp;<i class='fa fa-share-alt' color-white></i>";
	            
    	//alert(datastring);
        $.ajax({
            type: "POST",
            url: "ajax/append_active_leave_maintenance.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {
            	if (data == "Error"){
            		$("#ErrorModal").modal("show");
            	}
            	else {	

            		var icon = "";
             
	                var modal_title = icon +"&nbsp;<span class='color-white'>Confirmation</span>";
		            // for size
		            $("#confirmation_modal_size").attr("class","modal-dialog modal-sm");
		            $("#confirmation_modal_title").html(modal_title);

		            $("#ConfirmationModal").modal("show");
		            $("#confirmation_modal_body").html(data);
		        }
            }
        });
	});
});