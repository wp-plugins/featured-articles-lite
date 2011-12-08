<?php
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors@codeflavors.com )
 * @version 2.4
 */
/*
Plugin Name: Featured articles Lite
Plugin URI: http://www.codeflavors.com/featured-articles-pro/
Description: Create fancy animated sliders into your blog pages by choosing from plenty of available options and different themes. Compatible with Wordpress 3.1+
Author: CodeFlavors
Version: 2.4
Author URI: http://www.codeflavors.com
*/

/**
 * Plugin administration capability, current version and Wordpress compatibility
 */
define('FA_CAPABILITY', 'edit_FA_slider');
define('FA_VERSION', '2.4');
define('FA_WP_COMPAT', '3.1');

include_once plugin_dir_path(__FILE__).'includes/common.php';
include_once plugin_dir_path(__FILE__).'includes/widgets.php';

/**
 * Keeps track of the current loop. Used only when displaying slideshows
 * using the automatic display feature.
 */
$FA_current_loop = array();

/**
 * Global variable that gets populated with the currently displayed slider options.
 * To get these options use function FA_get_option( 'option name or empty for all options' )
 */
$FA_slider_options = array();

/**
 * Global variable that stores javaScript settings for all slideshows to be displayed. All params
 * stored are placed into the output and used in slideshow script file.
 */
$FA_SLIDERS_PARAMS = array();

/**
 * Callback function for loop_start Wordpress hook.
 * This function displays slideshows that are automatically placed above the current page loop.
 * It checks if any slideshows are set to be displayed on the current front-end page and displays them.
 */
function featured_articles_slideshow(){
	
	$sliders = FA_display();
	if(!$sliders) return;
	
	global $FA_current_loop;
	foreach( $sliders as $slider_id ){
		if( !array_key_exists($slider_id, $FA_current_loop) ){
			$FA_current_loop[$slider_id] = 0;
		}
		
		// set global options for the current slider
		FA_set_slider_options( $slider_id );
		$loop_display = FA_get_option(array('_fa_lite_display', 'loop_display'));
		
		if( $FA_current_loop[$slider_id] != $loop_display  ){
			$FA_current_loop[$slider_id]+=1;
			continue;
		}
		$FA_current_loop[$slider_id]+=1;
		$postslist = FA_get_content($slider_id);
		
		global $post, $id;
		// save the original post
		$original_post = $post;
		// this is used for comments. The comments function uses a global $id variable to count comments. the current id is for the first item in loop
		$original_id = $id;
		$theme_option = FA_get_option(array( '_fa_lite_theme', 'active_theme'));
		
		// dark and light themes are one theme called classic. Check if theme is dark or light and set it to classic
		if( 'dark' == $theme_option || 'light' == $theme_option ){
			$theme_option['active_theme'] = 'classic';
		}
		
		/* theme display */
		$theme = 'themes/'.$theme_option.'/display.php';
		// @deprecated get slider size
		$styles = FA_style_size();
		
		if( !is_file( FA_dir($theme) ) ){
			$theme = 'themes/classic/display.php';
		}
		
		// create unique ID for the slider		
		$FA_slider_id = 'FA_slider_'.$slider_id;
			
		// @deprecated - set the options for older, not updated themes
		$options = FA_get_option('_fa_lite_aspect');
		
		// theme display file
		include( $theme );
		// give $post and $id his original value 
		$post = $original_post;
		$id = $original_id;
	}
}
/**
 * Function for manually placing slideshows in theme pages. 
 * It doesn't check current page so if you want to manually place a slideshow
 * directly in your theme, all page verifications must be done by you. This means
 * that if this function is called in header.php template, it will display on every
 * page of your blog.
 * 
 * Usage in themes:
 * <?php	
 *		if( function_exists('FA_display_slider') ){	
 *			FA_display_slider( $slider_id );
 *		}	
 *	?>
 */
