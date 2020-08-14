$(document).ready(function(){

	var availableTags = [];
	$("input[name='searchEmployee']").on('input', function(){

		var search = $(this).val();
		datastring = "search="+search;

		
		 $.ajax({
            type: "POST",
            url: "ajax/append_search_employee.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {

            	data = data.slice(0, -1);
            	data = data.slice(1, data.length);
            	availableTags = [data];
            	//alert(availableTags);
        		//alert(data);
       	     
            
            }
        }); 


		



 	});


	// var sad = ["Bogayan, Armando Jr","Sabitsana, Rita","c","d","e","f","g","h","i","j","k","l"];
 	 $("input[name='searchEmployee']").autocomplete({
	    source: function (request, response) {
        var results = $.ui.autocomplete.filter(availableTags, request.term);	       		        
		response(results);
		}
	});

		//var availableTags = [];

   
    	
  

		

	

	 

	  

});