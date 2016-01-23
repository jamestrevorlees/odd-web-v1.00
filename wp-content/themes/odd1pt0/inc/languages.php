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

# Localization
function prodoLocalization( ) {
	load_theme_textdomain( 'prodo', get_template_directory( ) . '/languages' );
}

add_action( 'after_setup_theme', 'prodoLocalization' );
