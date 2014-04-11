<?php
/*
Template Name:news 
*/

get_header(); ?>
		<div id="container" class = "wrapper">
			<div id = "newstitle">
				团队新闻
				<div id = "titlenews">news</div>
			</div>
			<div class = "news">
				<div class = "menu"></div>
				<div class = "content"></div>
			</div>
				<div id="content" role="main">

			<?php
				$cur_page = get_page($page_id);
				echo $cur_page -> post_content;
				?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php /** get_sidebar(); **/ ?>
<?php get_footer(); ?>