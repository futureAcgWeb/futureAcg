<?php
/**
 * The main template file.
 */

get_header(); ?>
    <div id = "wrap">
        <div id = "wrap-0" class = "wrap wrapon">
			<div class = "name">
				<img src="<?php bloginfo('template_url'); ?>/img/youzi.png">
			</div>
        </div>
        <div id = "wrap-1" class = "wrap wrapon">
           <div class = "photos">
				<div class = "thumbMain">
					<img src="<?php bloginfo('template_url'); ?>/img/1.jpg" id = "staticPic1">
					<div class = "mask">
						<div class = "infoContainer">
							<div class = "info">清华大学“未来动漫”兴趣团队是清华大学团委科创中心成立的动漫游戏研究创作团队。</div>
							<div class = "info">团队旨在进行动漫游戏研究与创作，并培养动漫游戏专业创新人才。</div>
							<div class = "info">团队成员由来自清华的15个不同院系的同学组成，从理工、人文社科、美术等不同领域而来，志在创作属于未来、属于自己的动漫游戏作品。</div>
						</div>
					</div>
				</div>
				<img src="<?php bloginfo('template_url'); ?>/img/1.jpg" id = "staticPic2">
				<img src="<?php bloginfo('template_url'); ?>/img/1.jpg" id = "staticPic2">
				<img src="<?php bloginfo('template_url'); ?>/img/1.jpg" id = "staticPic3">
				<img src="<?php bloginfo('template_url'); ?>/img/1.jpg" id = "staticPic3">
			</div>
        </div>
        
        <div id = "wrap-2" class = "wrap wrapon">
            <div class = "recentProjects">
				<div id = "projTitle">...............................................................近期项目...............................................................</div>
        <?php
        		/*
				*
				*this is for the list of recent projects.
				*@ \includes\project_function.php
				*/
				 project_index_print(); ?>
        			<div class = "viewmore">
					<div id ="moreWork">
						<a href = "works.html">全部作品</a>
					</div>
					<div id = "moreImg">
						<a href = "works.html"><img src="<?php bloginfo('template_url'); ?>/img/triangle.png"></a>
					</div>
				</div>
			</div>
        </div>
        
		<div id = "wrap-3" class = "wrap wrapon">
			<div class = "contactus">
				<div id = "projTitle" style = "padding-bottom:20px;">...............................................................联系我们...............................................................</div>
				<div class = "contactInfo">
					Email: thufutureACG@gmail.com
				</div>
				<div class = "contactInfo">
					联系地址：清华大学紫荆公寓C楼407
				</div>
			</div>
		</div>
    </div>

<?php get_footer(); ?>
