<?php 

/*
* Adds additional meta fields to the ht_kb_category taxonomy
*/


if( !class_exists( 'HT_Knowledge_Base_Category_Meta' ) ){
	class HT_Knowledge_Base_Category_Meta {

		//Constructor
		function __construct(){
			//add and edit
			add_action( 'ht_kb_category_add_form_fields', array($this, 'ht_kb_taxonomy_add_new_meta_field'), 10, 2 );
			add_action( 'ht_kb_category_edit_form_fields', array($this, 'ht_kb_taxonomy_edit_meta_field'), 10, 2 );

			//save
			add_action( 'edited_ht_kb_category', array( $this, 'ht_kb_save_meta' ), 10, 2 );  
			add_action( 'create_ht_kb_category', array( $this, 'ht_kb_save_meta' ), 10, 2 );

			//enqueue scripts
			add_action( 'ht_kb_category_add_form_fields', array($this, 'ht_kb_taxonomy_add_meta_scripts_and_styles') );
			add_action( 'ht_kb_category_edit_form_fields', array($this, 'ht_kb_taxonomy_add_meta_scripts_and_styles') );
		}

		/**
		* Add the meta fields to category creation section
		*/
		function ht_kb_taxonomy_add_new_meta_field() {
			// this will add the custom meta field to the add new term page
			$default_preview = plugins_url( 'img/no-image.png', dirname(__FILE__) );
			?>
			<?php if( current_theme_supports( 'ht_kb_category_icons' ) || current_theme_supports( 'ht-kb-category-icons' ) ): ?>
				<div class="form-field">
				<p>
	    			<label for="term_meta[meta_image]" class="meta-row"><?php _e( 'Category Image', 'ht-knowledge-base' )?></label>
	    			<img src="<?php echo $default_preview; ?>" id="meta-image-preview"  />
	    			<br/>
	    			<input type="hidden" name="term_meta[meta_image]" id="meta-image" value="" placeholder="<?php _e( 'attachment ID', 'ht-knowledge-base' ); ?>" />
	    			<input type="button" id="meta-image-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'ht-knowledge-base' ); ?>" />
	    			<input type="button" id="meta-image-remove" class="button" value="<?php _e( 'Remove Image', 'ht-knowledge-base' )?>" />
				</p>
				</div>
			<?php endif; //theme supports icons ?>
			<?php if( current_theme_supports( 'ht_kb_category_colors' ) ): ?>
				<div class="form-field">
				<p>
	    			<label for="term_meta[meta_color]" class="meta-row"><?php _e( 'Category Color', 'ht-knowledge-base' )?></label>
	    			<input type="text" name="term_meta[meta_color]" class="meta-color" value="#000000"  />
				</p>
				</div>
			<?php endif; //theme supports colors ?>

		<?php
		}
				
		/**
		* Add the meta fields to category editor screen
		* @param (Object) The WordPress term 
		*/
		function ht_kb_taxonomy_edit_meta_field($term) {
		 
			// put the term ID into a variable
			$t_id = $term->term_id;
		 
			// retrieve the existing value(s) for this meta field. This returns an array
			$term_meta = get_option( "taxonomy_$t_id" );

			$default_preview = plugins_url( 'img/no-image.png', dirname(__FILE__) );

			//get the attachment thumb array
			$attachment_thumb = ( isset ( $term_meta['meta_image'] ) ) ? wp_get_attachment_image_src( $term_meta['meta_image'], 'thumbnail' ) : null ;
			
			$thumbnail_url = ( !empty($attachment_thumb) ) ? $attachment_thumb[0] : $default_preview;

			?>
			<?php if( current_theme_supports( 'ht_kb_category_icons' ) || current_theme_supports( 'ht-kb-category-icons' ) ): ?>
				<tr class="form-field">
				<th scope="row" valign="top"><label for="term_meta[meta_image]"><?php _e( 'Category Image', 'ht-knowledge-base' ); ?></label></th>
					<td>
						<img src="<?php echo $thumbnail_url ?>" id="meta-image-preview"  />
						<input type="hidden" name="term_meta[meta_image]" id="meta-image" value="<?php echo esc_attr( $term_meta['meta_image'] ) ? esc_attr( $term_meta['meta_image'] ) : ''; ?>">
						<input type="button" id="meta-image-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'ht-knowledge-base' )?>" />
						<input type="button" id="meta-image-remove" class="button" value="<?php _e( 'Remove Image', 'ht-knowledge-base' )?>" />
						<p class="description"><?php _e( 'This will be displayed in various places in the Knowledge Base','ht-knowledge-base' ); ?></p>
					</td>
				</tr>
			<?php endif; //theme supports icons ?>
			<?php if( current_theme_supports( 'ht_kb_category_colors' ) ): ?>
				<tr class="form-field">
				<th scope="row" valign="top"><label for="term_meta[meta_color]"><?php _e( 'Category Color', 'ht-knowledge-base' ); ?></label></th>
					<td>
						<p>
	    					<input name="term_meta[meta_color]" type="text" value="<?php if ( isset ( $term_meta['meta_color'] ) ) echo $term_meta['meta_color']; ?>" class="meta-color" />
						</p>
					</td>
				</tr>
			<?php endif; //theme supports colors ?>
		<?php
		}

		/**
		* Enqueue the javascript and styles for category meta functionality
		*/
		function ht_kb_taxonomy_add_meta_scripts_and_styles(){
			//$screen = get_current_screen();

			$default_preview = plugins_url( 'img/no-image.png', dirname(__FILE__) );
			
			wp_enqueue_media();

			$ajax_error_string = __('Error saving orders', 'ht-knowledge-base');
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'ht-kb-category-meta-script', plugins_url( 'js/hkb-admin-category-meta-js.js', dirname(__FILE__) ), array( 'jquery', 'wp-color-picker' ), 1.0, true );	
			wp_localize_script( 'ht-kb-category-meta-script', 'meta_image',
            						array(
                						'title' => __( 'Choose or Upload an Image', 'ht-knowledge-base' ),
                						'button' => __( 'Use this image', 'ht-knowledge-base' ),
                						'no_image' => $default_preview
            							)
        						);	
		}

		/**
		* Update the category meta on save
		* @param (Int) $term_id The term ID
		*/
		function ht_kb_save_meta( $term_id ) {
			if ( isset( $_POST['term_meta'] ) ) {
				$t_id = $term_id;
				$term_meta = get_option( "taxonomy_$t_id" );
				$cat_keys = array_keys( $_POST['term_meta'] );
				foreach ( $cat_keys as $key ) {
					if ( isset ( $_POST['term_meta'][$key] ) ) {
						$term_meta[$key] = $_POST['term_meta'][$key];
					}
				}
				// Save the option array.
				update_option( "taxonomy_$t_id", $term_meta );
			}
		}  

	} //end class
} //end class exists

//run the module
if( class_exists( 'HT_Knowledge_Base_Category_Meta' ) ){
	$ht_knowledge_base_category_order = new HT_Knowledge_Base_Category_Meta();

}