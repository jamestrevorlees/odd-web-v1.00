<?php
/**
* Voting options - will hook onto a redux framework options array
*/


if(!class_exists('HT_Voting_Options')){

    class HT_Voting_Options{



        /**
         * Constructor
         */
        public function __construct() {
            //filter the option sections
            add_filter('ht_kb_option_sections_1', array($this, 'filter_options_sections_array'));
        }

        /**
        * Filter the options menu
        * @param $sections (Array) The options array to filter
        */
        function filter_options_sections_array($sections){

            $voting_settings_fields = array(
                                            array(
                                                'id'        => 'voting-display',
                                                'type'      => 'switch',
                                                'title'     => __('Enable Feedback', 'ht-knowledge-base'),
                                                'subtitle'  => __( 'Allow readers to vote', 'ht-knowledge-base'),
                                                'default'   => true,
                                            ),
                                            array(
                                                'id'        => 'anon-voting',
                                                'type'      => 'switch',
                                                'title'     => __('Enable Anonymous', 'ht-knowledge-base'),
                                                'subtitle'  => __('Allow users to vote that are not logged in', 'ht-knowledge-base'),
                                                'default'   => true,
                                                'required'  => array('voting-display', "=", 1),
                                            ),
                                            array(
                                                'id'        => 'upvote-feedback',
                                                'type'      => 'switch',
                                                'title'     => __('Upvote Feedback', 'ht-knowledge-base'),
                                                'subtitle'  => __('Collect feedback for upvotes', 'ht-knowledge-base'),
                                                'default'   => true,
                                                'required'  => array('voting-display', "=", 1),
                                            ),
                                            array(
                                                'id'        => 'downvote-feedback',
                                                'type'      => 'switch',
                                                'title'     => __('Downvote Feedback', 'ht-knowledge-base'),
                                                'subtitle'  => __('Collect feedback for downvotes', 'ht-knowledge-base'),
                                                'default'   => true,
                                                'required'  => array('voting-display', "=", 1),
                                            ),

                                        );

            $sections[] =  array(
                    'title'     => __('Article Feedback', 'ht-knowledge-base'),
                    'desc'      => __('Set various options for gathering article feedback', 'ht-knowledge-base'),
                    'icon'      => 'el-icon-thumbs-up',
                    'fields'    => $voting_settings_fields,
                );


            return $sections;
        }    
        
    }

}

if(class_exists('HT_Voting_Options')){
    $ht_voting_options_init = new HT_Voting_Options();
}
