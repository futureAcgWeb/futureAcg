<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after. Calls sidebar-footer.php for bottom widgets.
 */
?>
	</div><!-- #main -->
	
	
</div><!-- #wrapper -->

<?php  
	
	if( is_page('news') || is_page('projects')){
	
	}else{	?>
    <div class="footer <?php if(is_page()){ echo "page"; }?>">
        <div class="copyright">copyright Future ACG 2014</div>
    </div>
    <script>
	jQuery(document).ready(function(){
		if( jQuery("body").height() < jQuery(window).height() )
		{
			jQuery("body").height(jQuery(window).height() - 100 - 16);
		}
	});
	</script>
<?php	}  ?>

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
