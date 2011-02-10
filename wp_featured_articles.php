<?php
/**
 * @package Featured articles
 * @author Constantin Boiangiu ( constantin[at]php-help.ro )
 * @version 2.2
 */
/*
Plugin Name: Featured articles Lite
Plugin URI: http://www.php-help.ro/mootools-12-javascript-examples/wordpress-featured-content-plugin/
Description: Put a fancy JavaScript slider on any blog page, category page or home page to highlight your featured content.
Author: Constantin Boiangiu
Version: 2.2
Author URI: http://www.php-help.ro
*/

/**
 * Default options values. To change to your own values, login to admin page and see Settings->Featured Articles
 */
$FA_default = array(
	'num_articles'=>5,
	'display_order'=>1,
	'thumbnail_display'=>true,
	'section_title'=>'Featured articles',
	'section_display'=>1,
	'loop_display'=>0,
	'desc_truncate'=>500,
	'desc_truncate_noimg'=>800,
	'allowed_tags'=>'<a>',
	'display_from_category'=>array(),
	'display_pages'=>array(),
	'read_more'=>'Read more',
	'title_click'=>false,
	'displayed_content'=>1,
	'display_in_category'=>array(),
	'display_in_page'=>array(),
	'firstpage_display'=>true,
	'th_width'=>250,
	'th_height'=>250,
	'slider_width'=>'100%',
	'slider_height'=>300,
	'drop_moo'=>false,
	'show_author'=>true,
	/* JavaScript Settings */
	'slideDuration'=>5,
	'effectDuration'=>.6,
	'fadeDist'=>0,
	'fadePosition'=>'left',
	'stopSlideOnClick'=>false,
	'autoSlide'=>false,
	'mouseWheelNav'=>true,
	/* Navigation options */
	'bottom_nav'=>true,
	'sideways_nav'=>true,
	/* Theme options */
	'active_theme'=>'dark'
);
// Slider administration capability name
define('FA_CAPABILITY', 'edit_FA_slider');
/**
 * Do not change this. It enables the script to display the plugin only for the first loop
 */
$FA_current_loop = 0;
/**
 * Displays the featured articles box on index page
 *
 */
function wp_featured_articles(){
	global $FA_current_loop;
	$options = FA_get_options();
	
	if( !FA_check_display() || $FA_current_loop != $options['loop_display'] ){
		$FA_current_loop += 1;
		return;
	}
	$FA_current_loop += 1;
	
	$postslist = FA_get_content();
	
	global $post, $id;
	// save the original post
	$original_post = $post;
	// this is used for comments. The comments function uses a global $id variable to count comments. the current id is for the first item in loop
	$original_id = $id;
	
	/* theme display */
	$theme = 'themes/'.$options['active_theme'].'/display.php';
	
	//$styles = FA_style_size();		
	
	if( !is_file( FA_dir($theme) ) )
		$theme = 'themes/dark/display.php';	
	include( $theme );
	FA_dev_by();	
	// give $post and $id his original value 
	$post = $original_post;
	$id = $original_id;	
}

/*
 * Returns a simple array with width and height styling for easy access in theme.
 * These values are used for resizing the slider according to admin user settings
 */
function FA_style_size(){
	$options = FA_get_options();
	$size = array('x'=>'', 'y'=>'');
	if( !empty( $options['slider_width'] ) && $options['slider_width'] != 0 ){
		$size['x'] = 'width:'.$options['slider_width'].( is_numeric( $options['slider_width'] ) ? 'px':'' );
	}	
	if( !empty( $options['slider_height'] ) && $options['slider_height'] !=0 ){
		$size['y'] = 'height:'.$options['slider_height'].( is_numeric( $options['slider_height'] ) ? 'px':'' );
	}	
	return $size;
}

/**
 * Manual loading function. Place this in theme files like this:
 * 
 * <?php	
 *		if( function_exists('FA_display_slider') ){	
 *			FA_display_slider();
 *		}	
 *	?>
 */
