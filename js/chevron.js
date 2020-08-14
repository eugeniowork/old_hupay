$(document).ready(function(){
	// JQUERY CODES GOES HERE
    //$(".parent a").on("click", function () {
	//	alert($(this).next().class("glyphicon glyphicon-menu-up"));
    //});

	// for chevron  in side bar handling
	$("li.parent a[data-toggle='collapse']").on("click", function () {
		if ($(this).next().attr("class") == "children collapse"){
			$(this).children().next().attr("class","pull-right glyphicon glyphicon glyphicon-menu-up");
		}
		if ($(this).next().attr("class") == "children collapse in"){
			$(this).children().next().attr("class","pull-right glyphicon glyphicon glyphicon-menu-down");
		}

    });

});