
jQuery(document).ready(function(){

	jQuery(".news_content").hide();
	jQuery(".news_content:first").show();
	jQuery("a[newsid]").click(function(){
			jQuery(".news_content").hide();
			jQuery(".news-" + jQuery(this).attr("newsid")).show();
		});
	
});