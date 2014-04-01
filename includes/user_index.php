<?php
add_action('init', 'personal_index_init');   
function personal_index_init()    
{   
//register a new post type -> calendar
  $labels = array(   
    'name' => '个人主页',   
    'singular_name' => 'member',   
    'add_new' => '新建主页',   
    'add_new_item' => '新建个人主页',   
    'edit_item' => '编辑个人主页',   
    'new_item' => 'new_item',   
    'view_item' => 'view_item',   
    'search_items' => 'search_items',   
    'not_found' =>  'not_found',   
    'not_found_in_trash' => 'not_found_in_trash',    
    'parent_item_colon' => '',   
    'menu_name' => '团员个人主页管理'   
  
  );   
  $args = array(   
    'labels' => $labels,   
    'public' => true,   
    'publicly_queryable' => true,   
    'show_ui' => true,    
    'show_in_menu' => true,    
    'query_var' => false,   
    'rewrite' => true,   
    'capability_type' => 'post',   //!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    'has_archive' => true,    
    'hierarchical' => false,   
    'menu_position' => 5,   
    'supports' => array('title','editor','thumbnail','categories','comments','post-formats')   
  );    
  register_post_type('member',$args);
}

add_filter( 'manage_edit-member_columns', 'personal_index_columns' );
function personal_index_columns( $columns ) {
    $columns['owner'] = '成员';
    unset( $columns['author'] );
	unset( $columns['date'] );
    return $columns;
}
add_action( 'manage_posts_custom_column', 'fill_personal_index_columns' );
function fill_personal_index_columns( $column ) {
    if ( 'owner' == $column ) {
		$output = get_post_meta( get_the_ID(), 'index_owner', true );

		if( $output )	{
			echo get_user_by( "id" , $output ) -> display_name ;
			
		}
    }
}
?>