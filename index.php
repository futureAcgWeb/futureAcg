<?php
/**
 * The main template file.
 */

get_header(); ?>
    <div class = "menu">
        <span class = "active" onclick = "adapt(this);"><a href = "#"><em></em></a></span>
        <span class = "" onclick = "adapt(this);"><a href = "#wrap-1"><em></em></a></span>
        <span onclick = "adapt(this);"><a href = "#wrap-2"><em></em></a></span>
        <span onclick = "adapt(this);"><a><em></em></a></span>
    </div>
    <div id = "wrap">
        <div id = "wrap-0" class = "wrap wrapon">
            <img id = "title1" src="<?php bloginfo('template_url'); ?>/img/title1.png">
            <div class = "slides"></div>
            <div class = "descriptionContainer">
                <div class = "description">
                    清华大学"未来动漫"兴趣团队是清华大学团委科创中心成立的动漫游戏研究创作团队。
                </div> 
                <div class = "description">
                    团队旨在进行动漫游戏研究与创作，并培养动漫游戏专业人才。
                </div>
                <div class = "description">	
                    团队成员由来自清华的15个不同院系的同学组成，从理工、人文社科、美术等不同领域而来，志在创作属于未来、属于自己的动漫游戏作品。
                </div>
            </div>
            <a href="" class = "aMousedown">
                <span><img src="<?php bloginfo('template_url'); ?>/img/roll.png"></span>
            </a>
        </div>
        <div id = "wrap-1" class = "wrap wrapon">
            <img id = "title2" src="<?php bloginfo('template_url'); ?>/img/title2.png">
            <div id = "projectWrapper">
                <img src="" class = "projectThumb">
                <img src="" class = "projectThumb">
                <img src="" class = "projectThumb">
                <img src="" class = "projectThumb">
                <img src="" class = "projectThumb">
                <img src="" class = "projectThumb">
            </div>
            <a href="" id = "moreProject">全部作品<img src="<?php bloginfo('template_url'); ?>/img/allWorks.png"></a>
            <a href="" class = "aMousedown">
                <span><img src="<?php bloginfo('template_url'); ?>/img/roll.png"></span>
            </a>
        </div>
        <div id = "wrap-2" class = "wrap wrapon">
            <img src="<?php bloginfo('template_url'); ?>/img/title3.png" id = "title3">
            <div class = "slides"></div>
            <div class = "slides"></div>
        </div>
    </div>
		<div id="container">
			this is index
        </div><!-- #container -->

<?php get_footer(); ?>