function FA_display_slider(){

	$options = FA_get_options();
	$postslist = FA_get_content();
	
	define('FA_MANUALLY_LOADED', true);
	
	global $post, $id;
	// save the original post
	$original_post = $post;
	// this is used for comments. The comments function uses a global $id variable to count comments. the current id is for the first item in loop
	$original_id = $id;
	
	$styles = FA_style_size();
	/* theme display */
	$theme = 'themes/'.$options['active_theme'].'/display.php';
	if( !is_file( FA_dir($theme) ) )
		$theme = 'themes/dark/display.php';	
	include( $theme );
	FA_dev_by();	
	// give $post and $id his original value 
	$post = $original_post;
	$id = $original_id;		
}
/**
 * Developer link at the bottom of the slider. Don't delete this, you can disable it from administration panel under Featured articles Settings->Show author link
 * I would appreciate it if you could display the link to help spreading the word about this plugin. 
 * Thank you in advance.
 */
function FA_dev_by(){
	$options = FA_get_options();
	if( !$options['show_author'] ) return;
	$output = '<div class="wpf-dev">';
	$output.= '<a href="http://www.php-help.ro/mootools-12-javascript-examples/wordpress-featured-content-plugin/" title="Wordpress Featured Articles plugin" target="_blank">developed by php-help.ro</a>';
	$output.= '</div>';
	echo $output;
}

/**
 * Function to load stylesheets and scripts into the footer.
 * This is needed for manually defined sliders to load scripts and stylesheets 
 * only when needed
 */
function FA_load_scripts(){
	
	if( defined('FA_FOOTER_LOADED') || !defined('FA_MANUALLY_LOADED') || FA_check_display() ) return;

	define('FA_FOOTER_LOADED', true);
	$options = FA_get_options();
	
echo
"<script type='text/javascript'>
var FA_settings = {
	'container':'.FA_featured_articles',
	'slides':'.FA_article',
	'slideDuration':".($options['slideDuration']*1000).",
	'effectDuration':".($options['effectDuration']*1000).",
	'fadeDist':".$options['fadeDist'].",
	'fadePosition':'".$options['fadePosition']."',
	'stopSlideOnClick':'".$options['stopSlideOnClick']."',
	'autoSlide':'".$options['autoSlide']."',
	'mouseWheelNav':'".$options['mouseWheelNav']."'
}
</script>\n";
	
	$scripts = array();
	if( !$options['drop_moo'] ){
		$scripts[] = FA_path('scripts/mootools-1.2.4-core-yc.js');	
	}	
	$scripts[] = FA_path('scripts/FeaturedArticles.js');
	
	foreach( $scripts as $script_path ){
		echo '<script language="javascript" type="text/javascript" src="'.$script_path.'"></script>'."\n";
	}
	echo '<script language="javascript" type="text/javascript" src="'.FA_path('scripts/fa_dev.js').'"></script>'."\n";
	// load stylesheet
	$theme = 'themes/'.$options['active_theme'].'/stylesheet.css';
	if( !is_file( FA_dir($theme) ) )
		$theme = 'themes/dark/stylesheet.css';	
	echo '<link rel="stylesheet" type="text/css" href="'.FA_path( $theme ).'" />'."\n";
	echo '<link rel="stylesheet" type="text/css" href="'.FA_path( 'styles/fa_dev.css' ).'" />'."\n";	
}

/**
 * Returns the content list according to the settings
 */
