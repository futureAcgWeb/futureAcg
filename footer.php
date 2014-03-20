<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after. Calls sidebar-footer.php for bottom widgets.
 */
?>
	</div><!-- #main -->

		<?php 
		if( is_page() || is_home()){
		// footer wont be desplayed for index and main pages
		}
		else{
	?>
	<footer id="colophon" role="contentinfo">
	</footer><!-- #colophon -->
    <?php } ?>

</div><!-- #wrapper -->

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
