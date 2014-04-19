<?php
/*
Template Name:news 
*/

get_header(); ?>
		<div id="container" class = "wrapper">
			<div class = "news">
			<div class = "newsmenu">
				<div id = "newstitle">
					团队新闻
					<div id = "titlenews">news</div>
					<img id = "newsdash" src="<?php bloginfo('template_url'); ?>/img/dash.png">
				</div>
                <div class = "sidebar">
                <?php 
					$current_page = max($_GET['nav_paged'],1);
					$posts_per_page = 5;
					$postlist = get_posts( array(	'category'         	=> 'news',
													'orderby'          	=> 'post_date',
													'order'            	=> 'DESC',
													'post_type'        	=> 'post',
													'posts_per_page' 	=> $posts_per_page,
													'paged'				=> $current_page));
					$tag = sizeof($postlist);
					foreach ( $postlist as $post ) {
						echo '<div class = "item';
						if($tag == 1){
							echo ' last';
							}
						echo '" ><a newsid = "'. $post->ID .'">';
					  	echo $post->post_title;
						echo '</a></div>';
						$tag -=1;
					} 
					echo "<pre>";
					//print_r($postlist);
					echo "</pre>";
					?>
				</div>
                <div class = "numbar">
                <?php
					$postlist_all = get_posts( array(	'category'         	=> 'news',
													'orderby'          	=> 'post_date',
													'order'            	=> 'DESC',
													'post_type'        	=> 'post',));
					$nav_page = ceil( sizeof($postlist_all)/$posts_per_page);
					//echo $nav_page;
					$i = 1;
					for( $i = 1; $i <= $nav_page ; $i ++ ){
						echo '<div class = "';
						if ( $i == $current_page ){
							echo 'itemnumActive';
							}else{
							echo 'itemnum';	
							}
						echo '" id = "num1"><a href="?nav_paged='.$i.'" target="_self">'.$i.'</a></div>';	
					}
				?>			
				</div>
			</div>
			<div class = "content">
            	<?php
                foreach ( $postlist as $post ) : 
  					setup_postdata( $post ); ?>
                <div class="news_content news-<?php the_ID(); ?>">
                    <div class = "cnewstitle ">
                        <img src="<?php bloginfo('template_url'); ?>/img/triangle.png" style = "padding-right:10px;">
                        <?php the_title(); ?>
                    </div>
                    <div class = "newscontent">
                        <?php the_content(); ?>
                    </div>
                    <div class = "newstime">
                        <?php the_date('Y.m'); ?>
                    </div>
                </div><!--newscontent-->
                <?php endforeach; ?>
			</div><!-- content -->
			</div><!-- .news -->
		</div><!-- #container -->

<?php /** get_sidebar(); **/ ?>
<?php get_footer(); ?>