function FA_display_slider($slider_id, $echo = true){
	
	$slider = get_post($slider_id);
	if(!$slider || 'fa_slider' != $slider->post_type) return;
	
	// set global options for the current slider
	FA_set_slider_options( $slider_id );
	
	// get the posts
	$postslist = FA_get_content($slider_id);
	
	global $post, $id, $FA_SLIDERS_PARAMS;
	
	// save the original post
	$original_post = $post;
	// this is used for comments. The comments function uses a global $id variable to count comments. the current id is for the first item in loop
	$original_id = $id;
	
	// @deprecated slider size
	$styles = FA_style_size();
	/* theme display */
	$theme_option = FA_get_option(array( '_fa_lite_theme', 'active_theme'));
	$color_option = FA_get_option(array( '_fa_lite_theme', 'active_theme_color'));
	
	$theme = 'themes/'.$theme_option.'/display.php';
	// if theme doesn't exists, go for classic dark
	if( !is_file( FA_dir($theme) ) ){
		$theme = 'themes/classic/display.php';
		$theme_option = 'classic';
		$color_option = 'dark.css';
	}	
	
	wp_enqueue_style('FA_Lite_'.$theme_option, FA_path('themes/'.$theme_option.'/stylesheet.css'));
	
	// load the color stylesheet if any
	if( !empty( $color_option ) ){
		$color_path = FA_dir( 'themes/'.$theme_option.'/colors/'.$color_option );
		if( is_file($color_path) ){
			wp_enqueue_style(
				'FA_Lite_'.$theme_option.'-'.$color_option, 
				FA_path('themes/'.$theme_option.'/colors/'.$color_option)
			);		
		}
	}
	// load the js starter if any
	$custom_starter = 'themes/'.$theme_option.'/starter.js';
	if( is_file(FA_dir($custom_starter)) ){
		wp_enqueue_script('FA_starter-'.$theme, FA_path($custom_starter), array('jquery'));
	}else{
		wp_enqueue_script('FA_general_starter', FA_path('scripts/script-loader.js'), array('jquery'));
	}
	
	$js_options = FA_get_option('_fa_lite_js');
	$FA_SLIDERS_PARAMS['FA_slider_'.$slider_id] = FA_lite_json($js_options);
	if( !$echo ){
		ob_start();
	}
	$FA_slider_id = 'FA_slider_'.$slider_id;
	
	// @deprecated - set the options for older, not updated themes
	$options = FA_get_option('_fa_lite_aspect');
	
	include( $theme );
		
	if(!$echo){
		$slider_content = ob_get_contents();
		ob_end_clean();
	}
	
	// give $post and $id his original value 
	$post = $original_post;
	$id = $original_id;
	if(!$echo){
		return $slider_content;
	}	
}
/**
 * Function to load stylesheets and scripts into the footer.
 * This is needed for manually defined sliders to load scripts and stylesheets 
 * only when needed. Uses global variable $FA_SLIDERS_PARAMS to output JavaScript
 * parameters for each slideshow.
 * This function uses hook wp_footer. Your theme must respect WP standards and 
 * call wp_footer() function in footer.php, right above </body> closing tag.
 */
function FA_load_footer(){
	global $FA_SLIDERS_PARAMS;
	
	if(!$FA_SLIDERS_PARAMS) return;
	
	wp_print_styles();
	wp_register_script('jquery-mousewheel', FA_path('scripts/jquery.mousewheel.min.js'), 'jquery', '3.0.2');
	wp_enqueue_script('FeaturedArticles-jQuery', FA_path('scripts/FeaturedArticles.jquery.js'), array('jquery', 'jquery-mousewheel'), '1.0');
	wp_enqueue_script('FA_footer', FA_path('scripts/fa_footer.js'), array('FeaturedArticles-jQuery'));
	
	wp_localize_script('FA_footer', 'FA_Lite_footer_params', $FA_SLIDERS_PARAMS);	
	wp_print_scripts();		
}
/**
 * Add neccessary scripts for slideshows to run. This function is used only
 * for automatically placed slideshows (the ones that display above the current page loop).
 * It's hooked to wp_print_scripts
 */
