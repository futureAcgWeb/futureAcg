<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
 <div id = "wrap">
        <div id = "wrap-0" class = "wrap wrapon">
        	
			<div style = "margin-left: auto;margin-right: auto;width: 300px;">
            	<div style="
                    font-size: 29px;
                    margin-bottom: 10px;
                    color: #635F5F;
                    margin-top: 27px;
                "> 404 您要的页面未找到 </div>
				<img src="<?php bloginfo('template_url'); ?>/img/youzi.png">
			</div>
        </div>
 </div>

<?php get_footer(); ?>