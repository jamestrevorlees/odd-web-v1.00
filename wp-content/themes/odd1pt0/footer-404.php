<?php
global $prodoConfig;

$loader = false;
if ( $prodoConfig['preloader'] and ! $prodoConfig['preloader-only-home'] ) {
	$loader = true;
}
?>

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