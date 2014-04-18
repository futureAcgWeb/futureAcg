
      function adapt(link){
        var span = document.getElementsByTagName("span");
         //var allLinks = ul.getElementsByTagName("li");
         for (var i=0; i<span.length-2; i++) {
          span[i].className = "";
         }
         link.className = "active";
      }
jQuery(document).ready(function(){

	jQuery(".foucebox").slide({
		effect:"fold",
		autoPlay:true,
		delayTime:300,
		startFun:function(i){
			jQuery(".foucebox .showDiv").eq(i).find("h2").css({display:"none",bottom:0}).animate({opacity:"show",bottom:"60px"},300);
			jQuery(".foucebox .showDiv").eq(i).find("p").css({display:"none",bottom:0}).animate({opacity:"show",bottom:"10px"},300);
		}
	});
	
});