function FA_get_content(){
	$options = FA_get_options();
	
	switch ($options['display_order']){
		case 1:
			$order = 'ASC';
		break;
		
		case 2:
			$order = 'DESC';
		break;		
	}
	$args = array();
	$args['numberposts'] = $options['num_articles'];
	$args['order'] = 'DESC';
	
	if( $options['displayed_content'] == 2 ){		
		if( $options['display_pages'][0] )
			$args['include'] = implode(',', $options['display_pages']);	
	}else{
		if( $options['display_from_category'][0] )
			$args['category'] = implode(',',$options['display_from_category']);
	}
	
	/* get posts depending on what order was chosen ( by date, by comment count or by meta key ) */
	switch ( $options['display_order'] ){
		case 1: default:
			$args['orderby'] = 'post_date';
		break;	
		case 2:
			$args['orderby'] = 'post_date';
			$args['meta_key'] = '_fa_featured';
			$args['meta_value'] = 1;
		break;
		case 3:
			$args['orderby'] = 'comment_count post_date';
		break;
		case 4:
			$args['orderby'] = 'rand';
		break;
	}	
	
	if( $options['displayed_content'] == 1 ){
		$postslist = get_posts($args);
	}else{
		$postslist = get_pages($args);
	}	
	return $postslist;
}
/**
 * Returns the plugin options
 */
function FA_get_options(){
	global $FA_default;
	$options = get_option('FA_options', $FA_default);
	return $options;
}

/**
 * Add JavaScript
 *
 */
function FA_add_scripts(){
	
	if( !FA_check_display() ) return ;
	
	$options = FA_get_options();
	
	$dependency = null;
	if( !$options['drop_moo'] ){
		wp_register_script( 'mootools_core', FA_path('scripts/mootools-1.2.4-core-yc.js'), false, '1.2.4' );
		$dependency = array( 'mootools_core' );		
	}	
	/* add featured articles Javascript functionality */
	wp_enqueue_script( 'FeaturedArticles', FA_path('scripts/FeaturedArticles.js'), $dependency, '1.0' );	
	wp_localize_script( 'FeaturedArticles', 'FA_settings', array(
  		'container'			=>'.FA_featured_articles',
		'slides'			=>'.FA_article',		
		/* Dynamic options */
		'slideDuration'		=>$options['slideDuration']*1000,
		'effectDuration'	=>$options['effectDuration']*1000,
		'fadeDist'			=>$options['fadeDist'],
		'fadePosition'		=>$options['fadePosition'],
		'stopSlideOnClick'	=>$options['stopSlideOnClick'],
		'autoSlide'			=>$options['autoSlide'],
		'mouseWheelNav'		=>$options['mouseWheelNav']		
	));
	if( $options['show_author'] ){
		wp_enqueue_script( 'FA_dev', FA_path('scripts/fa_dev.js'), $dependency, '1.0' );
	}	
}
/**
 * Add stylesheets
 *
 */
function FA_add_styles(){
	
	$is_main_slider = FA_check_display();
	
	if( !$is_main_slider ) return;
	
	$options = FA_get_options();
	
	if( $is_main_slider ){	
		$theme = 'themes/'.$options['active_theme'].'/stylesheet.css';
		if( !is_file( FA_dir($theme) ) )
			$theme = 'themes/dark/stylesheet.css';	
		wp_register_style('FA_styles', FA_path( $theme ));
		wp_enqueue_style( 'FA_styles');
		if( $options['show_author'] ) {
			wp_register_style('FA_dev', FA_path( 'styles/fa_dev.css' ));
			wp_enqueue_style( 'FA_dev');
		}	
	}		
}
/**
 * Used to verify if scripts and stylesheets need to be added.
 * Checks for admin pages, homepage, categories and pages
 *
 * @return bool
 */
function FA_check_display(){
	
	$options = FA_get_options();
	$display = true;
	
	if( is_home() ){
		if( !$options['firstpage_display'] )
			$display = false;		
	}else if( is_category() ){
		if( !is_category( $options['display_in_category'] ) || !$options['display_in_category'] )
			$display = false;
	}else if( is_page() ){
		if( !is_page( $options['display_in_page'] ) || !$options['display_in_page'] )
			$display = false;	
	}else{ 
		$display = false;
	}
		
	return $display;	
}

/**
 * Image detection inside post
 *
 * @param object $post
 * @return string - image path
 */
