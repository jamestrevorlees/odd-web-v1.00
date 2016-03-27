<?php
/*
Plugin Name: Shortcoder
Plugin URI: http://www.aakashweb.com
Description: Shortcoder is a plugin which allows to create a custom shortcode and store HTML, Javascript and other snippets in it. So if that shortcode is used in any post or pages, then the code stored in the shortcode get exceuted in that place. You can create a shortcode for Youtube videos, adsense ads, buttons and more.
Author: Aakash Chakravarthy
Version: 3.4.1
Author URI: http://www.aakashweb.com/
*/

define('SC_VERSION', '3.4.1');
define('SC_AUTHOR', 'Aakash Chakravarthy');
define('SC_URL', plugins_url('',__FILE__) );
define('SC_ADMIN', admin_url( 'options-general.php?page=shortcoder' ) );

$sc_donate_link = 'http://bit.ly/scDonate';



// Load languages
load_plugin_textdomain('shortcoder', false, basename(dirname(__FILE__)) . '/languages/');



// Add admin menu
function sc_add_menu() {
	add_options_page( 'Shortcoder', 'Shortcoder', 'manage_options', 'shortcoder', 'sc_admin_page' );
}

add_action('admin_menu','sc_add_menu');



// Load the Javascripts
function sc_admin_js(){
	// Check whether the page is the Shortcoder admin page.
	if (isset($_GET['page']) && $_GET['page'] == 'shortcoder'){
		wp_enqueue_script(array(
			'jquery',
			'jquery-ui-core',
			'jquery-ui-draggable',
			'jquery-ui-droppable'
		));
		wp_enqueue_script('shortcoder-admin-js', SC_URL . '/sc-admin-js.js?v=' . SC_VERSION);
	}
}
add_action('admin_print_scripts', 'sc_admin_js');



// Load the CSS
function sc_admin_css(){
	if (isset($_GET['page']) && $_GET['page'] == 'shortcoder') {
		wp_enqueue_style('shortcoder-admin-css', SC_URL . '/sc-admin-css.css?v=' . SC_VERSION);
	}
}
add_action('admin_print_styles', 'sc_admin_css');



// Plugin on activate fixes
function sc_onactivate(){
	$sc_options = get_option('shortcoder_data');
	$sc_flags = get_option('shortcoder_flags');
	
	// Move the flag version fix to sc_flags option
	if(isset($sc_options['_version_fix'])){
		unset($sc_options['_version_fix']);
		update_option('shortcoder_data', $sc_options);
	}
	
	$sc_flags['version'] = SC_VERSION;
	update_option('shortcoder_flags', $sc_flags);

}
register_activation_hook(__FILE__, 'sc_onactivate');




// Register the shortcode
add_shortcode('sc', 'shortcoder');

function shortcoder_all_ok($name){

	$sc_options = get_option('shortcoder_data');
	
	if($sc_options[$name]['disabled'] == 0){
		if(current_user_can('level_10') && $sc_options[$name]['hide_admin'] == 1){
			return false;
		}else{
			return true;
		}
	}else{
		return false;
	}
}

// Main function
function shortcoder($atts, $content) { 
	
	$sc_options = get_option('shortcoder_data');

	// Get the Shortcode name
	if(isset($atts[0])){
		$sc_name = str_replace(array('"', "'", ":"), '', $atts[0]);
		unset($atts[0]);
	}else{
		// Old version with "name" param support
		if(array_key_exists("name", $atts)){
			$tVal = $atts['name'];
			if(array_key_exists($tVal, $sc_options)){
				$sc_name = $tVal;
				unset($atts['name']);
			}
		}
	}
	
	if(!isset($sc_name)){
		return '';
	}
	
	// Check whether shortcoder can execute
	if(shortcoder_all_ok($sc_name)){
	
		$sc_content_final = '';
	
		// If SC has parameters, then replace it
		if(!empty($atts)){
			$keys = array();
			$values = array();
			$i = 0;
	
			// Seperate Key and value from atts
			foreach($atts as $k=>$v){
				if($k !== 0){
					$keys[$i] = "%%" . $k . "%%";
					$values[$i] = $v;
				}
				$i++;
			}
			
			// Replace the params
			$sc_content = $sc_options[$sc_name]['content'];	
			$sc_content_rep1 = str_replace($keys, $values, $sc_content);
			$sc_content_final = preg_replace('/%%[^%\s]+%%/', '', $sc_content_rep1);
			
		}
		else{
		
			// If the SC has no params, then replace the %vars%
			$sc_content = $sc_options[$sc_name]['content'];	
			$sc_content_final = preg_replace('/%%[^%\s]+%%/', '', $sc_content);
			
		}
		
		return "<!-- Start Shortcoder content -->" . do_shortcode( $sc_content_final ) . "<!-- End Shortcoder content -->";
		
	}else{
		return '';
	}
}


// Workaround for shortcoder not working in WordPress 4.4
function sc_replace_colon_content( $content ){
	return str_replace( '[sc:', '[sc name=', $content );
}
add_filter( 'the_content', 'sc_replace_colon_content', 5 );


