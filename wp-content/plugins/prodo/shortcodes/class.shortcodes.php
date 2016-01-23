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

class ProdoShortcodes {
	/*
	 * Variables
	 */

	# Accordions
	static $accordionsCounters;
	static $accordionCounters;

	# Tabs
	static $tabsCounters;
	static $tabsTitles;
	static $tabsActive;

	# Pricing Tables
	static $pricingTableColumns;

	# Columns
	static $columnsCount;
	static $columnsOffset;

	/*
	 * Functions
	 */

	# Convert Columns
	public static function getColumnsNumber( $fraction ) {
		list( $x, $y ) = explode( '/', $fraction );

		$x = intval( $x ) > 0 ? intval( $x ) : 1;
		$y = intval( $y ) > 0 ? intval( $y ) : 1;

		return round( $x * ( 12 / $y ) );
	}

	# Shortcodes Fix
	# https://gist.github.com/bitfade/4555047
	public static function filter( $content ) {
		$block = join( '|', array(
			'button',   'column',    'clear',    'twitter-feed', 'details',      'map',         'contact-form',  'our-clients', 'bars',      'bar',
			'progress', 'milestone', 'info-box', 'our-team',     'services',     'service',     'portfolio',     'accordions',  'accordion', 'tabs',
			'tab',      'promotion', 'alert',    'quote',        'services-alt', 'service-alt', 'pricing-table', 'plan',        'milestone', 'counter',
			'blog', 'google-map'
		) );

		$rep = preg_replace( "/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content );
		$rep = preg_replace( "/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $rep );

		return $rep;
	}

	/*
	 * Shortcodes
	 */

	# Icon ([icon])
	public static function icon( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'name'   => 'fa-asterisk',
			'size'   => 'inherit',
			'spin'   => 'false',
			'color'  => 'inherit',
			'rotate' => '',
			'class'  => '',
			'align'  => ''
		), $atts ) );

		$styles = array( );
		if ( $color != 'inherit' ) $styles[] = 'color: ' . $color;
		if ( $size != 'inherit' ) $styles[] = 'font-size: ' . $size . 'px';

		return '<i class="fa ' . esc_attr( $name ) . ( $spin != 'false' ? ' fa-spin' : '' ) . ( ! empty( $align ) ? ' pull-' . esc_attr( $align ) : '' ) . ( ! empty( $rotate ) ? ' fa-' . esc_attr( $rotate ) : '' ) . ( ! empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . '"' . ( count( $styles ) > 0 ? 'style="' . esc_attr( implode( '; ', $styles ) ) . '"' : '' ) . '></i>' . ( ! empty( $content ) ? ' ' . do_shortcode( $content ) : '' );
	}