function FA_add_scripts(){	
	$sliders = FA_display();	
	if(!$sliders) return;
	
	$js_options = array();
	foreach( $sliders as $slider_id ){
		$options = FA_slider_options($slider_id, '_fa_lite_js');		
		$js_options['FA_slider_'.$slider_id] = FA_lite_json($options);

		$theme_option = FA_slider_options($slider_id, '_fa_lite_theme');
		
		// load the js starter if any
		if( file_exists(FA_dir('themes/'.$theme_option['active_theme']))){
			$custom_starter = 'themes/'.$theme_option['active_theme'].'/starter.js';
		}else{
			$custom_starter = 'themes/classic/starter.js';
		}
		
		if( is_file(FA_dir($custom_starter)) ){
			wp_enqueue_script('FA_starter-'.$theme_option['active_theme'], FA_path($custom_starter), array('jquery'));
		}else{
			wp_enqueue_script('FA_general_starter', FA_path('scripts/script-loader.js'), array('jquery'));
		}
	}
	
	wp_register_script('jquery-mousewheel', FA_path('scripts/jquery.mousewheel.min.js'), 'jquery', '3.0.2');
	wp_enqueue_script('FeaturedArticles-jQuery', FA_path('scripts/FeaturedArticles.jquery.js'), array('jquery', 'jquery-mousewheel'), '1.0');
	wp_localize_script('FeaturedArticles-jQuery', 'FA_Lite_params', $js_options);	
}
/**
 * Add neccessary stylesheets for slideshows set to be displayed automatically.
 * Hooked to wp_print_styles
 */
function FA_add_styles(){	
	$sliders = FA_display();	
	if(!$sliders) return;
	
	foreach( $sliders as $slider_id ){
		$theme = FA_slider_options($slider_id, '_fa_lite_theme');
		
		$theme_path = 'themes/'.$theme['active_theme'].'/stylesheet.css';
		$load_default = false;
		if( !is_file( FA_dir($theme_path) ) ){
			$theme_path = 'themes/classic/stylesheet.css';
			$load_default = true;
		}	
			
		wp_register_style('FA_style_'.$theme['active_theme'], FA_path($theme_path));
		wp_enqueue_style('FA_style_'.$theme['active_theme']);
		
		if( $load_default ){
			$colors_path = 'themes/classic/colors/dark.css';
			wp_enqueue_style('FA_style_classic-dark', FA_path($colors_path));
			continue;
		}
		
		if( !empty( $theme['active_theme_color'] ) ){
			$colors_path = 'themes/'.$theme['active_theme'].'/colors/'.$theme['active_theme_color'];
			if( is_file( FA_dir($colors_path)) ){
				wp_enqueue_style('FA_style_'.$theme['active_theme'].'-'.$theme['active_theme_color'], FA_path($colors_path));
			}	
		}		
	}			
}

/**
 * Plugin administration menu
 */
function FA_plugin_menu(){
	
	$menu_slug = 'featured-articles-lite';
	
	add_menu_page( 'FA Lite', 'FA Lite', FA_CAPABILITY, $menu_slug, 'fa_slideshows', FA_path('styles/ico.png') ); 
	$main_page = add_submenu_page( $menu_slug, 'FA Lite Sliders', 'Sliders', FA_CAPABILITY, $menu_slug, 'fa_slideshows');
	$new_slideshow = add_submenu_page( $menu_slug, 'FA Lite Slider', 'Add New Slider', FA_CAPABILITY, $menu_slug.'-new-slideshow', 'fa_new_slideshow');
	
	// only administrator can manage slider settings user capabilities
	add_submenu_page( $menu_slug, 'Settings', 'Settings', 'manage_options', $menu_slug.'/settings.php');
	$pro_page = add_submenu_page($menu_slug, 'Featured Articles PRO', 'Go PRO!', FA_CAPABILITY, $menu_slug.'/pro.php');
	
	add_submenu_page(NULL,'Add content', 'Add content', FA_CAPABILITY, $menu_slug.'/add_content.php');
	add_submenu_page(NULL, 'Preview Slider', 'Preview Slider', FA_CAPABILITY, $menu_slug.'/preview.php');
		
	// styles for editing/creating sliders pages
	add_action('admin_print_styles-'.$main_page, 'FA_edit_styles');
	add_action('admin_print_styles-'.$new_slideshow, 'FA_edit_styles');
	// styles for creating slides pages
	add_action('admin_print_styles-featured-articles-lite/pro.php', 'FA_pro_styles');
		
	add_action('admin_print_styles-post.php', 'FA_post_edit_scripts');
	add_action('admin_print_styles-post-new.php', 'FA_post_edit_scripts');	
}
add_action('admin_menu', 'FA_plugin_menu');

