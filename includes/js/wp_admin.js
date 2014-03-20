jQuery(document).ready(function () {


	jQuery("[dbvalue]").each( function (){
		jQuery(this).val(jQuery(this).attr('dbvalue'));
	});
	
	//transform the msg in the urls
	jQuery('.urlinput').find('.inputtext').change(function (){
		//switch the informations to json	
		var str = "{";
		var flag = false;
		jQuery('.urlinput').each(function () {
			if(flag){
				str = str + ",";
			}
			str = str + '{"title":"' + jQuery(this).find(".urlname").val()	
			+'","url":"' + jQuery(this).find(".urladdr").val() +'"}'
			flag = true;
			});
		str = str + "}"
		jQuery('#urls_json').html(str);
		
	});
	
	//add and del button 
	jQuery(".botton.url_del").click( function(){
		
		if (confirm("确认要删除？")) {
			   jQuery(this).parent().parent().remove();
			   var str = "[";
				var flag = false;
				jQuery('.urlinput').each(function () {
					if(flag){
						str = str + ",";
						}
					str = str + '{"title":"' + jQuery(this).find(".urlname").val()	
					+'","url":"' + jQuery(this).find(".urladdr").val() +'"}'
					flag = true;
					});
					str = str + "]"
					jQuery('#urls_json').html(str);

		 }//end of if
		 
	});
	jQuery(".botton.url_add").click( function(){
		var str = '	<tr class = "urlinput" id = "url_1" >'
		+ '<td ><label for="textfield"></label>'
        + '<input type="text" class = "urlname inputtext" name="contest_url_name_1" id="contest_url_name_1" /></td>'
        + ' <td><input type="text" class = "urladdr inputtext"  name="contest_url_2" id="contest_url_2" /></td>'
        + ' <td class = "urledit" >'
        + '		   	<input type="button" class="botton url_del"  value="-"  /></td></tr>'
		
		jQuery(this).parent().parent().parent().parent().next().append(str);
		//add event to new elements
		jQuery(this).parent().parent().parent().parent().next().find(".botton").last().click(function (){
			
				if (confirm("确认要删除？")) {
				   	jQuery(this).parent().parent().remove();
				   	var str = "[";
					var flag = false;
					jQuery('.urlinput').each(function () {
						if(flag){
							str = str + ",";
						}
						str = str + '{"title":"' + jQuery(this).find(".urlname").val()	
						+'","url":"' + jQuery(this).find(".urladdr").val() +'"}'
						flag = true;
						});
					str = str + "]"
					jQuery('#urls_json').html(str);
			 }
		});
		jQuery(this).parent().parent().parent().parent().next().find(".urlinput").last().find(".inputtext").change(
			function (){
			//switch the informations to json	
			var str = "{";
			var flag = false;
			jQuery('.urlinput').each(function () {
				if(flag){
					str = str + ",";
				}
				str = str + '{"title":"' + jQuery(this).find(".urlname").val()	
				+'","url":"' + jQuery(this).find(".urladdr").val() +'"}'
				flag = true;
				});
			str = str + "}"
			jQuery('#urls_json').html(str);
		});
		


	});
});
