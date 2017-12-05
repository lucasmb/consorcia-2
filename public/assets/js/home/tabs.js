$(document).on('click', '.tab', function () {
	
    $(this).siblings().removeClass("active");    
	$(this).addClass("active");

	var tabsContent = $(this).parent().parent().find(".tabs-content");
	tabsContent.children().hide(); 

	$("#"+ $(this).attr("tab")).fadeIn(300);	

	
});