function FA_article_image ($post){
	// if thumbnails are stopped from admin, return false
	$options = FA_get_options();
	if( !$options['thumbnail_display'] ) 
		return false;
	
		
	// check for custom field image	
	$meta_image_id = get_post_meta($post->ID, '_fa_image', true);
	$meta_image = wp_get_attachment_image_src( $meta_image_id, array($options['th_width'], $options['th_height']) );
	
	if( $meta_image )
		return $meta_image[0];
	// check for image in text
	preg_match_all("#\<img(.*)src\=(\"|\')(.*)(\"|\')#Ui", $post->post_content, $matches);
	return isset($matches[3][0]) ? $matches[3][0] : false;		
}
/**
 * Truncates a text on a given number of characters. Based on the Smarty plugin
 *
 * @param string $string
 * @param int $length
 * @param string $etc
 * @param bool $break_words
 * @param bool $middle
 * @return string
 */
function FA_truncate_text($string, $length = 80, $etc = '...', $break_words = false, $middle = false){
    if ($length == 0)
        return '';
	
    $options = FA_get_options();    
    // remove shortcodes from content
    $string = strip_shortcodes( $string );    
    // remove all HTML tags except links
    $string = strip_tags($string, $options['allowed_tags']);    
    
            
    if (strlen($string) > $length) {
        $length -= strlen($etc);
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
        }
        if(!$middle) {
            return substr($string, 0, $length).$etc;
        } else {
            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
        }
    } else {
        return $string;
    }
}

/**
 * Adds the admin menu for the settings 
 *
 */
function FA_plugin_menu(){
	add_menu_page( 'FA Lite', 'FA Lite', FA_CAPABILITY, __FILE__, 'FA_plugin_options', FA_path('styles/ico.png') ); 
	add_submenu_page( __FILE__, 'Settings', 'FA Lite Settings', FA_CAPABILITY, __FILE__, 'FA_plugin_options');
	// only administrator can manage slider settings user capabilities
	add_submenu_page( __FILE__, 'Permissions', 'Permissions', 'manage_options', __FILE__ . '/FA_permissions', 'FA_permissions'); 	
}
/**
 * Manages permissions page
 */
function FA_permissions(){
	$options = FA_get_options();
	global $wp_roles;
	if( !empty($_POST) ){
		if( !wp_verify_nonce($_POST['FA_perm'],'FA_permissions') ){
			die('Sorry, your action is invalid.');
		}else{
			// get wordpress roles
			$roles = $wp_roles->get_names();
			foreach( $roles as $role=>$name ){
				// administrator has default access so skip this role
				if( 'administrator' == $role ) continue;
				// add/remove editing capabilities
				if( isset( $_POST['role'][$role] ) ){
					$wp_roles->add_cap($role, FA_CAPABILITY);
				}else{
					$wp_roles->remove_cap($role, FA_CAPABILITY);
				}
			}
			wp_redirect('admin.php?page=featured-articles-lite/wp_featured_articles.php/FA_permissions');	
			exit();	
		}	
	}
	// display edit form
	include('displays/permissions.php');
}

/**
 * Returns the complete path of a given file from within the plugin
 *
 * @param string $file
 * @return string
 */
function FA_path( $file ){
	$path = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	return $path.$file;	
}
/**
 * Returns complete path of a file within the plugin
 *
 * @param string $file
 * @return string
 */
function FA_dir( $file ){
	$path =  WP_PLUGIN_DIR . "/" . plugin_basename( dirname( __FILE__ ) );
	if( substr( $path, -1, 1 ) == '/' )
		return $path.$file;
	else 
		return $path.'/'.$file;	
}
/**
 * Outputs the admin page
 *
 */
