<?php
/* subtool: club projects */
/* this page is for the edit page of this type of poster */
// === Define Metabox Fields ====================================== //

$meta_box_project = array(
	'id' => 'meta_box_project',
	'title' => '项目属性设置',
	'page' => 'project',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		'endmark',
		'member_in_charge',
	),	
);

/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/
 	
add_action('admin_menu', 'project_add_meta');
function project_add_meta(){
	global $meta_box_project;
	add_meta_box($meta_box_project['id'], $meta_box_project['title'], 'project_show_box', $meta_box_project['page'], $meta_box_project['context'], $meta_box_project['priority']);
}



/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/
function project_show_box() {
	global $post;
	// Use nonce for verification
	echo '<input type="hidden" name="project_meta_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	// Get current meta data
	// 1. Endmark
	$meta = get_post_meta( $post->ID ,'endmark',true );
	 ?>   

<div  id="project_form" >
	<div><strong>项目状态</strong>: 
	  <label>
	         <input name="endmark" type="radio" id="endmark_0" value="0" <?php if(!$meta){ echo 'checked="checked"'; } ?> />
进行中</label>
      <label>
        <input name="endmark" type="radio" id="endmark_1" value="1" <?php if($meta){ echo 'checked="checked"'; }?> />
        已完结</label>
	  <br />
	</div>
    <div><strong>项目成员</strong>（高亮的为项目负责人，点击成员姓名进行设置）:
    <?php fACG_project_get_members($post->ID , array('select' => true , 'container_id' => 'admin_project_members' ));  
	
	//3. person in charge of the project
	$meta = get_post_meta( $post->ID ,'member_in_charge',true );
	?>
    <input type="text" name = "member_in_charge" id = "member_in_charge" style="display:none" value = "<?php echo $meta;  ?>" />
</div>
	<?php 
	}
	




/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/
 add_action('save_post', 'project_save_data');
function project_save_data($post_id) { 
	global $meta_box_project;
	// verify nonce
	if (!wp_verify_nonce($_POST['project_meta_nonce'], basename(__FILE__))) {
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
	foreach ($meta_box_project['fields'] as $field) {
		$old = get_post_meta($post_id, $field, true);
		$new = $_POST[$field];

		if ($new && $new != $old) {
			//add or update meta
			 update_post_meta($post_id, $field,$new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field, $old);
		}
	}
	// for the member list
	$old = json_encode(fACG_project_get_memberlist( $post_id ));
	$new =  $_POST["member_json"];
	if ($new != $old){
		$ar = json_decode(str_replace('"', '', $new),true);
		fACG_project_member_update($post_id,$ar);
	}

}

?>
