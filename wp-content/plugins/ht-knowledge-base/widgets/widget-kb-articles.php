<?php

class HT_KB_Articles_Widget extends WP_Widget {

    private $defaults;

    /*--------------------------------------------------*/
    /* Constructor
    /*--------------------------------------------------*/

    /**
    * Specifies the classname and description, instantiates the widget,
    * loads localization files, and includes necessary stylesheets and JavaScript.
    */
    public function __construct() {

        //update classname and description
        parent::__construct(
            'ht-kb-articles-widget',
            __( 'Knowledge Base Articles', 'ht-knowledge-base' ),
            array(
              'classname'	=>	'hkb_widget_articles',
              'description'	=>	__( 'A widget for displaying Knowledge Base articles', 'ht-knowledge-base' )
            )
        );

        $default_widget_title = __('Knowledge Base Articles', 'ht-knowledge-base');

        $this->defaults = array(
            'title' => $default_widget_title,
            'num' => '5',
            'sort_by' => '',
            'asc_sort_order' => '',
            'comment_num' => '',
            'rating' => '',
            'category' => 'all',
            'thumb' => true,
          );

    } // end constructor

    /*--------------------------------------------------*/
    /* Widget API Functions
    /*--------------------------------------------------*/

    /**
    * Outputs the content of the widget.
    *
    * @param array args The array of form elements
    * @param array instance The current instance of the widget
    */
    public function widget( $args, $instance ) {

        extract( $args, EXTR_SKIP );

        $instance = wp_parse_args( $instance, $this->defaults );

        //$title = $instance['title'];
        $title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';

        $view_count_meta_key = '';

        $valid_sort_orders = array('date', 'title', 'comment_count', 'rand', 'modified', 'popular', 'helpful');
        if ( in_array($instance['sort_by'], $valid_sort_orders) ) {
            $sort_by = $instance['sort_by'];
            $sort_order = (bool) $instance['asc_sort_order'] ? 'ASC' : 'DESC';
        } else {
            // by default, display latest first
            $sort_by = 'date';
            $sort_order = 'DESC';
        }

        if($instance['sort_by']=='popular'){
            $sort_by = 'meta_value_num';
            $view_count_meta_key = '_ht_kb_post_views_count';
        }

        if($instance['sort_by']=='helpful'){
            $sort_by = 'meta_value_num';
            $view_count_meta_key = HT_USEFULNESS_KEY;
        }        

        // Setup time/date
        $post_date = the_date( 'Y-m-d','','', false );
        $month_ago = date( "Y-m-d", mktime(0,0,0,date("m")-1, date("d"), date("Y")) );
        if ( $post_date > $month_ago ) {
        $post_date = sprintf( __( '%1$s ago', 'example' ), human_time_diff( get_the_time('U'), current_time('timestamp') ) );
        } else {
        	$post_date = get_the_date();
        }


        $args = array(
            'post_type' => 'ht_kb',
            'orderby' => $sort_by,
            'order' => $sort_order,
            'meta_key' => $view_count_meta_key,
            'posts_per_page' => $instance["num"],
            'ignore_sticky_posts' => 1
        ); 


        $category = $instance['category'];

        if($category=='' || $category=='all'){
            //do nothing - no tax query required
        } else {
            //tax query
            $args['tax_query'] = array(
            array(
            'taxonomy' => 'ht_kb_category',
            'field' => 'term_id',
            'terms' => $category,
            'operator' => 'IN'
            ));
        }  

        echo $before_widget;

        if ( $title )
            echo $before_title . $title . $after_title;

        $wp_query = new WP_Query($args);
        if($wp_query->have_posts()) : ?>

        <ul>

        <?php while($wp_query->have_posts()) : $wp_query->the_post(); ?>

            <?php 
            $post_format = get_post_format();
            $pf_class = 'hkb-widget-article__format-';
            $pf_class .= ( !empty( $post_format ) ) ? $post_format : 'standard'; ?>

            <li class="<?php echo $pf_class; ?>"> 

                <a class="hkb-widget__entry-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

                <?php if ( $instance['comment_num'] || $instance['comment_num'] ) : ?>
                <ul class="hkb-meta">

                    <?php if ( $instance['rating'] ): ?>      
                        <?php
                          $article_usefulness = ht_usefulness( get_the_ID() );
                          $helpful_article = ( $article_usefulness >= 0 ) ? true : false;
                          $helpful_article_class = ( $helpful_article ) ? 'hkb-meta__usefulness--good' : 'hkb-meta__usefulness--bad';
                        ?>
                        <li class="hkb-meta__usefulness <?php echo $helpful_article_class; ?>">
                          <?php echo $article_usefulness  ?>
                        </li>
                    <?php endif; //rating ?>

                    <?php if ( $instance['comment_num'] ) : ?>
                        <?php $number = get_comments_number(get_the_ID()); 
                        if ($number != 0) :	?>
                            <li class="hkb-meta__comments">
                                <?php comments_number( __( '0', 'ht-knowledge-base' ), __( '1', 'ht-knowledge-base' ), __( '%', 'ht-knowledge-base' ) ); ?>
                            </li>
                        <?php endif; //number ?>
                    <?php endif; //instance ?>

                </ul>
                <?php endif; ?>

            </li>
            <?php endwhile; ?>

        </ul>

        <?php endif;

        echo $after_widget;

    } // end widget

    /**
    * Processes the widget's options to be saved.
    *
    * @param array new_instance The previous instance of values before the update.
    * @param array old_instance The new instance of values to be generated via the update.
    */
    public function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        //update widget's old values with the new, incoming values
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['category'] = $new_instance['category'];
        $instance['num'] = $new_instance['num'];
        $instance['sort_by'] = $new_instance['sort_by'];
        $instance['asc_sort_order'] = $new_instance['asc_sort_order'] ? 1 : 0;
        $instance['comment_num'] = $new_instance['comment_num'] ? 1 : 0;
        $instance['rating'] = $new_instance['rating'] ? 1 : 0;

