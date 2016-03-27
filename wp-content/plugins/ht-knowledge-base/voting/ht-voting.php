<?php
/*
* Voting module
*/

/*
* +1 	upvote
* -1 	downvote
*  0	neutral
*
*/


if( !class_exists('HT_Voting') ){
	if(!defined('HT_VOTING_KEY')){
		define('HT_VOTING_KEY', '_ht_voting');
	}

	if(!defined('HT_USEFULNESS_KEY')){
		define('HT_USEFULNESS_KEY', '_ht_kb_usefulness');
	}	


	class HT_Voting {		

		//constructor
		function __construct(){
			$this->add_script = false;

			//no longer needs text domain loading - uses ht-knowledge-base
			//load_plugin_textdomain('ht-voting', false, basename( dirname( __FILE__ ) ) . '/languages' );

			add_action( 'init', array( $this, 'register_ht_voting_shortcode_scripts_and_styles' ) );
			add_action( 'wp_footer', array( $this, 'print_ht_voting_shortcode_scripts_and_styles' ) );
			add_shortcode( 'ht_voting', array( $this , 'ht_voting_post_shortcode' ) );
			add_shortcode( 'ht_voting_comment', array( $this , 'ht_voting_comment_shortcode' ) );
			add_action( 'wp_head', array( $this, 'ht_voting_head' ) );

			//display voting
			add_action( 'ht_kb_end_article', array($this, 'ht_voting_display_voting' ) );

			//ajax filters
        	add_action( 'wp_ajax_ht_voting', array( $this, 'ht_ajax_voting_callback' ) );
        	add_action( 'wp_ajax_nopriv_ht_voting', array( $this, 'ht_ajax_voting_callback' ) );
        	add_action( 'wp_ajax_ht_voting_update_feedback', array( $this, 'ht_ajax_voting_update_feedback_callback' ) );
        	add_action( 'wp_ajax_nopriv_ht_voting_update_feedback', array( $this, 'ht_ajax_voting_update_feedback_callback' ) );
			include_once('php/ht-vote-class.php');
			//meta-boxes
			include_once('php/ht-voting-meta-boxes.php');
			//voting options
			include_once('php/ht-voting-options.php');
			//voting backend
			include_once('php/ht-voting-backend.php');

			//activation hook
			register_activation_hook( __FILE__, array( 'HT_Voting', 'ht_voting_plugin_activation_hook' ) );

		}

		/**
		* Initial activation to add update votes
		*/
		static function ht_voting_plugin_activation_hook(){

		 	//perform upgrade actions
		 	HT_Voting::ht_kb_voting_activation_upgrade_actions();

		}

		static function ht_kb_voting_activation_upgrade_actions(){
			//upgrade - set initial meta if required

			//get all ht_kb articles
			$args = array(
					  'post_type' => 'ht_kb',
					  'posts_per_page' => -1,
					 );
			$ht_kb_posts = get_posts( $args );

			//loop and ugrade
			foreach ( $ht_kb_posts as $post ) {
				//upgrade if required
			   HT_Voting::ht_kb_voting_upgrade_votes( $post->ID );
			   
			}
		}

		static function ht_kb_voting_upgrade_votes($post_id){
			//get old votes
			$votes = get_post_meta($post_id, HT_VOTING_KEY);
			//delete old votes
			delete_post_meta($post_id, HT_VOTING_KEY);
			foreach ($votes as $key => $vote) {
				$key = md5( strval($vote->magnitude) . $vote->ip . $vote->time . $vote->user_id );
	            $vote->key = $key;
	            $vote->comments = '';
	            //add vote
	            add_post_meta($post_id, HT_VOTING_KEY, $vote);
			}

		}

		/**
		* Voting post shortcode
		* @param array $attrs The shortcode passed attribute
		* @param array $content The shortcode passed content (this will always be ignored in this context)
		*/
		function ht_voting_post_shortcode($atts, $content = null){
			global $post;
			//shortcode used so scripts and styles required
			$this->add_script = true;
			
			//extract arttributes
			extract(shortcode_atts(array(  
	                'display' => 'standard',
	                'allow' => 'user',
	            ), $atts));

			ob_start();

			$this->ht_voting_post_display($post->ID, $allow, $display);
			//return whatever has been passed so far
			return ob_get_clean();
		}

		/**
		* 
		* @param Int $post_id
		* @param String $allow
		* @param String $display
		*/
		function ht_voting_post_display($post_id, $allow='user', $display='standard', $vote=null){
				//get votes so far
				$votes = $this->get_post_votes($post_id);
			?>
				<div class="ht-voting" id ="ht-voting-post-<?php echo $post_id ?>">
					<?php $this->ht_voting_post_render($post_id, $allow, $votes, $display, $vote); ?>
				</div>
			<?php
		}



		/**
		* Voting comment shortcode
		* @param array $attrs The shortcode passed attribute
		* @param array $content The shortcode passed content (this will always be ignored in this context)
		*/
		function ht_voting_comment_shortcode($atts, $content = null){
			//shortcode used so scripts and styles required
			$this->add_script = true;
			
			//extract arttributes
			extract(shortcode_atts(array( 
					'comment_id' => 0, 
	                'display' => 'standard',
	                'allow' => 'user',
	            ), $atts));	

			//return if no comment id set
			if($comment_id == 0)
				return;

			ob_start();
			
			$this->ht_voting_comment_display($comment_id, $allow, $display);
			//return whatever has been passed so far
			return ob_get_clean();
		}

		/**
		* Voting comment display
		* @param String $comment_id
		* @param String $allow
		* @param String $display
		*/
		function ht_voting_comment_display($comment_id, $allow='user', $display='standard'){
			?>

				<div class="ht-voting comment" id ="ht-voting-comment-<?php echo $comment_id; ?>">
				<?php

					//get votes so far
					$votes = $this->get_comment_votes($comment_id);
					if( true ){
						$this->ht_voting_comment_render($comment_id, $allow, $votes, $display);
					}
				?>
			</div><!-- /ht-voting -->

			<?php
		}


		/**
		 * Comment filter
		 * @param (String) $content The comment content
		 * @return (String) Filtered comment content
		 */
		function ht_voting_get_comment_text_filter( $content, $comment, $args ) {
			global $post;

			//if this is an archive, admin page or not a knowledge base post, return
			if(!is_single() || is_admin() || $post->post_type!='ht_kb' )
				return $content;

			//if voting isn't installed, return
			if(!class_exists('HT_Voting'))
				return $content;

			//comment voting
			ob_start();

			?>
			<div class="clearfix"></div>
			<div class="ht-voting-comments-section">
			<?php
				global $ht_knowledge_base_options;
				$voting_disabled =  get_post_meta( get_the_ID(), '_ht_voting_voting_disabled', true );
				if(!$voting_disabled){
					if( !empty($ht_knowledge_base_options) ){
						if( $ht_knowledge_base_options['voting-display'] ){
							if( $ht_knowledge_base_options['anon-voting']) {
								//anon voting 
								ht_voting_comment( $comment->comment_ID, 'anon', 'numbers');
							} else {
								//user voting
								ht_voting_comment( $comment->comment_ID, 'user', 'numbers');
							}
						}
					} else {
						//no global options, default behaviour
						ht_voting_comment( $comment->comment_ID, 'user', 'numbers');
					}
					
				}

			?>
			</div><!--/ht-voting-comments-section-->
			<?php
			$comment_vote = ob_get_clean();

			return $content . $comment_vote;
		}


		/**
		 * Get post votes
		 * @param (Int) $post_id The post id for the votes to fetch
		 * @return (Array) Vote objects array
		 */
		function get_post_votes($post_id){
			$votes = get_post_meta( $post_id, HT_VOTING_KEY, false);
			return (array)$votes;
		}

		/**
		 * Get comment votes
		 * @param (Int) $post_id The comment id for the votes to fetch
		 * @return (Array) Vote objects array
		 */
		function get_comment_votes($comment_id){
			$votes = get_comment_meta( $comment_id, HT_VOTING_KEY, false);
			return (array)$votes;
		}


		/**
		* Render the voting for a post
		* @param (Int) $post_id The post id
		* @param (String) $allow Whether to allow anonymous voting ('anon')
		* @param (Array) $votes An array of existing votes
		* @param (String) $display How the voting display should be rendered
		* @param (Object) $new_vote The vote that has just been made (or null if first render)
		*/
		function ht_voting_post_render($post_id, $allow, $votes, $display='standard', $new_vote=null){
			global $ht_knowledge_base_options;
			//load font awesome
			//wp_enqueue_style( 'font-awesome', plugins_url( 'css/font-awesome.min.css', __FILE__ ) );

			//enqueue script
			wp_enqueue_script( 'ht-voting-frontend-script'); 

			//add localization if required         

			$number_of_votes = is_array($votes) ? count($votes) : 0;
			$number_of_helpful = 0;
			foreach ((array)$votes as $vote) {
				if($vote->magnitude==10)
					$number_of_helpful++;
			}

			//get current user votes
			$user_vote = $this->get_users_post_vote( $post_id );


			$user_vote_direction = 'none';

			if( is_a( $user_vote, 'HT_Vote_Up' ) )
				$user_vote_direction = 'up';

			if( is_a( $user_vote, 'HT_Vote_Down' ) )
				$user_vote_direction = 'down';		


			$nonce = ( $allow!='anon' && !is_user_logged_in() ) ? '' : wp_create_nonce('ht-voting-post-ajax-nonce');
			$feedback_nonce = ( $allow!='anon' && !is_user_logged_in() ) ? '' : wp_create_nonce('ht-voting-feedback-ajax-nonce');
			$vote_enabled_class = ( $allow!='anon' && !is_user_logged_in() ) ? 'disabled' : 'enabled';

			?>
			<?php if($display=='lowprofile'): ?>
				<div class="ht-voting-links ht-voting-<?php echo $user_vote_direction; ?>">
					<a class="ht-voting-upvote <?php echo $vote_enabled_class; ?>" rel="nofollow" data-direction="up" data-type="post" data-nonce="<?php echo $nonce; ?>" data-id="<?php echo $post_id; ?>" data-allow="<?php echo $allow; ?>" data-display="<?php echo $display; ?>" href="<?php echo '#'; // $this->vote_post_link('up', $post_id, $allow); ?>"></a>
					<a class="ht-voting-downvote <?php echo $vote_enabled_class; ?>" rel="nofollow" data-direction="down" data-type="post" data-nonce="<?php echo $nonce; ?>" data-id="<?php echo $post_id; ?>" data-allow="<?php echo $allow; ?>" data-display="<?php echo $display; ?>" href="<?php echo '#'; // $this->vote_post_link('down', $post_id, $allow); ?>"></a>
				</div>
			<?php else: ?>
				<?php if($allow!='anon' && !is_user_logged_in()): ?>	
					<div class="voting-login-required">
					<?php _e('You must log in to vote', 'ht-knowledge-base'); ?>
					</div>
				<?php endif; ?>
				<div class="ht-voting-links ht-voting-<?php echo $user_vote_direction; ?>">
					<a class="ht-voting-upvote <?php echo $vote_enabled_class; ?>" rel="nofollow" data-direction="up" data-type="post" data-nonce="<?php echo $nonce; ?>" data-id="<?php echo $post_id; ?>" data-allow="<?php echo $allow; ?>" data-display="<?php echo $display; ?>" href="<?php echo '#'; // $this->vote_post_link('up', $post_id, $allow); ?>"><i class="hkb-upvote-icon"></i><span><?php _e('Yes', 'ht-knowledge-base'); ?></span></a>
					<a class="ht-voting-downvote <?php echo $vote_enabled_class; ?>" rel="nofollow" data-direction="down" data-type="post" data-nonce="<?php echo $nonce; ?>" data-id="<?php echo $post_id; ?>" data-allow="<?php echo $allow; ?>" data-display="<?php echo $display; ?>" href="<?php echo '#'; // $this->vote_post_link('down', $post_id, $allow); ?>"><i class="hkb-upvote-icon"></i><span><?php _e('No', 'ht-knowledge-base'); ?></span></a>
				</div>
				<?php if(empty($new_vote)): ?>
					<!-- no new vote -->
				<?php elseif( 	( is_a($new_vote, 'HT_Vote_Up') && (  !isset($ht_knowledge_base_options['upvote-feedback']) || true==$ht_knowledge_base_options['upvote-feedback'] ) ) || 
                            	( is_a($new_vote, 'HT_Vote_Down') && ( !isset($ht_knowledge_base_options['downvote-feedback']) || true==$ht_knowledge_base_options['downvote-feedback'] ) ) 
                            ): ?>
					<div class="ht-voting-comment <?php echo $vote_enabled_class; ?>" data-nonce="<?php echo $feedback_nonce; ?>"  data-vote-key="<?php echo $vote->key; ?>" data-id="<?php echo $post_id; ?>">
						<textarea class="ht-voting-comment__textarea" rows="4" cols="50" placeholder="<?php _e('Thanks for your feedback, add a comment here to help improve the article', 'ht-knowledge-base'); ?>"><?php if(isset($new_vote->comments)) $new_vote->comments; ?></textarea>
						<button class="ht-voting-comment__submit" type="button"><?php _e('Send Feedback', 'ht-knowledge-base'); ?></button>
					</div>
				<?php else: ?>
                    	<div class="ht-voting-thanks"><?php _e('Thanks for your feedback', 'ht-knowledge-base'); ?></div>
				<?php endif;//vote_key ?>	
			<?php endif; ?>

			<?php
		}

		/**
		 * Render the comment vote section
		 * @param (Int) $comment_id The comment id for the comment voting section to render
		 * @param (String) $allow Whether to allow anonymous voting ('anon')
		 * @param (Array) $votes An array of existing votes
		 * @param (String) $display How the voting display should be rendered
		 */
		function ht_voting_comment_render($comment_id, $allow, $votes, $display='standard'){
			
			//load font awesome
			wp_enqueue_style( 'font-awesome', plugins_url( 'css/font-awesome.min.css', __FILE__ ) );

			$number_of_votes = is_array($votes) ? count($votes) : 0;
			$number_of_helpful = 0;
			foreach ((array)$votes as $vote) {
				if($vote->magnitude==10)
					$number_of_helpful++;
			}

			//get current user votes
			$user_vote = $this->get_users_comment_vote( $comment_id );

			$user_vote_direction = 'none';

			if( is_a( $user_vote, 'HT_Vote_Up' ) )
				$user_vote_direction = 'up';

			if( is_a( $user_vote, 'HT_Vote_Down' ) )
				$user_vote_direction = 'down';	

			$nonce = ( $allow!='anon' && !is_user_logged_in() ) ? '' :  wp_create_nonce('ht-voting-comment-ajax-nonce');
			$vote_enabled_class = ( $allow!='anon' && !is_user_logged_in() ) ? 'disabled' : 'enabled';		

			?>
			<?php if($display=='numbers'): ?>
				<?php if($allow!='anon' && !is_user_logged_in()): ?>	
					<div class="ht-voting-login-required">
					<?php _e('You must log in to vote', 'ht-knowledge-base'); ?>
					</div>
				<?php endif; ?>
				<div class="ht-voting-links ht-voting-<?php echo $user_vote_direction; ?>">
					<a class="ht-voting-upvote <?php echo $vote_enabled_class; ?>" data-direction="up" data-display="numbers" data-type="comment" data-nonce="<?php echo $nonce; ?>" data-id="<?php echo $comment_id; ?>"  href="<?php echo '#'; // $this->vote_comment_link('up', $comment_id, $allow); ?>"><?php echo $number_of_helpful; ?></a>
					<a class="ht-voting-downvote <?php echo $vote_enabled_class; ?>" data-direction="down" data-display="numbers" data-type="comment" data-nonce="<?php echo $nonce; ?>" data-id="<?php echo $comment_id; ?>"  href="<?php echo '#'; // $this->vote_comment_link('down', $comment_id, $allow); ?>"><?php echo $number_of_votes-$number_of_helpful; ?></a>
				</div>
			<?php else: ?>
				<?php if($allow!='anon' && !is_user_logged_in()): ?>	
					<div class="ht-voting-login-required">
					<?php _e('You must log in to vote', 'ht-knowledge-base'); ?>
					</div>
				<?php endif; ?>
				<div class="ht-voting-links ht-voting-<?php echo $user_vote_direction; ?>">
					<a class="ht-voting-upvote <?php echo $vote_enabled_class; ?>" data-direction="up" data-type="comment" data-nonce="<?php echo $nonce; ?>" data-id="<?php echo $comment_id; ?>"  href="<?php echo '#'; // $this->vote_comment_link('up', $comment_id, $allow); ?>"><?php _e('Up', 'ht-knowledge-base'); ?></a>
					<a class="ht-voting-downvote <?php echo $vote_enabled_class; ?>" data-direction="down" data-type="comment" data-nonce="<?php echo $nonce; ?>" data-id="<?php echo $comment_id; ?>"  href="<?php echo '#'; // $this->vote_comment_link('down', $comment_id, $allow); ?>"><?php _e('Down', 'ht-knowledge-base'); ?></a>
				</div>
			<?php endif; ?>

			<?php
		}

		/**
		* Get the voting link
		* @param (String) $direction The direction up/down
		* @param (Int) $post_id The id of the post for the voting link
		* @param (String) $allow Whether to allow anonymous voting ('anon')
		*/
		function vote_post_link($direction, $post_id, $allow='anon'){
			$bookmark = 'ht-voting-post-'.$post_id;
			if($allow!='anon' && !is_user_logged_in())
				return '?' . '#' . $bookmark ;
			$security = wp_create_nonce( 'ht-post-vote' );
			return '?' . 'vote=' . $direction . '&post=' . $post_id . '&_htvotenonce=' . $security . '#' . $bookmark ;
		}

		/**
		* Get the voting link
		* @param (String) $direction The direction up/down
		* @param (Int) $post_id The id of the post for the voting link
		* @param (String) $allow Whether to allow anonymous voting ('anon')
		*/
		function vote_comment_link($direction, $comment_id, $allow='anon'){
			$bookmark = 'ht-voting-comment-'.$comment_id;
			if($allow!='anon' && !is_user_logged_in())
				return '?' . '#' . $bookmark ;
			//todo add security nonce (required?)
			return '?' . 'vote=' . $direction . '&comment=' . $comment_id . '#' . $bookmark ;
		}


		/**
		* Get a post vote for a user
		* @param (Int) $post_id The post_id to get the user vote for
		* @param (Array) $votes Existing vote array object to search for first (otherwise load post meta)
		* @return (Object) Vote object
		*/
		function get_users_post_vote($post_id, $votes=null){
			//create a dummy vote to compare
			if(class_exists('HT_Vote_Up')){
				$comp_vote = new HT_Vote_Up();
			} else {
				return;
			}
			//get all votes
			$votes = ( empty($votes) ) ? get_post_meta($post_id, HT_VOTING_KEY) : $votes;
			//loop through and compare users vote
			if($votes && !empty($votes)){
				foreach ($votes as $key => $vote) {
					//if user id is same (and not 0), return vote
					if( $vote->user_id > 0 && $vote->user_id == $comp_vote->user_id )
						return $vote;
					//if ip is same, return vote
					if( $vote->ip == $comp_vote->ip )
						return $vote;
					//else try next one
					continue;
				}
			} else {
				return;
			}
		}

		/**
		* Get a vote by key
		* @param (Int) $post_id The post_id to get the user vote for
		* @param (Array) $votes Existing vote array object to search for first (otherwise load post meta)
		* @param (Int) $vote_key The key of the vote to fetch
		* @return (Object) Vote object
		*/
		function get_users_post_vote_by_key($post_id, $votes=null, $vote_key=-1){
			//get all votes
			$votes = ( empty($votes) ) ? get_post_meta($post_id, HT_VOTING_KEY) : $votes;
			//loop through and compare users vote
			if($votes && !empty($votes)){
				foreach ($votes as $key => $vote) { 
					if(property_exists($vote, 'key') && $vote_key==$vote->key){
						return $vote;
					}
				}
			} else {
				return;
			}
		}

		/**
		* Get a comment vote for a user
		* @param (Int) $comment_id The comment_id to get the user vote for
		* @param (Array) $votes Existing vote array object to search for first (otherwise load meta)
		* @return (Object) Vote object
		*/
		function get_users_comment_vote($comment_id, $votes=null){
			//create a dummy vote to compare
			if(class_exists('HT_Vote_Up')){
				$comp_vote = new HT_Vote_Up();
			} else {
				return;
			}
			//get all votes
			$votes = ( empty($votes) ) ? get_comment_meta($comment_id, HT_VOTING_KEY) : $votes;
			//loop through and compare users vote
			if($votes && !empty($votes)){
				foreach ($votes as $key => $vote) {
					//if user id is same (and not 0), return vote
					if( $vote->user_id > 0 && $vote->user_id == $comp_vote->user_id )
						return $vote;
					//if ip is same, return vote
					if( $vote->ip == $comp_vote->ip )
						return $vote;
					//else try next one
					continue;
				}
			} else {
				return;
			}
		}

		/**
		* Test whether the user has voted
		* @param (Int) $post_id The post_id to get the user vote for
		* @param (Array) $votes Existing vote array object to search for first (otherwise load post meta)
		* @return (Bool) True when user has already voted
		*/
		function has_user_voted($post_id, $votes=null){
			$user_vote = $this->get_users_post_vote( $post_id, $votes );
			$voted = (empty( $user_vote )) ? false : true;
			return $voted;
		}

		/**
	    * Register scripts and styles
	    */
	    public function register_ht_voting_shortcode_scripts_and_styles(){
	           if( !current_theme_supports( 'hero-voting-frontend-styles' ) ){	           		
	                //wp_enqueue_style( 'ht-voting-frontend-style', plugins_url( 'css/ht-voting-frontend-style.css', __FILE__ ), false, true );
	           }

	          
	           wp_register_script( 'ht-voting-frontend-script', plugins_url( 'js/ht-voting-frontend-script.js', __FILE__ ), array('jquery') , 1.0, true );
	            
	           
	            wp_localize_script( 'ht-voting-frontend-script', 'voting', array( 
	            		'log_in_required' => __('You must be logged in to vote on this', 'ht-knowledge-base'), 
                		'ajaxurl' => admin_url( 'admin-ajax.php' ), 
                		'ajaxnonce' => wp_create_nonce('ht-voting-ajax-nonce') 
	                ));
	                
				
	    }

	    /**
	    * Print scripts and styles
	    */
	    public function print_ht_voting_shortcode_scripts_and_styles(){
	           if( $this->add_script ){
	                wp_print_styles( 'ht-voting-frontend-style' );
	           }
	            

	    }

	   /**
	    * HT Voting Head
	    */
	    public function ht_voting_head(){
	    	global $_GET;
	    	$direction = array_key_exists('vote', $_GET) ? $_GET['vote'] : '';
	    	$post_id = array_key_exists('post', $_GET) ? $_GET['post'] : '';
	    	$comment_id = array_key_exists('comment', $_GET) ? $_GET['comment'] : '';
	    	$nonce = array_key_exists('_htvotenonce', $_GET) ? $_GET['_htvotenonce'] : '';
	    	if(!empty($direction)){
	    		//verify security
	    		if ( ! wp_verify_nonce( $nonce, 'ht-post-vote' ) ) {
	    			die( 'Security check - head' ); 
	    		} else {
	    			if(!empty($post_id) ){
			    		//vote	post    		
			    		$this->vote_post($post_id, $direction);
			    	}
			    	if(!empty($comment_id) ){
			    		//vote	comment		
			    		$this->vote_comment($comment_id, $direction);
			    	}
	    		}   				
	    	}	    	
	    }

	   /**
	    * Ajax voting callback 
	    */
	    public function ht_ajax_voting_callback(){
	        global $_POST;
	    	$direction = array_key_exists('direction', $_POST) ? sanitize_text_field($_POST['direction']) : '';
	    	//type - either post or comment
	    	$type = array_key_exists('type', $_POST) ? sanitize_text_field($_POST['type']) : '';
	    	$nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
	    	$id = array_key_exists('id', $_POST) ? sanitize_text_field($_POST['id']) : '';
	    	$allow = array_key_exists('allow', $_POST) ? sanitize_text_field($_POST['allow']) : '';
	    	$display = array_key_exists('display', $_POST) ? sanitize_text_field($_POST['display']) : '';

	        if(!empty($direction)){
	    			if( $type=='post' ){
	    				 if ( ! wp_verify_nonce( $nonce, 'ht-voting-post-ajax-nonce' ) ){
	    				 	die( 'Security check - voting callback' );
	    				 } else {
	    				 	//vote	post    		
			    			$vote = $this->vote_post($id, $direction);
							$this->ht_voting_post_display($id, $allow, $display, $vote);
	    				 }	
			    	}		
	    	}	  
	        die(); // this is required to return a proper result
	    }


	   /**
	    * Ajax add feedback callback
	    */
	    public function ht_ajax_voting_update_feedback_callback(){
	        global $_POST;
	    	$vote_key = array_key_exists('key', $_POST) ? sanitize_text_field($_POST['key']) : '';
	    	$post_id = array_key_exists('id', $_POST) ? sanitize_text_field($_POST['id']) : '';
	    	$nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
	    	$comment = array_key_exists('comment', $_POST) ? sanitize_text_field($_POST['comment']) : '';
	        if(!empty($vote_key)){
				 if ( ! wp_verify_nonce( $nonce, 'ht-voting-feedback-ajax-nonce' ) ){
				 	die( 'Security check - update feedback callback' );
				 } else {
				 	//add feedback to vote
				 	$this->ht_voting_add_vote_comment($vote_key, $post_id, $comment);
				 	_e('Thanks for your feedback', 'ht-knowledge-base');
				 }				    	
	    	}	  
	        die(); // this is required to return a proper result
	    }

	   /**
	    * Perform the voting action for a post
	    * @param (Int) $post_id The post id to add a vote to
	    * @param (String) $direction Direction of vote up/down/neutral
	    */
	    public function vote_post($post_id, $direction){
	    	//get the users vote and delete it
	    	$user_vote = $this->get_users_post_vote($post_id);
	    	if($user_vote && !empty($user_vote)){
	    		//delete the old use vote
	    		delete_post_meta($post_id, HT_VOTING_KEY, $user_vote);
	    		//update the helpfulness
	    		if( is_a( $user_vote, 'HT_Vote_Up' ) )
					$this->update_article_helpfulness($post_id, -1);

				if( is_a( $user_vote, 'HT_Vote_Down' ) )
					$this->update_article_helpfulness($post_id, +1);
	    	}

	    	$vote = null;

	    	switch($direction){
	    		case 'up':
	    			if(class_exists('HT_Vote_Up')){
	    				$vote = new HT_Vote_Up();
	    				add_post_meta($post_id, HT_VOTING_KEY, $vote, false);
	    				$this->update_article_helpfulness($post_id, +1);
	    			}
	    			break;
	    		case 'down':
	    			if(class_exists('HT_Vote_Down')){
	    				$vote = new HT_Vote_Down();
	    				add_post_meta($post_id, HT_VOTING_KEY, $vote, false);
	    				$this->update_article_helpfulness($post_id, -1);
	    			}
	    			break;
	    		case 'neutral':
	    			if(class_exists('HT_Vote_Neutral')){
	    				$vote = new HT_Vote_Neutral();
	    				add_post_meta($post_id, HT_VOTING_KEY, $vote, false);
	    			}
	    			break;
	    		default:
	    			//numeric value
	    			if(is_numeric($direction)&&class_exists('HT_Vote_Value')){
						$vote_val = intval($direction);
						$vote = new HT_Vote_Value( $vote_val );
						echo " ADDING POST META ";
						var_dump($vote);
						echo HT_VOTING_KEY;
						echo $post_id;
						add_post_meta($post_id, HT_VOTING_KEY, $vote, false);
	    			}
	    			break;
	    	}

	    	//return the vote just made
	    	if(is_a($vote, 'HT_Vote')){
	    		return $vote;
	    	}
	    }

	   /**
	    * Perform the voting action for a comment
	    * @param (Int) $comment_id The comment id to add a vote to
	    * @param (String) $direction Direction of vote up/down/neutral
	    */
	    public function vote_comment($comment_id, $direction){
	    	//get the users vote and delete it
	    	$user_vote = $this->get_users_comment_vote($comment_id);
	    	if($user_vote && !empty($user_vote)){
	    		//delete the old use vote
	    		delete_comment_meta($comment_id, HT_VOTING_KEY, $user_vote);
	    		//update the helpfulness
	    		if( is_a( $user_vote, 'HT_Vote_Up' ) )
					$this->update_comment_helpfulness($comment_id, -1);

				if( is_a( $user_vote, 'HT_Vote_Down' ) )
					$this->update_comment_helpfulness($comment_id, +1);
	    	}

	    	switch($direction){
	    		case 'up':
	    			if(class_exists('HT_Vote_Up')){
	    				$vote = new HT_Vote_Up();
	    				add_comment_meta($comment_id, HT_VOTING_KEY, $vote, false);
	    				$this->update_comment_helpfulness($comment_id, +1);
	    			}
	    			break;
	    		case 'down':
	    			if(class_exists('HT_Vote_Down')){
	    				$vote = new HT_Vote_Down();
	    				add_comment_meta($comment_id, HT_VOTING_KEY, $vote, false);
	    				$this->update_comment_helpfulness($comment_id, -1);
	    			}
	    			break;
	    		case 'neutral':
	    			if(class_exists('HT_Vote_Neutral')){
	    				$vote = new HT_Vote_Neutral();
	    				add_comment_meta($comment_id, HT_VOTING_KEY, $vote, false);
	    			}
	    			break;
	    		default:
	    			//numeric value
	    			if(is_numeric($direction)&&class_exists('HT_Vote_Value')){
						$vote_val = intval($direction);
						$vote = new HT_Vote_Value( $vote_val );
						echo " ADDING POST META ";
						var_dump($vote);
						echo HT_VOTING_KEY;
						echo $comment_id;
						add_comment_meta($comment_id, HT_VOTING_KEY, $vote, false);
	    			}
	    			break;
	    	}
	    }

	    /**
	    * Update article helpfulness
	    * @param (Int) $post_id The post id to update the helpfulness for
	    * @param (Int) $value +1 or -1
		*/
	    function update_article_helpfulness($post_id, $value){
	    	//get existing helpfulness
	    	$helpfulness = get_post_meta( $post_id, HT_USEFULNESS_KEY, true);

	    	//if not yet set, set it
	    	if(empty($helpfulness))
	    		$helpfulness = 0;

	    	//update the helpfulness
	    	$helpfulness = $helpfulness + $value;

	    	//save the post meta
	    	update_post_meta( $post_id, HT_USEFULNESS_KEY, $helpfulness );

	    	//also update the user helpfulness
	    	$post = get_post( $post_id );
	    	$this->update_user_helpfulness( $post->post_author, $value );
	    }

	    /**
	    * Update comment helpfulness
	    * @param (Int) $comment_id The comment id to update the helpfulness for
	    * @param (Int) $value +1 or -1
		*/
	    function update_comment_helpfulness($comment_id, $value){
	    	//get existing helpfulness
	    	$helpfulness = get_comment_meta( $comment_id, HT_USEFULNESS_KEY, true);

	    	//if not yet set, set it
	    	if(empty($helpfulness))
	    		$helpfulness = 0;

	    	//update the helpfulness
	    	$helpfulness = $helpfulness + $value;

	    	//save the comment meta
	    	update_comment_meta( $comment_id, HT_USEFULNESS_KEY, $helpfulness );

	    	//also update the user helpfulness
	    	$comment = get_comment( $comment_id );
	    	$this->update_user_helpfulness( $comment->user_id, $value );
	    }

	    /**
	    * Update user helpfulness
	    * @param (Int) $user_id The user id to update the helpfulness for
	    * @param (Int) $value +1 or -1
		*/
	    function update_user_helpfulness($user_id, $value){
	    	//get existing helpfulness
	    	$helpfulness = get_user_meta( $user_id, HT_USEFULNESS_KEY, true);

	    	//if not yet set, set it
	    	if(empty($helpfulness))
	    		$helpfulness = 0;

	    	//update the helpfulness
	    	$helpfulness = $helpfulness + $value;

	    	//save the user meta
	    	update_user_meta( $user_id, HT_USEFULNESS_KEY, $helpfulness );
	    }

	    /**
		 * Upgrade the meta key values
		 * @param (Int) $post_id The post id being upgraded
		 */
		public static function ht_voting_upgrade_post_meta_fields($postID){
			//keys to be upgraded
			HT_Voting::ht_voting_upgrade_voting_meta_fields($postID, 'voting_checkbox');
			HT_Voting::ht_voting_upgrade_voting_meta_fields($postID, 'voting_reset');
			HT_Voting::ht_voting_upgrade_voting_meta_fields($postID, 'voting_reset_confirm');
		}


	    /**
		 * Upgrade a post meta field
		 * @param (String) $name The name of the meta field to be upgraded
		 */
		static function ht_voting_upgrade_voting_meta_fields($postID, $name){
			$old_prefix = '_ht_knowledge_base_';
			$new_prefix = '_ht_voting_';

			//get the old value
			$old_value = get_post_meta($postID, $old_prefix . $name, true);
			if(!empty($old_value)){
				//get the new value
				$new_value = get_post_meta($postID, $new_prefix . $name, true);
				if(empty($new_value)){
					//sync the new value to the old value
					update_post_meta($postID, $new_prefix . $name, $old_value);
				}
				
			}
			//delete old meta key
			delete_post_meta($postID, $old_prefix . $name);
		}

		/**
		* Display voting - use shortcode
		*/
		function ht_voting_display_voting(){
			global $ht_knowledge_base_options;
			$voting_disabled =  get_post_meta( get_the_ID(), '_ht_voting_voting_disabled', true );
			$allow_voting_on_this_article = $voting_disabled ? false : true;


		
			// voting
			if($ht_knowledge_base_options['voting-display'] && $allow_voting_on_this_article ){ ?>
				<div class="hkb-feedback">
					<h3 class="hkb-feedback__title"><?php _e('Was this article helpful?', 'ht-knowledge-base'); ?></h3>
					<?php if( $ht_knowledge_base_options['anon-voting'])
						echo do_shortcode('[ht_voting allow="anon"]');
					else
						echo do_shortcode('[ht_voting allow="user"]');
					?>
				</div>
				<?php
			}


		}


		/**
		* Get the comments for a vote
		* @param (String) $vote_key The vote key
		* @param (Int) $post_id The post id
		* @return (String) Comments/feedback for vote
		*/
		function ht_voting_get_vote_comment($vote_key, $post_id){
			$post_comments = $this->get_post_votes($post_id);
			$post_comment = null;
			foreach ($post_comments as $key => $comment) {
				if(property_exists($comment, 'key') && $vote_key==$comment->key){
					$post_comment = $comment;
				}				
			}
			return $post_comment;
		}

		/**
		* Add vote comments/feedback
		* @param (String) $vote_key The vote key
		* @param (Int) $post_id The post id
		* @param (String) $comment Comments/Feedback to add to vote
		*/
		function ht_voting_add_vote_comment($vote_key, $post_id, $comment=''){
			$vote = $this->get_users_post_vote_by_key($post_id, null, $vote_key);
			if(isset($vote)){
				//delete vote from db
				delete_post_meta($post_id, HT_VOTING_KEY, $vote);
				//set comments
				$vote->set_comments($comment);
				//re-add vote to db
				add_post_meta($post_id, HT_VOTING_KEY, $vote, false);
			} else {
				_e('Cannot retrieve vote', 'ht-knowledge-base');
				echo $vote_key;
			}
		}

		/**
		* Delete vote by key
		* @param (String) $vote_key The vote key
		* @param (Int) $post_id The post id
		*/
		function ht_voting_delete_vote($vote_key, $post_id){
			$vote = $this->get_users_post_vote_by_key($post_id, null, $vote_key);
			delete_post_meta($post_id, HT_VOTING_KEY, $vote);
			//update the helpfulness
    		if( is_a( $vote, 'HT_Vote_Up' ) )
				$this->update_article_helpfulness($post_id, -1);

			if( is_a( $vote, 'HT_Vote_Down' ) )
				$this->update_article_helpfulness($post_id, +1);
		}    

		/**
		* Deletes all votes for a post
		* @param (Int) $post_id The post id
		*/
		function ht_voting_delete_all_post_votes($post_id){
			delete_post_meta($post_id, HT_VOTING_KEY);
			delete_post_meta($post_id, HT_USEFULNESS_KEY);
			update_post_meta( get_the_ID(), HT_USEFULNESS_KEY, 0 );
		}

		/**
		* Update article usefulness
		* @param (Int) $post_id The post id
		*/
		function ht_voting_update_article_usefulness($post_id){
			$usefulness = 0;
			//get all votes
			$votes = ht_voting_get_post_votes($post_id);
			foreach ($votes as $key => $vote) {
				//update the helpfulness
	    		if( is_a( $vote, 'HT_Vote_Up' ) ){
	    			$usefulness++;
	    		} elseif ( is_a( $vote, 'HT_Vote_Down' ) ) {
	    			$usefulness--;
	    		}				
			}
			//update the usefulness
			update_post_meta($post_id, HT_USEFULNESS_KEY, $usefulness);
		}



	} //end class
} //end class exists

