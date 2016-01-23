<?php global $prodoConfig; ?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes( ); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php if ( ! function_exists( '_wp_render_title_tag' ) ) : ?>
<title><?php wp_title( '|', true, 'right' ); ?></title>
<?php endif; ?>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link href="<?php echo esc_url( ( ! empty( $prodoConfig['custom-favicon']['url'] ) ) ? $prodoConfig['custom-favicon']['url'] : get_template_directory_uri( ) . '/assets/images/favicon.png' ); ?>" rel="icon">
<!--[if lt IE 9]>
<script src="<?php echo esc_url( get_template_directory_uri( ) ); ?>/assets/js/html5shiv.min.js"></script>
<script src="<?php echo esc_url( get_template_directory_uri( ) ); ?>/assets/js/respond.min.js"></script>
<![endif]-->
<?php wp_head( ); ?>
</head>
<body <?php body_class( ( $prodoConfig['header-sticky'] ? 'nav-sticky' : null ) ); ?>>
	<?php if ( $prodoConfig['preloader'] and ! $prodoConfig['preloader-only-home'] ) : ?>
	<div class="page-loader">
		<div class="content">
			<div class="line">
				<div class="progress"></div>
			</div>
			<div class="text"><?php _e( 'Loading...', 'prodo' ); ?></div>
		</div>
	</div>
	<?php endif; ?>