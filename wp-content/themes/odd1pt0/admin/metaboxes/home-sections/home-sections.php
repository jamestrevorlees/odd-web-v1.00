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

function prodo_home_section_meta( ) {
	global $post;

	if ( $post !== null and get_post_meta( $post->ID, '_wp_page_template', true ) == 'templates/front.php' ) {
		add_meta_box( 'prodo_home_section', __( 'Home Section', 'prodo' ), 'prodo_home_section_callback', 'page', 'normal', 'high' );
		remove_post_type_support( 'page', 'editor' );
		remove_post_type_support( 'page', 'revisions' );
	}
}

function prodo_home_section_callback( $post ) {
	# Styles
	wp_register_style( 'prodo-home-sections', get_template_directory_uri( ) . '/admin/metaboxes/home-sections/styles.css' );
	wp_enqueue_style( 'prodo-home-sections' );

	# Scripts
	wp_register_script( 'prodo-home-sections', get_template_directory_uri( ) . '/admin/metaboxes/home-sections/functions.js', array( ), false, true );
	wp_localize_script( 'prodo-home-sections', 'prodo_home_lng', array(
		'insert_media' => __( 'Insert Media', 'prodo' ),
		'image'        => __( 'Image', 'prodo' ),
		'remove'       => __( 'Remove', 'prodo' )
	) );
	wp_enqueue_script( 'prodo-home-sections' );

	# Core
	wp_nonce_field( 'theme_nonce_safe', 'theme_nonce' );
	$meta = get_post_meta( $post->ID );

	if ( isset( $meta['header-section'] ) and ! empty( $meta['header-section'][0] ) and $meta['header-section'][0] != 'none' ) {
		$header_section = $meta['header-section'][0];
	} else {
		$header_section = false;
	}

	$section_height = get_post_meta( $post->ID, 'section-height', true );

	if ( empty( $section_height ) ) {
		$section_height = '100%';
	}
	?>

	<p><strong><?php _e( 'Section Type', 'prodo' ); ?></strong></p>
	<select name="header-section" class="meta-item-m" id="header-section">
		<option value="none" <?php if ( ! isset( $meta['header-section'] ) or $meta['header-section'] == 'none' ) echo ' selected="selected"'; ?>><?php _e( 'None', 'prodo' ); ?></option>
		<option value="video" <?php if ( isset( $meta['header-section'] ) ) selected( $meta['header-section'][0], 'video' ); ?>><?php _e( 'Video Background', 'prodo' ); ?></option>
		<option value="pattern" <?php if ( isset( $meta['header-section'] ) ) selected( $meta['header-section'][0], 'pattern' ); ?>><?php _e( 'Image Pattern', 'prodo' ); ?></option>
		<option value="image" <?php if ( isset( $meta['header-section'] ) ) selected( $meta['header-section'][0], 'image' ); ?>><?php _e( 'Fullscreen Image', 'prodo' ); ?></option>
		<option value="embed-video" <?php if ( isset( $meta['header-section'] ) ) selected( $meta['header-section'][0], 'embed-video' ); ?>><?php _e( 'Fullscreen Image and Video', 'prodo' ); ?></option>
		<option value="slideshow-static" <?php if ( isset( $meta['header-section'] ) ) selected( $meta['header-section'][0], 'slideshow-static' ); ?>><?php _e( 'Image Slideshow', 'prodo' ); ?></option>
		<option value="slideshow" <?php if ( isset( $meta['header-section'] ) ) selected( $meta['header-section'][0], 'slideshow' ); ?>><?php _e( 'Slideshow and Text Slider', 'prodo' ); ?></option>
	</select>

	<p><strong><?php _e( 'Section Height', 'prodo' ); ?></strong></p>
	<input type="text" class="meta-item-m" name="section-height" value="<?php echo esc_attr( $section_height ); ?>">
	<p><?php _e( 'Example, <strong>100%</strong> &ndash; in percents, <strong>700px</strong> &ndash; in pixels' ); ?></p>

	<div data-header-section="video" <?php echo ( $header_section != 'video' ? 'style="display: none;"' : '' ); ?>>
		<p><strong><?php _e( 'Background Video ID', 'prodo' ); ?></strong></p>
		<input type="text" class="meta-item-m" name="video-id" value="<?php echo esc_attr( get_post_meta( $post->ID, 'video-id', true ) ); ?>">
		<p><?php _e( 'Example', 'prodo' ); ?>, https://www.youtube.com/watch?v=<strong>kn-1D5z3-Cs</strong></p>
		<div style="margin-top:20px">
			<hr>
			<div>
				<?php
				wp_editor( empty( $meta['content-video'][0] ) ? '' : $meta['content-video'][0], 'content_video', array( 'textarea_name' => 'content-video', 'media_buttons' => false, 'textarea_rows' => 15 ) );
				?>
			</div>
		</div>
	</div>

	<div data-header-section="pattern" <?php echo ( $header_section != 'pattern' ? 'style="display: none;"' : '' ); ?>>
		<p><strong><?php _e( 'Background Pattern', 'prodo' ); ?></strong></p>
		<input type="text" class="meta-item-l" name="image-pattern" id="image-pattern-field" value="<?php echo esc_attr( get_post_meta( $post->ID, 'image-pattern', true ) ); ?>">
		<input type="button" class="button meta-item-upload" data-area="#image-pattern-field" value="<?php esc_attr_e( 'Choose or Upload an Image', 'prodo' ); ?>">
		<div style="margin-top:20px">
			<hr>
			<div>
				<?php
				wp_editor( empty( $meta['content-pattern'][0] ) ? '' : $meta['content-pattern'][0], 'content_pattern', array( 'textarea_name' => 'content-pattern', 'media_buttons' => false, 'textarea_rows' => 15 ) );
				?>
			</div>
		</div>
	</div>

	<div data-header-section="image" <?php echo ( $header_section != 'image' ? 'style="display: none;"' : '' ); ?>>
		<p><strong><?php _e( 'Background Image', 'prodo' ); ?></strong></p>
		<input type="text" class="meta-item-l" name="static-image" id="static-image-field" value="<?php echo esc_attr( get_post_meta( $post->ID, 'static-image', true ) ); ?>">
		<input type="button" class="button meta-item-upload" data-area="#static-image-field" value="<?php esc_attr_e( 'Choose or Upload an Image', 'prodo' ); ?>">
		<div style="margin-top:20px">
			<hr>
			<div>
				<?php
				wp_editor( empty( $meta['content-image'][0] ) ? '' : $meta['content-image'][0], 'content_image', array( 'textarea_name' => 'content-image', 'media_buttons' => false, 'textarea_rows' => 15 ) );
				?>
			</div>
		</div>
	</div>

	<div data-header-section="embed-video" <?php echo ( $header_section != 'embed-video' ? 'style="display: none;"' : '' ); ?>>
		<p><strong><?php _e( 'Embed Video URL', 'prodo' ); ?></strong></p>
		<input type="text" class="meta-item-l" name="embed-video" value="<?php echo esc_attr( get_post_meta( $post->ID, 'embed-video', true ) ); ?>">
		<p><?php _e( 'Example', 'prodo' ); ?>, https://www.youtube.com/embed/kn-1D5z3-Cs</p>

		<p><strong><?php _e( 'Video Preview Image', 'prodo' ); ?></strong></p>
		<input type="text" class="meta-item-l" name="embed-video-preview" id="embed-video-preview-field" value="<?php echo esc_attr( get_post_meta( $post->ID, 'embed-video-preview', true ) ); ?>">
		<input type="button" class="button meta-item-upload" data-area="#embed-video-preview-field" value="<?php esc_attr_e( 'Choose or Upload an Image', 'prodo' ); ?>">
		<p><?php _e( '210x140, or 420x280 for Retina displays', 'prodo' ); ?></p>

		<p><strong><?php _e( 'Background Image', 'prodo' ); ?></strong></p>
		<input type="text" class="meta-item-l" name="embed-image" id="embed-image-field" value="<?php  echo esc_attr( get_post_meta( $post->ID, 'embed-image', true ) ); ?>">
		<input type="button" class="button meta-item-upload" data-area="#embed-image-field" value="<?php esc_attr_e( 'Choose or Upload an Image', 'prodo' ); ?>">

		<div style="margin-top:20px">
			<hr>
			<div>
				<?php
				wp_editor( empty( $meta['content-embed'][0] ) ? '' : $meta['content-embed'][0], 'content_embed', array( 'textarea_name' => 'content-embed', 'media_buttons' => false, 'textarea_rows' => 15 ) );
				?>
			</div>
		</div>
	</div>

	<div data-header-section="slideshow-static" <?php echo ( $header_section != 'slideshow-static' ? 'style="display: none;"' : '' ); ?>>
		<div style="margin-bottom:20px">
			<p><strong><?php _e( 'Background Images', 'prodo' ); ?></strong></p>
			<input type="button" class="button meta-item-upload" data-area="#slideshow-static-fields" data-multiple="true" value="<?php esc_attr_e( 'Choose or Upload Images', 'prodo' ); ?>">
		</div>

		<div id="slideshow-static-fields">
			<?php
			if ( ! empty( $meta['slideshow-alt-images'][0] ) ) {
				$explode = explode( ',', $meta['slideshow-alt-images'][0] );
				if ( count( $explode ) > 0 ) {
					$i = 0;
					foreach ( $explode as $name ) {
						if ( ! empty( $name ) ) {
							$i ++;
							?>
							<div style="padding-bottom:10px" class="meta-item-row-alt">
								<?php _e( 'Image', 'prodo' ); ?> <span class="meta-item-row-alt-c"><?php echo $i; ?></span>
								<input type="text" class="meta-item-l alt" name="slideshow-alt-images[]" value="<?php echo esc_attr( $name ); ?>">
								<input type="button" class="button" data-remove-image="true" value="<?php esc_attr_e( 'Remove', 'prodo' ); ?>">
							</div>
							<?php
						}
					}
				}
			}
			?>
		</div>

		<hr>
		<div>
			<?php
			wp_editor( empty( $meta['content-slideshow-alt'][0] ) ? '' : $meta['content-slideshow-alt'][0], 'content_slideshow_alt', array( 'textarea_name' => 'content-slideshow-alt', 'media_buttons' => false, 'textarea_rows' => 15 ) );
			?>
		</div>
	</div>

	<div data-header-section="slideshow" <?php echo ( $header_section != 'slideshow' ? 'style="display: none;"' : '' ); ?>>
		<div id="slideshow-add-button">
			<p><strong><?php _e( 'Background Images', 'prodo' ); ?></strong></p>
			<input type="button" class="button meta-item-upload" data-area="#slideshow-fields" data-multiple="true" value="<?php esc_attr_e( 'Choose or Upload Images', 'prodo' ); ?>">
		</div>
		<div id="slideshow-fields">
			<?php
			$limit = 20;
			for ( $i = 1; $i <= $limit; $i ++ ) {
				?>
				<div style="margin-top:20px;display:none" class="meta-item-row" id="slideshow-field-<?php echo $i; ?>">
					<hr>
					<div>
						<?php
						wp_editor( empty( $meta['slideshow-slide-'.$i][0] ) ? '' : $meta['slideshow-slide-'.$i][0], 'slideshow_slide_' . $i, array( 'textarea_name' => 'slideshow-slide[' . $i . ']', 'media_buttons' => false, 'textarea_rows' => 15 ) );
						?>
						<p>
							<?php _e( 'Background Image', 'prodo' ); ?>
							<input type="text" class="meta-item-l alt" name="slideshow-field[<?php echo $i; ?>]" value="" />
						</p>
						<p><input type="button" value="<?php esc_attr_e( 'Remove Slide', 'prodo' ); ?>" class="button" data-remove-slide="true"></p>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>

	<script>
	/* <![CDATA[ */
	var prodoSlidesContent = [];
	<?php
	if ( ! empty( $meta['slideshow-images'][0] ) ) {
		$i = 0;
		$explode = explode( ',', $meta['slideshow-images'][0] );

		if ( count( $explode ) > 0 ) {
			foreach ( $explode as $name ) {
				$i ++;

				if ( ! empty( $name ) ) {
					?>
					prodoSlidesContent[prodoSlidesContent.length] = { id: <?php echo $i; ?>, url: '<?php echo esc_js( $name ); ?>' };
					<?php
				}
			}
		}
	}
	?>
	/* ]]> */
	</script>

	<?php
}

function prodo_home_section_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['theme_nonce'] ) || ! wp_verify_nonce( $_POST['theme_nonce'], 'theme_nonce_safe' ) ) return;
	if ( ! current_user_can( 'edit_posts' ) ) return;
	
	// Home Section Type
	if ( isset( $_POST['header-section'] ) ) {
		update_post_meta( $post_id, 'header-section', sanitize_text_field( $_POST['header-section'] ) );
	}

	// Section Height
	if ( isset( $_POST['section-height'] ) ) {
		update_post_meta( $post_id, 'section-height', sanitize_text_field( $_POST['section-height'] ) );
	}

	// Video Background
	if ( $_POST['header-section'] == 'video' ) {
		if ( isset( $_POST['video-id'] ) ) {
			update_post_meta( $post_id, 'video-id', sanitize_text_field( $_POST['video-id'] ) );
		}
		if ( isset( $_POST['content-video'] ) ) {
			update_post_meta( $post_id, 'content-video', wp_kses_post( $_POST['content-video'] ) );
		}
	}

	// Image Pattern
	if ( $_POST['header-section'] == 'pattern' ) {
		if ( isset( $_POST['image-pattern'] ) ) {
			update_post_meta( $post_id, 'image-pattern', sanitize_text_field( $_POST['image-pattern'] ) );
		}
		if ( isset( $_POST['content-pattern'] ) ) {
			update_post_meta( $post_id, 'content-pattern', wp_kses_post( $_POST['content-pattern'] ) );
		}
	}

	// Fullscreen Image
	if ( $_POST['header-section'] == 'image' ) {
		if ( isset( $_POST['static-image'] ) ) {
			update_post_meta( $post_id, 'static-image', sanitize_text_field( $_POST['static-image'] ) );
		}
		if ( isset( $_POST['content-image'] ) ) {
			update_post_meta( $post_id, 'content-image', wp_kses_post( $_POST['content-image'] ) );
		}
	}

	// Fullscreen Image and Video
	if ( $_POST['header-section'] == 'embed-video' ) {
		if ( isset( $_POST['embed-video'] ) ) {
			update_post_meta( $post_id, 'embed-video', sanitize_text_field( $_POST['embed-video'] ) );
		}
		if ( isset( $_POST['embed-video-preview'] ) ) {
			update_post_meta( $post_id, 'embed-video-preview', sanitize_text_field( $_POST['embed-video-preview'] ) );
		}
		if ( isset( $_POST['embed-image'] ) ) {
			update_post_meta( $post_id, 'embed-image', sanitize_text_field( $_POST['embed-image'] ) );
		}
		if ( isset( $_POST['content-embed'] ) ) {
			update_post_meta( $post_id, 'content-embed', wp_kses_post( $_POST['content-embed'] ) );
		}
	}

	// Slideshow
	if ( $_POST['header-section'] == 'slideshow-static' ) {
		if ( isset( $_POST['content-slideshow-alt'] ) ) {
			update_post_meta( $post_id, 'content-slideshow-alt', wp_kses_post( $_POST['content-slideshow-alt'] ) );
		}
		if ( isset( $_POST['slideshow-alt-images'] ) ) {
			update_post_meta( $post_id, 'slideshow-alt-images', sanitize_text_field( implode( ',', $_POST['slideshow-alt-images'] ) ) );
		} else update_post_meta( $post_id, 'slideshow-alt-images', '' );
	}

	// Slideshow and Text Slider
	if ( $_POST['header-section'] == 'slideshow' ) {
		if ( isset( $_POST['slideshow-field'] ) and count( $_POST['slideshow-field'] ) > 0 ) {
			update_post_meta( $post_id, 'slideshow-images', sanitize_text_field( implode( ',', $_POST['slideshow-field'] ) ) );
		} else if ( $_POST['header-section'] == 'slideshow' ) {
			update_post_meta( $post_id, 'slideshow-images', '' );
		}

		if ( isset( $_POST['slideshow-slide'] ) and count( $_POST['slideshow-slide'] ) > 0 ) {
			$i = 0;
			$string = '';
			$array = array( );

			foreach ( $_POST['slideshow-slide'] as $text ) {
				$i ++;

				if ( ! empty( $text ) or ! empty( $_POST['slideshow-field'][$i] ) ) {
					update_post_meta( $post_id, 'slideshow-slide-' . $i, wp_kses_post( $text ) );
				} else if ( empty( $text ) and empty( $_POST['slideshow-field'][$i] ) ) {
					update_post_meta( $post_id, 'slideshow-slide-' . $i, wp_kses_post( $text ) );
				}
			}
		}
	}
}

add_action( 'add_meta_boxes', 'prodo_home_section_meta' );
add_action( 'save_post', 'prodo_home_section_save' );
