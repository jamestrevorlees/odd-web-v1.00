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
 
class ProdoInit {
	# JavaScript Files
	public static function scripts( ) {
		if ( ! is_admin( ) ) {
			wp_register_script( 'modernizr', get_template_directory_uri( ) . '/assets/js/modernizr.min.js' );
			wp_register_script( 'bootstrap', get_template_directory_uri( ) . '/assets/bootstrap/js/bootstrap.min.js', array( ), false, true );
			wp_register_script( 'google-maps', 'http://maps.google.com/maps/api/js?sensor=false', array( ), false, true );
			wp_register_script( 'gmap',  get_template_directory_uri( ) . '/assets/js/jquery.gmap.min.js', array( ), false, true );
			wp_register_script( 'retina', get_template_directory_uri( ) . '/assets/js/retina.min.js', array( ), false, true );
			wp_register_script( 'scrollto', get_template_directory_uri( ) . '/assets/js/jquery.scrollto.min.js', array( ), false, true );
			wp_register_script( 'smoothscroll', get_template_directory_uri( ) . '/assets/js/smoothscroll.min.js', array( ), false, true );
			wp_register_script( 'mbytplayer', get_template_directory_uri( ) . '/assets/js/jquery.mb.ytplayer.min.js', array( ), false, true );
			wp_register_script( 'parallax', get_template_directory_uri( ) . '/assets/js/jquery.parallax.min.js', array( ), false, true );
			wp_register_script( 'isotope', get_template_directory_uri( ) . '/assets/js/jquery.isotope.min.js', array( ), false, true );
			wp_register_script( 'nav', get_template_directory_uri( ) . '/assets/js/jquery.nav.min.js', array( ), false, true );
			wp_register_script( 'knob', get_template_directory_uri( ) . '/assets/js/jquery.knob.min.js', array( ), false, true );
			wp_register_script( 'theme', get_template_directory_uri( ) . '/assets/js/prodo.min.js', array( ), false, true );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'modernizr' );
			wp_enqueue_script( 'bootstrap' );
			wp_enqueue_script( 'google-maps' );
			wp_enqueue_script( 'gmap' );
			wp_enqueue_script( 'retina' );
			wp_enqueue_script( 'scrollto' );
			wp_enqueue_script( 'smoothscroll' );
			wp_enqueue_script( 'mbytplayer' );
			wp_enqueue_script( 'parallax' );
			wp_enqueue_script( 'isotope' );
			wp_enqueue_script( 'nav' );
			wp_enqueue_script( 'knob' );
			wp_enqueue_script( 'theme' );

			if ( is_singular( ) && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( "comment-reply" );
			}
		} else {
			$currentPage = ( isset( $_GET['page'] ) ) ? $_GET['page'] : '';

			if ( $currentPage == 'site-sections' or
				 $currentPage == 'portfolio-reorder' or
				 $currentPage == 'clients-reorder' or
				 isset( $_GET['post'] )
				) {
					wp_enqueue_media( );
					wp_enqueue_script( 'jquery-ui-core' );
					wp_enqueue_script( 'jquery-ui-dropable' );
					wp_enqueue_script( 'jquery-ui-dragable' );
					wp_enqueue_script( 'jquery-ui-sortable', 'jquery' );
			}
		}
	}

	# CSS Files
	public static function styles( ) {
		global $prodoConfig;

		if ( ! is_admin( ) ) {
			$isDynamic = false;
			if ( isset( $prodoConfig ) ) {
				if ( $prodoConfig['styling-mode'] == 'color'
					or $prodoConfig['typography-content']['font-family']    != 'Open Sans' or intval( $prodoConfig['typography-content']['font-size'] ) != 14
					or $prodoConfig['typography-headers-h1']['font-family'] != 'Roboto' or intval( $prodoConfig['typography-headers-h1']['font-size'] ) != 36
					or $prodoConfig['typography-headers-h2']['font-family'] != 'Roboto' or intval( $prodoConfig['typography-headers-h2']['font-size'] ) != 30
					or $prodoConfig['typography-headers-h3']['font-family'] != 'Roboto' or intval( $prodoConfig['typography-headers-h3']['font-size'] ) != 24
					or $prodoConfig['typography-headers-h4']['font-family'] != 'Roboto' or intval( $prodoConfig['typography-headers-h4']['font-size'] ) != 18
					or $prodoConfig['typography-headers-h5']['font-family'] != 'Roboto' or intval( $prodoConfig['typography-headers-h5']['font-size'] ) != 14
					or $prodoConfig['typography-headers-h6']['font-family'] != 'Roboto' or intval( $prodoConfig['typography-headers-h6']['font-size'] ) != 12
				) $isDynamic = true;
			}

			wp_register_style( 'bootstrap', get_template_directory_uri( ) . '/assets/bootstrap/css/bootstrap.min.css' );
			wp_register_style( 'font-awesome', get_template_directory_uri( ) . '/assets/css/plugins/font-awesome.min.css' );
			wp_register_style( 'isotope', get_template_directory_uri( ) . '/assets/css/plugins/isotope.css' );

			if ( ! $isDynamic ) {
				wp_register_style( 'style', get_template_directory_uri( ) . '/assets/css/style.css' );
			} else {
				wp_register_style( 'style', get_template_directory_uri( ) . '/assets/style.php' );
			}
			
			wp_register_style( 'wp-style', get_template_directory_uri( ) . '/style.css' );
			wp_register_style( 'responsive', get_template_directory_uri( ) . '/assets/css/responsive.css' );
			wp_register_style( 'oldie', get_template_directory_uri( ) . '/assets/css/oldie.css' );

			wp_style_add_data( 'oldie', 'conditional', 'lt IE 9' );

			wp_enqueue_style( 'bootstrap' );
			wp_enqueue_style( 'font-awesome' );
			wp_enqueue_style( 'isotope' );
			wp_enqueue_style( 'style' );
			wp_enqueue_style( 'wp-style' );
			wp_enqueue_style( 'responsive' );
			wp_enqueue_style( 'oldie' );

			if ( isset( $prodoConfig ) and ! empty( $prodoConfig['custom-css'] ) ) {
				wp_add_inline_style( 'style', $prodoConfig['custom-css'] );
			}

			if ( empty( $prodoConfig['styling-mode'] ) or $prodoConfig['styling-mode'] == 'schemes' ) {
				if ( ! empty( $prodoConfig['styling-scheme'] ) and $prodoConfig['styling-scheme'] != 'default' ) {
					wp_register_style( 'color-scheme', get_template_directory_uri( ) . '/assets/css/colors/' . $prodoConfig['styling-scheme'] . '.css' );
					wp_enqueue_style( 'color-scheme' );
				}
			}
		} else {
			wp_register_style( 'font-awesome', get_template_directory_uri( ) . '/assets/css/plugins/font-awesome.min.css' );
			wp_enqueue_style( 'font-awesome' );
		}
	}

	# Google Fonts
	public static function fonts( ) {
		global $prodoConfig;

		$fonts = array( 'typography-content', 'typography-headers-h1', 'typography-headers-h2', 'typography-headers-h3', 'typography-headers-h4', 'typography-headers-h5', 'typography-headers-h6' );
		foreach ( $fonts as $key ) {
			if ( $prodoConfig[$key]['font-family'] == 'Open Sans' ) {
				wp_deregister_style( 'open-sans' );
				wp_deregister_style( 'options-google-fonts' );
				break;
			}
		}

		$fonts = array( );
		for ( $i = 1; $i <= 6; $i ++ ) {
			$key = 'typography-headers-h' . $i;
			if ( $prodoConfig[$key]['google'] ) {
				$name = strtolower( str_replace( ' ', '-', $prodoConfig[$key]['font-family'] ) );
				if ( ! in_array( $name, $fonts ) ) {
					$fonts[] = $name;
					$google = str_replace( ' ', '+', $prodoConfig[$key]['font-family'] );

					wp_register_style( $name, '//fonts.googleapis.com/css?family=' . $google . ':300,400,400italic,500,500italic' );
					wp_enqueue_style( $name );
				}
			}
		}
		if ( $prodoConfig['typography-content']['google'] ) {
			$name = strtolower( str_replace( ' ', '-', $prodoConfig['typography-content']['font-family'] ) );
			if ( ! in_array( $name, $fonts ) ) {
				$fonts[] = $name;
				$google = str_replace( ' ', '+', $prodoConfig['typography-content']['font-family'] );

				wp_register_style( $name, '//fonts.googleapis.com/css?family=' . $google . ':300,300italic,400,400italic,600,600italic,700,700italic' );
				wp_enqueue_style( $name );
			}
		}
	}

	# Initialization
	public static function init( ) {
		# Removing Demo Mode (Redux Framework)
		if ( class_exists('ReduxFrameworkPlugin') ) {
			remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance( ), 'plugin_metalinks' ), null, 2 );
			remove_action( 'admin_notices', array( ReduxFrameworkPlugin::get_instance( ), 'admin_notices' ) );
		}

		# Register Menus
		register_nav_menu( 'header-menu', __( 'Header Menu', 'prodo' ) );
		register_nav_menu( 'footer-menu', __( 'Footer Menu', 'prodo' ) );
	}

	# After Setup Theme
	public static function setup( ) {
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails', array( 'post', 'our-clients', 'our-team', 'portfolio' ) );
		add_theme_support( 'post-formats', array( 'gallery', 'aside', 'status', 'quote', 'link' ) );
		add_theme_support( 'html5', array( 'search-form' ) );
		add_theme_support( 'title-tag' );
	}

	# Main Menu Attributes
	public static function menuAtts( $atts, $item, $args = array( ) ) {
		if ( ! isset( $args->theme_location ) or $args->theme_location != 'header-menu' ) {
			return $atts;
		}

		if ( get_option( 'show_on_front', 'posts' ) == 'page' and get_option( 'page_on_front', 0 ) > 0 ) {
			$is_front_page = ProdoTheme::isFrontPage( get_the_ID( ) );

			if ( $is_front_page ) {
				$front_id = get_option( 'page_on_front' );
				if ( intval( $front_id ) == $item->object_id and $item->object_id == get_the_ID( ) ) {
					$atts['href'] = '#intro';
				}
			}

			if ( $item->object == 'page' ) {
				if ( $slug = self::sectionID( $item->object_id ) ) {
					if ( $is_front_page ) {
						$atts['href'] = '#' . $slug;
					} else {
						$atts['href'] = esc_url( site_url( '#' . $slug ) );
					}
				}
			}
		}

		return $atts;
	}

	# Main Menu Classes
	public static function menuClasses( $classes, $item, $args ) {
		if ( ! isset( $args->theme_location ) or $args->theme_location != 'header-menu' ) {
			return $classes;
		}

		if ( in_array( 'menu-item-has-children', $classes ) ) {
			$classes[] = 'dropdown';
		}

		return $classes;
	}

	# Fallback Menu
	public static function menuFallback( $menu, $args = array( ) ) {
		if ( isset( $args['prodo_fallback'] ) and isset( $args['prodo_class'] ) ) {
			$menu = preg_replace( '/ class="' . $args['menu_class'] . '"/', '', $menu );
			$menu = preg_replace( '/<ul>/', '<ul class="' . esc_attr( $args['prodo_class'] ) . '">', $menu );
		}

		return $menu;
	}

	# Section ID on Front Page
	public static function sectionID( $post_id ) {
		$sections = ( array ) @json_decode( get_option( 'prodo_sections', true ), true );

		if ( count( $sections ) > 0 ) {
			$post = get_post( $post_id );
			if ( $post !== null ) {
				if ( in_array( $post->post_name, $sections['page'] ) ) {
					return $post->post_name;
				}
			}
		}

		return false;
	}

	# More Link
	public static function moreLink( $link, $text ) {
		return str_replace( 'more-link', 'more-link btn btn-default', $link );
	}

	# Widgets
	public static function widgets( ) {
		# Sidebars
		$args = array(
			'name'          => __( 'Sidebar', 'prodo' ),
			'id'            => 'sidebar-primary',
			'description'   => '',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="row sidebar widget %2$s"><div class="col-md-12 col-sm-12">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<header><h4>',
			'after_title'   => '</h4></header>'
		);
		register_sidebar( $args );

		# Widgets
		register_widget( 'ProdoWidgetContact' );
	}

	# Embed Video
	public static function embed( $source, $url ) {
		$before = '<div class="embed-container">';
		$after = '</div>';
		
		if ( substr_count( $url, 'twitter.' ) > 0 ) {
			$before = $after = '';
		}
		return $before . $source . $after;
	}

	# Left Link Attributes (Nav. for Posts & Comments)
	public static function navLinkLeft( $atts = '' ) {
		$atts .= ( ! empty( $atts ) ? ' ' : '' ) . 'class="pull-left"';
		return $atts;
	}

	# Right Link Attributes (Nav. for Posts & Comments)
	public static function navLinkRight( $atts = '' ) {
		$atts .= ( ! empty( $atts ) ? ' ' : '' ) . 'class="pull-right"';
		return $atts;
	}

	# Password Form (Protected Posts)
	public static function passwordForm( ) {
		global $post;
		
		return '<div class="nothing-found">
		<form class="search-form" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
		<div style="padding-bottom:20px">' . __( 'To view this protected post, enter the password below:', 'prodo' ) . '</div>
		<input name="post_password" type="password" class="search-field" size="20" maxlength="20" /><input type="submit" name="Submit" class="search-submit" value="' . esc_attr__( 'Submit', 'prodo' ) . '" /></form></div>';
	}
}

