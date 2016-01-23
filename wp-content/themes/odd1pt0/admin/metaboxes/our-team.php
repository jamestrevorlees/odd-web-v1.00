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
 
class ProdoTeam {
	# Initialization
	public static function init( ) {
		add_meta_box( 'axminenko_member_information', __( 'Member Information', 'prodo' ), array( 'ProdoTeam', 'content' ), 'our-team', 'normal', 'high' );
	}

	# Metabox
	public static function content( $post ) {
		wp_nonce_field( 'axminenko_nonce_safe', 'axminenko_nonce' );
		$meta = get_post_meta( $post->ID );

		$output = '
		<p><strong>' . __( 'Member Activity', 'prodo' ) . '</strong></p>
		<input type="text" style="width: 100%;" name="activity" value="' . esc_attr( $meta['activity'][0] ) . '">

		<p><strong>' . __( 'Twitter URL', 'prodo' ) . '</strong></p>
		<input type="text" style="width: 100%;" name="twitter" value="' . esc_attr( $meta['twitter'][0] ) . '">

		<p><strong>' . __( 'Facebook URL', 'prodo' ) . '</strong></p>
		<input type="text" style="width: 100%;" name="facebook" value="' . esc_attr( $meta['facebook'][0] ) . '">

		<p><strong>' . __( 'LinkedIn URL', 'prodo' ) . '</strong></p>
		<input type="text" style="width: 100%;" name="linkedin" value="' . esc_attr( $meta['linkedin'][0] ) . '">

		<p><strong>' . __( 'Google+ URL', 'prodo' ) . '</strong></p>
		<input type="text" style="width: 100%;" name="google" value="' . esc_attr( $meta['google'][0] ) . '">';

		echo $output;
	}

	# Save
	public static function save( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( ! isset( $_POST['axminenko_nonce'] ) || ! wp_verify_nonce( $_POST['axminenko_nonce'], 'axminenko_nonce_safe' ) ) return;
		if ( ! current_user_can( 'edit_posts' ) ) return;

		if ( isset( $_POST['activity'] ) ) {
			update_post_meta( $post_id, 'activity', sanitize_text_field( $_POST['activity'] ) );
		}
		if ( isset( $_POST['twitter'] ) ) {
			update_post_meta( $post_id, 'twitter', sanitize_text_field( $_POST['twitter'] ) );
		}
		if ( isset( $_POST['facebook'] ) ) {
			update_post_meta( $post_id, 'facebook', sanitize_text_field( $_POST['facebook'] ) );
		}
		if ( isset( $_POST['linkedin'] ) ) {
			update_post_meta( $post_id, 'linkedin', sanitize_text_field( $_POST['linkedin'] ) );
		}
		if ( isset( $_POST['google'] ) ) {
			update_post_meta( $post_id, 'google', sanitize_text_field( $_POST['google'] ) );
		}
	}
}

add_action( 'add_meta_boxes', array( 'ProdoTeam', 'init' ) );
add_action( 'save_post', array( 'ProdoTeam', 'save' ) );
