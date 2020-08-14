$(document).ready(function(){

	
	$('[data-toggle="popover"]').popover();  
	$("a[id='hover_info']").hover(function() {
    	//alert("Hello World!");
        $(this).trigger("click");
    	
    });

});