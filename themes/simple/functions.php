<?php 
/**
 * Some details about the theme. 
 * Also notice key Fields. It stores the above field and flags it as enabled for this theme. All other themes will display this field disabled.
 */
function fa_theme_simple_details( $defaults ){
	$defaults = array(
		'author'		=> 'CodeFlavors',
		'authorURI'		=> 'http://www.codeflavors.com',
		'copyright'		=> 'author',
		'compatibility'	=> '3.0',
		'version'		=> '1.0',
		'name'			=> 'Simple',
		'fields'		=> array(
			'js-effect' => false
		),
		'colors'		=> array( // tell the plugin about the default color schemes so they won't be edited in theme editor
			'dark'
		),
		'classes' => array(
			'content-right' => __( 'Image left, content right', 'fapro' )
		),
		'stylesheets' => array(
			'font-awesome' => true
		),
		'scripts' => array(),
		'message'		=> '<strong>Responsive slider theme.</strong>'
	);	
	return $defaults;	
}
add_filter('fa-theme-details-' . fa_get_theme_key( __FILE__ ), 'fa_theme_simple_details', 1);

/**
 * CSS customizations
 */
function fa_theme_css_simple(){
	$rules = array(
		'container' => array(
			'css_selector' => '.fa_slider_simple', // all child elements from container will descend from this
			'description' => __( 'Slideshow container', 'fapro' ),
			'properties' => array( // not all properties are supported
				'border-width' 		=> '0px',
				'border-style' 		=> 'none',
				'border-color' 		=> 'transparent', 
				'background-color' 	=> '#FFFFFF'
			)	
		),
		'slide_title' => array(
			'css_selector' 	=> '.fa_slide_content h2',
			'description' 	=> __( 'Slide title', 'fapro' ),
			'properties' 	=> array(
				'font-size' 	=> '2.5em',
				'color'			=> '#575757',
				'font-weight' 	=> '300',
				'text-shadow'	=> '1px 1px 1px #DBDBDB'
			)
		),
		'slide_title_anchor' => array(
			'css_selector' 	=> '.fa_slide_content h2 a',
			'description' 	=> __( 'Slide title anchor', 'fapro' ),
			'properties' 	=> array(
				'color'				=> '#575757',
				'text-decoration' 	=> 'none'
			)
		),
		'slide_text' => array(
			'css_selector' 	=> '.fa_slide_content div.description',
			'description' 	=> __( 'Slide text', 'fapro' ),
			'properties' 	=> array(
				'font-size' 	=> '1em',
				'color' 		=> '#575757',
				'font-weight' 	=> 400
			)
		),
		'read_more' => array(
			'css_selector' 	=> '.fa_slide_content .fa_read_more',
			'description' 	=> __( 'Slide read more link', 'fapro' ),
			'properties' 	=> array(
				'font-size' 		=> '1em',
				'color'				=> '#FFFFFFF',
				'font-weight' 		=> 300,
				'text-decoration' 	=> 'none',
				'background-color'	=> '#575757'
			)
		),
		'content_play_video_link' => array(
			'css_selector' 	=> '.fa_slide_content .fa_play_video',
			'description' 	=> __( 'Play video link', 'fapro' ),
			'properties' 	=> array(
				'font-size' 		=> '1em',
				'color'				=> '#000000',
				'text-decoration' 	=> 'underline'
			)
		),
		'video_container' => array(
			'css_selector' 	=> '.fa_image .fa-video-wrap .fa-video',
			'description' 	=> __( 'Video container', 'fapro' ),
			'properties' 	=> array(
				'background-color' => '#0000000',
				'border-width' => '1px',
				'border-style' => 'solid',
				'border-color' => '#9999999',
				'background-color' => '#000000'
			)
		),
		'video_container_play_video' => array(
			'css_selector' 	=> '.fa_image .play-video',
			'description' 	=> __( 'Play video image link', 'fapro' ),
			'properties' 	=> array(
				'color' 		=> '#FFFFFF',
				'background' 	=> '#000000',
				'border-radius' => '5px'
			)
		),
		'nav_right' => array(
			'css_selector' 	=> '.go-forward',
			'description' 	=> __( 'Navigation forward', 'fapro' ),
			'properties' 	=> array(
				'color' 			=> '#C9C9C9',
				'background-color' 	=> '#EDEDED'
			)
		),
		'nav_left' => array(
			'css_selector' 	=> '.go-back',
			'description' 	=> __( 'Navigation back', 'fapro' ),
			'properties' 	=> array(
				'color' 			=> '#C9C9C9',
				'background-color' 	=> '#EDEDED'
			)
		),
		'nav_bottom_idle' => array(
			'css_selector' 	=> '.main-nav .fa-nav',
			'description' 	=> __( 'Navigation bottom', 'fapro' ),
			'properties' 	=> array(
				'color' 		=> '#777777',
				'text-shadow' 	=> '0px 0px 1px #999999'
			)
		),
		'nav_bottom_hover' => array(
			'css_selector' 	=> '.main-nav .fa-nav:hover',
			'description' 	=> __( 'Navigation bottom mouse over', 'fapro' ),
			'properties' 	=> array(
				'color' => '#000000'
			)
		),
		'nav_bottom_active' => array(
			'css_selector' 	=> '.main-nav .fa-nav.active',
			'description' 	=> __( 'Navigation bottom current item', 'fapro' ),
			'properties' 	=> array(
				'color' => '#575757'
			)
		),
		'progress_bar' => array(
			'css_selector' 	=> '.progress-bar',
			'description' 	=> __( 'Slide timer (on autoslide)', 'fapro' ),
			'properties' 	=> array(
				'background-color' => '#999999'
			)
		)
	);
	
	return $rules;
}