// Shortcoder admin page
function sc_admin_page(){
	
	$sc_updated = false;
	$sc_options = get_option('shortcoder_data');
	$sc_flags = get_option('shortcoder_flags');
	
	$title = __( "Create a Shortcode", 'shortcoder' );
	$button = __( "Create Shortcode", 'shortcoder' );
	$edit = 0;
	$sc_content = '';
	$sc_disable = 0;
	$sc_hide_admin = 0;
	
	// Insert shortcode
	if (isset($_POST["sc_form_main"]) && $_POST["sc_form_main"] == '1' && check_admin_referer('shortcoder_create_form')){
		$sc_options = get_option('shortcoder_data');
		$sc_name = stripslashes($_POST['sc_name']);
		
		$sc_post_disabled = isset( $_POST['sc_disable'] ) ? intval( $_POST['sc_disable'] ) : 0;
		$sc_post_hideadmin = isset( $_POST['sc_hide_admin'] ) ? intval( $_POST['sc_hide_admin'] ) : 0;
		
		$sc_options[$sc_name] = array(
			'content' => stripslashes($_POST['sc_content']),
			'disabled' => $sc_post_disabled,
			'hide_admin' => $sc_post_hideadmin
		);
		
		// Updating the DB
		update_option("shortcoder_data", $sc_options);
		$sc_updated = true;
		
		// Insert Message
		if($sc_updated == 'true'){
			echo '<div class="message updated fade"><p>' . __('Shortcode updated successfully !', 'shortcoder') . '</p></div>';
		}else{
			echo '<div class="message error fade"><p>' . __('Unable to create shortcode !', 'shortcoder') . '</p></div>';
		}
	}
	
	// Edit shortcode
	if (isset($_POST["sc_form_edit"]) && $_POST["sc_form_edit"] == '1' && check_admin_referer('shortcoder_edit_form')){
		$sc_options = get_option('shortcoder_data');
		$sc_name_edit = stripslashes($_POST['sc_name_edit']);
		
		if($_POST["sc_form_action"] == "edit"){
			$sc_content = stripslashes($sc_options[$sc_name_edit]['content']);
			$sc_disable = $sc_options[$sc_name_edit]['disabled'];
			$sc_hide_admin = $sc_options[$sc_name_edit]['hide_admin'];
			
			$title = __( 'Edit this Shortcode - ', 'shortcoder' ) . '<small>' . $sc_name_edit . '</small>';
			$button = __( 'Update Shortcode', 'shortcoder' );
			$edit = 1;
		}else{
			unset($sc_options[$sc_name_edit]);
			unset($sc_name_edit);
			update_option("shortcoder_data", $sc_options);
			echo '<div class="message updated fade"><p>' . __('Shortcode deleted successfully !', 'shortcoder') . '</p></div>';
		}
	}

	
?>

<!-- Shortcoder Admin page --> 

<div class="sc_modal">
<span class="sc_modal_close"></span>
<div></div>
</div>

<div class="wrap">
<?php sc_admin_buttons('fbrec'); ?>
<h2><img width="32" height="32" src="<?php echo SC_URL; ?>/images/shortcoder.png" align="absmiddle"/> Shortcoder<sup class="smallText"> v<?php echo SC_VERSION; ?></sup></h2>

<ul class="sc_share_wrap">
<li class="sc_donate" data-width="300" data-height="220" data-url="<?php echo SC_URL . '/js/share.php?i=1'; ?>"><a href="#"></a></li>
<li class="sc_share"  data-width="350" data-height="85" data-url="<?php echo SC_URL . '/js/share.php?i=2'; ?>"><a href="#"></a></li>
</ul>

<div id="content">
	
	<h3><?php echo $title; ?> <?php if($edit == 1) echo '<span class="button sc_back">&lt;&lt; ' . __( "Back", 'shortcoder' ) . '</span>'; ?> </h3>
	
	<form method="post" id="sc_form">
	
		<div class="sc_section">
			<label for="sc_name" class="sc_fld_title"><?php _e( "Title:", 'shortcoder' ); ?></label>
			<span class="sc_name_wrap"><input type="text" name="sc_name" id="sc_name" value="<?php echo isset($sc_name_edit) ? $sc_name_edit : ''; ?>" placeholder="Enter a shortcode name" class="widefat" required="required"/><div id="sc_code"></div></span>
		</div>
		

		<div class="sc_section">
			<label for="sc_content" class="sc_fld_title"><?php _e( "Content:", 'shortcoder' ); ?></label>
			<?php wp_editor( $sc_content, 'sc_content', array( 'wpautop'=> false, 'textarea_rows'=> 8 )); ?>
		</div>
		
		
		<div class="sc_section"><p><b>Note:</b> Use <strong style="color:#006600">%%someParameter%%</strong> to insert custom parameters. <a href="http://www.aakashweb.com/docs/shortcoder-doc/" target="_blank">Learn More</a></p></div>
		
		<div class="sc_section">
		
			<table width="100%"><tr>
				
				<td width="50%" class="sc_settings"><label><input name="sc_disable" type="checkbox" value="1" <?php echo $sc_disable == "1" ? 'checked="checked"' : ""; ?>/> <?php _e( "Temporarily disable this shortcode", 'shortcoder' ); ?></label>
				<label><input name="sc_hide_admin" type="checkbox" value="1" <?php echo $sc_hide_admin == "1" ? 'checked="checked"' : ""; ?>/> <?php _e( "Disable this Shortcode to admins", 'shortcoder' ); ?></label></td>
				
				<td><p align="right"><input type="submit" name="sc_submit" id="sc_submit" class="button-primary" value="<?php echo $button; ?>" /></p></td>
	
			</tr></table>
	
		</div>
		
		<?php wp_nonce_field('shortcoder_create_form'); ?>
		<input name="sc_form_main" type="hidden" value="1" />
	</form>
	
	<h3><?php _e( "Created Shortcodes", 'shortcoder' ); ?> <small>(<?php _e( "Click to edit", 'shortcoder' ); ?>)</small></h3>
	<form method="post" id="sc_edit_form">
		<ul id="sc_list" class="clearfix">
		<?php
			$sc_options = get_option('shortcoder_data');
			if(is_array($sc_options)){
				foreach($sc_options as $key=>$value){
					echo '<li>' . $key . '</li>';
				}
			}
		?>
		</ul>
		
		<?php wp_nonce_field('shortcoder_edit_form'); ?>
		<input name="sc_form_edit" type="hidden" value="1" />
		<input name="sc_form_action" id="sc_form_action" type="hidden" value="edit" />
		<input name="sc_name_edit" id="sc_name_edit" type="hidden" />
	</form>
	
	<div id="sc_delete" title="Drag & drop shortcodes to delete"></div>
	
</div><!-- Content -->

<br/>
<p align="center">
	<a href="http://www.aakashweb.com/forum/" target="_blank">Report bugs</a> | <a href="http://www.aakashweb.com/forum/" target="_blank">Support Forum</a> | <a href="http://www.aakashweb.com/wordpress-plugins/shortcoder/" target="_blank">Help</a> | <a href="http://bit.ly/scdonate" target="_blank">Donate</a><br/><br/>
	<a href="#" class="sc_open_video">(Demo video)</a><br /><br />
	<a href="https://twitter.com/vaakash" target="_blank">Follow @vaakash</a><br/><br/>
	<a href="http://www.aakashweb.com/" target="_blank" class="sc_credits">a plugin from Aakash Web</a>
</p>

<script>
var sc_version = '<?php echo SC_VERSION; ?>';
</script>

</div><!-- Wrap -->

<?php
}



