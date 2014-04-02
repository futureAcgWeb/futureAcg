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
	
	
	/* project edit page **********************************************************/
	
	jQuery(".member_check").change( function(){
			var str = '[';
			var flag = false;
			jQuery(".member_check").each( function(){
				//;
				if ( jQuery(this).is(':checked') ){
					if (flag){
						str = str + ",";
					}
					str = str + jQuery(this).attr("name")
					flag = true;
				}
			});
			str = str + ']';
			jQuery('#member_json').html(str);
			if ( !jQuery(this).is(':checked') ){
				var obj = jQuery('[memberId="'+ jQuery(this).attr("name") + '"]');
				if( obj.hasClass("member_in_charge")){
					obj.removeClass("member_in_charge");
					jQuery('#member_in_charge').val("");
				}			
			}
	});
	jQuery("#admin_project_members label").click( function(){
		var memberid = jQuery(this).attr("memberid");
		jQuery(".member_in_charge").removeClass("member_in_charge");
		jQuery(this).addClass("member_in_charge");
		if( !jQuery("#member_" + memberid ).is(':checked')  )
		{
			jQuery("#member_" + memberid ).attr("checked",true);
			var str = '[';
			var flag = false;
			jQuery(".member_check").each( function(){
				//;
				if ( jQuery(this).is(':checked') ){
					if (flag){
						str = str + ",";
					}
					str = str + jQuery(this).attr("name")
					flag = true;
				}
			});
			str = str + ']';
			jQuery('#member_json').html(str);
		}
		jQuery('#member_in_charge').val(memberid);
	});
	/* hide some options for users */
	jQuery("#new_role").children('[value="team_leader"]').hide();
	jQuery("#new_role").children('[value="team_director"]').hide();
	jQuery('input[name="endmark"]').change(function(){
		
		if(jQuery(this).val()==0){
			jQuery("#endtimediv").hide();
			jQuery('input[name="endtime"]').val('');
			}else{
			jQuery("#endtimediv").show();
			}
		});
});
