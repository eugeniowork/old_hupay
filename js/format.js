$(document).ready(function(){

	/*$("input[name='contactNo']").change( function(){
		var contactNo = $(this).val();
		var cp_format = /^0[9][0-9]{9}$/; // nakakatuwa naman tong regex :D
	    var landline_format = /^[0-9]{7}$/; // format 22222222
	    var area_landline_format = /^[0-9]{9}$/; // format 123456789

	    var id = $(this).attr("id");


	    if (contactNo == "") {
	        var div = $(this).closest("div");
	        div.removeClass("has-error has-success has-feedback");
	        $("#glypcn" + id).remove();
	        return false;
	  

	    }
	    else if (contactNo.match(cp_format) || contactNo.match(landline_format)|| contactNo.match(area_landline_format)) { // if success
	        var div = $(this).closest("div");
	        div.removeClass("has-error");
	        div.addClass("has-success has-feedback");
	        $("#glypcn" + id).remove();
	        div.append('<span id="glypcn' + id + '" class="glyphicon glyphicon-ok form-control-feedback"></span>');
	        return true;
	        //document.getElementById("cp_div_spouse1").className = "col-lg-4 has-success"; // for div purposes
	        //document.getElementById("cp_number_spouse1").className = "glyphicon glyphicon-ok form-control-feedback"; // for span purposes

	    }
	    else { // if failed
	        var div = $(this).closest("div");
	        div.removeClass("has-success");
	        div.addClass("has-error has-feedback");
	        $("#glypcn" + id).remove();
	        div.append('<span id="glypcn' + id + '" class="glyphicon glyphicon-remove form-control-feedback"></span>');
	        return false;
	        //document.getElementById("cp_div_spouse1").className = "col-lg-4 has-error"; // for div purposes
	        //document.getElementById("cp_number_spouse1").className = "glyphicon glyphicon-remove form-control-feedback"; // for span purposes
	    }

		});*/

});