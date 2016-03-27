<?php
/*
* Extension to enable enable sorting of knowledge base categories
*/

if( !class_exists( 'HT_Knowledge_Base_Live_Search' ) ){
	class HT_Knowledge_Base_Live_Search {

		public $add_script;

		//Constructor
		function __construct(){
			add_filter( 'search_template', array($this, 'ht_knowledge_base_live_search_template') );
			//register scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'ht_knowledge_base_live_search_register_scripts' ) );	
			//add filter to print editor styles and scripts
			add_action( 'wp_footer', array( $this, 'ht_knowledge_base_live_search_print_scripts' ) );

		}

		/**
		* Live search results functionality
		*/
		function ht_knowledge_base_live_search_template( $template ){
			global $ht_knowledge_base_options;
			//ensure this is a live search
			$ht_kb_search = ( array_key_exists('ht-kb-search', $_REQUEST) ) ? true : false;
			if( $ht_kb_search == false )
				return $template;

			if(!empty($_GET['ajax']) ? $_GET['ajax'] : null) { // Is Live Search 
				//check custom search

				//search string
				global $s;
				// Get FAQ cpt
				$ht_kb_cpt = 'ht_kb';
				?>

				<?php hkb_get_template_part('hkb-search-ajax'); ?>

				<?php
				wp_reset_query();

				//required to stop 
				die();
			} else {
				//non ajax search
				return $template;
			}
		}

		/**
		* Enqueue the javascript for live search
		*/
		function ht_knowledge_base_live_search_register_scripts(){
			//register live search script
			wp_register_script('ht-kb-live-search-plugin', plugins_url( 'js/jquery.livesearch.js', dirname( __FILE__ ) ), array( 'jquery' ), false, true);
			wp_register_script('ht-kb-live-search', plugins_url( 'js/hkb-livesearch-js.js', dirname( __FILE__ ) ), array( 'jquery', 'ht-kb-live-search-plugin' ), false, true);
			$search_url = '?ajax=1&ht-kb-search=1&';
			//if wpml is installed append language code if not in default language
			if(defined('ICL_LANGUAGE_CODE')){
				global $sitepress;
				$default_lang = $sitepress->get_default_language();
				if($default_lang != ICL_LANGUAGE_CODE ){
					$search_url .= 'lang=' . ICL_LANGUAGE_CODE . '&';
					$search_url = ICL_LANGUAGE_CODE . '/' . $search_url;
				}				
			}
			$focus_searchbox = !ht_kb_is_ht_kb_search() && hkb_focus_on_search_box();
			$search_url .= 's=';
			wp_localize_script( 'ht-kb-live-search', 'hkbJSSettings', array( 'liveSearchUrl' => home_url($search_url), 'focusSearchBox' => $focus_searchbox ) );
		}

		/**
		* Print the javascript for live search
		*/
		function ht_knowledge_base_live_search_print_scripts() {
			if ( ! $this->add_script )
				return;

			wp_print_scripts('ht-kb-live-search-plugin');
			wp_print_scripts('ht-kb-live-search');
		}


		/**
		* Activate live search
		*/
		function ht_knowledge_base_activate_live_search(){
			$this->add_script = true;			
		}



    }//end class
}//end class test

//run the module
if(class_exists('HT_Knowledge_Base_Live_Search')){

	global $ht_knowledge_base_live_search_init;
	$ht_knowledge_base_live_search_init = new HT_Knowledge_Base_Live_Search();
	
	function ht_knowledge_base_activate_live_search(){
		global $ht_knowledge_base_live_search_init;		

		$ht_knowledge_base_live_search_init->ht_knowledge_base_activate_live_search();
	}
}

