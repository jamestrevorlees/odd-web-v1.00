<?php

// Widget class.
class HT_KB_Categories_Widget extends WP_Widget {

    private $defaults;

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	* Specifies the classname and description, instantiates the widget,
	* loads localization files, and includes necessary stylesheets and JavaScript.
	*/
	public function __construct() {

    	// set classname and description
    	parent::__construct(
                	'ht-kb-categories-widget',
                	__( 'Knowledge Base Categories', 'ht-knowledge-base' ),
                	array(
                	'classname'	=>	'hkb_widget_categories',
                	'description'	=>	__( 'A widget for displaying Knowledge Base categories', 'ht-knowledge-base' )
                	)
    	);

        $default_widget_title = __( 'Knowledge Base Categories', 'ht-knowledge-base' );

        /*  default widget settings. */
        $this->defaults = array(
            'title' => $default_widget_title,
            'num' => '5',
            'sort_by' => 'name',
            'asc_sort_order' => '', 
            'hide_empty' => '', 
            'only_top_level' => '',
            'disp_article_count' => '',
        );


	} // end constructor


	/*-----------------------------------------------------------------------------------*/
	/*	Display Widget
	/*-----------------------------------------------------------------------------------*/
		
		function widget( $args, $instance ) {

            extract( $args, EXTR_SKIP );

            $instance = wp_parse_args( $instance, $this->defaults );
			
			$title = apply_filters('widget_title', $instance['title'] );

			$valid_sort_orders = array('count', 'name', 'id', 'slug' );
	        if ( in_array($instance['sort_by'], $valid_sort_orders) ) {
	          $sort_by = $instance['sort_by'];
	          $sort_order = (bool) $instance['asc_sort_order'] ? 'ASC' : 'DESC';
	        } else {
	          // by default, display alphabetically
	          $sort_by = 'name';
	          $sort_order = 'DESC';
	        }

	        $only_top_level = (bool) $instance['only_top_level'] ? 0 : '';
	        $hide_empty = (bool) $instance['hide_empty'] ? 1 : 0;
	        $disp_article_count = (bool) $instance['disp_article_count'] ? 1 : 0;

	        $num = empty($instance['num']) ? 5 : (int) $instance['num'];

			/* Before widget (defined by themes). */
			echo $before_widget;

			/* Display Widget */
			?> 
	        <?php /* Display the widget title if one was input (before and after defined by themes). */
					if ( $title )
						echo $before_title . $title . $after_title;
					?>
	                            
	                <?php
	                	$args = array(
						    'hide_empty'    => $hide_empty,
						    'number'		=> $num,
							'child_of' 		=> 0,
							'pad_counts' 	=> 1,
							'hierarchical'	=> 1,
							'parent'		=> $only_top_level,
							'orderby' 		=> $sort_by,
						  	'order' 		=> $sort_order
						); 

						$categories = get_terms('ht_kb_category', $args);
						
						echo '<ul class="hkb_category_widget__category_list">';
						 foreach($categories as $category) { 
						    echo '<li class="hkb_category_widget__category_item">';
						    if($disp_article_count){
						    	echo '<span class="hkb_category_widget__article_count">'. hkb_get_term_count($category) . '</span>';
						    }
						    echo '<a href="' . get_term_link( $category ) . '" title="' . sprintf( __( 'View all posts in %s', 'ht-knowledge-base' ), $category->name ) . '" ' . '>' . $category->name.'</a></li> ';
						 } 
						echo '</ul>';
						?>
								
								<?php

								/* After widget (defined by themes). */
								echo $after_widget;
		}


	/*-----------------------------------------------------------------------------------*/
	/*	Update Widget
	/*-----------------------------------------------------------------------------------*/
		
		function update( $new_instance, $old_instance ) {
			
			$instance = $old_instance;
			
			/* Strip tags to remove HTML (important for text inputs). */
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['sort_by'] = $new_instance['sort_by'];
      		$instance['asc_sort_order'] = $new_instance['asc_sort_order'] ? 1 : 0;
      		$instance['hide_empty'] = $new_instance['hide_empty'] ? 1 : 0;
      		$instance['only_top_level'] = $new_instance['only_top_level'] ? 1 : 0;
      		$instance['disp_article_count'] = $new_instance['disp_article_count'] ? 1 : 0;
      		$instance['num'] = $new_instance['num'];

			/* No need to strip tags for.. */

			return $instance;
		}
		

