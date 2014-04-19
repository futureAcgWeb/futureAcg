<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="container">
				<div id="content" role="main">
                <?php 
				if ( have_posts() ) while ( have_posts() ) : the_post(); 
					?>
                                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                    <div class="entry-title">
                                    	<h1><?php the_title(); ?></h1>
                                    <?php if(get_post_type()=='project'){ ?>
                                        <div><?php
											$array = get_post_meta( get_the_ID(), 'endtime' );
											$array2 = get_post_meta( get_the_ID(), 'starttime' );
											if( sizeof($array) > 0 ){
												echo  substr($array2[0],0,7) ." -- " .substr($array[0],0,7);
											}else{
												echo "进行中...";
											}
									  ?></div>
                                        <div>|项目负责人：<?php echo fACG_get_member_in_charge(get_the_ID()); ?>
                                        <span>|项目成员：<?php echo fACG_project_get_members(get_the_ID(),array('before'=>'','after'=>',','container'=>false));?>
                                        </div>
                                    <?php }?>
                                    </div>
                                    <div class="entry-content">
                                        <?php the_content(); ?>
                                        <?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
                                    </div><!-- .entry-content -->
                                
                                </div><!-- #post-## -->
                <?php
					 	comments_template( '', true ); ?>
                <?php endwhile; // end of the loop. ?>
			</div><!-- #content -->
		</div><!-- #container -->
<?php get_footer(); ?>
