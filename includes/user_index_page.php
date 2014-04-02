<?php

$meta_box_personal_index = array(
	'id' => 'meta_box_member',
	'title' => '项目属性设置',
	'page' => 'member',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' 	=> 	'index_owner',
			'type' 	=> 	'select',
			'title' =>	'成员',
			'descp'	=>	'仅可替换为无主页（包括在垃圾箱中）的成员',
		)
		,
	),	
);

/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/
 	
add_action('admin_menu', 'personal_index_add_meta');
function personal_index_add_meta(){
	global $meta_box_personal_index;
	add_meta_box($meta_box_personal_index['id'], $meta_box_personal_index['title'], 'personal_index_show_box', $meta_box_personal_index['page'], $meta_box_personal_index['context'], $meta_box_personal_index['priority']);
}


/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/
function personal_index_show_box() {
	global $post,$meta_box_personal_index ;
	// Use nonce for verification
	echo '<input type="hidden" name="member_meta_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	 ?>   

	<div  id="personal_index_form" >
    <?php 
		//print_r($meta_box_personal_index['fields']);
		foreach($meta_box_personal_index['fields'] as $k => $f ){
			personal_index_meta_show( $f );
		}//end of foreach ?>
    </div>    
	<?php 
}
	
function personal_index_meta_show( $f ){
		global $post;
		echo "<div>";
		echo "<strong>" . $f['title'] . "</strong>:";
		echo $f['descp'];
		echo "</br>";
	
		if( $f['name'] == 'index_owner' ){
			$exp = personal_index_owners();
			
			$owner = get_post_meta( $post->ID, 'index_owner', true );
			echo '<select name="' . $f['name'] .'" dbvalue = "' . $owner . '" >';
			
			$users = get_users( array('role' => "member", 'exclude' => $exp));
			$users = array_merge($users , get_users( array('role' => "former_member", 'exclude' => $exp )));
			$u = get_user_by('id',$owner);
			echo '<option value="' . $u->ID . '">'. $u->display_name . '</option>';
			foreach( $users as $u ){
		  		echo '<option value="' . $u->ID . '">'. $u->display_name . '</option>';
			}
			echo '</select>';		
		}else{
			echo "";
		}//end of if-else
		echo "</div>";
}
function personal_index_owners(){
	global $wpdb;
	$users = $wpdb->get_results('SELECT meta_value FROM wp_postmeta WHERE meta_key = "index_owner"');
	$rslt = array();
	foreach($users as $k => $u){
		$rslt[$k] = $u->meta_value;
	}
	return $rslt;
}


/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/
add_action('save_post', 'personal_index_save_data');
function personal_index_save_data($post_id) { 
	global $meta_box_personal_index;
	// verify nonce
	if (!wp_verify_nonce($_POST['member_meta_nonce'], basename(__FILE__))) {
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
	foreach ($meta_box_personal_index['fields'] as $k => $f ) {
		$old = get_post_meta($post_id, $f['name'], true);
		$new = $_POST[$f['name']];

		if ($new && $new != $old) {
			//add or update meta
			 update_post_meta($post_id, $f['name'],$new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $f['name'], $old);
		}
	}
}
?>