/**
 * Slideshows admin menu callback function. It displays all pages needed for listing/editing/creating/deleting
 * slideshows.
 */
function fa_slideshows(){
	// get the current action from link to determine what to show
	$action = isset( $_GET['action'] ) ? $_GET['action'] : '';
	$current_page = menu_page_url('featured-articles-lite', false);
	
	$screen = get_current_screen();
	$page_hook = $screen->id;
	
	
	// bulk delete
	if( (isset($_POST['action2']) && 'delete' == $_POST['action2']) || (isset($_POST['action']) && 'delete' == $_POST['action'] ) ){
		$action = 'bulk-delete';
	}
	
	switch( $action ){
		// edit/create slideshows
		case 'edit':
		case 'new':			
			$slider_id = isset( $_GET['id'] ) ? (int)$_GET['id'] : false;
			// set the current slider options
			FA_set_slider_options( $slider_id );
			
			// start meta boxes
			add_meta_box('submitdiv', 'Save Slider', 'fa_lite_save_panel', $page_hook, 'side');
			add_meta_box('fa-lite-implement', 'Manual placement', 'fa_lite_implement_panel', $page_hook, 'side');
			add_meta_box('fa-lite-info', 'Help, support &amp; info', 'fa_lite_info_panel', $page_hook, 'side');
			// include template
			include FA_dir('edit.php');
		break;	
		// delete individual slideshows
		case 'delete':
			if( wp_verify_nonce($_GET['_wpnonce']) ){
				FA_delete_sliders( $_GET['item_id'] );					
			}	
			wp_redirect( $current_page );
			exit();	
		break;
		// bulk delete slideshows
		case 'bulk-delete':
			if( wp_verify_nonce($_POST['FA_bulk_del'], 'featured-articles-sliders-bulk-delete') ){
				FA_delete_sliders( $_POST['item_id'] );				
			}
			wp_redirect( $current_page );
			exit();	
		break;	
		// show the slideshows list	
		default:
			include FA_dir('sliders.php');
		break;	
	}
}
/**
 * New slideshow admin menu callback function. All functionality is inside 
 * function fa_slideshows, it just sets the action to alert a new slideshow 
 * is created.
 */
function fa_new_slideshow(){
	$_GET['action'] = 'new';
	fa_slideshows();
}

/**
 * Slider edit - save slider metabox callback
 */
function fa_lite_save_panel(){
	$slider_id = isset( $_GET['id'] ) ? (int)$_GET['id'] : false;
	$options = FA_get_option('_fa_lite_aspect');
	
	$themes = FA_themes();
	$theme_options = FA_get_option('_fa_lite_theme');
	$current_theme = $theme_options['active_theme'];
	$fields = FA_fields( (array)$themes[$current_theme]['theme_config']['Fields'] );
	
	$current_page = menu_page_url('featured-articles-pro', false);
	include FA_dir('displays/panel_slider_save.php');
}
/**
 * Slider edit - slider manual implementation metabox callback
 */
function fa_lite_implement_panel(){
	$slider_id = isset( $_GET['id'] ) ? (int)$_GET['id'] : false;
	include FA_dir('displays/panel_slider_implement.php');
}

function fa_lite_info_panel(){
	include FA_dir('displays/panel_cf_info.php');
}

/**
 * Add scripts and styles for slider edit page in admin
 */
