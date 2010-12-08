<?php
/**
 * @package Featured articles
 * @author Constantin Boiangiu ( constantin[at]php-help.ro )
 * @version 1.0
 */
/*
Plugin Name: Featured articles Lite
Plugin URI: http://www.php-help.ro/mootools-12-javascript-examples/wordpress-featured-content-plugin/
Description: Put a fancy JavaScript slider on any blog page, category page or home page to highlight your featured content.
Author: Constantin Boiangiu
Version: 2.0
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
	'desc_truncate'=>500,
	'desc_truncate_noimg'=>800,
	'display_from_category'=>array(),
	'display_pages'=>array(),
	'displayed_content'=>1,
	'display_in_category'=>array(),
	'display_in_page'=>array(),
	'firstpage_display'=>true,
	'th_width'=>250,
	'th_height'=>250,
	'drop_moo'=>false,
	/* JavaScript Settings */
	'slideDuration'=>5,
	'effectDuration'=>.6,
	'fadeDist'=>0,
	'fadePosition'=>'left',
	'stopSlideOnClick'=>false,
	'autoSlide'=>false,
	/* Navigation options */
	'bottom_nav'=>true,
	'sideways_nav'=>true,
	/* Theme options */
	'active_theme'=>'dark'
);
/**
 * Do not change this. It enables the script to display the plugin only for the first loop
 */
$FA_displayed = 0;
/**
 * Displays the featured articles box on index page
 *
 */
function wp_featured_articles(){
	global $FA_default, $FA_displayed;
	$options = get_option('FA_options', $FA_default);
	
	if( !FA_check_display() || $FA_displayed > 0 ) return;
	
	$FA_displayed = 1;
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
			$args['meta_key'] = 'FA_featured';
			$args['meta_value'] = 1;
		break;
		case 3:
			$args['orderby'] = 'comment_count post_date';
		break;
	}	
	
	if( $options['displayed_content'] == 1 ){
		$postslist = get_posts($args);
	}else{
		$postslist = get_pages($args);
	}	
	
	global $post, $id;
	/* save the original post */
	$original_post = $post;
	/* this is used for comments. The comments function uses a global $id variable to count comments. the current id is for the first item in loop */
	$original_id = $id;
	
	/* theme display */
	$theme = 'themes/'.$options['active_theme'].'/display.php';
	if( !is_file( FA_dir($theme) ) )
		$theme = 'themes/dark/display.php';	
	include( 'themes/'.$options['active_theme'].'/display.php' );
	
	/* give $post and $id his original value */
	$post = $original_post;
	$id = $original_id;	
}
/**
 * Add JavaScript
 *
 */
function FA_add_scripts(){
	
	if( !FA_check_display() ) return ;
	
	global $FA_default;
	$options = get_option('FA_options', $FA_default);
	
	$dependency = null;
	if( !$options['drop_moo'] ){
		wp_register_script( 'mootools_core', FA_path('scripts/mootools-1.2.4-core-yc.js'), false, '1.2.4' );
		$dependency = array( 'mootools_core' );		
	}	
	/* add featured articles Javascript functionality */
	wp_enqueue_script( 'FeaturedArticles', FA_path('scripts/FeaturedArticles.js'), $dependency, '1.0' );	
	wp_localize_script( 'FeaturedArticles', 'FA_settings', array(
  		'container'			=>'FA_featured_articles',
		'slides'			=>'.FA_article',		
		'infoContainers'	=>'.FA_info',
		/* Dynamic options */
		'slideDuration'		=>$options['slideDuration']*1000,
		'effectDuration'	=>$options['effectDuration']*1000,
		'fadeDist'			=>$options['fadeDist'],
		'fadePosition'		=>$options['fadePosition'],
		'stopSlideOnClick'	=>$options['stopSlideOnClick'],
		'autoSlide'			=>$options['autoSlide']		
	));
}
/**
 * Add stylesheets
 *
 */
function FA_add_styles(){
	if( !FA_check_display() ) return;
	
	global $FA_default;
	$options = get_option('FA_options', $FA_default);
	
	$theme = 'themes/'.$options['active_theme'].'/stylesheet.css';
	if( !is_file( FA_dir($theme) ) )
		$theme = 'themes/dark/stylesheet.css';	
	
	wp_register_style('FA_styles', FA_path( $theme ));
	wp_enqueue_style( 'FA_styles');
}
/**
 * Used to verify if scripts and stylesheets need to be added.
 * Checks for admin pages, homepage, categories and pages
 *
 * @return bool
 */
function FA_check_display(){
	
	global $FA_default;
	$options = get_option('FA_options', $FA_default);
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
	preg_match_all("#\<img(.*)src\=(\"|\')(.*)(\"|\')#Ui", $post->post_content, $matches);
	return isset($matches[3][0]) ? $matches[3][0] : '';		
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
function truncate_text($string, $length = 80, $etc = '...', $break_words = false, $middle = false){
    if ($length == 0)
        return '';

    $string = strip_tags($string);    
    /* remove captions from text */
    $string = preg_replace( "|\[(.+?)\](.+?\[/\\1\])?|s", "", $string );
            
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
 * Adds the admin page menu for the settings 
 *
 */
function FA_plugin_menu(){
 	add_options_page('Featured articles settings', 'Featured articles', 2, __FILE__, 'FA_plugin_options');
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
	if( !strpos( $_GET['page'], basename(__FILE__) ) ) return;
	
	$dependency = array();
	wp_enqueue_script( 'FA_script_settings', FA_path('scripts/FA_admin.js'), array( 'jquery' ), '1.0' );
	wp_register_style('FA_admin_styles', FA_path('styles/admin.css'));
	wp_enqueue_style( 'FA_admin_styles');
}

/**
 * Admin action to add media button
 *
 */
function FA_add_meta(){
	global $post;
	echo '<a href="../wp-content/plugins/featured-articles-lite/add_meta.php?height=300&width=800&post='.$post->ID.'&TB_iframe=true" class="thickbox" title="'.__('Add new image for Featured Articles','wp_featured_articles').'""><img src="'.get_bloginfo('wpurl').'/wp-content/plugins/featured-articles-lite/styles/custom-image.png" alt="'.__('Add custom image field','wp_featured_articles').'"></a>';	
}

/**
 * Hooks
 */

add_action('admin_init', 'FA_admin_init');
add_action('admin_menu', 'FA_plugin_menu');
add_action('wp_print_scripts', 'FA_add_scripts');
add_action('wp_print_styles','FA_add_styles');
add_action('loop_start', 'wp_featured_articles',1);
add_action('media_buttons', 'FA_add_meta', 20);
?>