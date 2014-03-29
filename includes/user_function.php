<?php 
function user_role_init(){
	 global $wp_roles;
   	$wp_roles->remove_role( "subscriber");
	//$wp_roles->remove_role( "contributor");
	//$wp_roles->remove_role( "author");
	if( null == $wp_roles->get_role( "member")){
		$wp_roles->add_role( "member","成员",array('read' => 1 )); 
	}
	if( null == $wp_roles->get_role( "former_member")){
		$wp_roles->add_role( "former_member","毕业成员",array('read' => 1 )); 
	}
}
add_action("init","user_role_init");
?>