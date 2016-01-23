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

# Content Width
if ( ! isset( $content_width ) ) {
	$content_width = 750;
}

# Localize
include 'inc/languages.php';

# TGM Plugin Activation
include 'inc/tgm/tgm.plugin.activation.php';
include 'inc/tgm/plugins.php';

# Admin Panel
include 'admin/metaboxes/home-sections/home-sections.php';
include 'admin/metaboxes/our-team.php';
include 'admin/metaboxes/subtitle.php';

# Redux Framework
include 'admin/redux.php';

# Defaults
include 'inc/defaults.php';

# Widgets
include 'inc/widgets/contact.php';

# Theme Files
include 'inc/menu.php';
include 'inc/functions.php';
include 'inc/register.php';
include 'inc/custom.php';
include 'inc/twitter.php';