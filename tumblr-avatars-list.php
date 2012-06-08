<?php
/*
Plugin Name: Tumblr Avatars List
Plugin URI: http://originalexe.com/
Description: Tumblr Avatars List allows users to submit tumblr. usernames via widget displayed on your website. Usernames and avatar url-s are then stored in your database, and can be called with shortcodes.
Author: OriginalEXE
Version: 1.0
Author URI: http://originalexe.com/
*/

// Let's add options for this plugin that you can use to set up plugins parameters.

add_action('admin_menu', 'tumblr_avatars_list_menu');

function tumblr_avatars_list_menu() {
	add_options_page('Tumblr Avatars List Options', 'TAL Options', 'manage_options', 'tal-options', 'tal_options');
}

// Building the output for plugin options page.
function tal_options() {
	
	if ( $_POST['update_tal_options'] == 'true' ) { tal_options_update(); } 
	
	?>
	
	<div class="wrap"> 
        <div id="icon-options-general" class="icon32"><br /></div> 
        <h2>TAL Options</h2> 
		<form method="POST" action=""> 
			<h3>Front-end options</h3>
            <input type="hidden" name="update_tal_options" value="true" /> 
			<h4>Message to the visitors</h4> 
            <p><input type="text" name="visitor-message" id="visitor-message" size="15" value="<?php echo get_option('TAL_visitor-message'); ?>"/> Default is "Insert Tumblr username"</p>
			<h4>Input field value</h4> 
            <p><input type="text" name="input-field-value" id="input-field-value" size="15" value="<?php echo get_option('TAL_input_field'); ?>"/> Default is "my username"</p>	
			<h4>Submit button text</h4> 
            <p><input type="text" name="submit-button-text" id="submit-button-text" size="15" value="<?php echo get_option('TAL_submit_button'); ?>"/> Default is "Submit"</p>
			</br>
			<h3>Avatar Images</h3>
			<h4>Number of avatars displayed in sidebar widget</h4> 
            <p><input type="text" name="avatars-displayed" id="avatars-displayed" size="2" value="<?php echo get_option('TAL_avatars-displayed'); ?>"/> Default is 10</p>
			<h4>Image size stored in the database</h4> 
            <p><select name="img-size-database"> 
					<option value="<?php $imgsizedatabase = get_option('TAL_img-size-database',''); if($imgsizedatabase !== ''){ echo $imgsizedatabase;};?>"><?php if($imgsizedatabase !== ''){ echo $imgsizedatabase . 'x' . $imgsizedatabase;}; ?></option>
					<?php if($imgsizedatabase !== '16'){ echo '<option value="16">16x16</option>'; }; 
							if($imgsizedatabase !== '64'){ echo '<option value="64">64x64</option>'; };
							if($imgsizedatabase !== '128'){ echo '<option value="128">128x128</option>'; }; ?>
				</select> Note that there is a difference between this option and Image display size. Always choose the max value here that you are displaying anywhere with this plugin.</p>
            <h4>Image size displayed in a sidebar widget</h4> 
            <p><select name="img-size-widget"> 
					<option value="<?php $imgsizewidget = get_option('TAL_img-size-widget',''); if($imgsizewidget !== ''){ echo $imgsizewidget;};?>"><?php if($imgsizewidget !== ''){ echo $imgsizewidget . 'x' . $imgsizewidget;}; ?></option>
					<?php if($imgsizewidget !== '16'){ echo '<option value="16">16x16</option>'; }; 
					if($imgsizewidget !== '64'){ echo '<option value="64">64x64</option>'; };
					if($imgsizewidget !== '128'){ echo '<option value="128">128x128</option>'; }; ?>
				</select> Note that there is a difference between this option and Image display size. Always choose the max value here that you are displaying anywhere with this plugin.</p>
			<p><input type="submit" name="search" value="Update Options" class="button" /></p>  
        </form>  
	
	<?php
	}

// Let's write some functions to handle our options form.
function tal_options_update() {
	if($_POST['visitor-message'] !== ''){ update_option('TAL_visitor-message',     $_POST['visitor-message']);  };
    if($_POST['input-field-value'] !== ''){ update_option('TAL_input-field-value',     $_POST['input-field-value']);  };
	if($_POST['submit-button-text'] !== ''){ update_option('TAL_submit-button-text',     $_POST['submit-button-text']); };
	if($_POST['avatars-displayed'] !== ''){ update_option('TAL_avatars-displayed',     $_POST['avatars-displayed']); };
	if($_POST['img-size-database'] !== ''){ update_option('TAL_img-size-database',     $_POST['img-size-database']); };
	if($_POST['img-size-widget'] !== ''){ update_option('TAL_img-size-widget',     $_POST['img-size-widget']); };
	
}
	
// Include plugin stylesheet in the head of the document - the right way.
 function enqueue_my_styles()
{
	$path = plugins_url() .'/'.dirname( plugin_basename( __FILE__ ) );
	wp_enqueue_style('tumblr-list-styling',$path.'/styling.css' );
}

add_action( 'wp_print_styles', 'enqueue_my_styles' );

class TumblrAvatarsList extends WP_Widget
{
	function TumblrAvatarsList()	{
		$widget_ops = array('classname' => 'TumblrAvatarsList', 'description' => 'Display form for submitting tumblr usernames.' );
		$this->WP_Widget('TumblrAvatarsList', 'Tumblr Avatars List', $widget_ops);
	}

