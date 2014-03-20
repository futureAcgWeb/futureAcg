<?php
/**
 * Template Name: Portfolio 1 column
 */

get_header(); ?>

	<?php include_once (TEMPLATEPATH . '/title.php');?>   
  <?php global $more;	$more = 0;?>
  <?php $values = get_post_custom_values("category-include"); $cat=$values[0];  ?>
  <?php $catinclude = 'portfolio_category='. $cat ;?>
  
  <?php $temp = $wp_query;
	$wp_query= null;
	$wp_query = new WP_Query(); ?>
  <?php $wp_query->query("post_type=portfolio&". $catinclude ."&paged=".$paged.'&showposts=5'); ?>
  <?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'theme1815' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'theme1815' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>


<!-- BEGIN Gallery -->
<div id="gallery" class="one_column">
  <ul class="portfolio">
    <?php 
      $i=1;
      if ( have_posts() ) while ( have_posts() ) : the_post(); 
    ?>
    
      <li class="<?php echo $addclass; ?>">
				<?php
				$thumb = get_post_thumbnail_id();
				$img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
				$image = aq_resize( $img_url, 540, 320, true ); //resize & crop img
				
				//mediaType init
				$mediaType = get_post_meta($post->ID, 'tz_portfolio_type', true);
			 
			 
			 //check thumb and media type
				if(has_post_thumbnail($post->ID) && $mediaType != 'Video' && $mediaType != 'Audio'){ 
				
					
					//Disable overlay_gallery if we have Image post
					$prettyType = 0;
					
					if($mediaType != 'Image') { 
						
						$prettyType = "prettyPhoto[gallery".$i."]";
						
					} else { 
						
						$prettyType = 'prettyPhoto';
					
					} ?>
				
				
        <span class="image-border"><a class="image-wrap" href="<?php echo $img_url;?>" rel="<?php echo $prettyType; ?>" title="<?php the_title();?>"><img src="<?php echo $image ?>" alt="<?php the_title(); ?>" /><span class="zoom-icon"></span></a></span>
				
				
				<?php
					$thumbid = 0;
					$thumbid = get_post_thumbnail_id($post->ID);
				
					$images = get_children( array(
						'orderby' => 'menu_order',
						'order' => 'ASC',
            'post_type' => 'attachment',
            'post_parent' => $post->ID,
            'post_mime_type' => 'image',
            'post_status' => null,
            'numberposts' => -1
					) ); 
						/* $images is now a object that contains all images (related to post id 1) and their information ordered like the gallery interface. */
						if ( $images ) { 

							//looping through the images
							foreach ( $images as $attachment_id => $attachment ) {
							
							 if( $attachment->ID == $thumbid ) continue;
							?>
								<?php 
								$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' ); // returns an array
								$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
								$image_title = $attachment->post_title;
								?>
									
								<a href="<?php echo $image_attributes[0]; ?>" title="<?php the_title(); ?>" rel="<?php echo $prettyType; ?>" alt="<?php echo $alt; ?>" style="display:none;"><img src="<?php echo $image_attributes[0]; ?>" alt="<?php echo $alt; ?>"/></a>

							<?php
							}
					}
		
				?>
				
				<?php }else { ?>
				
        <span class="image-border"><a class="image-wrap" href="<?php the_permalink() ?>" title="<?php _e('Permanent Link to', 'theme1815');?> <?php the_title_attribute(); ?>" ><img src="<?php echo $image ?>" alt="<?php the_title(); ?>" /></a></span>
				
        <?php } ?>
				
				
        <div class="folio-desc">
					<h6><a href="<?php the_permalink(); ?>"><?php $title = the_title('','',FALSE); echo substr($title, 0, 40); ?></a></h6>
					<p><?php $excerpt = get_the_excerpt(); echo my_string_limit_words($excerpt,98);?></p>
					<a href="<?php the_permalink() ?>" class="button"><?php _e('Read more', 'theme1815'); ?></a>
				</div>
				
				
      </li>
    
  
    <?php $i++; $addclass = ""; endwhile; ?>
  </ul>
  <div class="clear"></div>
	
</div><!-- END Gallery -->





<?php if(function_exists('wp_pagenavi')) : ?>
	<?php wp_pagenavi(); ?>
<?php else : ?>
  <?php if ( $wp_query->max_num_pages > 1 ) : ?>
    <nav class="oldernewer">
      <div class="older">
        <?php next_posts_link( __('&laquo; Older Entries', 'theme1815')) ?>
      </div><!--.older-->
      <div class="newer">
        <?php previous_posts_link(__('Newer Entries &raquo;', 'theme1815')) ?>
      </div><!--.newer-->
    </nav><!--.oldernewer-->
  <?php endif; ?>
<?php endif; ?>
<!-- Page navigation -->

<?php $wp_query = null; $wp_query = $temp;?>

<!-- end #main -->
<?php get_footer(); ?>