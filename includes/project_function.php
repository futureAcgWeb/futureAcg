<?php
/* subtool: club projects */
/* this page is for the functions related to projects */
     
/* for project member relation ************************/
function project_member_install(){
	  global $wpdb;
	  $table_name = $wpdb->prefix . "project_member";
	  if(strcasecmp($wpdb->get_var("SHOW TABLES LIKE \"". $table_name . "\""),$table_name) != 0){
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
function fACG_get_current_members( $former = true){
	
	$users = get_users( array('role' => "member"));
	if( $former ){
		$users = array_merge($users , get_users( array('role' => "former_member")));
	}
	return $users;
}
// print functions
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
	  if($args['container']){
		  echo '<'.$args['container'].'  ';
		  if( '' != $args['container_class']){	echo 'class = "' . $args['container_class'] .'"'; 	}
		  if( '' != $args['container_id']){	echo 'id = "' . $args['container_id'] .'"'; 	}
		  echo '>';// print container
	  }
	  $user_select = fACG_project_get_memberlist($post_id) ;
	  if( $args['select'] ){
		  $users = fACG_get_current_members();
	  }else{
		  $users = get_users( array( 'include'	=> fACG_project_get_memberlist($post_id)) );
	  }
	  foreach( $users as $u ){
		  echo $args['before'];
		  $user_print = $u->display_name;
		  if( $args['select'] ){
			  $pinch = get_post_meta( $post_id ,'member_in_charge',true );
			  	
			  $user_print = '<input type="checkbox" class = "member_check" name="'. $u->ID  .'" id = "member_'. $u->ID  .'"';
			  if ( in_array($u->ID , $user_select) ){
				  $user_print = $user_print . ' checked = "checked" ';
			  }
			  $user_print = $user_print .'/>  <label ';
			  
			  if ( $u->ID == $pinch ){	$user_print = $user_print . 'class = "member_in_charge"'; }
			  $user_print = $user_print . ' memberId ="'. $u->ID  .'">'.$u->display_name.'</label>' ;
		  }else{
		  }
		  
		  echo $user_print;
		  echo $args['after'];
	  }
	  ?>
	  <?php
	  if($args['container']){
	  		echo '</'.$args['container'].'>';
	  }
	  if( $args['select'] ){
		  echo '<textarea name="member_json" id="member_json" cols="45" rows="5" style="display:none">'; // 
		  echo str_replace('"', '', json_encode($user_select));
		  echo '</textarea>';
	  }
}
function fACG_get_member_in_charge( $post_id, $link = false ){
	$user_id = get_post_meta( $post_id, 'member_in_charge', true );
	$user = get_user_by( 'id', $user_id );
	return $user->display_name;
}
function fACG_get_member_in_charge_id( $post_id){
	$user_id = get_post_meta( $post_id, 'member_in_charge', true );
	return $user_id;
}
function fACG_get_project_type($post_id, $link = false, $admin = false){
	$output = get_the_terms( $post_id, 'project_type');	
	$str = "";
	if($output)	{
			foreach( $output as $o ){
				if($link){
					if($admin){
						$str  = $str . "[<a href='?post_type=project&project_type=$o->id'>" . $o->name . "</a>]";
					}
				}else{
					$str  = $str . "[" . $o->name . "]";
				}
			}//end of foreach
	}//end of if
	return $str;
}
function project_index_print(){
	
				$args = array('post_type'=>'project','post_status'=>'publish');
				$the_query = new WP_Query( $args );
				// The Loop
				$num_perpage = 4;
				$page = 0;
				if ( $the_query->have_posts() ) {
					echo '<div class="projWrapper">';	
					while ( $the_query->have_posts() ) {
						$page += 1;
						$the_query->the_post();
						echo '<div class="projThumb" ';
						if( $page == $num_perpage ){
							echo 'style="padding-right:0px;"';	
						}
						echo '>';
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'home_thumbnail' );
						}
						else {
							echo '<img src="'.get_bloginfo('template_url').'/img/1.jpg" class = "projImg" />';
						}
						echo '<a href="'.get_permalink().'"><div class="mask">';
						echo '<div class="infoContainer">
									<div>' . get_the_title() . '</div>
										<div>';
										$end = get_post_meta( get_the_ID(), 'endmark', true );
										if( empty($end ) ){
											echo "进行中...";	
										}else{
											echo substr(get_post_meta( get_the_ID(), 'endtime', true ),0,4);
										}
						echo			'</div>
									</div>
								</div></a>';
						echo '</div>';
						if( $page == $num_perpage ){
							break;	
						}
					}//end of while loop
					echo '</div>';
						
				} else {
					// no posts found
				}
				// Restore original Post Data 
				wp_reset_postdata();
	}

?>