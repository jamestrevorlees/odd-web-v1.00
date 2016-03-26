<?php
/**
 * Created by PhpStorm.
 * User: jamestrevorlees
 * Date: 2016/03/26
 * Time: 10:19 AM
 */


add_action('wp_enqueue_scripts', 'odd1pt0_enqueue_styles');

function odd1pt0_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}
