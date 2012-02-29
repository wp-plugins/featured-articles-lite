<?php 
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @version 2.4
 */

include FA_dir('includes/custom_wp_posts_table.php');


// get already selected pages
$slider_id = isset( $_GET['id'] ) ? (int)$_GET['id'] : false;
$options = FA_slider_options( $slider_id );

wp_enqueue_style('FA_add_content', FA_path('styles/add_content_modal.css'));
wp_enqueue_script('FA_content_add_script', FA_path('scripts/admin_content_add_modal.js'), array('jquery'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<title><?php _e('Choose content')?></title>
<?php
wp_admin_css( 'css/global' );
wp_admin_css();
wp_admin_css( 'css/colors' );
wp_admin_css( 'css/ie' );
if ( is_multisite() )
	wp_admin_css( 'css/ms' );
?>
<script language="javascript" type="text/javascript">
	var FA_parent_item = '#FA_content_2 #display_pages';
	var FA_item_id_prefix = 'FA_page_';
	var FA_fields_prefix = 'display_pages';
</script>
<?php	
do_action('admin_print_scripts');
do_action('admin_print_styles');
do_action('admin_head');
?>
</head>
<body>
<div class="wrap">
	<div class="icon32 icon32-posts-page" id="icon-edit"><br></div>
    <h2>Select the content you want to display in slideshow</h2>
	<?php 
	/*
	 * PAGES
	 * */
	$pages_table = new FA_List_Posts_Table(array(
		'singular'=>'page', 
		'plural'=>'pages'
	));
	// set columns to be displayed
    $columns = array(
    	'cb'        	=> '', //Render a checkbox instead of text
    	'post_title' 	=> 'Title',
    	'post_author'   => 'Author',
    	'post_date'  	=> 'Date'
    );
	$pages_table->columns = $columns;   
   
    // get the records from DB
    
	$pages_table->prepare_items();
    $pages_table->display();
	?>
	<input type="button" value="Done, close window" id="close_window" />	
</div>
</body>
</html>
<?php die();?>    