if(class_exists('HT_Voting')){
	global $ht_voting_init;

	$ht_voting_init = new HT_Voting();

	if(!function_exists('ht_voting_post')){
		function ht_voting_post( $post_id=null, $allow='user', $display='standard', $vote=null ){
			global $post, $ht_voting_init;
			$post_id = ( empty( $post_id ) ) ? $post->ID : $post_id;
			$ht_voting_init->ht_voting_post_display( $post_id, $allow, $display, $vote );
		}
	} //end if ht_voting_post

	if(!function_exists('ht_voting_comment')){
		function ht_voting_comment( $comment_id=null, $allow='user', $display='standard' ){
			global $ht_voting_init;
			if( empty( $comment_id ) )
				return;

			$ht_voting_init->ht_voting_comment_display( $comment_id, $allow, $display );
		}
	} //end if ht_voting_comment

	if(!function_exists('ht_usefulness')){
		function ht_usefulness( $post_id=null ){
			global $post;
			//set the post id
			$post_id = ( empty( $post_id ) ) ? $post->ID : $post_id;
			//get the post usefulness meta
			$post_usefulness = get_post_meta( $post_id, HT_USEFULNESS_KEY, true );
			//convert to integer
			$post_usefulness_int = empty($post_usefulness) ? 0 : intval($post_usefulness);
			//return as integer
			return $post_usefulness_int;
		}
	} //end if ht_usefulness

	if(!function_exists('ht_voting_get_post_votes')){
		function ht_voting_get_post_votes( $post_id=null ){
			global $ht_voting_init;
			
			return $ht_voting_init->get_post_votes($post_id);
		}
	} //end if ht_voting_get_post_votes


	if(!function_exists('ht_voting_delete_vote')){
		function ht_voting_delete_vote( $vote_key, $post_id ){
			global $ht_voting_init;
			
			return $ht_voting_init->ht_voting_delete_vote($vote_key, $post_id);
		}
	} //end if ht_voting_delete_vote

	if(!function_exists('ht_voting_delete_all_post_votes')){
		function ht_voting_delete_all_post_votes( $post_id ){
			global $ht_voting_init;
			
			return $ht_voting_init->ht_voting_delete_all_post_votes( $post_id );
		}
	} //end if ht_voting_delete_all_post_votes


	if(!function_exists('ht_voting_update_article_usefulness')){
		function ht_voting_update_article_usefulness( $post_id ){
			global $ht_voting_init;
			
			return $ht_voting_init->ht_voting_update_article_usefulness( $post_id );
		}
	} //end if ht_voting_update_article_usefulness
	





}
