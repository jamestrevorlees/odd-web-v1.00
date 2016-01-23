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
 
if ( ! class_exists( 'ProdoRedux' ) ) {
	class ProdoRedux {
		public $args        = array( );
		public $sections    = array( );
		public $theme;
		public $ReduxFramework;

		public function __construct( ) {
			if ( ! class_exists( 'ReduxFramework' ) ) {
				return;
			}
			if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
				$this->initSettings( );
			} else {
				add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
			}
		}

		public function initSettings( ) {
			if ( is_admin( ) ) {
				load_textdomain( 'prodo', get_template_directory( ) . '/languages/' . get_locale( ) . '.mo' );
			}
			
			$this->setArguments( );
			$this->setSections( );

			if ( ! isset( $this->args['opt_name'] ) ) {
				return;
			}

			$this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
		}

		public function setSections( ) {
			$this->sections[] = array(
				'title'     => __( 'Main Options', 'prodo' ),
				'icon'      => 'el-icon-home',
				'fields'    => array(
					array(
						'id'        => 'header-sticky',
						'type'      => 'switch',
						'title'     => __( 'Header Mode', 'prodo' ),
						'on'        => __( 'Sticky', 'prodo' ),
						'off'       => __( 'Normal', 'prodo' ),
						'default'   => true
					),
					array(
						'id'        => 'header-style',
						'type'      => 'select',
						'title'     => __( 'Header Style', 'prodo' ),
						'desc'      => __( 'Select a header style', 'prodo' ),
						'options'   => array(
							'one'       => __( 'Style One &ndash; Default', 'prodo' ),
							'two'       => __( 'Style One &ndash; With Social Icons', 'prodo' ),
							'three'     => __( 'Style One &ndash; Alt. Hover', 'prodo' ),
							'four'      => __( 'Style One &ndash; Social Icons, Alt. Hover', 'prodo' ),
							'five'      => __( 'Style Two &ndash; Default', 'prodo' ),
							'six'       => __( 'Style Two &ndash; Alt. Hover', 'prodo' ),
							'seven'     => __( 'Style Two &ndash; Highlight Current Item', 'prodo' ),
							'eight'     => __( 'Style Three &ndash; Default', 'prodo' ),
							'nine'      => __( 'Style Three &ndash; With Social Icons', 'prodo' ) ),
						'default'   => 'two'
					),
					array(
						'id'        => 'home-page-title',
						'type'      => 'text',
						'title'     => __( 'Home Page Title', 'prodo' ),
						'desc'      => __( 'This title used only for navigation menu', 'prodo' ),
						'default'   => __( 'Home', 'prodo' )
					),
					array(
						'id'        => 'preloader',
						'type'      => 'switch',
						'title'     => __( 'Page Loader', 'prodo' ),
						'on'        => __( 'Enabled', 'prodo' ),
						'off'       => __( 'Disabled', 'prodo' ),
						'default'   => true
					),
					array(
						'id'        => 'preloader-only-home',
						'type'      => 'switch',
						'title'     => __( 'Page Loader Location', 'prodo' ),
						'on'        => __( 'Only Home Page', 'prodo' ),
						'off'       => __( 'All Pages', 'prodo' ),
						'default'   => true
					),
					array(
						'id'        => 'animations',
						'type'      => 'switch',
						'title'     => __( 'Animations on Scroll', 'prodo' ),
						'on'        => __( 'Enabled', 'prodo' ),
						'off'       => __( 'Disabled', 'prodo' ),
						'default'   => true
					),
					array(
						'id'        => 'multiple-videos',
						'type'      => 'switch',
						'title'     => __( 'Multiple Video Sections', 'prodo' ),
						'subtitle'  => __( 'Per page', 'prodo' ),
						'on'        => __( 'Allow', 'prodo' ),
						'off'       => __( 'Deny', 'prodo' ),
						'default'   => false
					),
					array(
						'id'        => 'tracking-code',
						'type'      => 'textarea',
						'title'     => __( 'Tracking Code', 'prodo' ),
						'desc'      => __( 'Paste your Google Analytics (or other) tracking code here', 'prodo' )
					),
				),
			);

			$this->sections[] = array(
				'title'     => __( 'Personalization', 'prodo' ),
				'icon'      => 'el-icon-torso',
				'fields'    => array(
					array(
						'id'        => 'custom-favicon',
						'type'      => 'media',
						'title'     => __( 'Upload Favicon', 'prodo' ),
						'mode'      => false,
						'desc'      => __( 'Upload a 16px (or, 32px) PNG, GIF image that will represent your website\'s favicon', 'prodo' )
					),
					array(
						'id'    => 'opt-divide',
						'type'  => 'divide'
					),
					array(
						'id'        => 'logo-dark',
						'type'      => 'media',
						'title'     => __( 'Upload Dark Logo', 'prodo' ),
						'subtitle'  => __( 'Normal size', 'prodo' ),
						'mode'      => false,
						'desc'      => __( 'Upload a logotype image image that will represent your website', 'prodo' )
					),
					array(
						'id'        => 'logo-light',
						'type'      => 'media',
						'title'     => __( 'Upload Light Logo', 'prodo' ),
						'subtitle'  => __( 'Normal size', 'prodo' ),
						'mode'      => false,
						'desc'      => __( 'Upload a logotype image image that will represent your website', 'prodo' )
					),
					array(
						'id'        => 'logo-dark-retina',
						'type'      => 'media',
						'title'     => __( 'Upload Dark Logo (2X)', 'prodo' ),
						'subtitle'  => __( 'Double size (for Retina displays)', 'prodo' ),
						'mode'      => false,
						'desc'      => __( 'Upload a logotype image image that will represent your website', 'prodo' )
					),
					array(
						'id'        => 'logo-light-retina',
						'type'      => 'media',
						'title'     => __( 'Upload Light Logo (2X)', 'prodo' ),
						'subtitle'  => __( 'Double size (for Retina displays)', 'prodo' ),
						'mode'      => false,
						'desc'      => __( 'Upload a logotype image image that will represent your website', 'prodo' )
					),
				),
			);

			$this->sections[] = array(
				'title'     => __( 'Styling Options', 'prodo' ),
				'icon'      => 'el-icon-asterisk',
				'fields'    => array(
					array(
						'id'           => 'styling-mode',
						'type'         => 'button_set',
						'title'        => __( 'Styling Mode', 'prodo' ),
						'options'      => array(
							'schemes'        => __( 'Predefined Schemes', 'prodo' ),
							'color'         => __( 'Custom Colors', 'prodo' ) ),
						'default'      => 'schemes'
					),
					array(
						'id'    => 'opt-divide',
						'type'  => 'divide'
					),
					array(
						'id'        => 'styling-scheme',
						'type'      => 'select',
						'title'     => __( 'Predefined Scheme', 'prodo' ),
						'desc'      => __( 'Select a predefined color scheme', 'prodo' ),
						'options'   => array(
							'default'       => __( 'Default', 'prodo' ),
							'softred'       => __( 'Soft Red', 'prodo' ),
							'cyan'          => __( 'Cyan', 'prodo' ),
							'green'         => __( 'Green', 'prodo' ),
							'orange'        => __( 'Orange', 'prodo' ),
							'brown'         => __( 'Brown', 'prodo' ),
							'sandy'         => __( 'Sandy', 'prodo' ),
							'softcyan'      => __( 'Soft Cyan', 'prodo' )
						),
						'default'   => 'default'
					),
					array(
						'id'           => 'styling-color',
						'type'         => 'color',
						'transparent'  => false,
						'title'        => __( 'Custom Color', 'prodo' ),
						'desc'         => __( 'Pick a primary color', 'prodo' ),
						'default'      => '#2185c5'
					),
					array(
						'id'        => 'custom-css',
						'type'      => 'textarea',
						'title'     => __( 'Custom CSS', 'prodo' ),
						'desc'      => __( 'Quickly add some CSS to your theme by adding it to this block', 'prodo' )
					),
				),
			);

			$this->sections[] = array(
				'title'     => __( 'Layout Settings', 'prodo' ),
				'icon'      => 'el-icon-lines',
				'fields'    => array(
					array(
						'id'        => 'layout-standard',
						'type'      => 'image_select',
						'compiler'  => false,
						'title'     => __( 'Standard Pages Layout', 'prodo' ),
						'subtitle'  => __( 'Select one of layouts for standard pages', 'prodo' ),
						'options'   => array(
							'1' => array( 'alt' => '1 Column',       'img' => ReduxFramework::$_url . 'assets/img/1col.png' ),
							'2' => array( 'alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png' ),
							'3' => array( 'alt' => '2 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/2cr.png' ),
						),
						'default'   => '1'
					),
					array(
						'id'        => 'layout-archive',
						'type'      => 'image_select',
						'compiler'  => false,
						'title'     => __( 'Archive Pages Layout', 'prodo' ),
						'subtitle'  => __( 'Select one of layouts for archive pages', 'prodo' ),
						'options'   => array(
							'1' => array( 'alt' => '1 Column',       'img' => ReduxFramework::$_url . 'assets/img/1col.png' ),
							'2' => array( 'alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png' ),
							'3' => array( 'alt' => '2 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/2cr.png' ),
						),
						'default'   => '3'
					),
					array(
						'id'        => 'layout-search',
						'type'      => 'image_select',
						'compiler'  => false,
						'title'     => __( 'Search Page Layout', 'prodo' ),
						'subtitle'  => __( 'Select one of layouts for search page', 'prodo' ),
						'options'   => array(
							'1' => array( 'alt' => '1 Column',       'img' => ReduxFramework::$_url . 'assets/img/1col.png' ),
							'2' => array( 'alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png' ),
							'3' => array( 'alt' => '2 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/2cr.png' ),
						),
						'default'   => '3'
					),
				),
			);

			$this->sections[] = array(
				'title'     => __( 'Blog Options', 'prodo' ),
				'icon'      => 'el-icon-pencil',
				'fields'    => array(
					array(
						'id'        => 'allow-share-posts',
						'type'      => 'switch',
						'title'     => __( 'Allow Sharing Posts', 'prodo' ),
						'subtitle'  => __( 'Via Social Networks', 'prodo' ),
						'on'        => __( 'Yes', 'prodo' ),
						'off'       => __( 'No', 'prodo' ),
						'default'   => true
					),
					array(
						'id'        => 'breadcrumbs',
						'type'      => 'switch',
						'title'     => __( 'Breadcrumbs', 'prodo' ),
						'subtitle'  => __( 'Breadcrumbs on single pages', 'prodo' ),
						'on'        => __( 'Enabled', 'prodo' ),
						'off'       => __( 'Disabled', 'prodo' ),
						'default'   => true
					),
				),
			);

			$this->sections[] = array(
				'title'     => __( 'Typography', 'prodo' ),
				'icon'      => 'el-icon-text-height',
				'fields'    => array(
					array(
						'id'            => 'typography-content',
						'type'          => 'typography',
						'title'         => __( 'Content &mdash; Font', 'prodo' ),
						'google'        => true,
						'update_weekly' => true,
						'font-backup'   => false,
						'font-style'    => false,
						'font-weight'   => false,
						'subsets'       => false,
						'font-size'     => true,
						'line-height'   => false,
						'text-align'    => false,
						'color'         => false,
						'preview'       => array(
							'text'          => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
						),
						'default'       => array(
							'font-family'   => 'Open Sans',
							'font-size'     => '14',
							'google'        => true,
						),
					),
					array(
						'id'            => 'typography-headers-h1',
						'type'          => 'typography',
						'title'         => __( 'Headers &mdash; H1', 'prodo' ),
						'google'        => true,
						'update_weekly' => true,
						'font-family'   => true,
						'font-backup'   => false,
						'font-style'    => false,
						'font-weight'   => false,
						'subsets'       => false,
						'font-size'     => true,
						'line-height'   => false,
						'text-align'    => false,
						'color'         => false,
						'preview'       => array(
							'text'          => 'Lorem ipsum dolor sit amet.'
						),
						'default'       => array(
							'font-family'   => 'Roboto',
							'font-size'     => '36',
							'google'        => true,
						),
					),
					array(
						'id'            => 'typography-headers-h2',
						'type'          => 'typography',
						'title'         => __( 'Headers &mdash; H2', 'prodo' ),
						'google'        => true,
						'update_weekly' => true,
						'font-family'   => true,
						'font-backup'   => false,
						'font-style'    => false,
						'font-weight'   => false,
						'subsets'       => false,
						'font-size'     => true,
						'line-height'   => false,
						'text-align'    => false,
						'color'         => false,
						'preview'       => array(
							'text'          => 'Lorem ipsum dolor sit amet.'
						),
						'default'       => array(
							'font-family'   => 'Roboto',
							'font-size'     => '30',
							'google'        => true,
						),
					),
					array(
						'id'            => 'typography-headers-h3',
						'type'          => 'typography',
						'title'         => __( 'Headers &mdash; H3', 'prodo' ),
						'google'        => true,
						'update_weekly' => true,
						'font-family'   => true,
						'font-backup'   => false,
						'font-style'    => false,
						'font-weight'   => false,
						'subsets'       => false,
						'font-size'     => true,
						'line-height'   => false,
						'text-align'    => false,
						'color'         => false,
						'preview'       => array(
							'text'          => 'Lorem ipsum dolor sit amet.'
						),
						'default'       => array(
							'font-family'   => 'Roboto',
							'font-size'     => '24',
							'google'        => true,
						),
					),
					array(
						'id'            => 'typography-headers-h4',
						'type'          => 'typography',
						'title'         => __( 'Headers &mdash; H4', 'prodo' ),
						'google'        => true,
						'update_weekly' => true,
						'font-family'   => true,
						'font-backup'   => false,
						'font-style'    => false,
						'font-weight'   => false,
						'subsets'       => false,
						'font-size'     => true,
						'line-height'   => false,
						'text-align'    => false,
						'color'         => false,
						'preview'       => array(
							'text'          => 'Lorem ipsum dolor sit amet.'
						),
						'default'       => array(
							'font-family'   => 'Roboto',
							'font-size'     => '18',
							'google'        => true,
						),
					),
					array(
						'id'            => 'typography-headers-h5',
						'type'          => 'typography',
						'title'         => __( 'Headers &mdash; H5', 'prodo' ),
						'google'        => true,
						'update_weekly' => true,
						'font-family'   => true,
						'font-backup'   => false,
						'font-style'    => false,
						'font-weight'   => false,
						'subsets'       => false,
						'font-size'     => true,
						'line-height'   => false,
						'text-align'    => false,
						'color'         => false,
						'preview'       => array(
							'text'          => 'Lorem ipsum dolor sit amet.'
						),
						'default'       => array(
							'font-family'   => 'Roboto',
							'font-size'     => '14',
							'google'        => true,
						),
					),
					array(
						'id'            => 'typography-headers-h6',
						'type'          => 'typography',
						'title'         => __( 'Headers &mdash; H6', 'prodo' ),
						'google'        => true,
						'update_weekly' => true,
						'font-family'   => true,
						'font-backup'   => false,
						'font-style'    => false,
						'font-weight'   => false,
						'subsets'       => false,
						'font-size'     => true,
						'line-height'   => false,
						'text-align'    => false,
						'color'         => false,
						'preview'       => array(
							'text'          => 'Lorem ipsum dolor sit amet.'
						),
						'default'       => array(
							'font-family'   => 'Roboto',
							'font-size'     => '12',
							'google'        => true,
						),
					),
				),
			);

			$this->sections[] = array(
				'title'     => __( 'Social Accounts', 'prodo' ),
				'icon'      => 'el-icon-heart',
				'fields'    => array(
					array(
						'id'        => 'social-facebook',
						'type'      => 'text',
						'title'     => __( 'Facebook Link', 'prodo' ),
						'desc'      => __( 'Paste link to your account', 'prodo' ),
						'default'   => 'http://facebook.com/'
					),
					array(
						'id'        => 'social-twitter',
						'type'      => 'text',
						'title'     => __( 'Twitter Link', 'prodo' ),
						'desc'      => __( 'Paste link to your account', 'prodo' ),
						'default'   => 'http://twitter.com/'
					),
					array(
						'id'        => 'social-instagram',
						'type'      => 'text',
						'title'     => __( 'Instagram Link', 'prodo' ),
						'desc'      => __( 'Paste link to your account', 'prodo' ),
						'default'   => 'http://instagram.com/'
					),
					array(
						'id'        => 'social-linkedin',
						'type'      => 'text',
						'title'     => __( 'LinkedIn Link', 'prodo' ),
						'desc'      => __( 'Paste link to your account', 'prodo' ),
						'default'   => 'http://linkedin.com/'
					),
					array(
						'id'        => 'social-flickr',
						'type'      => 'text',
						'title'     => __( 'Flickr Link', 'prodo' ),
						'desc'      => __( 'Paste link to your account', 'prodo' ),
						'default'   => ''
					),
					array(
						'id'        => 'social-vimeo',
						'type'      => 'text',
						'title'     => __( 'Vimeo Link', 'prodo' ),
						'desc'      => __( 'Paste link to your account', 'prodo' ),
						'default'   => ''
					),
					array(
						'id'        => 'social-lastfm',
						'type'      => 'text',
						'title'     => __( 'Last FM Link', 'prodo' ),
						'desc'      => __( 'Paste link to your account', 'prodo' ),
						'default'   => ''
					),
					array(
						'id'        => 'social-dribble',
						'type'      => 'text',
						'title'     => __( 'Dribble Link', 'prodo' ),
						'desc'      => __( 'Paste link to your account', 'prodo' ),
						'default'   => ''
					),
					array(
						'id'        => 'social-youtube',
						'type'      => 'text',
						'title'     => __( 'YouTube Link', 'prodo' ),
						'desc'      => __( 'Paste link to your account', 'prodo' ),
						'default'   => ''
					),
					array(
						'id'        => 'social-microsoft',
						'type'      => 'text',
						'title'     => __( 'Microsoft ID Link', 'prodo' ),
						'desc'      => __( 'Paste link to your account', 'prodo' ),
						'default'   => ''
					),
					array(
						'id'        => 'social-google-plus',
						'type'      => 'text',
						'title'     => __( 'Google + Link', 'prodo' ),
						'desc'      => __( 'Paste link to your account', 'prodo' ),
						'default'   => ''
					),
					array(
						'id'        => 'social-picasa',
						'type'      => 'text',
						'title'     => __( 'Picasa Link', 'prodo' ),
						'desc'      => __( 'Paste link to your account', 'prodo' ),
						'default'   => ''
					),
					array(
						'id'        => 'social-pinterest',
						'type'      => 'text',
						'title'     => __( 'Pinterest Link', 'prodo' ),
						'desc'      => __( 'Paste link to your account', 'prodo' ),
						'default'   => ''
					),
					array(
						'id'        => 'social-wordpress',
						'type'      => 'text',
						'title'     => __( 'Wordpress Link', 'prodo' ),
						'desc'      => __( 'Paste link to your account', 'prodo' ),
						'default'   => ''
					),
					array(
						'id'        => 'social-dropbox',
						'type'      => 'text',
						'title'     => __( 'Dropbox Link', 'prodo' ),
						'desc'      => __( 'Paste link to your account', 'prodo' ),
						'default'   => ''
					),
				),
			);

			$this->sections[] = array(
				'title'     => __( 'Home Section', 'prodo' ),
				'icon'      => 'el-icon-screen',
				'fields'    => array(
					array(
						'id'        => 'home-magic-mouse',
						'type'      => 'switch',
						'title'     => __( 'Animated Magic Mouse', 'prodo' ),
						'on'        => __( 'Enabled', 'prodo' ),
						'off'       => __( 'Disabled', 'prodo' ),
						'default'   => true
					),
					array(
						'id'    => 'opt-divide',
						'type'  => 'divide'
					),
					array(
						'id'        => 'home-video-play-btn',
						'type'      => 'switch',
						'title'     => __( 'Video Play Button', 'prodo' ),
						'subtitle'  => __( 'Fullscreen Video Mode', 'prodo' ),
						'on'        => __( 'Enabled', 'prodo' ),
						'off'       => __( 'Disabled', 'prodo' ),
						'default'   => true
					),
					array(
						'id'        => 'home-video-mutted',
						'type'      => 'switch',
						'title'     => __( 'Video Mutted', 'prodo' ),
						'subtitle'  => __( 'Fullscreen Video Mode', 'prodo' ),
						'on'        => __( 'Yes', 'prodo' ),
						'off'       => __( 'No', 'prodo' ),
						'default'   => true
					),
					array(
						'id'        => 'home-video-loop',
						'type'      => 'switch',
						'title'     => __( 'Video Loop', 'prodo' ),
						'subtitle'  => __( 'Fullscreen Video Mode', 'prodo' ),
						'on'        => __( 'Yes', 'prodo' ),
						'off'       => __( 'No', 'prodo' ),
						'default'   => true
					),
					array(
						'id'        => 'home-video-start-at',
						'type'      => 'text',
						'title'     => __( 'Start Video At', 'prodo' ),
						'desc'      => __( 'Enter value in seconds', 'prodo' ),
						'subtitle'  => __( 'Fullscreen Video Mode', 'prodo' ),
						'default'   => '0'
					),
					array(
						'id'        => 'home-video-stop-at',
						'type'      => 'text',
						'title'     => __( 'Stop Video At', 'prodo' ),
						'desc'      => __( 'Enter value in seconds', 'prodo' ),
						'subtitle'  => __( 'Fullscreen Video Mode', 'prodo' ),
						'default'   => '0'
					),
					array(
						'id'        => 'home-video-overlay',
						'type'      => 'slider',
						'title'     => __( 'Video Overlay Opacity', 'prodo' ),
						'subtitle'  => __( 'Fullscreen Video Mode', 'prodo' ),
						'desc'      => __( 'In percents (0% &ndash; fully transparent)', 'prodo' ),
						'default'   => '40',
						'min'           => 0,
						'step'          => 1,
						'max'           => 100,
						'display_value' => 'text'
					),
					array(
						'id'        => 'home-video-placeholder',
						'type'      => 'media',
						'title'     => __( 'Video Callback Image', 'prodo' ),
						'desc'      => __( 'This image will be shown if browser does not support fullscreen video background', 'prodo' ),
						'subtitle'  => __( 'Fullscreen Video Mode', 'prodo' ),
						'mode'      => false,
					),
					array(
						'id'    => 'opt-divide',
						'type'  => 'divide'
					),
					array(
						'id'        => 'home-slideshow-timeout',
						'type'      => 'text',
						'title'     => __( 'Slideshow Timeout', 'prodo' ),
						'desc'      => __( 'Enter value in seconds', 'prodo' ),
						'subtitle'  => __( 'Slideshow Mode', 'prodo' ),
						'default'   => '10'
					),
				),
			);

			$this->sections[] = array(
				'title'     => __( 'Contact Section', 'prodo' ),
				'icon'      => 'el-icon-phone',
				'fields'    => array(
					array(
						'id'        => 'contact-email',
						'type'      => 'text',
						'title'     => __( 'Target Email address', 'prodo' ),
						'default'   => ''
					),
					array(
						'id'        => 'contact-template',
						'type'      => 'textarea',
						'title'     => __( 'Email Template', 'prodo' ),
						'desc'      => __( 'Available tags &ndash; {from}, {email}, {phone}, {message}, {date}, {ip}', 'prodo' ),
						'default'   => __( "Dear Administrator,\nYou have one message from {from} ({email}).\n\n{message}\n\n{date}\n{phone}", 'prodo' )
					),
				),
			);

			$this->sections[] = array(
				'title'     => __( 'Map Options', 'prodo' ),
				'icon'      => 'el-icon-map-marker',
				'fields'    => array(
					array(
						'id'        => 'map-latitude',
						'type'      => 'text',
						'title'     => __( 'Latitude of a Point', 'prodo' ),
						'desc'      => __( 'Example, 40.706279', 'prodo' ),
						'default'   => '40.706279'
					),
					array(
						'id'        => 'map-longitude',
						'type'      => 'text',
						'title'     => __( 'Longitude of a Point', 'prodo' ),
						'desc'      => __( 'Example, -74.005121', 'prodo' ),
						'default'   => '-74.005121'
					),
					array(
						'id'            => 'map-zoom-level',
						'type'          => 'slider',
						'title'         => __( 'Zoom Level', 'prodo' ),
						'desc'          => __( 'Zoom level between 0 to 21', 'prodo' ),
						'default'       => 14,
						'min'           => 0,
						'step'          => 1,
						'max'           => 21,
						'display_value' => 'text'
					),
					array(
						'id'        => 'map-marker-state',
						'type'      => 'switch',
						'title'     => __( 'Map Marker', 'prodo' ),
						'on'        => __( 'Enabled', 'prodo' ),
						'off'       => __( 'Disabled', 'prodo' ),
						'default'   => true
					),
					array(
						'id'        => 'map-marker',
						'type'      => 'media',
						'title'     => __( 'Marker Image', 'prodo' ),
						'mode'      => false,
					),
					array(
						'id'        => 'map-marker-popup-title',
						'type'      => 'text',
						'title'     => __( 'Marker Popup Title', 'prodo' ),
						'default'   => __( 'About Prodo theme', 'prodo' )
					),
					array(
						'id'        => 'map-marker-popup-text',
						'type'      => 'editor',
						'title'     => __( 'Marker Popup Text', 'prodo' ),
						'default'   => __( 'Here we are. Come to drink a coffee!', 'prodo' )
					),
				),
			);

			$this->sections[] = array(
				'title'     => __( 'Footer Options', 'prodo' ),
				'icon'      => 'el-icon-chevron-down',
				'fields'    => array(
					array(
						'id'        => 'footer-button-top',
						'type'      => 'switch',
						'title'     => __( 'Back to Top Button', 'prodo' ),
						'on'        => __( 'Enabled', 'prodo' ),
						'off'       => __( 'Disabled', 'prodo' ),
						'default'   => true
					),
					array(
						'id'        => 'footer-text',
						'type'      => 'editor',
						'title'     => __( 'Footer Text', 'prodo' ),
						'desc'      => __( 'You can use the shortcodes in your footer text', 'prodo' ),
						'default'   => __( '2015 &copy; Prodo. All rights reserved.', 'prodo' )
					),
				),
			);
		}

		public function setArguments( ) {
			$theme = wp_get_theme( );

			$this->args = array(
				'opt_name'           => 'prodoConfig',
				'display_name'       => $theme->get( 'Name' ),
				'display_version'    => $theme->get( 'Version' ),
				'menu_type'          => 'menu',
				'allow_sub_menu'     => true,
				'menu_title'         => __( 'Theme Options', 'prodo' ),
				'page_title'         => __( 'Theme Options', 'prodo' ),
				'google_api_key'     => 'AIzaSyBJdg3uIY67Y_m4o9Bl1F3E2ZBBnX2f41A',
				'async_typography'   => false,
				'admin_bar'          => false,
				'global_variable'    => '',
				'dev_mode'           => false,
				'output'             => false,
				'compiler'           => false,
				'customizer'         => false,
				'page_priority'      => 102,
				'page_parent'        => 'themes.php',
				'page_permissions'   => 'manage_options',
				'menu_icon'          => 'dashicons-art',
				'last_tab'           => '',
				'page_icon'          => 'icon-themes',
				'page_slug'          => 'theme-options',
				'save_defaults'      => true,
				'default_show'       => false,
				'default_mark'       => '',
				'show_import_export' => false,
				'update_notice'      => false,
			);

			$this->args['share_icons'][] = array(
				'url'   => 'https://www.facebook.com/a.axminenko',
				'title' => 'Facebook',
				'icon'  => 'el-icon-facebook'
			);
			$this->args['share_icons'][] = array(
				'url'   => 'http://twitter.com/axminenko',
				'title' => 'Twitter',
				'icon'  => 'el-icon-twitter'
			);
		}

	}
	
	global $prodoInstance;
	$prodoInstance = new ProdoRedux( );
}
