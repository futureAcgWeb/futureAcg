<?php 
function user_role_init(){
	 global $wp_roles;
   	$wp_roles->remove_role( "subscriber");
	if( null == $wp_roles->get_role( "member")){
		$wp_roles->add_role( "member","成员",array('read' => 1 )); 
	}
	if( null == $wp_roles->get_role( "former_member")){
		$wp_roles->add_role( "former_member","毕业成员",array('read' => 1 )); 
	}
	if( null == $wp_roles->get_role( "team_leader")){
		$wp_roles->add_role( "team_leader","队长",array('read' => 1 )); 
	}
		if( null == $wp_roles->get_role( "team_director")){
		$wp_roles->add_role( "team_director","辅导员",array('read' => 1 )); 
	}
	$role = get_role( 'administrator' );
	$role->add_cap("modify_positions");
	$role->add_cap("scoring_system");
	$role = get_role( 'team_director' );
	$role->add_cap("modify_positions");
	$role->add_cap("scoring_system");
	$role = get_role( 'team_leader' );
	$role->add_cap("modify_positions");
	$role->add_cap("scoring_system");
	
	//add_submenu_page( 'users.php', '成员设置', '成员设置', 'manage_options', 'member-clert-set', 'member_clert_set' ); 
	
}
add_action("init","user_role_init");


/*   change the columns                    */
add_filter('manage_users_columns','position_user_change_columns');
function position_user_change_columns($columns){
	unset( $columns['email']);
	unset( $columns['role'] );
	unset( $columns['posts'] );
	unset( $columns['name'] );
	$columns['display_name'] = '姓名';
	$columns['positions'] = '职位';
	$columns['index'] = '主页';
    return $columns;
}

add_action('manage_users_custom_column','position_user_show_all',20,3);
function position_user_show_all( $value,$column_name,$user_id){
	if( 'positions' == $column_name ){
		global $wp_roles;
		$role_names = $wp_roles->get_names();
		$user = new WP_User( $user_id );
		$roles = $user->roles;
		$str = "";
		foreach( $roles as $k => $r )
		{
			$str = $str . '[' . $role_names[$r].']' ;
		}
		return $str;
	}elseif( 'index' == $column_name ){
		if( null == get_user_index_url( $user_id ) ){
			return "";	
		}else{
			return "[<a href=\"" . get_user_index_url( $user_id ) . "\">主页</a>]";
		}
		
	}elseif( 'display_name' == $column_name ){
		$user = new WP_User( $user_id );
		return 	$user->display_name;
	}
}

function get_user_index_url( $id ){
	global $wpdb;
	$rslt = $wpdb->get_results("SELECT post_id FROM wp_postmeta WHERE meta_key = \"index_owner\" AND meta_value = \"". $id ."\"",ARRAY_N);
	if( null == $rslt ){
		return null;
	}
	return get_permalink( $rslt[0][0] );
	
}
/*     edit member positions     */
add_filter( 'user_row_actions', 'position_user_row_actions', 10, 2 );
function position_user_row_actions( $actions, $user_object ) {
	echo "<pre>";
	
	//print_r($user_object);
	echo "</pre>";
	$roles = $user_object->roles;
	$id = $user_object->ID;
	if( in_array("member",$roles)){
		if( in_array("team_leader",$roles) ){
			$actions['unset_team_leader'] = '<a href="'.position_user_url('unset_team_leader',$id ).'">取消队长</a>';
		}
		elseif( in_array("team_director",$roles) )
		{
			$actions['unset_team_director'] = '<a href="'.position_user_url('unset_team_director',$id ).'">取消导员</a>';
		}else{
			$actions['set_team_leader'] = '<a href="'.position_user_url('set_team_leader',$id ).'">设队长</a>';
			$actions['set_team_director'] = '<a href="'.position_user_url('set_team_director',$id ).'">设导员</a>';
			if(in_array("administrator",$roles) ){
				$actions['unset_admin'] = '<a href="'.position_user_url('unset_admin',$id ).'">取消管理员</a>';
			}else{
				$actions['set_admin'] = '<a href="'.position_user_url('set_admin',$id ).'">设管理员</a>';
				if(in_array("editor",$roles) ){
					$actions['unset_editor'] = '<a href="'.position_user_url('unset_editor',$id ).'">取消编辑</a>';
				}else{
					$actions['set_editor'] = '<a href="'.position_user_url('set_editor',$id ).'">设编辑</a>';
				}
			}			
		}
	}
	elseif( in_array("former_member",$roles) ){
		$actions['set_editor'] = '<a href="'.position_user_url('set_editor',$id).'">设编辑</a>';
		$actions['set_admin'] = '<a href="'.position_user_url('set_admin',$id).'">设管理员</a>';
	}
	//print_r($actions);
	
	return $actions;
}
function position_user_url($actionname,$id){
	return wp_nonce_url( 'users.php', '_wpnonce' ) . '&amp;action=' . $actionname . '&amp;id=' . $id;
}
add_action("load-users.php","positions_set");
function positions_set(){
	if (!wp_verify_nonce($_GET['_wpnonce'], '_wpnonce')) {
		return 0;
	}
	if (!current_user_can('modify_positions') ){
		return 0;	
	}
	$action = $_GET['action'];
	$id = $_GET['id'];
	$user = new WP_User( $id );
	$type_del = false;
	$rolename = "";
	switch($action){
		case 'set_editor':
		$rolename = 'editor';
		break;
		case 'set_admin':
		$rolename = 'administrator';
		break;
		case 'set_team_director':
		$rolename = 'team_director';
		$user->remove_role('editor');
		break;
		case 'set_team_leader':
		$rolename = 'team_leader';
		$user->remove_role('editor');
		break;
		case 'unset_editor':
		$rolename = 'editor';
		$type_del = true;
		break;
		case 'unset_admin':
		$rolename = 'administrator';
		$type_del = true;
		break;
		case 'unset_team_director':
		$rolename = 'team_director';
		$type_del = true;
		break;
		case 'unset_team_leader':
		$rolename = 'team_leader';	
		$type_del = true;
		break;	
	}
	if($type_del){
		$user->remove_role($rolename);	
	}else{
		$user->add_role($rolename);	
	}
	wp_redirect('users.php');
}
add_filter('user_contactmethods','my_user_contactmethods');
function my_user_contactmethods($user_contactmethods ){
	 $user_contactmethods ['weibo'] = '新浪微博';
	 $user_contactmethods ['t-qq'] = '腾讯QQ';
	 $user_contactmethods ['profe'] = '职业';
	 return $user_contactmethods ;
}
?>