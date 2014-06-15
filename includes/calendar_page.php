<?php
/* subtool: contest calendar */
/* this page is for the edit page of this type of poster */
// === Define Metabox Fields ====================================== //
$meta_box_calendar = array(
	'id' => 'meta_box_calendar',
	'title' => '比赛属性设置',
	'page' => 'calendar',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		'endmark',
		'start_date',
		'end_date',
		'urls_json',
		'repeat_freq',
	),	
);

/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/
 	
add_action('admin_menu', 'calendar_add_meta');
function calendar_add_meta(){
	global $meta_box_calendar;
	add_meta_box($meta_box_calendar['id'], $meta_box_calendar['title'], 'calendar_show_box', $meta_box_calendar['page'], $meta_box_calendar['context'], $meta_box_calendar['priority']);
}



/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/
function calendar_show_box_url($json){
	$obj = json_decode($json);
	if(sizeof($obj)==0){
		return "";
	}
	foreach( $obj as $k=>$o ){
		//var_dump($o);

	?>
	
	<tr class = "urlinput" >
           <td ><label for="textfield"></label>
             <input type="text" class = "urlname inputtext"  value = "<?php echo $o->title; ?>" /></td>
           <td><input type="text" class = "urladdr inputtext"  value = "<?php echo $o->url; ?>" /></td>
           <td class = "urledit" >
               	<input type="button" class="botton url_del"  value="-"  />
               
                            
            </td>
     </tr>
     <?php
	}
	}
function calendar_show_box() {
	global $post;
	// Use nonce for verification
	echo '<input type="hidden" name="calendar_meta_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	// Get current meta data
	// 1. Endmark
	$meta = get_post_meta( $post->ID ,'endmark',true );
	 ?>   

<div  id="calendar_form" >
	<div>是否已停办: 
	  <label>
	         <input name="endmark" type="radio" id="endmark_0" value="0" <?php if(!$meta){ echo 'checked="checked"'; } ?> />
否</label>
      <label>
        <input name="endmark" type="radio" id="endmark_1" value="1" <?php if($meta){ echo 'checked="checked"'; }?> />
        是</label>
	  <br />
	</div>
    <div>首次比赛日期信息:</div>
<table border="0">
          <tr>
          
          <?php   
	// 2. Dates		  
		  	echo '
            <td>开始日期:</td>
            <td><input name = "start_date" type="text" style="border:1px solid #999;" onClick="fPopCalendar(event,this,this)" onFocus="this.select()" readonly="readonly" ';
			$meta = get_post_meta( $post->ID ,'start_date',true);
			if(! empty($meta)){
				echo 'dbvalue = "' . $meta . '"'; 
			}		
		  	echo '/></td>
            <td>截止日期：</td>
            <td><input name = "end_date" type="text" style="border:1px solid #999;" onClick="fPopCalendar(event,this,this)" onFocus="this.select()" readonly="readonly" ';
			$meta = get_post_meta( $post->ID ,'end_date',true);
			if(! empty($meta)){
				echo 'dbvalue = "' . $meta . '"'; 
			}		
		  	echo '/></td>' ?>
          </tr>
  </table>
   <div>首次比赛日期信息:
   
   比赛重复周期：<select name = "repeat_freq" <?php
   			$meta = get_post_meta( $post->ID ,'repeat_freq',true);
			if(! empty($meta)){
				echo 'dbvalue = "' . $meta . '"'; 
			}   
    ?>  style="width:100px" >
     <option value="0">从不</option>
     <option value="1">每年</option>
     <option value="2">每两年</option>
     <option value="3">每三年</option>
   </select>
	</div>
   <div>历届比赛官网:</div>
   <table border="0">
   <thead>
         <tr>
           <th>标题</th>
           <th>链接</th>
           <th><span class="urledit" style="float:right">
             <input type="button" class="botton url_add"   value="+" />
           </span></th>
         </tr>
    </thead>
    <tbody>
         <?php
		 $meta = get_post_meta( $post->ID ,'urls_json',true);
		 if($meta){
		 	calendar_show_box_url($meta);
		 }
		 ?>
     </tbody>
  </table>
 	<textarea name="urls_json" id="urls_json" cols="45" rows="5" style="display:none"> <?php echo $meta; ?></textarea>
</div>
	<?php 
	}
	




/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/
 add_action('save_post', 'calendar_save_data');
function calendar_save_data($post_id) { 
	global $meta_box_calendar;
	// verify nonce
	if (!wp_verify_nonce($_POST['calendar_meta_nonce'], basename(__FILE__))) {
		return $post_id;
	}
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	foreach ($meta_box_calendar['fields'] as $field) {
		$old = get_post_meta($post_id, $field, true);
		$new = $_POST[$field];
		if('endmark'==$field){	
			if( $new == 1 ){
				update_post_meta($post_id, $field,$new);
			}else{
				delete_post_meta($post_id, $field);
			}
			continue;	
		}
		if ($new && $new != $old) {
			//add or update meta
			if($field == 'urls_json' ){
				$cnt = $new;
			}else{
				$cnt = stripslashes(htmlspecialchars($new));
			}
			 update_post_meta($post_id, $field,$cnt);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}

}

?>