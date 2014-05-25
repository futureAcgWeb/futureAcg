<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;
	

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet"type="text/css" media="all" href="<?php bloginfo('template_url'); ?>/css/index.css" />
<script type="text/javascript" src = "<?php bloginfo('template_url'); ?>/js/jquery.js"> </script>
<?php if( is_home() ){ ?>
<script type="text/javascript" src = "<?php bloginfo('template_url'); ?>/js/jquery.SuperSlide.js"> </script>
<script type="text/javascript" src = "<?php bloginfo('template_url'); ?>/js/index.js"> </script>

<?php } ?>
<?php if (is_page('news')){ ?>
<script type="text/javascript" src = "<?php bloginfo('template_url'); ?>/js/news.js"> </script>
<link rel="stylesheet"type="text/css" media="all" href="<?php bloginfo('template_url'); ?>/css/news.css" />
<?php } ?>
<?php if (is_page('members')){ ?>
<link rel="stylesheet"type="text/css" media="all" href="<?php bloginfo('template_url'); ?>/css/members.css" />
<?php } ?>
<?php if (is_page('projects')){ ?>
<link rel="stylesheet"type="text/css" media="all" href="<?php bloginfo('template_url'); ?>/css/works.css" />
<?php } ?>

<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>
<div id="wrapper" class="hfeed">
	<div id="header">
		<div class = "headContainer">
			<div id = "title"><img src="<?php bloginfo('template_url'); ?>/img/LOGO.png"></div>
            <?php if(!is_user_logged_in()){?>
            <div id = "login"><?php wp_loginout(); ?> </div>
            <?php }?>
			<?php wp_nav_menu( 
				array( 'container_id'	=> 'nav',
						'before'    	=> '<div>',
						'after'			=> '</div>',
						'theme_location' => 'primary' ) ); ?>
		</div><!-- #nav -->

	</div><!-- #header -->

	<div id="main">
