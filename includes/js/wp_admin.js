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
		
	/* scoring system page **********************************************************/
	jQuery("#score_descrp_select").change(function(){
			if(jQuery(this).val()==0){
				jQuery("#score_descrp").show();
			}else{
				jQuery("#score_descrp").hide();
			}
		
		});
	jQuery("#clear_all").click(function(){
			jQuery(".score_detail").val("");
			//transfer the input into json form
			var str = "[";
			var flag = false;
			jQuery("#score_detail_table").find("tr").each(function(){
				var id = jQuery(this).find(".score_detail").attr("member_id");
				
				if(  id > 0 ){
					if(flag){
						str = str + ",";
					}
					flag = true;
					str = str + "{ \"id\":" + 	id +", \"score\":";
					if( jQuery(this).find(".score_detail").val().match(/^[-\+]?\d+(\.\d+)?$/)){
							str = str + jQuery(this).find(".score_detail").val();
					}else{
						str = str + "0";	
					}
					str = str +",\"descrp\":\"" + jQuery(this).find(".score_detail_descrp").val() + "\"}";
				}
				
			});
			str = str +"]";
			jQuery("#score_details").html(str);
		});
	jQuery("#copy_fist_row").click(function(){
			jQuery(".score_detail").val(jQuery(".score_detail").first().val());
			//transfer the input into json form
			var str = "[";
			var flag = false;
			jQuery("#score_detail_table").find("tr").each(function(){
				var id = jQuery(this).find(".score_detail").attr("member_id");
				
				if(  id > 0 ){
					if(flag){
						str = str + ",";
					}
					flag = true;
					str = str + "{ \"id\":" + 	id +", \"score\":";
					if( jQuery(this).find(".score_detail").val().match(/^[-\+]?\d+(\.\d+)?$/)){
							str = str + jQuery(this).find(".score_detail").val();
					}else{
						str = str + "0";	
					}
					str = str +",\"descrp\":\"" + jQuery(this).find(".score_detail_descrp").val() + "\"}";
				}
				
			});
			str = str +"]";
			jQuery("#score_details").html(str);
		});
		
	jQuery("#score_detail_table").find("input").change(function(){
			//transfer the input into json form
			var str = "[";
			var flag = false;
			jQuery("#score_detail_table").find("tr").each(function(){
				var id = jQuery(this).find(".score_detail").attr("member_id");
				
				if(  id > 0 ){
					if(flag){
						str = str + ",";
					}
					flag = true;	
					str = str + "{ \"id\":" + 	id +", \"score\":";
					if( jQuery(this).find(".score_detail").val().match(/^[-\+]?\d+(\.\d+)?$/)){
							str = str + jQuery(this).find(".score_detail").val();
					}else{
						str = str + "0";	
					}
					str = str +",\"descrp\":\"" + jQuery(this).find(".score_detail_descrp").val() + "\"}";
				}
				
			});
			str = str +"]";
			jQuery("#score_details").html(str);
		});
	//for editing scores
	jQuery(".editable").dblclick(function(){
			if( jQuery(this).find("input").length != 0 ){
				return;	
			}
			jQuery(".editable").find("input").each(function(){
				jQuery(this).parent().html(jQuery(this).val());				
			});
			var value = jQuery(this).html();
			if( jQuery(this).hasClass("score_date") ){
				var str = '<input name="score_time" type="text" value="' + value + '" class="edit_input score_detail_edit" style="border:1px solid #999;" onclick="fPopCalendar(event,this,this)" onfocus="this.select()" readonly="readonly">';
				jQuery(this).html(str);
				jQuery("#change_click").show();
				var flag = true;
				// when the date is changed
				jQuery("td[onclick^='fSetSelected']").click(function(){
					if( flag ){
						flag = false;
						if( jQuery("input[name='score_time']").val() == jQuery("input[name='score_time']").parent().attr("dbvalue")){
							flag = true;
							return;
						}
						var obj =  jQuery("input[name='score_time']").first();
						jQuery("#processing").html("正在操作中...");
						var scoreid = obj.parent().parent().attr("scorename");
						var data = {
								'action'	: 	'update_score_details',
								'table'		:	'score',
								'type'		:	"score_date",
								'scoreid'	: 	scoreid,
								'memberid'	:	-1,
								'datatype'	: 	'%s',
								'new'		:	obj.val(),
							};
						jQuery.post(ajaxurl, data, function(response) {
							if( response > 0 ){
								jQuery("#processing").html("操作完成！");
								
							}
							else{
								jQuery("#processing").html("更新出现问题，请刷新页面重试！");
							}
						});
						jQuery(".editable").find("input").each(function(){
							obj.parent().attr("dbvalue") = obj.val();	
							obj.parent().html(jQuery(this).val());	
						});
					}// ---- end of if flag
				});
				return;
			}// --- end of if is score_date 
			jQuery(this).html('<input type="text" class="edit_input score_detail_edit" value = "'+value+'">');
			
			/* 
			*for changing data
			*/
			jQuery(".edit_input").change(function(){
				//alert(jQuery(this).val());
				var obj = jQuery(this).parent();
				jQuery("#processing").html("正在操作中...");
				var scoreid = obj.parent().attr("scorename");
				var memberid = obj.parent().attr("scoremember");
				var datatype = '%s';
				var newdata = jQuery(this).val();
				if( obj.attr("info") == 'score'){
					datatype = '%d';
				}
				var table = "score_detail";
				
				if( obj.attr("table") == "score" ){
					table = obj.attr("table");
					memberid = -1 ;
				}
				var data = {
						'action'	: 	'update_score_details',
						'table'		:	table,
						'type'		:	obj.attr("info"),
						'scoreid'	: 	scoreid,
						'memberid'	:	memberid,
						'datatype'	: 	datatype,
						'new'		:	newdata,
					};
				jQuery.post(ajaxurl, data, function(response) {
/*					jQuery("#processing").html(response);
					return;*/
					if( response > 0 ){
						jQuery("#processing").html("操作完成！");
						
					}
					else{
						jQuery("#processing").html("更新出现问题，请刷新页面重试！");
					}
				});
				
				jQuery(".editable").find("input").each(function(){
					obj.html(newdata);	
				});
				
				// refresh the sum
				if( obj.attr("info") == "score" ){
					var sum = 0;
					jQuery("td[scoremember='" + memberid + "']").each(function(){
						sum = sum +  parseInt(jQuery(this).find("div:last").html());	
					});
					jQuery(".score_sum[scoremember='" + memberid + "']").html(sum);
				}
				// refresh the descrp
				if( obj.attr("info") == "descrp" &&   table == "score_detail"){
					var obj2 = jQuery("td[scoremember='" + memberid + "'][scorename='"+ scoreid +"']");
					if( obj2.find(".bottomdirection").length == 0){
						obj2.children().before("<div class='bottomdirection'></div>");
					}
					obj2.attr("title",newdata);
				}
			});

		});
	//for changing a single tuple
		
	//for deleting the whole score records
	jQuery(".score_delete").click(function(){
		if(!confirm('确认删除此条积分的全部数据？')){
			return;	
		}
		jQuery("#processing").html("正在操作中...");
		var scoreid = jQuery(this).attr("scoreid");
		var data = {
				'action': 'delete_score',
				'scoreid': scoreid,
			};
		  
		jQuery.post(ajaxurl, data, function(response) {
			if( response > 0 ){
				jQuery("#processing").html("操作完成！");
				jQuery("[scorename='" + scoreid + "']").remove();
			}
			else{
				jQuery("#processing").html("删除出现问题，请刷新页面重试！");
			}
		});
	});	
	jQuery(".score_block").click(function(){
		if(jQuery(this).hasClass("block_select")){
			jQuery(".block_select").removeClass("block_select");
			jQuery("#detail_descrp_edit").html("");
			return;
		}
		jQuery(".block_select").removeClass("block_select");
		jQuery(this).addClass("block_select");
		var obj = jQuery(this).parent()
		jQuery('th[scorename="'+obj.attr("scorename")+'"]').addClass("block_select");
		jQuery('th[scoremember="'+obj.attr("scoremember")+'"]').addClass("block_select");
		var content = jQuery(this).parent().attr("title");
		if(content == ""){
			content = "&nbsp;";
		}
		jQuery("#detail_descrp_edit").html(content);
		jQuery("#detail_descrp_edit").parent().attr("scorename",obj.attr("scorename"));
		jQuery("#detail_descrp_edit").parent().attr("scoremember",obj.attr("scoremember"));
	});
	
	/*
	*
	* for new project scoring page
	******/
	jQuery('#click_new_ps').click(function(){
		jQuery("#new_project_scoring").show();
		jQuery(this).hide();
		});
		
	jQuery('#click_cancel_ps').click(function(){
		jQuery("#new_project_scoring").hide();
		jQuery("#click_new_ps").show();
		});
	jQuery('#click_submit_ps').click(function(){
		jQuery("#new_project_scoring").hide();
		jQuery("#click_new_ps").show();
		});
	jQuery('input[name="CheckboxGroup"]').change(function(){
			var str = "[";
			var flag = false;
			jQuery('input[name="CheckboxGroup"]').each(function(){
				if( jQuery(this).is(':checked') ){
						if(flag){
							str = str + ",";	
						}	
						flag = true;
						str = str + jQuery(this).val();
				}
			});
			str = str + "]";
			jQuery("#projectlist").val(str);
		});
	
});