	/*-----------------------------------------------------------------------------------*/
	/*	Widget Settings
	/*-----------------------------------------------------------------------------------*/
		 
		function form( $instance ) {

			
			$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>
			
	        <!-- Widget Title: Text Input -->
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'ht-knowledge-base') ?></label>
				<input  type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			<p>
            <label for="<?php echo $this->get_field_id("num"); ?>">
              <?php _e( 'Number of categories to show', 'ht-knowledge-base' ); ?>
              :
              <input style="text-align: center;" id="<?php echo $this->get_field_id("num"); ?>" name="<?php echo $this->get_field_name("num"); ?>" type="text" value="<?php echo absint($instance["num"]); ?>" size='3' />
            </label>
          	</p>
          	<p>
            <label for="<?php echo $this->get_field_id("sort_by"); ?>">
              <?php _e( 'Sort by', 'ht-knowledge-base' ); ?>
              :
              <select id="<?php echo $this->get_field_id("sort_by"); ?>" name="<?php echo $this->get_field_name("sort_by"); ?>" class="ht-kb-widget-admin-dropdown">
                <option value="name"<?php selected( $instance["sort_by"], "name" ); ?>><?php _e( 'Name', 'ht-knowledge-base' ); ?></option>
                <option value="count"<?php selected( $instance["sort_by"], "count" ); ?>><?php _e( 'Number of articles', 'ht-knowledge-base' ); ?></option>
                <option value="slug"<?php selected( $instance["sort_by"], "slug" ); ?>><?php _e( 'Slug', 'ht-knowledge-base' ); ?></option>
                <option value="id"<?php selected( $instance["sort_by"], "id" ); ?>><?php _e( 'ID', 'ht-knowledge-base' ); ?></option>
              </select>
            </label>
          </p>
          <p>
            <label for="<?php echo $this->get_field_id("asc_sort_order"); ?>">
              <input type="checkbox" class="checkbox"
          id="<?php echo $this->get_field_id("asc_sort_order"); ?>"
          name="<?php echo $this->get_field_name("asc_sort_order"); ?>"
          <?php checked( (bool) $instance["asc_sort_order"], true ); ?> />
              <?php _e( 'Reverse sort order', 'ht-knowledge-base' ); ?>
            </label>
          </p>
          <p>
            <label for="<?php echo $this->get_field_id("hide_empty"); ?>">
              <input type="checkbox" class="checkbox"
          id="<?php echo $this->get_field_id("hide_empty"); ?>"
          name="<?php echo $this->get_field_name("hide_empty"); ?>"
          <?php checked( (bool) $instance["hide_empty"], true ); ?> />
              <?php _e( 'Hide empty categories', 'ht-knowledge-base' ); ?>
            </label>
          </p>
          <p>
            <label for="<?php echo $this->get_field_id("only_top_level"); ?>">
              <input type="checkbox" class="checkbox"
          id="<?php echo $this->get_field_id("only_top_level"); ?>"
          name="<?php echo $this->get_field_name("only_top_level"); ?>"
          <?php checked( (bool) $instance["only_top_level"], true ); ?> />
              <?php _e( 'Only top level categories', 'ht-knowledge-base' ); ?>
            </label>
          </p>
          <p>
            <label for="<?php echo $this->get_field_id("disp_article_count"); ?>">
              <input type="checkbox" class="checkbox"
          id="<?php echo $this->get_field_id("disp_article_count"); ?>"
          name="<?php echo $this->get_field_name("disp_article_count"); ?>"
          <?php checked( (bool) $instance["disp_article_count"], true ); ?> />
              <?php _e( 'Display article count', 'ht-knowledge-base' ); ?>
            </label>
          </p>
		
			<?php
		}
} //end class


// Remember to change 'Widget_Name' to match the class name definition
add_action( 'widgets_init', create_function( '', 'register_widget("HT_KB_Categories_Widget");' ) );
