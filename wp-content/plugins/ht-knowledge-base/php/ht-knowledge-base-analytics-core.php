<?php
/*
* Self contained analytics core
*/

//if you want to disable hkb data capture, remove the next line
define( 'HKB_ANALYTICS_DATA_CAPTURE', true );

if( !class_exists( 'HKB_Analytics_Core' ) ){
    class HKB_Analytics_Core {

        function __construct() {

            //init the saving fuctionality
            if ( defined( 'HKB_ANALYTICS_DATA_CAPTURE' ) &&  true === HKB_ANALYTICS_DATA_CAPTURE ){
                add_action('the_posts', array($this,'hkba_save_searches'),20);
            }            

            //add activation action for table
            add_action( 'ht_kb_activate', array( $this, 'on_activate' ));
            //deactivation hook, currently unused
            //register_deactivation_hook( __FILE__, array( 'HKB_Analytics_Core', 'hkba_plugin_deactivation_hook' ) );
        }

        function hkba_save_searches($posts) {
            global $wp_query;

            //break if already performing a save search
            if ( defined( 'DOING_HKBA_SAVE_SEARCH' ) && DOING_HKBA_SAVE_SEARCH === true) {
                return $posts;
            } else {
                define('DOING_HKBA_SAVE_SEARCH', true);
            }


            //check if the request is a search, and if so then save details.
            //hooked on a filter but does not change the posts

            if( is_search()
                && !is_paged() 
                && !is_admin() 
                && !empty($_GET['ht-kb-search']) )
                {
                    //get search terms
                    //search string is the raw query
                    $search_string = $wp_query->query_vars['s'];
                    if (get_magic_quotes_gpc()) {
                        $search_string = stripslashes($search_string);
                    }
                    //search terms is the words in the query
                    $search_terms = $search_string;
                    $search_terms = preg_replace('/[," ]+/', ' ', $search_terms);
                    $search_terms = trim($search_terms);
                    $hit_count = $wp_query->found_posts;
                    $details = '';

                    //sanitise as necessary
                    $search_string = esc_sql($search_string);
                    $search_terms = esc_sql($search_terms);
                    $details = esc_sql($details);
            }


           if(  is_search()
                && !empty($_GET['ht-kb-search']) //Knowledge Base search
                && !is_paged() //is not a second page search
                && !is_admin()//is not the dashboard
                && empty($_GET['ajax']) //not live search
                ){
                    //Non-Live search flow
                    //create search data object
                    $search_data = (object) array(
                        'search_string' => $search_terms,
                        'hit_count' => $hit_count,
                        'timestamp' => current_time( 'timestamp' ),
                        'details'   => ''
                    );

                    //save search to db
                    $this->ht_kb_save_search($search_data);
                    return $posts;
            } 

            return $posts;
        }




        private function ht_kb_save_search($search_data){

                global $wpdb;

                // Save search into the db
                $query = "INSERT INTO {$wpdb->prefix}hkb_analytics_search_atomic ( id ,  terms , datetime , hits )
                VALUES (NULL, '$search_data->search_string', NOW(), $search_data->hit_count)";
                $run_query = $wpdb->query($query);

        }

        function hkba_create_table() {
            //add the table into the database
            global $wpdb;
            $table_name = $wpdb->prefix . "hkb_analytics_search_atomic";
            if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
              require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
              $create_hkb_analytics_table_sql = "
                                                  CREATE TABLE {$table_name} (
                                                    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                                                    terms VARCHAR(50) NOT NULL,
                                                    datetime DATETIME NOT NULL,
                                                    hits INT(11) NOT NULL,
                                                    PRIMARY KEY (id)
                                                  )
                                                  CHARACTER SET utf8 COLLATE utf8_general_ci;
                                                  ";
              dbDelta($create_hkb_analytics_table_sql);
            }
        }

        function on_activate( $network_wide = null ) {
            global $wpdb;
            //@todo - query multisite compatibility
            if ( is_multisite() && $network_wide ) {
                //store the current blog id
                $current_blog = $wpdb->blogid;
                //get all blogs in the network and activate plugin on each one
                $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
                foreach ( $blog_ids as $blog_id ) {
                    switch_to_blog( $blog_id );
                    $this->hkba_create_table();
                    restore_current_blog();
                }
            } else {
                $this->hkba_create_table();
            }
        }

        static function hkba_plugin_activation_hook() {
            $this->on_activate();
        }

        static function hkba_plugin_deactivation_hook() {
            //do nothing
        }


    }
}

//run the module
if( class_exists( 'HKB_Analytics_Core' ) ){
    $hkb_analytics_core_init = new HKB_Analytics_Core();
}