	function shortcode_tumblr( $atts ) {
	extract( shortcode_atts( array(
		'limit' => '10',
		'size' => '32',
	), $atts ) );
	
	$images_list = get_option('tumblr_images');
	
	// Initialize counter for foreach loop...
	$count = 1;
	
	if($images_list){
		$final = '<ul id="tumblr_list">';
		foreach ($images_list as &$image) {
			
			// Check if count is bigger than limit. If yes, break the loop...
			if ($count > $limit) {
				break;
			}
			$imagedisplay = $image;
			$imgdisplaysize = $size;
			$imgdisplaysizewidth = 'width="'.$imgdisplaysize.'"';
			$imagedisplay = str_replace('width=""',$imgdisplaysizewidth,$imagedisplay);
			$imgdisplaysizeheight = 'height="'.$imgdisplaysize.'"';
			$imagedisplay = str_replace('height=""',$imgdisplaysizeheight,$imagedisplay);
			$final .= '<li class="tumblr_list_items" style="height: '.$imgdisplaysize.'px;" >'.$imagedisplay.'</li>';
			$count = ++$count;
		}
		$final .= '</ul>';
	}
	return $final;
	}
	
	function form($instance)	{
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = $instance['title'];
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
	<?php
	}
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
	function get_tumblr_avatar($usern){
		$usern = str_replace(' ','',$usern);
		$link = 'http://'.$usern.'.tumblr.com/';
		function get_http_response_code($url) {
			$headers = get_headers($url);
			return substr($headers[0], 9, 3);
		}
		if(get_http_response_code($link)!="404"){
			$page = file_get_contents($link);
		} else {
			$page = '';
		}
		if ($page !== '') {
			if (preg_match('/<link.+?>/si', $page, $link_matches)
				&& strpos($link_matches[0], 'shortcut icon') !== false
				&& preg_match('/href\s*=\s*"(http:.+?)"/si', $link_matches[0], $matches))
					{
						$avatar = $matches[1];
					}
			$imgsizedatabase = get_option('TAL_img-size-database','64');
			$bigavatar = str_replace('16',$imgsizedatabase,$avatar);
			$avatarlink = '<img src="'.$bigavatar.'" title ="'.$usern.'" width="" height=""/>';
			return $avatarlink;
			} else {
			$avatarlink = '';
			return $avatarlink;
			}
		
				
	};
	$images_list = get_option('tumblr_images');
	
	// Set the limit for foreach loop...
	$limitnum = get_option('TAL_avatars-displayed',10);
	
	// Initialize counter for foreach loop...
	$count = 1;
	
	if($images_list){
		echo '<ul id="tumblr_list">';
		foreach ($images_list as &$image) {
			
			// Check if count is bigger than limit. If yes, break the loop...
			if ($count > $limitnum) {
				break;
			}
			$imagedisplay = $image;
			$imgdisplaysize = get_option('TAL_img-size-widget','32');
			$imgdisplaysizewidth = 'width="'.$imgdisplaysize.'"';
			$imagedisplay = str_replace('width=""',$imgdisplaysizewidth,$imagedisplay);
			$imgdisplaysizeheight = 'height="'.$imgdisplaysize.'"';
			$imagedisplay = str_replace('height=""',$imgdisplaysizeheight,$imagedisplay);
			echo '<li class="tumblr_list_items" style="height: '.$imgdisplaysize.'px;" >'.$imagedisplay.'</li>';
			$count = ++$count;
		}
		echo '</ul>';
	}
	
	?>
	<br />
    <form method="POST" action=""> 
		<input type="hidden" name="update_usernames" value="true" /> 
		<input type="text" name="email" id="email" size="13" value=""/>
		<h4><?php echo get_option('TAL_visitor-message','Insert Tumblr username'); ?> </h4>
		<p><input type="text" name="username" id="username" size="10" value="<?php echo get_option('TAL_input-field-value','my username'); ?>" onFocus="value=''"/></p>
		<p><input type="submit" name="search" value="<?php echo get_option('TAL_submit-button-text','Submit'); ?>" class="button" /></p>
    </form>  
 <?php
	function tumblr_update(){  
	
		//Check if array with usernames exist, if not - create it...
		if(get_option(tumblr_usernames) == ''){$temporary = array(); update_option(tumblr_usernames, $temporary);};
		
		// Check if array with images exist, if not - create it...
		if(get_option(tumblr_images) == ''){$temporary2 = array(); update_option(tumblr_images, $temporary2);};
		
		// Add image to database...
		$new_username = $_POST['username'];
		$new_username = strtolower($new_username);
		$stored_images = get_option(tumblr_images);
		$new_image = get_tumblr_avatar($new_username);
		if ($new_image !== '') {
			array_unshift($stored_images, $new_image);
			update_option('tumblr_images', $stored_images);
		}
		
		// Add username to database...
		if ($new_image !== '') {
			$stored_usernames = get_option(tumblr_usernames);
			array_unshift($stored_usernames, $new_username);
			update_option('tumblr_usernames', $stored_usernames); 
			}
	}
	
	// Check if form is submitted. If true - execute our function...
	$securitycheck = $_POST['email'];
	$usercheck = $_POST['username'];
	if ( $_POST['update_usernames'] == 'true' && !$securitycheck && preg_match('/^[\w\-]+$/', $usercheck)) { 
		tumblr_update(); 
		};
    
	echo $after_widget;
  }
 
}
add_shortcode( 'tal', array('TumblrAvatarsList', 'shortcode_tumblr') );
add_action( 'widgets_init', create_function('', 'return register_widget("TumblrAvatarsList");') );

?>