add_action( 'wp_enqueue_scripts', array( 'ProdoInit', 'fonts' ) );
add_action( 'wp_enqueue_scripts', array( 'ProdoInit', 'styles' ) );
add_action( 'wp_enqueue_scripts', array( 'ProdoInit', 'scripts' ) );
add_action( 'admin_enqueue_scripts', array( 'ProdoInit', 'styles' ) );
add_action( 'admin_enqueue_scripts', array( 'ProdoInit', 'scripts' ) );

add_action( 'init', array( 'ProdoInit', 'init' ) );
add_action( 'after_setup_theme', array( 'ProdoInit', 'setup' ) );
add_action( 'widgets_init', array( 'ProdoInit', 'widgets' ) );
add_action( 'the_content_more_link', array( 'ProdoInit', 'moreLink' ), 10, 2 );
add_filter( 'the_password_form', array( 'ProdoInit', 'passwordForm' ) );

add_filter( 'nav_menu_link_attributes', array( 'ProdoInit', 'menuAtts' ), 10, 3 );
add_filter( 'nav_menu_css_class', array( 'ProdoInit', 'menuClasses' ), 10, 3 );
add_filter( 'wp_page_menu', array( 'ProdoInit', 'menuFallback' ), 10, 2 );

add_filter( 'next_posts_link_attributes', array( 'ProdoInit', 'navLinkLeft' ) );
add_filter( 'previous_posts_link_attributes', array( 'ProdoInit', 'navLinkRight' ) );
add_filter( 'previous_comments_link_attributes', array( 'ProdoInit', 'navLinkLeft' ) );
add_filter( 'next_comments_link_attributes', array( 'ProdoInit', 'navLinkRight' ) );

add_filter( 'embed_oembed_html', array( 'ProdoInit', 'embed' ), 10, 3 );
add_filter( 'video_embed_html', array( 'ProdoInit', 'embed' ) );
