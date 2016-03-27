<?php


if(!function_exists('hkb_category_thumb_img')){
    /**
    * Print the category thumb img
    * @param (Object) $category The category (not required)
    */
    function hkb_category_thumb_img($category=null){  
        $category_thumb_att_id  =  hkb_get_category_thumb_att_id($category);
        if( !empty( $category_thumb_att_id ) && $category_thumb_att_id!=0 ){
            $category_thumb_obj = wp_get_attachment_image_src( $category_thumb_att_id, 'hkb-thumb');                                
            $category_thumb_src = $category_thumb_obj[0];

            echo '<img src="' . $category_thumb_src . '" class="hkb-category__icon" />';
        }
    }
}

if(!function_exists('hkb_category_class')){
    /**
    * Print the category class
    * @param (Object) $category The category (not required)
    */
    function hkb_category_class($category=null){
        $ht_kb_category_class = "hkb-category-hasicon";

        $category_thumb_att_id  =  hkb_get_category_thumb_att_id($category);
        if( !empty( $category_thumb_att_id ) && $category_thumb_att_id!=0 ){
            $ht_kb_category_class = "hkb-category-hasthumb";
        }

        echo $ht_kb_category_class;
    }
}

if(!function_exists('hkb_has_category_custom_icon')){
    /**
    * Print the category custom icon true/false
    * @param (Object) $category The category (not required)
    */
    function hkb_has_category_custom_icon($category=null){
        $data_ht_category_custom_icon = 'false';

        $category_thumb_att_id  =  hkb_get_category_thumb_att_id($category);
        if( !empty( $category_thumb_att_id ) && $category_thumb_att_id!=0 ){
            $data_ht_category_custom_icon = 'true';
        }

        echo $data_ht_category_custom_icon;
    }
}

if(!function_exists('hkb_term_name')){
    /**
    * Print the term name
    * @param (Object) $category The category (not required)
    */
    function hkb_term_name($category=null){
        $term = hkb_get_term($category);
        if($term && isset($term->name)){
            echo $term->name;
        }
        
    }
}

if(!function_exists('hkb_get_term_desc')){
    /**
    * Return the term description
    * @param (Object) $category The category (not required)
    */
    function hkb_get_term_desc($category=null){
        $hkb_term_desc = '';
        $term = hkb_get_term($category);
        if($term && isset($term->description)){
            $hkb_term_desc = $term->description;
        }
        return $hkb_term_desc;
    }
}
if(!function_exists('hkb_term_desc')){
    /**
    * Print the term description
    * @param (Object) $category The category (not required)
    */
    function hkb_term_desc($category=null){
        echo hkb_get_term_desc($category);
    }
}

if(!function_exists('hkb_get_term_count')){
    /**
    * Return the term count
    * @param (Object) $category The category (not required)
    */
    function hkb_get_term_count($category=null){
        $term = hkb_get_term($category);
        $count = 0;
        $taxonomy = 'ht_kb_category';
        $args = array('child_of' => $term->term_id);
        $count = $term->count;
        $tax_terms = get_terms($taxonomy,$args);
        foreach ($tax_terms as $tax_term) {
            $count +=$tax_term->count;
        }
        return $count;

    }
}

function wp_get_postcount($id) {
  
}

if(!function_exists('hkb_term_count')){
    /**
    * Print the term count
    * @param (Object) $category The category (not required)
    */
    function hkb_term_count($category=null){
        echo hkb_get_term_count( $category );
    }
}

if(!function_exists('hkb_get_related_articles')){
    /**
    * Get related articles
    * @return (Array) An array of posts 
    */
    function hkb_get_related_articles(){
        global $post, $ht_knowledge_base_options, $orig_post;
        $related_articles = array();
        
        //check show related option
        if(!hkb_show_related_articles()){
            return $related_articles;
        }

        $orig_post = $post;
        $categories = get_the_terms($post->ID, 'ht_kb_category');

        if ($categories) {  
            $category_ids = array();
            foreach($categories as $individual_category) 
                $category_ids[] = $individual_category->term_id;

            $args=array(
                'post_type' => 'ht_kb',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'ht_kb_category',
                        'field' => 'term_id',
                        'terms' => $category_ids
                    )
                ),
                'post__not_in' => array($post->ID),
                'posts_per_page'=> 6, // Number of related posts that will be shown.
                'ignore_sticky_posts'=>1
            );

            $related_articles = new wp_query( $args );

        }
            
         return $related_articles; 
    }
}

if(!function_exists('hkb_after_releated_post_reset')){
    /**
    * Reset afer related articles
    */
    function hkb_after_releated_post_reset(){
        global $post, $orig_post;
        $post = $orig_post;
        wp_reset_postdata(); 
    }
}

if(!function_exists('hkb_post_format_class')){
    /**
    * Print post format class
    * @param (Int) $post_id The post id
    */
    function hkb_post_format_class($post_id=null){
        $post_id = isset($post_id) ? $post_id : get_the_ID();
        //set post format class  
        if ( get_post_format( $post_id )=='video') { 
          $ht_kb_format_class = 'format-video';
        } else {
          $ht_kb_format_class = 'format-standard';
        } 

        echo $ht_kb_format_class;
    }
}

if(!function_exists('hkb_post_type_class')){
    /**
    * Print post type class
    * @param (Int) $post_id The post id
    */
    function hkb_post_type_class($post_id=null){
        $post_id = isset($post_id) ? $post_id : get_the_ID();
        //post type 
        $post_type = get_post_type( $post_id );
        $ht_kb_type_class = 'hkb-post-type-' . $post_type;

        echo $ht_kb_type_class;
    }
}

if(!function_exists('hkb_term_link')){
    /**
    * Print term link
    * @param (Object) $term The term
    */
    function hkb_term_link($term){
        global $wp_query; 
        $term_link = get_term_link( $term );
        $link = is_wp_error( $term_link ) ? '#' : esc_url( $term_link );
        echo $link;
    }
}

if(!function_exists('hkb_get_category_thumb_att_id')){
    /**
    * Get the category thumb attachment id
    * @param (Object) $category The category (not required)
    * @return (Int) Thumb attachment id
    */
    function hkb_get_category_thumb_att_id($category=null){
        $term = hkb_get_term($category);
        $term_meta = get_hkb_term_meta($term);
        $category_thumb_att_id = 0;

        if(is_array($term_meta)&&array_key_exists('meta_image', $term_meta)&&!empty($term_meta['meta_image']))
            $category_thumb_att_id = $term_meta['meta_image'];

        return $category_thumb_att_id;

    }
}

if(!function_exists('hkb_get_category_color')){
    /**
    * Get the category colour
    * @param (Object) $category The category  (not required)
    * @return (String) The category colour
    */
    function hkb_get_category_color($category=null){
        $term = hkb_get_term($category);
        $term_meta = get_hkb_term_meta($term);
        $category_color = '#222'; 

        if(is_array($term_meta)&&array_key_exists('meta_color', $term_meta)&&!empty($term_meta['meta_color']))
            $category_color = $term_meta['meta_color'];

        return $category_color;
    }
}


if(!function_exists('get_hkb_term_meta')){
    /**
    * Get term meta
    * @pluggable
    * @return (Array) The term meta
    */
    function get_hkb_term_meta($term=0){
            
            // retrieve the existing value(s) for this meta field. This returns an array
            $term_meta = get_option( 'taxonomy_'.$term);

            return $term_meta;
    }//end function
}//end function exists
