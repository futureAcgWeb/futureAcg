<?php

/**

Template Name: members

 */

get_header(); ?>
<div id="container">
	<div class = "wrapper">
		<div class = "m_title">
			团队成员
		</div>
		<div class = "m_titleEng">
			member
		</div>
        <div id = "member_box">
        <style>

			.member_mask{
				background:	url(<?php bloginfo('template_url');   ?>/img/photo_mask.png);
			}
			.former_member_mask{
				background:	url(<?php bloginfo('template_url');   ?>/img/photo_mask2.png);
			}
		</style>
        	<ul>
            <?php 
				error_reporting(0);
				$members = get_users(array("orderby"	=>	"nicename", "role"	=> "member"));
				foreach ($members as $m) {
					?>
                    <li class = "member">
                    	<a>
                        <div class = "memberimg">
                        
                        	<div class = "member_mask" ></div>
                            <?php echo get_simple_local_avatar( $m->ID , $size = 145 ,$default = '', $alt = ''); ?>
                        </div>
                        <div class = "membername" user = "<?php echo $m->nickname; ?>"><?php echo $m->display_name; ?></div>
                        </a>
                    </li>
                    <?php
				}
				
				$members = get_users(array("orderby"	=>	"nicename", "role"	=> "former_member"));
				foreach ($members as $m) {
					?>
                    <li class = "member">
                    	<a>
                        <div class = "memberimg">
                        	<div class = "former_member_mask"></div>
                            <?php echo get_simple_local_avatar( $m->ID , $size = 145 , $default = '', $alt = '' ); ?>
                        </div>
                        <div class = "membername" user = "<?php echo $m->nickname; ?>"><?php echo $m->first_name ." ". $m->last_name; ?></div>                         
                       </a>
                    </li>
                    <?php
				}
			?>
		</ul><!--member_box-->

	</div>
 	</div><!-- .wrapper -->
 </div><!-- #container -->
<?php get_footer(); ?>