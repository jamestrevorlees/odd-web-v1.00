<?php
/*
Plugin Name: The Events Calendar: Filter Bar
Description: Creates an advanced filter panel on the frontend of your events list views.
Version: 4.0.3
Author: Modern Tribe, Inc.
Author URI: http://m.tri.be/25
Text Domain: tribe-events-filter-view
License: GPLv2
*/

/*
Copyright 2012 Modern Tribe Inc. and the Collaborators

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * Function used to load the the Filters View addon.
 *
 * @since 3.4
 * @author PaulHughes01
 *
 * @return void
 */
function TribeEventsFilterViewsLoad() {

	tribe_init_filterbar_autoloading();

	$classes_exist = class_exists( 'Tribe__Events__Main' ) && class_exists( 'Tribe__Events__Filterbar__View' );
	$version_ok = $classes_exist && version_compare( Tribe__Events__Main::VERSION, Tribe__Events__Filterbar__View::REQUIRED_TEC_VERSION, '>=' );

	if ( ! ( $classes_exist && $version_ok ) ) {
		add_action( 'admin_notices', 'tribe_events_filter_view_show_fail_message' );

		return;
	}

	add_filter( 'tribe_tec_addons', array( 'Tribe__Events__Filterbar__View', 'initAddon' ) );
	Tribe__Events__Filterbar__View::init( __FILE__ );
	new Tribe__Events__Filterbar__PUE( __FILE__ );
}

/**
 * Shows message if the plugin can't load due to TEC not being installed.
 *
 * @since 3.4
 * @author PaulHughes01
 *
 * @return void
 */
function tribe_events_filter_view_show_fail_message() {
	if ( current_user_can( 'activate_plugins' ) ) {
		$url = 'plugin-install.php?tab=plugin-information&plugin=the-events-calendar&TB_iframe=true';
		$title = __( 'The Events Calendar', 'tribe-events-filter-view' );
		echo '<div class="error"><p>' . sprintf( __( 'To begin using The Events Calendar: Filter Bar, please install the latest version of <a href="%s" class="thickbox" title="%s">The Events Calendar</a>.', 'tribe-events-filter-view' ), esc_url( $url ), esc_attr( $title ) ) . '</p></div>';
	}
}

add_action( 'plugins_loaded', 'TribeEventsFilterViewsLoad', 10 );

/**
 * Requires the autoloader class from the main plugin class and sets up
 * autoloading.
 */
function tribe_init_filterbar_autoloading() {
	if ( ! class_exists( 'Tribe__Autoloader' ) ) {
		return;
	}

	$autoloader = Tribe__Autoloader::instance();

	$autoloader->register_prefix( 'Tribe__Events__Filterbar__', dirname( __FILE__ ) . '/src/Tribe' );

	// deprecated classes are registered in a class to path fashion
	foreach ( glob( dirname( __FILE__ ) . '/src/deprecated/*.php' ) as $file ) {
		$class_name = str_replace( '.php', '', basename( $file ) );
		$autoloader->register_class( $class_name, $file );
	}
	$autoloader->register_autoloader();
}
