<?php   
/* subtool: club projects			               */
/* this page is for the register of this post type */
/***************************************************/
/* initializations**********************************/
add_action('init', 'project_init');   
function project_init()    
{   
//register a new post type -> calendar
  $labels = array(   
    'name' => '社团项目',   
    'singular_name' => 'project',   
    'add_new' => '新建项目',   
    'add_new_item' => '新建项目',   
    'edit_item' => '编辑项目',   
    'new_item' => 'new_item',   
    'view_item' => 'view_item',   
    'search_items' => 'search_items',   
    'not_found' =>  'not_found',   
    'not_found_in_trash' => 'not_found_in_trash',    
    'parent_item_colon' => '',   
    'menu_name' => '社团项目'   
  
  );   
  $args = array(   
    'labels' => $labels,   
    'public' => true,   
    'publicly_queryable' => true,   
    'show_ui' => true,    
    'show_in_menu' => true,    
    'query_var' => true,   
    'rewrite' => true,   
    'capability_type' => 'post',   //!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    'has_archive' => true,    
    'hierarchical' => false,   
    'menu_position' => 5,   
    'supports' => array('title','editor','thumbnail','categories','comments')   
  );    
  register_post_type('project',$args);  
 //
  // Add new taxonomy(type of works accepted) for calendar
	$labels = array(
		'name'              => "项目类别",
		'singular_name'     => "项目类别",
		'search_items'      => "查询项目类别",
		'all_items'         => "所有项目类别",
		'parent_item'       => null,
		'parent_item_colon' => null,
		'edit_item'         => "编辑项目类别",
		'update_item'       => "更新项目类别",
		'add_new_item'      => "新增项目类别",
		'new_item_name'     => "新增项目类别名称",
		'menu_name'         => "项目类别",
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_tagcloud' 	=> false,
		'rewrite'           => array( 'slug' => 'project_type' ),
	);

	register_taxonomy( 'project_type', 'project', $args );
}

// add columns on the list pag/e--
add_filter( 'manage_edit-project_columns', 'project_columns' );
function project_columns( $columns ) {
	$columns['project_state'] = '项目状态';
	$columns['person_in_charge'] = '项目负责人';
	unset( $columns['author'] );
	unset( $columns['date'] );
    return $columns;
}

add_action( 'manage_posts_custom_column', 'fill_project_columns' );
function fill_project_columns( $column ) {
		if('project_state' == $column){
		if($meta = get_post_meta( get_the_ID() ,'endmark',true )){
				echo '已完结';	
		}else{
				echo '进行中';	
		}//end of inner if-else
	}elseif('person_in_charge'  == $column){
		echo fACG_get_member_in_charge(get_the_ID());	
		
	}
}

// add filter for the work type
add_action( 'restrict_manage_posts', 'project_filter_list' );
function project_filter_list() {
    $screen = get_current_screen();
    global $wp_query;
    if ( $screen->post_type == 'project' ) {
        wp_dropdown_categories( array(
            'show_option_all' => '显示所有作品类别',
            'taxonomy' => 'project_type',
            'name' => 'project_type',
            'orderby' => 'name',
            'selected' => ( isset( $wp_query->query['project_type'] ) ? $wp_query->query['project_type'] : '' ),
            'hierarchical' => false,
            'depth' => 5,
            'show_count' => false,
            'hide_empty' => true,
        ) );
    }
}

add_filter( 'parse_query','project_perform_filtering' );
function project_perform_filtering( $query ) {
    $qv = &$query->query_vars;

    if ( ( $qv['project_type'] ) && is_numeric( $qv['project_type'] ) ) {
        $term = get_term_by( 'id', $qv['project_type'], 'project_type' );
        $qv['project_type'] = $term->slug;
    }
}  
?> 