// Helper for SC TinyMCE button
function sc_admin_footer(){

	if (isset($_GET['page']) && $_GET['page'] == 'shortcoder')
		return;
	
	echo "
<script>
window.onload = function(){
	if( typeof QTags === 'function' )
		QTags.addButton( 'QT_sc_insert', 'Shortcoder', sc_show_insert );
}
function sc_show_insert(){
	tb_show('Insert a Shortcode', '" . SC_URL . "/sc-insert.php?TB_iframe=true');
}
</script>
";
}
add_action('admin_footer', 'sc_admin_footer');




function sc_admin_buttons($type){
	switch($type){
		case 'fbrec':
		echo '<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Ffacebook.com%2Faakashweb&amp;send=false&amp;layout=button_count&amp;width=130&amp;show_faces=false&amp;action=recommend&amp;colorscheme=light&amp;font=arial&amp;height=21&amp;appId=106994469342299" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width: 130px; height:21px;float: right;margin-top: 15px;" allowtransparency="true"></iframe>';
		break;
	}
}




// Action Links
function sc_plugin_actions($links, $file){
	static $this_plugin;
	global $sc_donate_link;
	
	if(!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	if( $file == $this_plugin ){
		$settings_link = "<a href='$sc_donate_link' title='If you like this plugin, then just make a small Donation to continue this plugin development.' target='_blank'>" . __('Make Donations', 'shortcoder') . '</a> ';
		$links = array_merge(array($settings_link), $links);
	}
	return $links;
}
add_filter('plugin_action_links', 'sc_plugin_actions', 10, 2);




// Shortcoder tinyMCE buttons
function sc_addbuttons() {
	if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') )
		return;
	
	if (isset($_GET['page']) && $_GET['page'] == 'shortcoder')
		return;
	
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "sc_add_tinymce_plugin");
		add_filter('mce_buttons', 'sc_register_button');
	}
}
 
function sc_register_button($buttons) {
   array_push($buttons, "separator", "scbutton");
   return $buttons;
}

function sc_add_tinymce_plugin($plugin_array) {
   $plugin_array['scbutton'] = SC_URL . '/js/tinymce/editor_plugin.js';
   return $plugin_array;
}
add_action('init', 'sc_addbuttons'); // init process for button control

?>