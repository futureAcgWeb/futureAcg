<?php

/**

Template Name: members

 */

get_header(); ?>
<div id="container">
			<div id="content" >

 <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
                
                                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                    <?php if ( is_front_page() ) { ?>
                                        <div class="entry-title"><?php the_title(); ?></div>
                                    <?php } else { ?>
                                        <div class="entry-title"><?php the_title(); ?></div>
                                    <?php } ?>
                
                                    
                                </div><!-- #post-## -->
                
                               
                
                <?php endwhile; // end of the loop. ?>
	</div>
    </div>
<?php get_footer(); ?>