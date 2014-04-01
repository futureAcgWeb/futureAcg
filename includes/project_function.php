<?php
/* subtool: club projects */
/* this page is for the functions related to projects */
     
/* for project member relation ************************/
function project_member_install(){
	  global $wpdb;
	  $table_name = $wpdb->prefix . "project_member";
	  if($wpdb->get_var("SHOW TABLES LIKE " . $table_name . "") != $table_name){
		  $sql = "CREATE TABLE " . $table_name . " (
			  post_id bigint(20) NOT NULL,
			  user_id bigint(20) NOT NULL,
			  participate bool NOT NULL default '1',
			  PRIMARY KEY ( post_id , user_id )
			  );";
			  require_once(ABSPATH . "wp-admin/includes/upgrade.php");
			  dbDelta($sql);
	  }
}

add_action( 'after_setup_theme' ,'project_member_install');
function fACG_project_member_update( $post_id, $member_array ){
	  global $wpdb;
	  $table_name = $wpdb->prefix . "project_member";
	  $sql = "DELETE FROM " . $table_name . " 
			  WHERE post_id = " . $post_id . ";";
	  $wpdb->query($sql);
	  if( sizeof($member_array) > 0 ){
		  $sql = "INSERT INTO " . $table_name . " 
				  VALUES ";
		  foreach( $member_array as $k=>$m ){	
			  if( $k > 0 ){ 
				  $sql = $sql . "," ; 
			  }	
			  $sql = $sql . "(" .  $post_id . ", " . $m . " , 1 ) ";
		  }
		  echo $sql;
		  $wpdb->query($sql);
	  }//end of 'if the total number of members is more than 1.'
}
/* retrival the list of members */
function fACG_project_get_memberlist( $post_id ){
	global $wpdb;
	$rslt = $wpdb->get_results("SELECT user_id FROM wp_project_member WHERE post_id = " . $post_id ,ARRAY_N);
	$user_array = array();
	foreach( $rslt as $k=>$m  )
	{
		$user_array[$k]=$m[0];
	}
	return $user_array;
}
//Current members
function fACG_get_current_members(){
	
	$users = get_users( array('role' => "member"));
	$users = array_merge($users , get_users( array('role' => "editor")));
	return $users;
}
function fACG_project_get_members( $post_id , $args = array() ){	
	  $defaults = array(
		  'container'   => 'ul',
		  'container_class' 	=> '',
		  'container_id'    	=> '',
		  'fallback_cb' => 'wp_page_menu',
		  'before'      => '<li>',
		  'after'       => '</li>',
		  'link'		=> false,
		  'depth'		=> 0,
		  'select'		=> false
	  );
	  $args = wp_parse_args( $args, $defaults );
	  
	  echo '<'.$args['container'].' ';
	  if( '' != $args['container_class']){	echo 'class = "' . $args['container_class'] .'"'; 	}
	  if( '' != $args['container_id']){	echo 'id = "' . $args['container_id'] .'"'; 	}
	  echo '>';// print container
	  $user_select = fACG_project_get_memberlist($post_id) ;
	  if( $args['select'] ){
		  $users = fACG_get_current_members();
	  }else{
		  $users = get_users( array( 'include'	=> fACG_project_get_memberlist($post_id) ) );
	  }
	  foreach( $users as $u ){
		  echo $args['before'];
		  $user_print = $u->display_name;
		  if( $args['link'] ){ 
				  // to do : add personal page link 
		  }
		  if( $args['select'] ){
			  $pinch = get_post_meta( $post_id ,'member_in_charge',true );
			  	
			  $user_print = '<input type="checkbox" class = "member_check" name="'. $u->ID  .'" id = "member_'. $u->ID  .'"';
			  if ( in_array($u->ID , $user_select) ){
				  $user_print = $user_print . ' checked = "checked" ';
			  }
			  $user_print = $user_print .'/>  <label ';
			  
			  if ( $u->ID == $pinch ){	$user_print = $user_print . 'class = "member_in_charge"'; }
			  $user_print = $user_print . ' memberId ="'. $u->ID  .'">'.$u->display_name.'</label>' ;
		  }
		  
		  echo $user_print;
		  echo $args['after'];
	  }
	  ?>
	  <?php
	  echo '</'.$args['container'].'>';
	  echo '<textarea name="member_json" id="member_json" cols="45" rows="5" style="display:none">'; // 
	  echo str_replace('"', '', json_encode($user_select));
	  echo '</textarea>';
}

?>