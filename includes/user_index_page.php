<?php

$meta_box_personal_index = array(
	'id' => 'meta_box_personal_index',
	'title' => '项目属性设置',
	'page' => 'personal_index',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' 	=> 'index_owner',
			'type' 	=> 'select',
			'title' =>	'成员',
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
	echo '<input type="hidden" name="personal_index_meta_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	 ?>   

<div  id="personal_index_form" >
    <?php 
		print_r($meta_box_personal_index['fields']);
		foreach($meta_box_personal_index['fields'] as $k => $f ){
			personal_index_meta_show( $f );
		} ?>
	<?php 
	}
	
function personal_index_meta_show( $f ){
		echo "<div>";
		echo "<strong>" . $f['title'] . "</strong>:";
	
		if( $f['name'] == 'index_owner' ){
			?>
            <select name="<?php echo $f['name']; ?>" dbvalue = "<?php echo get_post_meta( $post->ID, 'index_owner', true )  ?>" >
            
            </select>
            <?
			
		}
		else{
			
		}
	
	}


/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/
 add_action('save_post', 'personal_index_save_data');
function personal_index_save_data($post_id) { 
	global $meta_box_personal_index;
	// verify nonce
	if (!wp_verify_nonce($_POST['personal_index_meta_nonce'], basename(__FILE__))) {
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