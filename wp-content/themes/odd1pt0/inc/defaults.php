<?php
/**
 * Prodo
 * WordPress Theme
 *
 * Web: https://www.facebook.com/a.axminenko
 * Email: a.axminenko@gmail.com
 *
 * Copyright 2015 Alexander Axminenko
 */

# Some Demo Content
# And Variables for Redux Framework
class ProdoDefaults {
	# Initialize
	public static function init( ) {
		global $prodoConfig;

		if ( ! isset( $prodoConfig ) or count( $prodoConfig ) == 0 ) {
			$prodoConfig = self::redux( );
		}
		if ( ! get_option( 'prodo_started', false ) ) {
			self::content( );
			self::save( );
		}
	}

	# Save State
	public static function save( ) {
		update_option( 'prodo_started', 1 );
	}

	# Pages
	public static function content( ) {
		# Index Page
		$data = array(
			'post_content'   => '',
			'post_name'      => 'prodo-index',
			'post_title'     => __( 'Demonstration Home Page', 'prodo' ),
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'page_template'  => 'templates/front.php',
			'post_author'    => 1,
			'ping_status'    => 'closed',
			'comment_status' => 'closed'
		);

		$id = wp_insert_post( $data, false );

		if ( $id !== false ) {
			update_post_meta( $id, 'header-section', 'video' );
			update_post_meta( $id, 'video-id', 'kn-1D5z3-Cs' );
			update_post_meta( $id, 'content-video', "<h1 class=\"animate\" style=\"text-align: center\">" . __( 'Awesome Onepage Template', 'prodo' ) . "</h1>\n<h2 class=\"text-light animate\" style=\"text-align: center\">" . __( 'Made with Love in New-York', 'prodo' ) . "</h2>" );

			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $id );
		}

		# Sample Page
		$data = array(
			'post_content'   => "<h2 style=\"text-align: center\">" . __( 'Hello, World!', 'prodo' ) . "</h2>\n<p style=\"text-align: center\">Quisque id tellus ullamcorper, elementum velit.\nSed non augue dui. Nullam sed felis semper, scelerisque tortor id, auctor lacus.</p>",
			'post_name'      => 'prodo-sample',
			'post_title'     => __( 'Prodo\'s Sample Page', 'prodo' ),
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'post_author'    => 1,
			'ping_status'    => 'closed',
			'comment_status' => 'closed'
		);

		wp_insert_post( $data, false );

		# Site Sections
		$data = array(
			'layout'      => array( 'normal' ),
			'content'     => array( '' ),
			'page'        => array( 'prodo-sample' ),
			'background'  => array( '' ),
			'image'       => array( '' ),
			'video-start' => array( '0' ),
			'video'       => array( '' ),
			'overlay'     => array( 'disabled' )
		);

		if ( ! get_option( 'prodo_sections', false ) ) {
			update_option( 'prodo_sections', json_encode( $data ) );
		}

		return true;
	}

	# Default Options for Redux Framework
	public static function redux( ) {
		return array(
			'header-sticky'          => 1,
			'header-style'           => 'two',
			'home-page-title'        => __( 'Home', 'prodo' ),
			'preloader'              => 1,
			'preloader-only-home'    => 1,
			'animations'             => 1,
			'multiple-videos'        => '',
			'tracking-code'          => '',
			'custom-favicon'         => array( 'url' => '' ),
			'logo-dark'              => array( 'url' => '' ),
			'logo-light'             => array( 'url' => '' ),
			'logo-dark-retina'       => array( 'url' => '' ),
			'logo-light-retina'      => array( 'url' => '' ),
			'styling-mode'           => 'schemes',
			'styling-scheme'         => 'default',
			'styling-color'          => '#000000',
			'custom-css'             => '',
			'layout-standard'        => 1,
			'layout-archive'         => 3,
			'layout-search'          => 3,
			'allow-share-posts'      => 1,
			'breadcrumbs'            => 1,
			'typography-content'     => array( 'font-family' => 'Open Sans', 'google' => 1, 'font-size' => '14px' ),
			'typography-headers-h1'  => array( 'font-family' => 'Roboto',    'google' => 1, 'font-size' => '36px' ),
			'typography-headers-h2'  => array( 'font-family' => 'Roboto',    'google' => 1, 'font-size' => '30px' ),
			'typography-headers-h3'  => array( 'font-family' => 'Roboto',    'google' => 1, 'font-size' => '24px' ),
			'typography-headers-h4'  => array( 'font-family' => 'Roboto',    'google' => 1, 'font-size' => '18px' ),
			'typography-headers-h5'  => array( 'font-family' => 'Roboto',    'google' => 1, 'font-size' => '14px' ),
			'typography-headers-h6'  => array( 'font-family' => 'Roboto',    'google' => 1, 'font-size' => '12px' ),
			'social-facebook'        => 'http://facebook.com/',
			'social-twitter'         => 'http://twitter.com/',
			'social-instagram'       => 'http://instagram.com/',
			'social-linkedin'        => 'http://linkedin.com/',
			'home-magic-mouse'       => 1,
			'home-video-play-btn'    => 1,
			'home-video-mutted'      => 1,
			'home-video-loop'        => 1,
			'home-video-start-at'    => 0,
			'home-video-stop-at'     => 0,
			'home-video-overlay'     => 40,
			'home-video-placeholder' => array( 'url' => '' ),
			'home-slideshow-timeout' => 10,
			'contact-email'          => '',
			'contact-template'       => '',
			'map-latitude'           => '40.706279',
			'map-longitude'          => '-74.005121',
			'map-zoom-level'         => 14,
			'map-marker-state'       => 1,
			'map-marker'             => array( 'url' => '' ),
			'map-marker-popup-title' => __( 'About Prodo theme', 'prodo' ),
			'map-marker-popup-text'  => __( 'Here we are. Come to drink a coffee!', 'prodo' ),
			'footer-button-top'      => 1,
			'footer-text'            => '2015 &copy; Prodo. All rights reserved.'
		);
	}
}

add_action( 'after_setup_theme', array( 'ProdoDefaults', 'init' ) );