function FA_edit_styles(){
	wp_enqueue_script(array(
		'FA_edit_script',
		'FA_tooltip_script',
		'jquery-ui-dialog',
		'post',
		'postbox',
		'jquery-cookie'
	));
	
	wp_enqueue_style(array(
		'FA_edit_styles',
		'FA_tooltip_styles',
		'FA_dialog',
		'thickbox',
		'jquery-ui-dialog'
	));
}
/**
 * Styling for go pro page
 */
function FA_pro_styles(){
	wp_register_style('FA_pro_styles', FA_path('styles/pro.css'));
	wp_enqueue_style('FA_pro_styles');
}
/**
 * Starting with version 2.4, slideshow themes can have their own funcitons.php file.
 * Using this file in a custom created theme allows to create new optional fields for 
 * the theme that are unique to it. Also, by using this functionality, messages can be displayed to user
 * when selecting your theme from themes drop-down in slideshow editing administration area.
 * 
 * Function for loading themes extra functionality. These files are called only in administration are. 
 */
function FA_run_themes_functions(){
	global $plugin_page;
	
	if( !strstr($plugin_page, 'featured-articles') ) return;	
	
	// include themes function files to allow them to run filters and hooks on admin display
	$themes = FA_themes();
	foreach( $themes as $theme=>$configs ){
		if( $configs['funcs'] ){
			include_once $configs['funcs'];
		}
	}
}
add_action('admin_init', 'FA_run_themes_functions');

/**
 * Post editing FA metabox scripts.
 */
function FA_post_edit_scripts(){
	wp_enqueue_script('farbtastic');
	wp_enqueue_style( array(
		'farbtastic'
	));
}
/**
 * Load admin styles and scripts.
 */
function FA_admin_init(){	
	// register styles
	wp_register_script('FA_edit_script', FA_path('scripts/admin_edit.js'), array( 'jquery', 'jquery-ui-sortable' ), '1.0');
	wp_register_style('FA_edit_styles', FA_path('styles/admin_edit.css'));	
	wp_register_script('FA_tooltip_script', FA_path('scripts/simpleTooltip.jquery.js'), array( 'jquery' ), '1.0');
	wp_register_style('FA_tooltip_styles', FA_path('styles/admin_tooltip.css'));	
	wp_register_style('FA_dialog', FA_path('styles/jquery-ui-dialog.css'));	
}
add_action('admin_init', 'FA_admin_init');
/**
 * Register sliders post type into wordpress
 */
function FA_init(){
	
	register_post_type( 'fa_slider', 
		array(
			'labels' => array(
	        	'name' => 'Featured Articles Sliders',
	        	'singular_name' => __( 'Featured Articles Slider' )
	   		),
	    	'public' => false
	    )
	);

	register_post_type( 'fa_slide',
		array(
			'labels' => array(
	        	'name' => 'fa_slide',
	        	'singular_name' => __( 'Featured Articles Slide' )
	   		),
	    	'public' => false
	    )
	);	
}

/**
 * Add meta-box for posts and pages options.
 */
function FA_post_actions() {
	if( !current_user_can(FA_CAPABILITY) ) return;
    // meta box to add custom image to post or page, insert shortcode into post and feature post/page into slider
	add_meta_box( 'FA-actions', 'Featured Articles Lite', 'FA_meta_box', 'post', 'normal', 'high' );
    add_meta_box( 'FA-actions', 'Featured Articles Lite', 'FA_meta_box', 'page', 'normal', 'high' );
}
/**
 * Display the meta box for posts and pages
 */
