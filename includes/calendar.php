<?php   
/* subtool: contest calendar 				*/
/********************************************/
/* initializations***************************/
add_action('init', 'calendar_init');   
function calendar_init()    
{   
//register a new post type -> calendar
  $labels = array(   
    'name' => '比赛日历',   
    'singular_name' => 'contest_calendar',   
    'add_new' => '新建比赛',   
    'add_new_item' => '新建比赛日历',   
    'edit_item' => '编辑比赛日历',   
    'new_item' => 'new_item',   
    'view_item' => 'view_item',   
    'search_items' => 'search_items',   
    'not_found' =>  'not_found',   
    'not_found_in_trash' => 'not_found_in_trash',    
    'parent_item_colon' => '',   
    'menu_name' => '比赛日历'   
  
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
    'supports' => array('title','editor','author','thumbnail','categories','comments')   
  );    
  register_post_type('calendar',$args);  
 //
  // Add new taxonomy(type of works accepted) for calendar
	$labels = array(
		'name'              => "接收作品类别",
		'singular_name'     => "接收作品类别",
		'search_items'      => "查询作品类别",
		'all_items'         => "所有作品类别",
		'parent_item'       => null,
		'parent_item_colon' => null,
		'edit_item'         => "编辑作品类别",
		'update_item'       => "更新作品类别",
		'add_new_item'      => "新增作品类别",
		'new_item_name'     => "新增作品类别名称",
		'menu_name'         => "作品类别",
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_tagcloud' 	=> false,
		'rewrite'           => array( 'slug' => 'contest_work_type' ),
	);

	register_taxonomy( 'contest_work_type', 'calendar', $args );
}
// add columns on the list page
add_filter( 'manage_edit-calendar_columns', 'calendar_columns' );
function calendar_columns( $columns ) {
    $columns['contest_work_type'] = '接收作品类型';
	$columns['contest_end'] = '是否停赛';
    unset( $columns['author'] );
	unset( $columns['date'] );
    return $columns;
}
add_action( 'manage_posts_custom_column', 'fill_columns' );
function fill_columns( $column ) {
    if ( 'contest_work_type' == $column ) {
		$output = get_the_terms( get_the_ID(), 'contest_work_type');	
		if($output)	{
			foreach( $output as $o ){
			echo "[<a href='?post_type=calendar&contest_work_type=$o->id'>" . $o->name . "</a>]";
			}
		}
    }
	elseif ( 'contest_end' == $column ){
			if($meta = get_post_meta( get_the_ID() ,'endmark',true )){
				echo '是';	
			}else{
				echo '否';	
			}
	}
}

// add filter for the work type
add_action( 'restrict_manage_posts', 'calendar_filter_list' );
function calendar_filter_list() {
    $screen = get_current_screen();
    global $wp_query;
    if ( $screen->post_type == 'calendar' ) {
        wp_dropdown_categories( array(
            'show_option_all' => '显示所有作品类别',
            'taxonomy' => 'contest_work_type',
            'name' => 'contest_work_type',
            'orderby' => 'name',
            'selected' => ( isset( $wp_query->query['contest_work_type'] ) ? $wp_query->query['contest_work_type'] : '' ),
            'hierarchical' => false,
            'depth' => 3,
            'show_count' => false,
            'hide_empty' => true,
        ) );
    }
}
add_filter( 'parse_query','calendar_perform_filtering' );
function calendar_perform_filtering( $query ) {
    $qv = &$query->query_vars;

    if ( ( $qv['contest_work_type'] ) && is_numeric( $qv['contest_work_type'] ) ) {
        $term = get_term_by( 'id', $qv['contest_work_type'], 'contest_work_type' );
        $qv['contest_work_type'] = $term->slug;
    }
}  
?> 