<?php
global $prodoConfig;

$loader = false;
$isFrontPage = ProdoTheme::isFrontPage( get_the_ID( ) );

if ( $prodoConfig === null ) {
	$loader = true;
} else if ( $prodoConfig['preloader'] ) {
	if ( ( $prodoConfig['preloader-only-home'] and $isFrontPage ) or ! $prodoConfig['preloader-only-home'] ) {
		$loader = true;
	}
}
?>
<footer class="footer offsetTopS offsetBottomS <?php echo ( ( $isFrontPage ? 'no-border' : '' ) ); ?>">
	<div class="container offsetTopS offsetBottomS">
		<div class="row">
			<?php wp_nav_menu( array( 'theme_location' => 'footer-menu', 'container' => 'div', 'container_class' => 'col-md-12 menu_container', 'depth' => -1, 'fallback_cb' => false ) ); ?>
			<div class="col-md-5 col-sm-5">
				<?php if ( $prodoConfig['footer-button-top'] or $prodoConfig === null ) : ?>
				<a class="to-top"><i class="fa fa-angle-up"></i></a>
				<?php endif; ?>
				<span><?php echo do_shortcode( $prodoConfig['footer-text'] ); ?></span>
			</div>
			<div class="col-md-7 col-sm-7 social">
				<?php echo ProdoTheme::socialIcons( '<a href="%3$s" title="%2$s" target="_blank"><i class="fa fa-%1$s"></i></a>' ); ?>
			</div>
		</div>
	</div>
</footer>

<script>
var Prodo = {
	'loader': <?php echo ( $loader ? 'true' : 'false' ); ?>,
	'animations': <?php echo ( $prodoConfig['animations'] ? 'true' : 'false' ); ?>,
	'navigation': <?php echo ( $prodoConfig['header-sticky'] ? '\'sticky\'' : '\'normal\'' ) . "\n"; ?>
};
</script>

<?php if ( isset( $prodoConfig['tracking-code'] ) ) : ?>
<?php echo $prodoConfig['tracking-code']; ?>
<?php endif; ?>
<?php wp_footer( ); ?>
</body>
</html>