function FA_plugin_options(){
	
	global $FA_default;
	$default_settings = $FA_default;
	
	/* save values from POST */
	if( $_POST ){
		if( !wp_verify_nonce( $_POST['FA-save_wpnonce'], 'FA_saveOptions' ) ) {
	        return false;
	    }
		/* loop default values to save from POST */
	    foreach ( $FA_default as $key=>$value ){		
			if( isset( $_POST[$key] ) ){
				if( is_numeric( $value ) ){
					if( is_numeric( $_POST[$key] ) )
						$default_settings[$key] = $_POST[$key];
				}else if (is_bool( $value )) {
					$default_settings[$key] = true;
				}else{
					$default_settings[$key] = $_POST[$key];
				}
			}else{
				$default_settings[$key] = false;
			}		
		}	    
	    update_option('FA_options', $default_settings);	
	    echo '<div id="update">Settings successfully updated.</div>';
	}
	/* get options already saved */
	$saved_settings = get_option('FA_options', $default_settings);	
	
	$themes = array();
	if ($handle = opendir( FA_dir('themes') )) {
	    while (false !== ($file = readdir($handle))) {
	        if( $file == '.' || $file == '..' ) continue;
	        $themes[] = $file;
	    }	
	    closedir($handle);
	}
	
	/* display the editing form */
	include('displays/settings.php');
}
/**
 * Load admin styles and scripts
 *
 */
function FA_admin_init(){
	
	if( !is_admin() ) return ;
	wp_enqueue_script( 'FA_script_settings', FA_path('scripts/FA_admin.js'), array( 'jquery' ), '1.0' );
	wp_register_style('FA_admin_styles', FA_path('styles/admin.css'));
	wp_enqueue_style( 'FA_admin_styles');
	
	// give permission to administrator to change slider settings
	if( current_user_can('manage_options') ){
		if( !current_user_can( FA_CAPABILITY ) ){
			global $wp_roles;
			$wp_roles->add_cap('administrator', FA_CAPABILITY);
		}
	}	
}

/**
 * Add box into sidebar for posts and pages
 */
function FA_post_actions() {
    add_meta_box( 'FA-actions', 'Featured Articles Lite', 'FA_meta_box', 'post', 'side', 'high' );
    add_meta_box( 'FA-actions', 'Featured Articles Lite', 'FA_meta_box', 'page', 'side', 'high' );
}
/**
 * Display the meta box for posts and pages
 */
function FA_meta_box(){
	global $post;
	// get current image attached by the user for FA Artiles
	$current_image_id = get_post_meta($post->ID, '_fa_image', true);
	$current_image = false;
	if( $current_image_id ){
		$image = wp_get_attachment_image_src( $current_image_id, 'thumbnail' );
		$current_image = $image[0];
	}
		
	// check if post is already featured or not
	$featured = get_post_meta($post->ID, '_fa_featured', true);
		
	include('displays/meta_box.php');
}
/**
 * Saves the data for featured posts
 */
function FA_save_meta(){
	if( isset($_POST['fa_nonce']) && wp_verify_nonce($_POST['fa_nonce'],'fa_article_featured') ){
		$id = (int)$_POST['post_ID'];
		// feature post
		if( isset( $_POST['_fa_featured'] ) ){
			update_post_meta( $_POST['post_ID'], '_fa_featured', 1 );
		}else{
			delete_post_meta( $_POST['post_ID'], '_fa_featured' );	
		}
		// delete custom image
		if( isset( $_POST['fa_remove_meta_image'] ) ){
			delete_post_meta( $_POST['post_ID'], '_fa_image' );	
		}	
	}
}

/**
 * Hooks
 */
add_action('admin_init', 'FA_admin_init');
add_action('admin_menu', 'FA_plugin_menu');
add_action('admin_menu', 'FA_post_actions');
add_action( 'save_post', 'FA_save_meta' );
// script loading in header
add_action('wp_print_scripts', 'FA_add_scripts');
add_action('wp_print_styles','FA_add_styles');
// script loading in footer for manually implemented sliders
add_action('wp_footer', 'FA_load_scripts');
add_action('loop_start', 'wp_featured_articles',1);
?>