function FA_meta_box(){
	global $post;
	// get current image attached by the user for FA Artiles
	$current_image_id = get_post_meta($post->ID, '_fa_image', true);
	if( $current_image_id ){
		$image = wp_get_attachment_image_src( $current_image_id, 'thumbnail' );
		$current_image = $image[0];
	}
		
	// check if post is already featured or not
	$meta = get_post_meta($post->ID, '_fa_featured', true);
	$featured = !empty($meta) ? $meta : array();
	global $post, $id;
	// save the original post
	$original_post = $post;
	// this is used for comments. The comments function uses a global $id variable to count comments. the current id is for the first item in loop
	$original_id = $id;
	/* get the posts */
	$args = array(
        'post_type' => 'fa_slider',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    );
	$loop = new WP_Query( $args );
	// extra fields
	$fields = array(
		'_fa_cust_title'=>'',
		'_fa_cust_link'=>'',
		'_fa_cust_class'=>'',
		'_fa_cust_txt'=>'',
		'_fa_bg_color'=>''
	);
	foreach($fields as $field=>$val){
		$opt = get_post_meta($post->ID, $field, true);
		if( $opt ){
			$fields[$field] = $opt;
		}
	}
	
	include('displays/panel_post.php');
	$post = $original_post;
	$id = $original_id;
}
/**
 * Saves the data for featured posts/pages
 */
function FA_save_meta(){
	if( !current_user_can(FA_CAPABILITY) ) return;
	if( isset($_POST['fa_nonce']) && wp_verify_nonce($_POST['fa_nonce'],'fa_article_featured') ){
		$id = (int)$_POST['post_ID'];		
		// feature post
		
		if( isset( $_POST['fa_lite_featured'] ) ){
			update_post_meta( $_POST['post_ID'], '_fa_featured', $_POST['fa_lite_featured']);
		}else{
			delete_post_meta( $_POST['post_ID'], '_fa_featured' );	
		}
		// delete custom image
		if( isset( $_POST['fa_remove_meta_image'] ) ){
			delete_post_meta( $_POST['post_ID'], '_fa_image' );	
		}
		// add custom stuff 
		$extra_fields = array(
			'fa_cust_title'=>'_fa_cust_title',
			'fa_cust_link'=>'_fa_cust_link',
			'fa_cust_class'=>'_fa_cust_class',
			'fa_cust_txt'=>'_fa_cust_txt',
			'fa_bg_color'=>'_fa_bg_color'
			
		);
		foreach( $extra_fields as $post_key=>$meta_field ){
			if( empty( $_POST[$post_key] ) ){
				delete_post_meta( $_POST['post_ID'], $meta_field );
			}else{
				update_post_meta( $_POST['post_ID'], $meta_field, $_POST[$post_key]);
			}
		}	
	}
}

/**
 * Shortcode slider display
 */
add_shortcode('FA_Lite', 'FA_lite_shortcode');
function FA_lite_shortcode($atts){
	extract(shortcode_atts(array(
	      'id' => false
    ), $atts));
    return FA_display_slider($id, false); 
}

/**
 * Activation hook to add admin capabilities 
 */
function FA_activation(){
	// give permission to administrator to change slider settings
	if( current_user_can('manage_options') ){
		if( !current_user_can( FA_CAPABILITY ) ){
			global $wp_roles;
			$wp_roles->add_cap('administrator', FA_CAPABILITY);
		}
	}
	
	$plugin_details = array(
		'version'=>FA_VERSION,
		'wp_version'=>get_bloginfo('version'),
		'plugin_activation_date'=>date('d M Y H:i:s')
	);
	
	$create = add_option('fa_plugin_details', $plugin_details, '', false);
	if( !$create ){
		update_option('fa_plugin_details', $plugin_details);
	}
}
register_activation_hook(__FILE__, 'FA_activation');

/**
 * Admin messages
 */
function FA_admin_head(){
	if( !isset($_GET['page']) || !strstr($_GET['page'], 'featured-articles') ) return;
	/**
	 * @todo - afiseaza mesaje catre user
	 */
}
add_action('all_admin_notices', 'FA_admin_head');
/**
 * Hooks
 */
add_action('init', 'FA_init');

add_action('admin_menu', 'FA_post_actions');
add_action('save_post', 'FA_save_meta');
// script loading in header
add_action('wp_print_scripts', 'FA_add_scripts');
add_action('wp_print_styles','FA_add_styles');
// script loading in footer for manually implemented sliders
add_action('wp_footer', 'FA_load_footer');
add_action('loop_start', 'featured_articles_slideshow',1);
?>