	# Button ([button])
	public static function button( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'url'     => '#',
			'icon'    => '',
			'target'  => '_self',
			'size'    => 'normal',
			'color'   => '',
			'rounded' => '',
			'inverse' => '',
			'link'    => '',
			'class'   => ''
		), $atts ) );

		$block = ( substr_count( $class, 'block' ) > 0 );
		$classesBlock = '';
		if ( $block ) {
			$classesBlock = preg_replace( '/block/', '', $class );
			$class = '';
		}

		if ( ! empty( $icon ) ) {
			$icon = self::icon( array( 'name' => $icon ) );
		}
		
		return ( $block ? '<div' . ( ! empty( $classesBlock ) ? ' class="' . esc_attr( $classesBlock ) . '"' : '' ) . '>' : '' ) . '<a href="' . esc_url( $url ) . '" class="btn btn-' . ( ! empty( $link ) ? 'link' : 'default' ) . ( $size == 'small' ? ' btn-small' : '' ) . ( ! empty( $rounded ) ? ' btn-rounded' : '' ) . ( ! empty( $inverse ) ? ' btn-inverse' : '' ) . ( ! empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . '"' . ( $target != '_self' ? ' target="' . esc_attr( $target ) . '"' : '' ) . ( ! empty( $color ) ? ' style="color: ' . esc_attr( $color ) . ';"' : '' ) . '>' . ( ! empty( $icon ) ? $icon . ' ' : '' ) . $content . '</a>' . ( $block ? '</div>' : '' );
	}

	# Column ([column])
	public static function column( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'size'  => '1/2',
			'class' => ''
		), $atts ) );

		$count = self::getColumnsNumber( $size );
		$rowOpen = $rowClose = false;

		self::$columnsOffset = ( self::$columnsOffset > 0 ) ? self::$columnsOffset : 0;
		if ( self::$columnsOffset == 0 ) {
			if ( preg_match( '#col-md-offset-([0-9]+)#is', $class, $matches ) ) {
				$offset = intval( $matches[1] );
				if ( self::$columnsOffset == 0 ) self::$columnsOffset = $offset;
			}
		}

		if ( $count == 12 - ( self::$columnsOffset * 2 ) ) {
			$rowOpen = $rowClose = true;
			self::$columnsOffset = 0;
		} else {
			if ( self::$columnsCount == 0 ) {
				self::$columnsCount += $count;
				$rowOpen = true;
			} else {
				self::$columnsCount += $count;
				if ( self::$columnsCount == 12 - ( self::$columnsOffset * 2 ) ) {
					self::$columnsCount = self::$columnsOffset = 0;
					$rowClose = true;
				}
			}
		}

		return ( $rowOpen ? '<div class="row">' : '' ) . '<div class="col-md-' . $count . ( ! empty( $class ) ? ' ' . esc_attr( $class ) . '' : '' ) . '">' . do_shortcode( $content ) . '</div>' . ( $rowClose ? '</div>' : '' );
	}

	# Clear ([clear])
	public static function clear( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class'  => '',
			'gap'	 => 0
		), $atts ) );

		$before = $attributes = '';

		if ( self::$columnsCount > 0 ) {
			self::$columnsCount = self::$columnsOffset = 0;
			$before = '</div>';
		}

		if ( $gap > 0 ) {
			if      ( $gap == 20 )  $class .= ' offsetTopS';
			else if ( $gap == 60 )  $class .= ' offsetTop';
			else if ( $gap == 80 )  $class .= ' offsetTopL';
			else if ( $gap == 120 ) $class .= ' offsetTopX';
			else {
				$attributes = ' style="padding-top: ' . intval( $gap ) . 'px;"';
			}
		}

		return $before . '<div class="clear' . ( ! empty( $class ) ? ' ' . trim( esc_attr( $class ) ) : '' ) . '"' . $attributes . '></div>';
	}

	# Twitter Feed ([twitter-feed])
	public static function twitter( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'account' => '',
			'limit'   => 5,
			'reply'   => 'true',
			'delay'   => 0
		), $atts ) );

		if ( $account == '' ) {
			return;
		}

		$output = '';
		$counter = 0;
		$delay = intval( $delay );
		$tweets = ProdoTwitter::getTweets( $account );

		if ( is_array( $tweets ) and count( $tweets ) > 0 ) {
			foreach( $tweets as $tweet ) {
				if ( $counter < $limit ) {
					$srid = ( int ) $tweet->in_reply_to_user_id;
					if ( $srid == 0 || $reply == 'true' ) {
						$text = preg_replace( array( "/\\r/", "/\\n/" ), array( '', ' ' ), $tweet->text );
						$output .= '<li><span class="tweet_text">' . ProdoTwitter::parseLinks( $text ) . '</span></li>';
						$counter ++;
					}
				}
			}
			if ( $counter > 0 ) {
				$output = '<div class="twitter"><div class="twitter-feed">
					<ul class="tweet_list" data-arrows=".twitter-arrows"' . ( $delay > 0 ? ' data-delay="' . ( $delay * 1000 ) . '"' : '' ) . '>' . $output . '</ul></div>
				<div class="offsetTopS"><a href="' . esc_url( 'https://twitter.com/' . $account ) . '" target="_blank" class="twitter-author">' . esc_html( '@' . $account ) . '</a></div></div>
				<div class="row arrows twitter-arrows text-center"><a class="arrow left"><i class="fa fa-chevron-left"></i></a><a class="arrow right"><i class="fa fa-chevron-right"></i></a></div>';
			}
		}

		return $output;
	}

	# Google Map (Section) ([map])
	public static function map( $atts, $content = null ) {
		global $prodoConfig;

		$marker = get_template_directory_uri( ) . '/assets/images/marker.png';

		if ( ! $prodoConfig['map-marker-state'] ) {
			$marker = false;
		} else if ( ! empty( $prodoConfig['map-marker']['url'] ) ) {
			$marker = $prodoConfig['map-marker']['url'];
		}

		return '
		<section class="section map">
		<div id="google-map" data-map-zoom="' . ( $prodoConfig['map-zoom-level'] > 0 ? intval( $prodoConfig['map-zoom-level'] ) : 10 ) . '" data-latitude="' . ( ! empty( $prodoConfig['map-latitude'] ) ? esc_attr( $prodoConfig['map-latitude'] ) : '40.706279' ) . '" data-longitude="' . ( ! empty( $prodoConfig['map-longitude'] ) ? esc_attr( $prodoConfig['map-longitude'] ) : '-74.005121' ) . '"' . ( $marker !== false ? ' data-marker="' . esc_url( $marker ) . '"' : '' ) . '></div>
		' . ( $marker !== false ? '<div id="map-info">
			<div id="content">
				<div id="siteNotice"></div>
				<h4 id="firstHeading" class="firstHeading">' . esc_html( $prodoConfig['map-marker-popup-title'] ) . '</h4>
				<div id="bodyContent">' . apply_filters( 'the_content', do_shortcode( $prodoConfig['map-marker-popup-text'] ) ) . '</div></div>
		</div>' : '' ) . '
		</section>';
	}

	# Google Map (Shortcode) ([google-map])
	public static function googleMap( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'address'   => 'New York, United States',
			'latitude'  => '',
			'longitude' => '',
			'zoom'      => '15',
			'height'    => '200'
		), $atts ) );

		return '<div class="googlemap" ' . ( ( ! empty( $latitude ) and ! empty( $longitude ) ) ? 'data-latlng="' . esc_attr( $latitude ) . ',' . esc_attr( $longitude ) . '"' : 'data-address="' . esc_attr( $address ) . '"' ) . ' data-zoom="' . intval( $zoom ) . '" style="height: ' . intval( $height ) . 'px;"></div>';
	}

	# Contact Form ([contact-form])
	public static function contactForm( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title' => __( 'Get in Touch', 'prodo' )
		), $atts ) );

		return '
		<div id="prodo-contact-form" class="contact-form field-action" data-url="' . esc_url( get_template_directory_uri( ) . '/inc/ajax.php' ) . '">
			<div class="contact-form-area">
				<div class="row">
					<div class="col-md-6 col-sm-6">
						<div class="field"><input type="text" name="name" class="field-name" placeholder="' . esc_attr__( 'Name', 'prodo' ) . '"></div>
						<div class="field"><input type="email" name="email" class="field-email" placeholder="' . esc_attr__( 'Email', 'prodo' ) . '"></div>
						<div class="field"><input type="text" name="phone" class="field-phone" placeholder="' . esc_attr__( 'Phone', 'prodo' ) . '"></div></div>
					<div class="col-md-6 col-sm-6">
						<div class="field"><textarea name="message" class="field-message" placeholder="' . esc_attr__( 'Message', 'prodo' ) . '"></textarea></div></div>
				</div>
				<div class="row"><div class="col-md-12 text-center"><button type="submit" class="btn btn-default" id="contact-submit">' . __( 'Send Message', 'prodo' ) . '</button></div></div>
			</div>
			<div class="contact-form-result">
				<div class="row">
					<div class="col-md-12"><h3>' . __( 'Thank you so much for the Email!', 'prodo' ) . '</h3><p>' . __( 'Your message has already arrived! We\'ll contact you shortly.', 'prodo' ) . '</p><h5>' . __( 'Goog day.', 'prodo' ) . '</h5></div>
				</div></div>
		</div>';
	}

	# Our Clients ([our-clients])
	public static function ourClients( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'column' => '1/4',
			'limit'  => -1,
			'class'  => ''
		), $atts ) );

		$limit = intval( $limit );
		$query = array(
			'post_type'   => 'our-clients',
			'numberposts' => $limit,
			'orderby'     => 'menu_order',
			'order'       => 'ASC'
		);
		$rows = get_posts( $query );
		$output = '';

		if ( count( $rows ) > 0 ) {
			$output .= '<div class="row">';

			foreach ( $rows as $row ) {
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $row->ID ), 'full' );
				$output .= '<div class="col-md-' . self::getColumnsNumber( $column ) . ( ! empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . ' animation animation-from-left"><img src="' . $thumb[0] . '" width="' . floor( $thumb[1] / 2 ) . '" class="img-responsive center-block" alt="' . esc_attr( apply_filters( 'the_title', $row->post_title ) ) . '"></div>';
			}

			$output .= '</div>';
		}
		return $output;
	}

	# Circular Bars ([bars])
	public static function bars( $atts, $content = null ) {
		return '<div class="row circular-bars clearfix text-center">' . do_shortcode( $content ) . '</div>';
	}

	# Circular Bar ([bar])
	public static function bar( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'value'  => 100,
			'title'  => '',
			'column' => '1/6',
			'class'  => ''
		), $atts ) );

		return '<div class="col-xs-6 col-sm-4 col-md-' . self::getColumnsNumber( $column ) . ( ! empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . '"><input data-value="' . intval( $value ) . '" disabled><div class="h5">' . esc_html( $title ) . '</div></div>';
	}

	# Progress Bar ([progress])
	public static function progress( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'  => '',
			'value'  => 75
		), $atts ) );

		return '<div class="bar"><div class="progress-heading"><h5 class="progress-title">' . esc_html( $title ) . '</h5><div class="progress-value"></div></div><div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="' . intval( $value ) . '" aria-valuemin="0" aria-valuemax="100"></div></div></div>';
	}

	# Milestone Counters ([milestone])
	public static function milestone( $atts, $content = null ) {
		return '<div class="row">' . do_shortcode( $content ) . '</div>';
	}

	# Milestone Counter ([counter])
	public static function counter( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'column' => '1/6',
			'class'  => '',
			'from'   => '1',
			'to'     => '100',
			'title'  => '',
		), $atts ) );

		return '<div class="col-md-' . self::getColumnsNumber( $column ) . ( ! empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . '"><div class="milestone"><div class="counter" data-from="' . intval( $from ) . '" data-to="' . intval( $to ) . '">' . $to . '</div><div class="description">' . esc_html( $title ) . '</div></div></div>';
	}

	# Highlight ([highlight])
	public static function highlight( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'  => '',
		), $atts ) );

		return '<span class="highlight' . ( $style == 'dark' ? '-dark' : '' ) . '">' . do_shortcode( $content ) . '</span>';
	}

	# Quote ([quote])
	public static function quote( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'information'  => '',
		), $atts ) );

		return '<blockquote><p>' . do_shortcode( $content ) . '</p>' . ( ! empty( $information ) ? '<footer>' . esc_html( $information ) . '</footer>' : '' ) . '</blockquote>';
	}

	# Info Box (Section) ([info-box])
	public static function infoBox( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'url' => '',
			'button' => '',
			'class' => ''
		), $atts ) );

		if ( ! empty( $button ) and ! empty( $url ) ) {
			$button = '<a href="' . esc_url( $url ) . '" class="btn btn-default">' . esc_html( $button ) . '</a>';
		}

		return '
		<section class="section info-box">
		<div class="container">
			<div class="row' . ( ! empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . '">
				<div class="' . ( ! empty( $button ) ? 'col-lg-6 col-md-6 col-sm-6 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 text-center-xs' : 'col-md-12' ) . '">' . do_shortcode( $content ) . '</div>
				' . ( ! empty( $button ) ? '<div class="col-lg-3 col-md-4 col-sm-4 pull-right text-center-xs">'. $button . '</div>' : '' ) . '</div>
		</div>
		</section>';
	}

	# Our Team ([our-team])
	public static function ourTeam( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'column' => '1/3',
			'class'  => '',
			'limit'  => -1
		), $atts ) );

		$limit = intval( $limit );
		$query = array(
			'post_type' => 'our-team',
			'numberposts' => $limit
		);
		$rows = get_posts( $query );
		$output = '';

		if ( count( $rows ) > 0 ) {
			$output .= '<div class="row team">';

			foreach ( $rows as $row ) {
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $row->ID ), 'full' );
				$title = apply_filters( 'the_title', $row->post_title );

				$social = '';
				$meta = get_post_meta( $row->ID );

				if ( ! empty( $meta['twitter'][0] ) ) $social .= '<a href="' . esc_url( $meta['twitter'][0] ) . '" title="Twitter"><i class="fa fa-twitter"></i></a>';
				if ( ! empty( $meta['facebook'][0] ) ) $social .= '<a href="' . esc_url( $meta['facebook'][0] ) . '" title="Facebook"><i class="fa fa-facebook"></i></a>';
				if ( ! empty( $meta['linkedin'][0] ) ) $social .= '<a href="' . esc_url( $meta['linkedin'][0] ) . '" title="LinkedIn"><i class="fa fa-linkedin"></i></a>';
				if ( ! empty( $meta['google'][0] ) ) $social .= '<a href="' . esc_url( $meta['google'][0] ) . '" title="Google+"><i class="fa fa-google-plus"></i></a>';

				$output .= '<div class="offsetBottomL animation animation-from-bottom col-md-' . self::getColumnsNumber( $column ) . ( ! empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . '"><div class="photo"><img src="' . $thumb[0] . '" class="img-responsive img-rounded" alt="' . esc_attr( $title ) . '"></div><div class="details"><h4>' . esc_html( $title ) . '</h4><span>' . $meta['activity'][0] . '</span></p></div>' . ( ! empty( $social ) ? '<div class="social">' . $social . '</div>' : '' ) . '</div>';
			}

			$output .= '</div>';
		}
		return $output;
	}

	# Services ([services])
	public static function services( $atts, $content = null ) {
		return '</div><hr><div class="container"><div class="row services">' . do_shortcode( $content ) . '</div>';
	}

	# Service ([service])
	public static function service( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'         => '',
			'column'        => '1/3',
			'icon'          => 'fa-asterisk',
			'color'         => '',
			'class'         => '',
			'sticker'       => '',
			'sticker_color' => '',
		), $atts ) );

		return '
		<div class="text-center offsetTopL offsetBottomL animation animation-from-left col-sm-6 col-xs-6 col-md-' . self::getColumnsNumber( $column ) . ( ! empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . '">
			<div><i class="fa ' . esc_html( $icon ) . ( ! empty( $sticker ) ? ' sticker-icon' : '' ) . '"' . ( ! empty( $color ) ? ' style="color: ' . esc_attr( $color ) . ';"' : '' ) . '>' . ( ! empty( $sticker ) ? ' ' . self::sticker( array( 'label' => $sticker, 'color' => $sticker_color ) ) : '' ) . '</i></div>
			<header><h4>' . esc_html( $title ) . '</h4></header><p>' . do_shortcode( $content ) . '</p></div>';
	}

	# Services Alt ([services-alt])
	public static function servicesAlt( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class'  => ''
		), $atts ) );

		return '<div class="row services-alt' . ( ! empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . '">' . do_shortcode( $content ) . '</div>';
	}

	# Service Alt ([service-alt])
	public static function serviceAlt( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'icon'    => 'fa-asterisk',
			'column'  => '1/6',
			'class'   => ''
		), $atts ) );

		$icon = self::icon( array( 'name' => $icon ) );
		return '<div class="col-md-' . self::getColumnsNumber( $column ) . ( ! empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . '">' . $icon . '<h5>' . do_shortcode( $content ) . '</h5></div>';
	}

	# Portfolio ([portfolio])
	public static function portfolio( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'limit'      => -1,
			'order'      => 'menu_order',
			'filters'    => 'yes',
			'terms'      => ''
		), $atts ) );

		$filters_html = '';
		$limit = intval( $limit );

		if ( $filters == 'yes' ) {
			$categories = get_terms( 'portfolio-category', array( 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => 1 ) );
			$filters_html = '<a href="#" data-filter="*" class="active">' . __( 'All', 'prodo' ) . '</a>';

			if ( count( $categories ) > 0 ) {
				foreach ( $categories as $row ) {
					$filters_html .= '<a href="#" data-filter=".filter-' . esc_attr( $row->slug ) . '">' . esc_html( $row->name ) . '</a>';
				}
			}
		}

		$query = array(
			'post_type'   => 'portfolio',
			'numberposts' => $limit,
			'order'       => ( ( $order == 'date' or $order == 'modified' or $order == 'rand' ) ? 'DESC' : 'ASC' ),
			'orderby'     => $order
		);

		if ( ! empty( $terms ) ) {
			$terms_arr = explode( ',', $terms );
			$terms_query = array( );

			if ( is_array( $terms_arr ) and count( $terms_arr ) > 0 ) {
				foreach ( $terms_arr as $term ) {
					$terms_query[] = trim( esc_sql( $term ) );
				}

				$query['tax_query'] = array(
					array(
						'taxonomy' => 'portfolio-category',
						'field'    => 'slug',
						'terms'    => $terms_query
					)
				);
			}
		}

		$rows = get_posts( $query );
		$output = $projects = '';

		if ( count( $rows ) > 0 ) {
			foreach ( $rows as $row ) {
				$info = wp_get_object_terms( $row->ID, 'portfolio-category' );
				$category = array( );
				$filter = '';

				foreach( $info as $item ) {
					$category[] = $item->name;
					$filter .= 'filter-' . $item->slug . ' ';
				}

				$category = implode( ', ', $category );
				$filter = rtrim( $filter );
				unset( $info );

				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $row->ID ), 'full' );
				$title = apply_filters( 'the_title', $row->post_title );
				$link = get_permalink( $row->ID );

				if ( get_option( 'show_on_front', 'posts' ) == 'page' and get_option( 'page_on_front', 0 ) > 0 and ProdoTheme::isFrontPage( get_the_ID( ) ) ) {
					$href = site_url( '#view-' . $row->post_name );
				} else {
					$href = $link;
				}

				$projects .= '
				<div class="animation animation-from-left">
					<div class="portfolio-item' . ( ! empty( $filter ) ? ' ' . esc_attr( $filter ) : '' ) . '" rel="' . esc_attr( $row->post_name ) . '">
						<img src="' . $thumb[0] . '" alt="' . esc_attr( $title ) . '"><div class="overlay"></div>
						<div class="details">' . esc_html( $category ) . '</div>
						<div class="href"><a href="' . esc_url( $href ) . '" data-url="' . esc_url( $link ) . '"></a></div></div></div>';
			}

			if ( $filters == 'yes' and ! empty( $filters_html ) ) {
				$output = '<div class="row"><div class="col-md-12 portfolio-filters">' . $filters_html . '</div></div>';
			}

			$output .= '</div><div class="container-fluid offsetTop' . ( ( $filters == 'yes' and ! empty( $filters_html ) ) ? '' : 'S' ) . '"><div class="row portfolio-items clearfix" data-on-line-lg="5" data-on-line-md="5" data-on-line-sm="4" data-on-line-xs="2">' . $projects . '</div>';
		}

		return $output;
	}

	# Blog Posts ([blog])
	public static function blog( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'column' => '1/4',
			'limit'  => '3'
		), $atts ) );

		if ( $column == '1/2' ) {
			$count_string = 'two';
		} else if ( $column == '1/3' ) {
			$count_string = 'four';
		} else {
			$count_string = 'three';
		}

		$output = '';

		$query = new WP_Query( array(
			'post_type'      => 'post',
			'posts_per_page' => intval( $limit )
		) );

		if ( $query->have_posts( ) ) {
			while ( $query->have_posts( ) ) {
				$query->the_post( );

				$post_title = get_the_title( );
				$attr_title = the_title_attribute( array( 'before' => '', 'after' => '', 'echo' => false ) );

				$format = get_post_format( get_the_ID( ) );
				$format_css = 'responsive-images';

				if ( $format == 'status' ) {
					$format_css = 'format-holder status';
				} else if ( $format == 'link' ) {
					$format_css = 'format-holder link';
				} else if ( $format == 'aside' ) {
					$format_css = 'format-holder aside';
				}

				if ( $format != 'gallery' ) {
					$post_content = apply_filters( 'the_content', get_the_content( __( 'Read More', 'prodo' ) ) );
				} else {
					$post_content = ProdoTheme::postGallery( __( 'Read More', 'prodo' ), false );
				}

				$output .= '
				<article id="post-' . esc_attr( get_the_ID( ) ) . '" class="' . esc_attr( implode( ' ', get_post_class( 'blog-post masonry offsetTopS offsetBottom', get_the_ID( ) ) ) ) . '">
					<header>
						' . ( ! empty( $post_title ) ? '<h3><a href="' . esc_url( get_the_permalink( ) ) . '" title="' . $attr_title . '">' . esc_html( $post_title ) . '</a></h3>' : '' ) . '
						<div class="info">
							' . ProdoTheme::postCategories( get_the_ID( ), '<span>', '</span>', false ) . '
						</div>
					</header>
					<div class="responsive-images">
						' . $post_content . '
					</div>
				</article>';
			}
		} else {
			wp_reset_postdata( );

			return '';
		}

		wp_reset_postdata( );

		return '<div class="row"><div class="col-md-12 col-sm-12 blog-masonry blog-masonry-' . esc_attr( $count_string ) . '">' . $output . '</div></div>';
	}

	# Accordion ([accordions])
	public static function accordions( $atts, $content = null ) {
		self::$accordionsCounters = ( self::$accordionsCounters > 0 ) ? ( int ) self::$accordionsCounters : 0;
		self::$accordionsCounters ++;

		$content = do_shortcode( $content );

		return '<div class="panel-group" id="accordion' . self::$accordionsCounters . '">' . $content . '</div>';
	}

	# Accordion Tab ([accordion])
	public static function accordion( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'  => '',
			'opened' => 'no',
		), $atts ) );

		self::$accordionCounters = ( self::$accordionCounters > 0 ) ? ( int ) self::$accordionCounters : 0;
		self::$accordionCounters ++;

		return '
		<div class="panel panel-default">
			<div class="panel-heading"><h5 class="panel-title"><a href="#collapse' . self::$accordionCounters . '" data-toggle="collapse" data-parent="#accordion' . self::$accordionsCounters . '">' . esc_html( $title ) . '</a></h5></div>
			<div id="collapse' . self::$accordionCounters . '" class="panel-collapse collapse' . ( $opened == 'yes' ? ' in' : '' ) . '"><div class="panel-body"><p>' . do_shortcode( $content ) . '</p></div></div></div>';
	}

	# Pricing Tables ([pricing-table])
	public static function pricingTable( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'column' => '1/3'
		), $atts ) );

		self::$pricingTableColumns = self::getColumnsNumber( $column );

		return '<div class="row pricing-tables">' . do_shortcode( $content ) . '</div>';
	}

	# Pricing Table Plan ([plan])
	public static function plan( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'         => __( 'Empty Title', 'prodo' ),
			'price'         => __( '0$/month', 'prodo' ),
			'link'          => '#',
			'button'        => __( 'Purchase', 'prodo' ),
			'sticker'       => '',
			'sticker_color' => '',
		), $atts ) );

		return '
		<div class="col-md-' . self::$pricingTableColumns . ' col-sm-' . self::$pricingTableColumns . '">
			<div class="plan">
				<header>
					<h3>' . esc_html( $title ) . ( ! empty( $sticker ) ? ' ' . self::sticker( array( 'label' => $sticker, 'color' => $sticker_color ) ) : '' ) . '</h3>
					<span class="info">' . esc_html( $price ) . '</span>
				</header>
				' . do_shortcode( $content ) . '
				<a href="' . esc_url( $link ) . '" class="btn btn-default">' . esc_html( $button ) . '</a>
			</div>
		</div>';
	}

	# Tabs ([tabs])
	public static function tabs( $atts, $content = null ) {
		self::$tabsTitles = array( );
		self::$tabsActive = true;

		$content = do_shortcode( $content );

		$output = '<ul class="nav nav-tabs">';

		if ( count( self::$tabsTitles ) > 0 ) {
			$i = 0;
			foreach ( self::$tabsTitles as $id => $title ) {
				$output .= '<li' . ( $i == 0 ? ' class="active"' : '' ) . '><a href="#tab' . esc_attr( $id ) . '" data-toggle="tab">' . $title . '</a></li>';
				$i ++;
			}
		}

		$output .= '</ul><div class="tab-content">' . $content . '</div>';

		return $output;
	}

	# Tab ([tab])
	public static function tab( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'  => ''
		), $atts ) );

		self::$tabsCounters = ( self::$tabsCounters > 0 ) ? ( int ) self::$tabsCounters : 0;
		self::$tabsCounters ++;

		self::$tabsTitles[self::$tabsCounters] = $title;

		$output = '<div class="tab-pane' . ( self::$tabsActive === true ? ' active' : '' ) . '" id="tab' . self::$tabsCounters . '"><p>' . do_shortcode( $content ) . '</p></div>';

		self::$tabsActive = false;

		return $output;
	}

	# Promotion Boxes ([promotion])
	public static function promotion( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'  => '',
			'style'  => 'one'
		), $atts ) );

		if ( $style == 'two' ) $classes = ' line-top';
		else if ( $style == 'three' ) $classes = ' line-top line-grey';
		else $classes = '';

		return '<div class="promotion-box' . esc_attr( $classes ) . '"><h4>' . esc_html( $title ) . '</h4><p>' . do_shortcode( $content ) . '</p></div>';
	}

	# Alert ([alert])
	public static function alert( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'  => '',
			'type'  => 'info'
		), $atts ) );

		if ( $type != 'success' and $type != 'info' and $type != 'warning' and $type != 'danger' ) {
			$type = 'info';
		}

		return '<div class="alert alert-' . $type . '"><h4>' . esc_html( $title ) . '</h4><p>' . do_shortcode( $content ) . '</p></div>';
	}

	# Stickers ([sticker])
	public static function sticker( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'label'  => 'Hi!',
			'color'  => '',
			'icon'   => '',
		), $atts ) );

		if ( $color != 'green' and $color != 'blue' and $color != 'orange' and $color != 'red' ) {
			$color = '';
		}

		return ( ! empty( $icon ) ? '<i class="fa ' . esc_attr( $icon ) . ' sticker-icon">' : '' ) . '<span class="sticker' . ( ! empty( $color ) ? ' ' . esc_attr( $color ) : '' ) . '">' . esc_html( $label ) . '</span>' . ( ! empty( $icon ) ? '</i>' : '' );
	}

	# Dropcap ([dropcap])
	public static function dropcap( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'letter'  => 'A',
			'style'   => ''
		), $atts ) );

		return '<span class="dropcap' . ( $style == 'alt' ? ' alt' : '' ) . '">' . esc_html( $letter ) . '</span>';
	}

	# Portfolio Project Details + Share ([details])
	public static function details( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'share' => 'no'
		), $atts ) );

		$panel = '
		<div class="share-panel">
			<span>' . __( 'Share', 'prodo' ) . '</span>
			<div class="social">
				<a title="Twitter" onclick="shareTo( \'twitter\', \'#share-title\', \'#share-image\', \'#view-\' + $( \'#portfolio-details\' ).attr( \'data-current\' ) )"><i class="fa fa-twitter"></i></a>
				<a title="Facebook" onclick="shareTo( \'facebook\', \'#share-title\', \'#share-image\', \'#view-\' + $( \'#portfolio-details\' ).attr( \'data-current\' ) )"><i class="fa fa-facebook"></i></a>
				<a title="Pinterest" onclick="shareTo( \'pinterest\', \'#share-title\', \'#share-image\', \'#view-\' + $( \'#portfolio-details\' ).attr( \'data-current\' ) )"><i class="fa fa-pinterest"></i></a>
				<a title="LinkedIn" onclick="shareTo( \'linkedin\', \'#share-title\', \'#share-image\', \'#view-\' + $( \'#portfolio-details\' ).attr( \'data-current\' ) )"><i class="fa fa-linkedin"></i></a>
			</div>
		</div>';

		$content = preg_replace( '/<ul/', '<ul class="fa-ul details"', $content );
		$content = preg_replace( '/<li>/', '<li><i class="fa-li fa fa-angle-right colored"></i> ', $content );

		return do_shortcode( $content ) . ( $share == 'yes' ? $panel : '' );
	}
}

