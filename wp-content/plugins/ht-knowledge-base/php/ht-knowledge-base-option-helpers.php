<?php


if(!function_exists('hkb_show_knowledgebase_search')){
    /**
    * Get the Knowledge Base search display option
    * @param (String) $location The location of where to display (currently unused)
    * @return (Bool) The option
    */
    function hkb_show_knowledgebase_search($location=null){
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['search-display'] ) ){
            return $ht_knowledge_base_options['search-display'];
        } else {
            return false;
        }
    }
}

if(!function_exists('hkb_archive_columns')){
    /**
    * Number of archive columns to display
    * @return (Int) The option
    */
    function hkb_archive_columns(){
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['archive-columns'] ) ){
            return $ht_knowledge_base_options['archive-columns'];
        } else {
            return 2;
        }
    }
}

if(!function_exists('hkb_archive_columns_string')){
    /**
    * Number of archive columns to display (as a string)
    * @return (String) The option
    */
    function hkb_archive_columns_string(){
        // Set column variable to class needed for CSS
        $columns = hkb_archive_columns();
        if ($columns == '1') :
            $columns = 'one';
        elseif ($columns == '2') :
            $columns = 'two';
        elseif ($columns == '3') :
            $columns = 'three';
        elseif ($columns == '4') :
            $columns = 'four';
        else :
            $columns = 'two';
        endif; 

        return $columns;
    }
}



if(!function_exists('hkb_archive_display_subcategories')){
    /**
    * Get the Knowledge Base subcategory count display option
    * @return (Bool) The option
    */
    function hkb_archive_display_subcategories(){   
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['sub-cat-display'] ) ){
            return $ht_knowledge_base_options['sub-cat-display'];
        } else {
            return false;
        }
    }
}

if(!function_exists('hkb_archive_display_subcategory_count')){
    /**
    * Get the Knowledge Base subcategory count display option
    * @return (Bool) The option
    */
    function hkb_archive_display_subcategory_count(){   
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['sub-cat-article-count'] ) ){
            return $ht_knowledge_base_options['sub-cat-article-count'];
        } else {
            return false;
        }
    }
}

if(!function_exists('hkb_archive_display_subcategory_articles')){
    /**
    * Get the Knowledge Base subcategory list display option
    * @return (Bool) The option
    */
    function hkb_archive_display_subcategory_articles(){    
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['sub-cat-article-display'] ) ){
            return $ht_knowledge_base_options['sub-cat-article-display'];
        } else {
            return false;
        }
    }
}

if(!function_exists('hkb_archive_hide_empty_categories')){
    /**
    * Get the Knowledge Base hide empty categories option
    * @return (Bool) The option
    */
    function hkb_archive_hide_empty_categories(){   
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['hide-empty-kb-categories'] ) ){
            return $ht_knowledge_base_options['hide-empty-kb-categories'];
        } else {
            return false;
        }
    }
}

if(!function_exists('hkb_get_knowledgebase_searchbox_placeholder_text')){
    /**
    * Get the Knowledge Base searchbox placeholder text
    * @return (String) The placeholder text
    */
    function hkb_get_knowledgebase_searchbox_placeholder_text(){
        global $post, $ht_knowledge_base_options;

        //there is an issue with the global ht_knowledge_base_options not being translated, hence we'll revert to the get_option call
        $ht_knowledge_base_options = get_option('ht_knowledge_base_options');
        
        $placeholder_text =     (isset($ht_knowledge_base_options) && is_array($ht_knowledge_base_options) && array_key_exists('search-placeholder-text', $ht_knowledge_base_options)) ? 
                                $ht_knowledge_base_options['search-placeholder-text'] : 
                                __('Search the Knowledge Base', 'ht-knowledge-base');
        return $placeholder_text;

    }
}

if(!function_exists('hkb_show_knowledgebase_breadcrumbs')){
    /**
    * Get the Knowledge Base breadcrumbs option
    * @param (String) $location The location of where to display (currently unused)
    * @return (Bool) The option
    */
    function hkb_show_knowledgebase_breadcrumbs($location=null){
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['breadcrumbs-display'] ) ){
            return $ht_knowledge_base_options['breadcrumbs-display'];
        } else {
            return false;
        }
    }
}

