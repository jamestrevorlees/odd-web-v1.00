<?php

/**
* Include and setup custom metaboxes and fields.
*/

if ( file_exists( dirname( HT_KB_MAIN_PLUGIN_FILE ) . '/cmb2/init.php' ) ) {
	require_once dirname( HT_KB_MAIN_PLUGIN_FILE ) . '/cmb2/init.php';
} elseif ( file_exists(  dirname( HT_KB_MAIN_PLUGIN_FILE ) . '/CMB2/init.php' ) ) {
	require_once  dirname( HT_KB_MAIN_PLUGIN_FILE ) . '/CMB2/init.php';
}

if (!class_exists('HT_Knowledge_Base_Meta_Boxes')) {

    class HT_Knowledge_Base_Meta_Boxes {

    	//Constructor
    	public function __construct() {
    		add_filter( 'cmb2_init', array( $this, 'ht_knowledge_base_register_meta_boxes') );
    	 }

    	 /**
		 * Register meta boxes
		 * @uses the meta-boxes module
		 * @param (Array) $meta_boxes The exisiting metaboxes
		 * @param (Array) Filtered metaboxes
		 */
		function ht_knowledge_base_register_meta_boxes() {

			$prefix = '_ht_knowledge_base_';

			$ht_kb_article_options_metabox = new_cmb2_box( array(
				'id'           => $prefix . 'metabox',
				'title' 		=> __( 'Article Options', 'ht-knowledge-base' ),
				'object_types' => array( 'ht_kb', ), // Post type
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true, // Show field names on the left

			) );

			$ht_kb_article_options_metabox->add_field( array(
				'name' => 'update_dummy',
				'id'   => $prefix .'updade_dummy',
				'type' => 'title',
				'show_on_cb' => array( $this, 'maybe_upgrade_meta_fields' ),
			) );

			$ht_kb_article_options_metabox->add_field( array(
				'name' => __( 'Attachments', 'ht-knowledge-base' ),
				'description' => __( 'Add attachments to this article', 'ht-knowledge-base' ),
				'id'   => $prefix .'file_advanced',
				'type' => 'file_list',
				'max_file_uploads' => 4,
				'mime_type' => '', // Leave blank for all file types
			) );

			$ht_kb_article_options_metabox->add_field( array(
				'name' => __( 'View Count', 'ht-knowledge-base' ),
				'description' => __( 'Set the view count for this article', 'ht-knowledge-base' ),
				'id'   => HT_KB_POST_VIEW_COUNT_KEY,
				'type' => 'text',
				'default' => 1,
				'sanitization_cb' => array($this, 'santize_view_count_field'), // custom sanitization callback parameter
			) );

		}


		function santize_view_count_field($new_value, $args, $field){
			$old_value = $field->value();
			if( preg_match('/^\d+$/', $new_value ) ){
				return (int) $new_value;
			} else {
				return $old_value;
			}			
		}

		function my_admin_notice() {
			?>
			<div class="updated">
				<p><?php _e( 'Updated!', 'ht-knowledge-base' ); ?></p>
			</div>
			<?php
		}


		/**
		 * Upgrade the meta key values.
		 */
		function maybe_upgrade_meta_fields(){
			ht_kb_upgrade_article_meta_fields( get_the_ID() );
			//return a false so the dummy does not display
			return false;
		}




    } //end class

}//end class exists


//run the module
if(class_exists('HT_Knowledge_Base_Meta_Boxes')){
	$ht_knowledge_base_meta_boxes_init = new HT_Knowledge_Base_Meta_Boxes();
}
