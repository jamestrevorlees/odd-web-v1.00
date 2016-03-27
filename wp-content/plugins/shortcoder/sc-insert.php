<?php
/**
 * Shortcoder include for inserting and editing shortcodes in post and pages
 * v1.3
 **/
 
if ( ! isset( $_GET['TB_iframe'] ) )
	define( 'IFRAME_REQUEST' , true );


// Load WordPress
require_once('../../../wp-load.php');

if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
    wp_die(__('You do not have permission to edit posts.'));

// Load all created shortodes
$sc_options = get_option('shortcoder_data');

if( empty($sc_options) )
	die( "Sorry !! No Shortcodes to Insert<br/><a href='" . SC_ADMIN . "' target='_blank'>Create Shortcodes</a>" );
		
?>

<html>
<head>
<title>Shortcodes created</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<style type="text/css">
body{
	font: 13px Arial, Helvetica, sans-serif;
	padding: 10px;
	background: #f2f2f2;
}
h2{
	font-size: 23px;
	font-weight: normal;
}
h4{
	margin: 0 0 20px 0;
}
hr{
	border-width: 0px;
	margin: 15px 0;
	border-bottom: 1px solid #DFDFDF;
}
.sc_wrap{
	border: 1px solid #DFDFDF;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.sc_shortcode{
	border-bottom: 1px solid #CCC;
	padding: 0px;
	background: #FFF;
}
.sc_shortcode_name{
	cursor: pointer;
	padding: 10px;
}
.sc_shortcode_name:hover{
	background: #fbfbfb;
}
.sc_params{
	border: 1px solid #DFDFDF;
	background: #F9F9F9;
	margin: 0 -1px -1px;
	padding: 20px;
	display: none;
}
.sc_insert{
	background: linear-gradient(to bottom, #09C, #0087B4);
	color: #FFF;
	padding: 5px 15px;
	border: 1px solid #006A8D;
	font-weight: bold;
}

.sc_insert:hover{
	opacity: 0.8;
}
input[type=text], textarea{
	padding: 5px;
	border: 1px solid #CCC;
	width: 120px;
	margin: 0px 25px 10px 0px;
}
.sc_toggle{
	background: url(images/toggle-arrow.png) no-repeat;
	float: right;
	width: 16px;
	height: 16px;
	opacity: 0.4;
}

.sc_share_iframe{
	background: #FFFFFF;
	border: 1px solid #dfdfdf;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	-moz-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
	-webkit-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
	box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
}
.sc_credits{
	background: url(images/aw.png) no-repeat;
	padding-left: 23px;
	color: #8B8B8B;
	margin-left: -5px;
	font-size: 13px;
	text-decoration: none;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	
	$('.sc_shortcode_name').append('<span class="sc_toggle"></span>');
	
	$('.sc_insert').click(function(){
		var params = '';
		var scname = $(this).attr('data-name');
		var sc = '';
		
		$(this).parent().children().find('input[type="text"]').each(function(){
			if($(this).val() != ''){
				attr = $(this).attr('data-param');
				val = $(this).val();
				params += attr + '="' + val + '" ';
			}
		});
		
		// Not used
		if(wsc(scname)){
			name = '"' + scname + '"';
		}else{
			name = scname;
		}
		//
		
		sc = '[sc name="' + scname + '" ' + params + ']';
		
		if( typeof parent.send_to_editor !== undefined ){
			parent.send_to_editor(sc);
		}
		
	});
	
	$('.sc_share_bar img').mouseenter(function(){
		$this = $(this);
		$('.sc_share_iframe').remove();
		$('body').append('<iframe class="sc_share_iframe"></iframe>');
		$('.sc_share_iframe').css({
			position: 'absolute',
			top: $this.offset()['top'] - $this.attr('data-height') - 15,
			left: $this.offset()['left'] - $this.attr('data-width')/2 ,
			width: $this.attr('data-width'),
			height: $this.attr('data-height'),
		}).attr('src', $this.attr('data-url')).hide().fadeIn();
	
	});
	
	$('.sc_shortcode_name').click(function(e){
		$('.sc_params').slideUp();
		if($(this).next('.sc_params').is(':visible')){
			$(this).next('.sc_params').slideUp();
		}else{
			$(this).next('.sc_params').slideDown();
		}
	})
	
});

var sc_closeiframe = function(){
	$('.sc_share_iframe').remove();
}

function wsc(s){
	if(s == null)
		return '';
	return s.indexOf(' ') >= 0;
}
</script>
</head>
<body>
<?php sc_admin_buttons('fbrec'); ?>
<h2><img src="images/shortcoder.png" align="absmiddle" alt="Shortcoder" width="35px"/> Insert shortcode to editor</h2>

<div class="sc_wrap">
<?php
foreach($sc_options as $key=>$value){
	if($key != '_version_fix'){
		echo '<div class="sc_shortcode"><div class="sc_shortcode_name">' . $key;
		echo '</div>';
		preg_match_all('/%%[^%\s]+%%/', $value['content'], $matches);
		echo '<div class="sc_params">';
		if(!empty($matches[0])){
			echo '<h4>Available parameters: </h4>';
			$temp = array();
			foreach($matches[0] as $k=>$v){
				$cleaned = str_replace('%', '', $v);
				if(!in_array($cleaned, $temp)){
					array_push($temp, $cleaned);
					echo '<label>' . $cleaned . ': <input type="text" data-param="' . $cleaned . '"/></label> ';
				}
			}
			echo'<hr/>';
		}else{
			echo 'No parameters avaialble - ';
		}
		echo '<input type="button" class="sc_insert cupid-blue" data-name="' . $key . '" value="Insert Shortcode"/>';
		echo '</div>';
		echo '</div>';
	}
}
?>
</div>

<p class="sc_share_bar" align="center">
	<img class="sc_donate" src="images/donate.png" data-width="300" data-height="220" data-url="<?php echo SC_URL . '/js/share.php?i=1'; ?>"/>
	&nbsp;&nbsp;&nbsp;
	<img class="sc_share" src="images/share.png" data-width="350" data-height="85" data-url="<?php echo SC_URL . '/js/share.php?i=2'; ?>"/>
</p>

<p align="center"><a class="sc_credits" href="http://www.aakashweb.com/" target="_blank">a Aakash Web plugin</a></p>

</body>
</html>