        return $instance;

    } // end widget

    /**
    * Generates the administration form for the widget.
    *
    * @param array instance The array of keys and values for the widget.
    */
    public function form( $instance ) {

      $instance = wp_parse_args((array) $instance, $this->defaults);

      //category option, todo - delete?
      $args = array(
        'type'                     => 'post',
        'child_of'                 => 0,
        'parent'                   => '',
        'orderby'                  => 'name',
        'order'                    => 'ASC',
        'hide_empty'               => 1,
        'hierarchical'             => 1,
        'exclude'                  => '',
        'include'                  => '',
        'number'                   => '',
        'taxonomy'                 => 'category',
        'pad_counts'               => false 
      ); 

      $categories =   get_terms('ht_kb_category');

      // Store the values of the widget in their own variable

      $title = strip_tags($instance['title']);
      $num = $instance['num'];
      $sort_by = $instance['sort_by'];
      $asc_sort_order = $instance['asc_sort_order'];
      $comment_num = $instance['comment_num'];
      $rating = $instance['rating'];
      $category = $instance['category'];
      ?>
      <label for="<?php echo $this->get_field_id("title"); ?>">
        <?php _e( 'Title', 'ht-knowledge-base' ); ?>
        :
        <input type="text" class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
      </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id("category"); ?>">
          <?php _e( 'Category', 'ht-knowledge-base' ); ?>
          :
          <select id="<?php echo $this->get_field_id("category"); ?>" name="<?php echo $this->get_field_name("category"); ?>" class="ht-kb-widget-admin-dropdown">
             <option value="all"<?php selected( $instance["category"], "all" ); ?>><?php _e('All', 'ht-knowledge-base'); ?></option>
            <?php foreach ($categories as $category): ?> 
              <option value="<?php echo $category->term_id; ?>"<?php selected( $instance["category"], $category->term_id ); ?>><?php echo $category->name; ?></option>
            <?php endforeach; ?>
          </select>
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id("num"); ?>">
          <?php _e( 'Number of posts to show', 'ht-knowledge-base' ); ?>
          :
          <input style="text-align: center;" id="<?php echo $this->get_field_id("num"); ?>" name="<?php echo $this->get_field_name("num"); ?>" type="text" value="<?php echo absint($instance["num"]); ?>" size='3' />
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id("sort_by"); ?>">
          <?php _e( 'Sort by', 'ht-knowledge-base' ); ?>
          :
          <select id="<?php echo $this->get_field_id("sort_by"); ?>" name="<?php echo $this->get_field_name("sort_by"); ?>" class="ht-kb-widget-admin-dropdown">
            <option value="date"<?php selected( $instance["sort_by"], "date" ); ?>><?php _e( 'Date', 'ht-knowledge-base' ); ?></option>
            <option value="title"<?php selected( $instance["sort_by"], "title" ); ?>><?php _e( 'Title', 'ht-knowledge-base' ); ?></option>
            <option value="comment_count"<?php selected( $instance["sort_by"], "comment_count" ); ?>><?php _e( 'Number of comments', 'ht-knowledge-base' ); ?></option>
            <option value="rand"<?php selected( $instance["sort_by"], "rand" ); ?>><?php _e( 'Random', 'ht-knowledge-base' ); ?></option>
            <option value="modified"<?php selected( $instance["sort_by"], "modified" ); ?>><?php _e( 'Modified', 'ht-knowledge-base' ); ?></option>
            <option value="popular"<?php selected( $instance["sort_by"], "popular" ); ?>><?php _e( 'Popular', 'ht-knowledge-base' ); ?></option>
            <option value="helpful"<?php selected( $instance["sort_by"], "helpful" ); ?>><?php _e( 'Helpful', 'ht-knowledge-base' ); ?></option>
          </select>
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id("asc_sort_order"); ?>">
          <input type="checkbox" class="checkbox"
      id="<?php echo $this->get_field_id("asc_sort_order"); ?>"
      name="<?php echo $this->get_field_name("asc_sort_order"); ?>"
      <?php checked( (bool) $instance["asc_sort_order"], true ); ?> />
          <?php _e( 'Reverse sort order (ascending)', 'ht-knowledge-base' ); ?>
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id("comment_num"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("comment_num"); ?>" name="<?php echo $this->get_field_name("comment_num"); ?>"<?php checked( (bool) $instance["comment_num"], true ); ?> />
          <?php _e( 'Show number of comments', 'ht-knowledge-base' ); ?>
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id("rating"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("rating"); ?>" name="<?php echo $this->get_field_name("rating"); ?>"<?php checked( (bool) $instance["rating"], true ); ?> />
          <?php _e( 'Show article rating', 'ht-knowledge-base' ); ?>
        </label>
      </p>
      <?php if ( function_exists('the_post_thumbnail') && current_theme_supports("post-thumbnails") ) : ?>
      <p style="display:none;">
        <label for="<?php echo $this->get_field_id('thumb'); ?>">
          <input type="checkbox" <?php echo $thumb; ?> class="checkbox" id="<?php echo $this->get_field_id('thumb'); ?>" name="<?php echo $this->get_field_name('thumb'); ?>"<?php checked( (bool) $instance["thumb"], true ); ?> />
          <?php _e( 'Show post thumbnail', 'ht-knowledge-base' ); ?>
        </label>
      </p>
      <?php endif; ?>
    <?php } // end form





} // end class

//Remember to change 'Widget_Name' to match the class name definition
add_action( 'widgets_init', create_function( '', 'register_widget("HT_KB_Articles_Widget");' ) );
