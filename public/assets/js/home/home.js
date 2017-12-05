/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


(function($) {

    $.fn.showLoadingView = function() {

		var doc = document.documentElement;
		var top = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
    	$("body").append( "<div id='overlay' style='top: "+top+"px;'><div id='loader'></div></div>" );
    	
    	$.scrollLock(true);

    	$("#overlay").fadeIn(250);

    }

    $.fn.closeLoadingView = function() {

    	$("#overlay").fadeOut(250, function() {
    		$("#overlay").remove();
    		$.scrollLock(false);
  		});
    	

    }

}(jQuery));