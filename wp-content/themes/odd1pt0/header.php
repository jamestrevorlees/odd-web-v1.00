<?php global $prodoConfig; ?>
<?php $isFrontPage = ProdoTheme::isFrontPage( get_the_ID( ) ); ?>
<!DOCTYPE html>
<html class="no-js <?php echo ( ( $prodoConfig['header-sticky'] and ! $isFrontPage ) ? 'nav-sticky' : '' ); ?> <?php echo ( is_admin_bar_showing( ) ? 'wp-bar' : '' ); ?>" <?php language_attributes( ); ?>>
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
	<?php if ( $prodoConfig['preloader'] or $prodoConfig === null ) : ?>
		<?php if ( ( $prodoConfig['preloader-only-home'] and $isFrontPage ) or ! $prodoConfig['preloader-only-home'] or $prodoConfig == null ) : ?>
		<div class="page-loader">
			<div class="content">
				<div class="line">
					<div class="progress"></div>
				</div>
				<div class="text"><?php _e( 'Loading...', 'prodo' ); ?></div>
			</div>
		</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( $isFrontPage ) : ?>
	<div class="navbar <?php echo ( ! empty( $prodoConfig['header-style'] ) ? esc_attr( $prodoConfig['header-style'] ) : 'one' ); ?>" role="navigation">
	<?php else : ?>
	<div class="navbar <?php echo ( $prodoConfig['header-sticky'] ? 'navbar-fixed-top' : '' ); ?> floating positive <?php echo ( ! empty( $prodoConfig['header-style'] ) ? esc_attr( $prodoConfig['header-style'] ) : 'one' ); ?>" role="navigation">
	<?php endif; ?>
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo esc_url( site_url( ) ); ?>">
					<?php $logo_dark = ! empty( $prodoConfig['logo-dark']['url'] ) ? $prodoConfig['logo-dark']['url'] : get_template_directory_uri( ) . '/assets/images/logo-dark-full.png'; ?>
					<?php $logo_light = ! empty( $prodoConfig['logo-light']['url'] ) ? $prodoConfig['logo-light']['url'] : get_template_directory_uri( ) . '/assets/images/logo-light-full.png'; ?>
					<?php if ( $isFrontPage ) : ?>
					<img src="<?php echo esc_url( $logo_light ); ?>" data-alt="<?php echo esc_url( $logo_dark ); ?>" alt="">
					<?php else : ?>
					<img src="<?php echo esc_url( $logo_dark ); ?>" alt="">
					<?php endif; ?>
				</a>
			</div>
			<div class="collapse navbar-collapse" id="navbar-collapse">
				<div class="social">
					<?php echo ProdoTheme::socialIcons( '<a href="%3$s" title="%2$s" target="_blank"><i class="fa fa-%1$s"></i></a>' ); ?>
				</div>
				<?php echo ProdoTheme::mainMenu( get_the_ID( ), 'nav navbar-nav navbar-right' ); ?>
			</div>
		</div>
	</div>