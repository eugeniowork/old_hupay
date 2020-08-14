$(document).ready(function(){

	var showChar = 100;  // How many characters are shown by default
    var ellipsestext = "...";
    //var moretext = "Show more >";
    //var lesstext = "Show less";
    
    $("td[id='readmoreValueMemo']").each(function() {

        var content = $(this).html();

 
        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent">&nbsp;&nbsp;</span>';
 
            $(this).html(html);
        }
 
    });




    


 });