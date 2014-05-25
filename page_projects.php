<?php

/**

Template Name: projects

 */

get_header(); ?>
	<div class = "wrapper">
		<div class = "sidemenu">
			<a href="?proj_type=" target="_self"><div id = "workstitle">
				团队作品
				<div id = "titleworks">works</div>
			</div>
            </a>
			<div class = "worknav">
            <?php
			 
			global $more; 
			$more = false; 

				$proj_type = $_GET['proj_type'];
				$cats = array(
					array(
						'title' => "动画",
						'title_en' => "Animation",
						'cat' => "animation",
					),
					array(
						'title' => "漫画",
						'title_en' => "Comic",
						'cat' => "comic",
					),
					array(
						'title' => "游戏",
						'title_en' => "CG Games",
						'cat'=> "game",
					),
					array(
						'title' => "其他",
						'title_en' => "Other",
						'cat' => "others",
					),	
				);
				foreach( $cats as $k => $cat ){
					?>
                    <div class = "navitem <?php if( $cat['cat'] == $proj_type){ echo "catChosen";}?>" >
                    	<a href="?proj_type=<?php echo $cat['cat'];?>" target="_self" style="color:black">
                        <div class = "workthumb">
                            <img src="<?php bloginfo('template_url');   ?>/img/<?php echo $cat['cat'];?>.png">
                        </div>
                        <div class = "navtitle">
                            <?php echo $cat['title'];?>
                        </div>
                        <div class = "enavtitle">
                            <?php echo $cat['title_en'];?>
                        </div>
                        </a>
					</div>
					<?php
				}
			?>
			</div>
		</div>
        
            <div class = "works">
            <?php
				
				$args = array(
							'offset'           => 0,
							'project_type'	   => $proj_type,
							'orderby'          => 'post_date',
							'order'            => 'DESC',
							'post_type'        => 'project',
							'post_mime_type'   => '',
							'post_parent'      => '',
							'post_status'      => 'publish'); 
				query_posts($args);
				if ( have_posts() ) while ( have_posts() ) : the_post();
			?>
			<div class = "workitem">
				<div class = "itemdashtop"></div>
				<div class = "workimg" id = "proj-<?php the_ID();?>" style="background:url(<?php 
																							$image_id = get_post_thumbnail_id(); 
																							$image_url = wp_get_attachment_image_src($image_id,'large', true); 
																							echo $image_url[0]; 
																						?>)">
              		<a href="<?php the_permalink(); ?> ">
					<div class = "play">
						<img src="<?php bloginfo('template_url');   ?>/img/play.png">
					</div>
					<div class = "info">
						<div style = "padding-top:10px;">《<?php the_title(); ?>》&nbsp;<?php 
						$array = get_post_meta( get_the_ID(), 'endtime' );
						if( sizeof($array) > 0 ){
							echo substr($array[0],0,4);
						}else{
							echo "进行中...";
						}
						?></div>
					</div>
                    </a>
                  </div>
                  <div class="project_content">
					<?php the_content("详情>>>"); ?>
                  </div>
                  <div class = "itemdashmid"></div>
                  <div class="proj_meta"><p><?php $array = get_post_meta( get_the_ID(), 'remark' ); echo $array[0]; ?></p>
                      <p><?php echo fACG_get_project_type(get_the_ID());?>
                      |项目负责人：<?php echo fACG_get_member_in_charge(get_the_ID()); ?>;&nbsp; 参与成员：<?php fACG_project_get_members(get_the_ID(),array('before'=>'','after'=>',','container'=>false)); ?></p>
                  
                  <div class = "itemdashbottom"></div>
				</div>
			</div>
			<?php
				endwhile;//end of foreach
			
			wp_reset_query();
			?>
		</div><!-- works -->
            
		
	</div>

<?php get_footer(); ?>