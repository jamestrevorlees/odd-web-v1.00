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
 
class ProdoSubtitle {
	# Initialization
	public static function init( ) {
		global $post;

		if ( $post !== null and get_post_meta( $post->ID, '_wp_page_template', true ) != 'templates/front.php' ) {
			add_meta_box( 'axminenko_subtitle', __( 'Visual Subtitle', 'prodo' ), array( 'ProdoSubtitle', 'content' ), 'page', 'side' );
			add_meta_box( 'axminenko_subtitle', __( 'Visual Subtitle', 'prodo' ), array( 'ProdoSubtitle', 'content' ), 'portfolio', 'normal' );
		}
	}

	# Metabox
	public static function content( $post ) {
		wp_nonce_field( 'axminenko_nonce_safe', 'axminenko_nonce' );
		$meta = get_post_meta( $post->ID );

		$subtitle = '';
		if ( isset( $meta['subtitle'] ) and isset( $meta['subtitle'][0] ) ) {
			$subtitle = $meta['subtitle'][0];
		}

		$output = '
		<div style="padding-top:15px">
			<input type="text" style="width: 100%;" name="subtitle" value="' . esc_attr( $subtitle ) . '">
			<p>' . __( 'Example', 'prodo' ) . ', <strong>' . __( 'Lorem ipsum dolor sit amet.', 'prodo' ) . '</strong></p>
		</div>';

		echo $output;
	}

	# Save
	public static function save( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( ! isset( $_POST['axminenko_nonce'] ) || ! wp_verify_nonce( $_POST['axminenko_nonce'], 'axminenko_nonce_safe' ) ) return;
		if ( ! current_user_can( 'edit_posts' ) ) return;

		if ( isset( $_POST['subtitle'] ) ) {
			update_post_meta( $post_id, 'subtitle', sanitize_text_field( $_POST['subtitle'] ) );
		}
	}
}

add_action( 'add_meta_boxes', array( 'ProdoSubtitle', 'init' ) );
add_action( 'save_post', array( 'ProdoSubtitle', 'save' ) );