if(!function_exists('hkb_show_usefulness_display')){
    /**
    * Get the Knowledge Base usefulness display option
    * @param (String) $location The location of where to display (currently unused)
    * @return (Bool) The option
    */
    function hkb_show_usefulness_display($location=null){
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['usefulness-display'] ) ){
            return $ht_knowledge_base_options['usefulness-display'];
        } else {
            return false;
        }
    }
}

if(!function_exists('hkb_show_viewcount_display')){
    /**
    * Get the Knowledge Base viewcount display option
    * @param (String) $location The location of where to display (currently unused)
    * @return (Bool) The option
    */
    function hkb_show_viewcount_display($location=null){
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['viewcount-display'] ) ){
            return $ht_knowledge_base_options['viewcount-display'];
        } else {
            return false;
        }
    }
}

if(!function_exists('hkb_show_comments_display')){
    /**
    * Get the Knowledge Base comments display option
    * @param (String) $location The location of where to display (currently unused)
    * @return (Bool) The option
    */
    function hkb_show_comments_display($location=null){
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['comments-display'] ) ){
            return $ht_knowledge_base_options['comments-display'];
        } else {
            return false;
        }
    }
}

if(!function_exists('hkb_show_related_articles')){
    /**
    * Get the Knowledge Base show related articles
    * @param (String) $location The location of where to display (currently unused)
    * @return (Bool) The option
    */
    function hkb_show_related_articles($location=null){
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['related-display'] ) ){
            return $ht_knowledge_base_options['related-display'];
        } else {
            return true;
        }
    }
}

if(!function_exists('hkb_show_search_excerpt')){
    /**
    * Get the Knowledge Base search excerpt display option
    * @param (String) $location The location of where to display (currently unused)
    * @return (Bool) The option
    */
    function hkb_show_search_excerpt($location=null){
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['search-excerpt'] ) ){
            return $ht_knowledge_base_options['search-excerpt'];
        } else {
            return false;
        }
    }
}

if(!function_exists('hkb_show_realted_rating')){
    /**
    * Get the Knowledge Base related rating display
    * @param (String) $location The location of where to display (currently unused)
    * @return (Bool) The option
    */
    function hkb_show_realted_rating($location=null){
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['related-rating'] ) ){
            return $ht_knowledge_base_options['related-rating'];
        } else {
            return false;
        }
    }
}

if(!function_exists('hkb_focus_on_search_box')){
    /**
    * Get the Knowledge Base related rating display
    * @param (String) $location The location of where to display (currently unused)
    * @return (Bool) The option
    */
    function hkb_focus_on_search_box($location=null){
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['search-focus-box'] ) ){
            return $ht_knowledge_base_options['search-focus-box'];
        } else {
            return false;
        }
    }
}


if(!function_exists('hkb_category_articles')){
    /**
    * Number of articles to display in category
    * @return (Int) The option
    */
    function hkb_category_articles($location=null){
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['sub-cat-article-number'] ) ){
            return $ht_knowledge_base_options['sub-cat-article-number'];
        } else {
            get_option('posts_per_page');
        }
    }
}


if(!function_exists('hkb_get_custom_styles_css')){
    /**
    * Get the Knowledge Base custom styles
    * @return (String) Custom CSS
    */
    function hkb_get_custom_styles_css(){   
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['ht-kb-custom-styles']) ){
            $styles = '';
            $styles .= '<style>';
            $styles .= $ht_knowledge_base_options['ht-kb-custom-styles'];
            $styles .= '</style>';
            return $styles;
        } else {
            return '';
        }
    }
}

if(!function_exists('hkb_custom_styles_sitewide')){
    /**
    * Whether to use custom styles sitewide
    * @return (Boolean) default false
    */
    function hkb_custom_styles_sitewide(){   
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['ht-kb-custom-styles-sitewide']) ){
            return $ht_knowledge_base_options['ht-kb-custom-styles-sitewide'];            
        } else {
            return false;
        }
    }
}

if(!function_exists('hkb_kb_search_sitewide')){
    /**
    * Whether to use search in kb sitewide
    * @return (Boolean) default false
    */
    function hkb_kb_search_sitewide(){   
        global $ht_knowledge_base_options;
        if ( isset( $ht_knowledge_base_options['kb-site-search']) ){
            return $ht_knowledge_base_options['kb-site-search'];            
        } else {
            return false;
        }
    }
}
