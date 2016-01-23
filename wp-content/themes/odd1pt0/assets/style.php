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

$path = explode( 'wp-content', __FILE__ );
require_once $path[0] . '/wp-load.php';

header( 'Content-Type: text/css' );

$find = $replace = array( );
$toEnd = '';

if ( ! isset( $prodoConfig ) ) {
	die( '/' . '* Redux Framework not included *' . '/' );
}

# List of Files
$list = array( 'common', 'layout', 'forms', 'blog', 'shortcodes', 'misc' );

# Primary Color
$defaultColor = '#2185c5';
$defaultColorRGB = '33, 133, 197';

if ( $prodoConfig['styling-mode'] == 'color' ) {
	if ( ! empty( $prodoConfig['styling-color'] ) and $prodoConfig['styling-color'] != $defaultColor ) {
		$find[] = '/' . $defaultColor . '/';
		$replace[] = esc_attr( $prodoConfig['styling-color'] );

		$red = hexdec( substr( $prodoConfig['styling-color'], 1, 2 ) );
		$green = hexdec( substr( $prodoConfig['styling-color'], 3, 2 ) );
		$blue = hexdec( substr( $prodoConfig['styling-color'], 5, 2 ) );

		$find[] = '/' . 'rgba\( ' . $defaultColorRGB . '/';
		$replace[] = 'rgba( ' . $red . ', ' . $green . ', ' . $blue;
	}
}
# / End

# Primary Font
$defaultFont = 'Open Sans';
$defaultSize = 14;

if ( $prodoConfig['typography-content']['font-family'] != $defaultFont ) {
	$find[] = '/' . $defaultFont . '/';
	$replace[] = esc_attr( $prodoConfig['typography-content']['font-family'] );
}
if ( $prodoConfig['typography-content']['font-size'] != $defaultSize ) {
	$find[] = '/' . 'font-size: ' . $defaultSize . 'px;' . '/';
	$replace[] = 'font-size: ' . intval( $prodoConfig['typography-content']['font-size'] ) . 'px;';
}
# / End

# Second Font
$defaultFont = 'Roboto';

if ( $prodoConfig['typography-headers-h5']['font-family'] != $defaultFont ) {
	$find[] = '/' . $defaultFont . '/';
	$replace[] = esc_attr( $prodoConfig['typography-headers-h5']['font-family'] );
}
# / End

# Headers
// H1
if ( $prodoConfig['typography-headers-h1']['font-family'] != $defaultFont ) {
	$toEnd .= "\n" . 'h1, .h1 { font-family: "' . esc_attr( $prodoConfig['typography-headers-h1']['font-family'] ) . '", sans-serif; }';
}
if ( $prodoConfig['typography-headers-h1']['font-size'] != 36 ) {
	$toEnd .= "\n" . 'h1, .h1 { font-size: "' . intval( $prodoConfig['typography-headers-h1']['font-size'] ) . 'px"; }';
}

// H2
if ( $prodoConfig['typography-headers-h2']['font-family'] != $defaultFont ) {
	$toEnd .= "\n" . 'h2, .h2 { font-family: "' . esc_attr( $prodoConfig['typography-headers-h2']['font-family'] ) . '", sans-serif; }';
}
if ( $prodoConfig['typography-headers-h2']['font-size'] != 30 ) {
	$toEnd .= "\n" . 'h2, .h2 { font-size: "' . intval( $prodoConfig['typography-headers-h2']['font-size'] ) . 'px"; }';
}

// H3
if ( $prodoConfig['typography-headers-h3']['font-family'] != $defaultFont ) {
	$toEnd .= "\n" . 'h3, .h3 { font-family: "' . esc_attr( $prodoConfig['typography-headers-h3']['font-family'] ) . '", sans-serif; }';
}
if ( $prodoConfig['typography-headers-h3']['font-size'] != 24 ) {
	$toEnd .= "\n" . 'h3, .h3 { font-size: "' . intval( $prodoConfig['typography-headers-h3']['font-size'] ) . 'px"; }';
}

// H4
if ( $prodoConfig['typography-headers-h4']['font-family'] != $defaultFont ) {
	$toEnd .= "\n" . 'h4, .h4 { font-family: "' . esc_attr( $prodoConfig['typography-headers-h4']['font-family'] ) . '", sans-serif; }';
}
if ( $prodoConfig['typography-headers-h4']['font-size'] != 18 ) {
	$toEnd .= "\n" . 'h4, .h4 { font-size: "' . intval( $prodoConfig['typography-headers-h4']['font-size'] ) . 'px"; }';
}

// H5
if ( $prodoConfig['typography-headers-h5']['font-family'] != $defaultFont ) {
	$toEnd .= "\n" . 'h5, .h5 { font-family: "' . esc_attr( $prodoConfig['typography-headers-h5']['font-family'] ) . '", sans-serif; }';
}
if ( $prodoConfig['typography-headers-h5']['font-size'] != 14 ) {
	$toEnd .= "\n" . 'h5, .h5 { font-size: "' . intval( $prodoConfig['typography-headers-h5']['font-size'] ) . 'px"; }';
}

// H6
if ( $prodoConfig['typography-headers-h6']['font-family'] != $defaultFont ) {
	$toEnd .= "\n" . 'h6, .h6 { font-family: "' . esc_attr( $prodoConfig['typography-headers-h6']['font-family'] ) . '", sans-serif; }';
}
if ( $prodoConfig['typography-headers-h6']['font-size'] != 12 ) {
	$toEnd .= "\n" . 'h6, .h6 { font-size: "' . intval( $prodoConfig['typography-headers-h6']['font-size'] ) . 'px"; }';
}

if ( ! empty( $toEnd ) ) {
	$toEnd = '/' . '*** Fonts Override ***' . '/' . "\n" . $toEnd;
}
# / End

echo '@charset "UTF-8";' . "\n\n";

foreach ( $list as $file ) {
	if ( @is_readable( get_template_directory( ) . '/assets/css/' . $file . '.css' ) ) {
		$source = @file_get_contents( get_template_directory( ) . '/assets/css/' . $file . '.css' );

		if ( $source ) {
			if ( count( $find ) > 0 ) {
				echo preg_replace( $find, $replace, $source ) . "\n";
			} else {
				echo $source . "\n";
			}
		}
	}
}

if ( ! empty( $toEnd ) ) {
	echo "\n" . $toEnd;
}
?>