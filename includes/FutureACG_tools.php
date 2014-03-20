<?php
/**
 * @package FutureACG 
 * @version 1.0
 */
/*
Plugin Name: FutureACG Tools 
Plugin URI: 
Description: 
Author: FutureACG
Version: 1.0
Author URI:
*/

/* add style sheet and js to the admin page */
add_action('admin_head', 'future_acg_admin_script');
function future_acg_admin_script() {

	?>
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/includes/css/wp_admin.css" />
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/includes/js/wp_admin.js"></script>
          <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/includes/js/datepicker.js"></script>
    <?php
	
}
/* contest calendar */
require_once(dirname(__FILE__)."\calendar.php");
require_once(dirname(__FILE__)."\calendar_page.php");

/* personal index for users */
require_once(dirname(__FILE__)."\personalIndex.php")



// This just echoes the chosen line, we'll position it later
/*$capability = 1;
$menu_slug = "ACG_calendar";
add_action('admin_menu', 'register_menu');
function register_menu() {
    add_menu_page( "未来动漫-比赛日历", "未来动漫-比赛日历","administrator",'FutureACG_tools/calendar','f_calendar',plugins_url('/img/menu_icon.png'), 6 ); 
}

function ACG_calendar(){
	}

function Acg_tools(){
	echo "hello world";
}*/

// Now we set that function up to execute when the admin_notices action is called
//add_action( 'admin_notices', 'Acg_tools' );

?>
