<?php

class HT_KB_Table_Of_Contents extends WP_Widget {

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
            'ht-kb-toc-widget',
            __( 'Knowledge Base Table of Contents', 'ht-knowledge-base' ),
            array(
              'classname'   =>  'hkb_widget_toc',
              'description' =>  __( 'A widget for displaying a Table of Contents on Knowledge Base ', 'ht-knowledge-base' )
            )
        );

        $default_widget_title = __('Contents', 'ht-knowledge-base');

        $this->defaults = array(
            'title' => $default_widget_title,
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
        global $ht_kb_toc_tools, $wp_query;

        if(!is_single())
            return;

        extract( $args, EXTR_SKIP );

        $instance = wp_parse_args( $instance, $this->defaults );

        $post = get_post( $wp_query->post->ID );

        //$title = $instance['title'];
        $title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';

        echo $before_widget;

        if ( $title )
            echo $before_title . $title . $after_title;


        if(is_a($ht_kb_toc_tools, 'HT_KB_TOC_Tools')){
            $find = array();
            $replace = array();

            //extract headings
            $ht_kb_toc_tools->ht_kb_toc_extract_headings($find, $replace, $post->post_content); ?>

            <nav id="navtoc" role="navigation">

            <?php
            //display items
            $ht_kb_toc_tools->ht_kb_display_items();
            ?>

            </nav>

            <?php
        }

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
        //$instance['category'] = $new_instance['category'];
        //$instance['asc_sort_order'] = $new_instance['asc_sort_order'] ? 1 : 0;

        return $instance;

    } // end widget

    /**
    * Generates the administration form for the widget.
    *
    * @param array instance The array of keys and values for the widget.
    */
    public function form( $instance ) {

      $instance = wp_parse_args((array) $instance, $this->defaults);

      // Store the values of the widget in their own variable

      $title = strip_tags($instance['title']);
      ?>
      <label for="<?php echo $this->get_field_id("title"); ?>">
        <?php _e( 'Title', 'ht-knowledge-base' ); ?>
        :
        <input type="text" class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
      </label>
      </p>
    <?php } // end form





} // end class

//Remember to change 'Widget_Name' to match the class name definition
add_action( 'widgets_init', create_function( '', 'register_widget("HT_KB_Table_Of_Contents");' ) );


if(!class_exists('HT_KB_TOC_Tools')){
    class HT_KB_TOC_Tools {

        private $anchors;
        private $items;
        private $current_level;
        private $toc_class;

        //constructor
        function __construct(){
            add_filter( 'the_content', array($this, 'ht_kb_toc_content_filter'), 100 ); 
        }

        /**
        * Content filter to extract headings and add IDs to the headings in the content
        */
        function ht_kb_toc_content_filter( $content ){

            $this->anchors = array();
            $find = array();
            $replace = array();

            //extract headings
            $this->ht_kb_toc_extract_headings($find, $replace, $content);

            //replace in content
            $content = $this->mb_find_replace($find, $replace, $content);

            return $content;

        }

        /**
        * Use implied pass-by-reference for find and replace variables
        */
        function ht_kb_toc_extract_headings( &$find, &$replace, $content ){
            $items = '';
            $this->current_level = 0;
            if ( preg_match_all('/(<h([1-6]{1})[^>]*>).*<\/h\2>/msuU', $content, $matches, PREG_SET_ORDER) ) {
                for ($i = 0; $i < count($matches); $i++) {
                    // get anchor and add to find and replace arrays
                    $anchor = $this->ht_kb_toc_generate_anchor( $matches[$i][0] );
                    $find[] = $matches[$i][0];
                    $replace[] = str_replace(
                                    array(
                                        $matches[$i][1],                // start h tag
                                        '</h' . $matches[$i][2] . '>'   // end h tag
                                    ),
                                    array(
                                        $matches[$i][1] . '<span id="' . $anchor . '">',
                                        '</span></h' . $matches[$i][2] . '>'
                                    ),
                                    $matches[$i][0]
                                );

                    if ( false ) {
                        //flat list
                        $items .= '<li><a href="#' . $anchor . '">';
                        //$items .= count($replace) ;
                        $items .= strip_tags($matches[$i][0]) . '</a></li>';
                    } else {
                        $items .= $this->ht_kb_build_hierachy( $matches[$i], $anchor );
                    }
                }
            }
            $this->items = $items;
            return $items;
        }

        /**
        * Display the items in the list
        */
        public function ht_kb_display_items(){
            echo '<ol class="nav">';
            echo balanceTags($this->items);
            echo '</ol><!-- /ht-kb-toc-widget -->';
        }

        public function ht_kb_build_hierachy($match, $anchor, $list_style='ol'){
            $new_level = $match[2];
            if(0==$this->current_level){
                //init
                $this->current_level = $new_level;
                $this->toc_class = 'active';
            }
            $items = '';
            if($this->current_level==$new_level){
                //add li
                $items .= '<li class="'. $this->toc_class .'"><a href="#' . $anchor . '">';
                $items .= strip_tags($match[0]) . '</a>';
            } elseif ($this->current_level>$new_level) {
                //remove level
                $items .= '</' . $list_style . '>';
                $items .= '<li><a href="#' . $anchor . '">';
                $items .= strip_tags($match[0]) . '</a></li>';
            } elseif($new_level>$this->current_level){
                //add level
                $items .= '<' . $list_style . '>';
                $items .= '<li><a href="#' . $anchor . '">';
                $items .= strip_tags($match[0]) . '</a></li>';
            }
            $this->current_level = $new_level;
            $this->toc_class = '';
            return $items;
        }


        /**
        * Anchor generator
        */
        private function ht_kb_toc_generate_anchor( $h_content = '' ){
            $anchor = '';
            if(empty($h_content)){
                //don't do anything if tag content empty
            } else {
                //generate anchor using santize title 
                $anchor = sanitize_title($h_content);
                if(empty($anchor)){
                    //append fragment
                    $anchor .= '_';
                    $h_content .= '_';
                }
                //check not already in array of anchors
                if(is_array($anchor) && in_array($anchor, $this->anchors)){
                    //recurse to generate unique anchor
                    $anchor = $this->ht_kb_toc_generate_anchor($h_content.'_');
                }
                //add new anchor to list of anchors
                $this->anchors[] = $anchor;
            }
            return $anchor;
        }

        /**
        * Multibyte safe find and replace
        */
        private function mb_find_replace( &$find = '', &$replace = '', &$string = '' ){
            if ( is_array($find) && is_array($replace) && $string ) {
                // check if multibyte strings are supported
                if ( function_exists( 'mb_strpos' ) ) {
                    for ($i = 0; $i < count($find); $i++) {
                        $string = 
                            mb_substr( $string, 0, mb_strpos($string, $find[$i]) ) . 
                            $replace[$i] . 
                            mb_substr( $string, mb_strpos($string, $find[$i]) + mb_strlen($find[$i]) )  
                        ;
                    }
                }
                else {
                    for ($i = 0; $i < count($find); $i++) {
                        $string = substr_replace(
                            $string,
                            $replace[$i],
                            strpos($string, $find[$i]),
                            strlen($find[$i])
                        );
                    }
                }
            }
            
            return $string;
        }
        


    }
}

if(class_exists('HT_KB_TOC_Tools')){
    //run the tool
    global $ht_kb_toc_tools;

    $ht_kb_toc_tools = new HT_KB_TOC_Tools();
}