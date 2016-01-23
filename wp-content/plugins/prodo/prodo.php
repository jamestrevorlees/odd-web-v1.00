<?php
/*
Plugin Name: Prodo Theme Features
Description: Core features for the Prodo theme.
Version: 1.4
Author: Alexander Axminenko
Author URI: https://www.facebook.com/a.axminenko
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: prodo
*/

if ( ! function_exists( 'add_action' ) ) {
	echo 'Hi there! I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'PRODO_PLUGIN_VERSION', '1.4' );
define( 'PRODO_PLUGIN_DIR',      plugin_dir_path( __FILE__ ) );

# Localization
function prodoPluginLocalization( ) {
	load_plugin_textdomain( 'prodo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'prodoPluginLocalization' );

# Include Features
require_once PRODO_PLUGIN_DIR . 'shortcodes/class.shortcodes.php';
require_once PRODO_PLUGIN_DIR . 'posts/class.posts.php';
require_once PRODO_PLUGIN_DIR . 'options/class.options.php';