# Admin Features
class ProdoShortcodesAdmin {
	# Add Button
	public static function buttonAdd( ) {
		if ( ! current_user_can( 'edit_posts' ) and ! current_user_can( 'edit_pages' ) ) {
			return;
		}
		
		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter( 'mce_external_plugins', array( 'ProdoShortcodesAdmin', 'pluginAdd' ) );
			add_filter( 'mce_buttons', array( 'ProdoShortcodesAdmin', 'buttonRegister' ) );
		}
	}

	# Register Button
	public static function buttonRegister( $buttons ) {
		array_push( $buttons, 'prodoShortcodes' );

		return $buttons;
	}

	# Add Plugin
	public static function pluginAdd( $plugin_array ) {
		$plugin_array['prodoShortcodes'] = plugin_dir_url( __FILE__ ) . 'shortcodes.js';

		return $plugin_array;
	}
}

# Register Shortcodes
add_shortcode( 'icon', array( 'ProdoShortcodes', 'icon' ) );
add_shortcode( 'button', array( 'ProdoShortcodes', 'button' ) );
add_shortcode( 'column', array( 'ProdoShortcodes', 'column' ) );
add_shortcode( 'clear', array( 'ProdoShortcodes', 'clear' ) );
add_shortcode( 'accordions', array( 'ProdoShortcodes', 'accordions' ) );
add_shortcode( 'accordion', array( 'ProdoShortcodes', 'accordion' ) );
add_shortcode( 'tabs', array( 'ProdoShortcodes', 'tabs' ) );
add_shortcode( 'tab', array( 'ProdoShortcodes', 'tab' ) );
add_shortcode( 'promotion', array( 'ProdoShortcodes', 'promotion' ) );
add_shortcode( 'alert', array( 'ProdoShortcodes', 'alert' ) );
add_shortcode( 'progress', array( 'ProdoShortcodes', 'progress' ) );
add_shortcode( 'bars', array( 'ProdoShortcodes', 'bars' ) );
add_shortcode( 'bar', array( 'ProdoShortcodes', 'bar' ) );
add_shortcode( 'milestone', array( 'ProdoShortcodes', 'milestone' ) );
add_shortcode( 'counter', array( 'ProdoShortcodes', 'counter' ) );
add_shortcode( 'highlight', array( 'ProdoShortcodes', 'highlight' ) );
add_shortcode( 'quote', array( 'ProdoShortcodes', 'quote' ) );
add_shortcode( 'sticker', array( 'ProdoShortcodes', 'sticker' ) );
add_shortcode( 'dropcap', array( 'ProdoShortcodes', 'dropcap' ) );
add_shortcode( 'pricing-table', array( 'ProdoShortcodes', 'pricingTable' ) );
add_shortcode( 'plan', array( 'ProdoShortcodes', 'plan' ) );
add_shortcode( 'map', array( 'ProdoShortcodes', 'map' ) );
add_shortcode( 'google-map', array( 'ProdoShortcodes', 'googleMap' ) );
add_shortcode( 'services-alt', array( 'ProdoShortcodes', 'servicesAlt' ) );
add_shortcode( 'service-alt', array( 'ProdoShortcodes', 'serviceAlt' ) );
add_shortcode( 'contact-form', array( 'ProdoShortcodes', 'contactForm' ) );
add_shortcode( 'our-clients', array( 'ProdoShortcodes', 'ourClients' ) );
add_shortcode( 'info-box', array( 'ProdoShortcodes', 'infoBox' ) );
add_shortcode( 'our-team', array( 'ProdoShortcodes', 'ourTeam' ) );
add_shortcode( 'services', array( 'ProdoShortcodes', 'services' ) );
add_shortcode( 'service', array( 'ProdoShortcodes', 'service' ) );
add_shortcode( 'portfolio', array( 'ProdoShortcodes', 'portfolio' ) );
add_shortcode( 'twitter-feed', array( 'ProdoShortcodes', 'twitter' ) );
add_shortcode( 'details', array( 'ProdoShortcodes', 'details' ) );
add_shortcode( 'blog', array( 'ProdoShortcodes', 'blog' ) );

# Register Admin Features
add_action( 'admin_head', array( 'ProdoShortcodesAdmin', 'buttonAdd' ) );

# Shortcodes Fix
add_filter( 'the_content', array( 'ProdoShortcodes', 'filter' ) );