<?php

/**
 * Class to manage custom post type for sliders and slides
 *
 */
class FA_Custom_Post_Type{
	/**
	 * Slider post type
	 * @var string
	 */
	private $type_slider 	= 'fa_slider';
	/**
	 * Stores capabilities
	 */
	private $capabilities = array();
	
	/**
	 * Constructor; hooks to all neccessary actions and filters.
	 */
	public function __construct(){
		// filter to register post types
		//add_action('init', array($this, 'register_post_type'), 1);
		$this->register_post_type();
		
		// custom post type messages
		add_filter('post_updated_messages', array($this, 'updated_messages'));
		
		// store post capabilities mapping for later use
		$this->set_capabilities();
	}
	
	/**
	 * Stores the plugin post capabilities mapping
	 */
	public function set_capabilities(){
		// post management capabilities
		$this->capabilities = array(
			'edit_posts' 			=> array(
				'label' => __('Create own sliders and slides', 'fapro'),
				'cap' 	=> 'edit_fa_items'
			),
			'delete_posts' 			=> array(
				'label' => __('Delete own slides and sliders', 'fapro'),
				'cap'	=> 'delete_fa_items'
			),	
			'publish_posts' 		=> array(
				'label' => __('Publish own slides and sliders', 'fapro'),
				'cap' 	=> 'publish_fa_items'
			),			
			'delete_others_posts' 	=> array(
				'label' => __('Delete other slides and sliders', 'fapro'),
				'cap'	=> 'delete_others_fa_items'
			),
			'edit_others_posts' 	=> array(
				'label'	=> __('Edit others slides and sliders', 'fapro'),
				'cap' 	=> 'edit_others_fa_items' 
			),
			'read_private_posts' 	=> array(
				'label' => __('Read private slides and sliders', 'fapro'),
				'cap'	=> 'read_private_fa_items'
			),
			// terms
			'manage_terms' => array(
				'label' => __('Manage slide groups', 'fapro'),
				'cap' => 'manage_fa_terms'
			),
			'edit_terms' => array(
				'label' => __('Edit slide groups', 'fapro'),
				'cap' => 'edit_fa_terms'
			),
			'delete_terms' => array(
				'label' => __('Delete slide groups', 'fapro'),
				'cap' => 'delete_fa_terms'
			),
			'assign_terms' => array(
				'label' => __('Assign slide groups', 'fapro'),
				'cap' => 'assign_fa_terms'
			)			
		);
	}
	
	/**
	 * Register slider and slide post types
	 */
	public function register_post_type(){
		/**
		 * Register slider post type
		 */
		register_post_type( $this->type_slider, 
			array(
				'labels' => array(
		        	'name' 				=> _x('Sliders', 'Slider post type name', 'fapro'),
		        	'singular_name' 	=> _x('Slider', 'Slider post type singular name',  'fapro' ),
					'menu_name' 		=> _x('FA Lite 3', 'Slider admin menu name', 'fapro'),
					'name_admin_bar' 	=> _x('FA Lite Slider', 'Admin bar slider post type name', 'fapro'),
					'all_items' 		=> __('Sliders', 'fapro'),
					'add_new' 			=> __('Add new', 'fapro'),
					'add_new_item' 		=> __('Add new slider', 'fapro'),
					'edit_item' 		=> __('Edit slider', 'fapro'),
					'new_item' 			=> __('New slider', 'fapro'),
					'view_item' 		=> __('View slider', 'fapro'),
					'search_items' 		=> __('Search', 'fapro'),
					'not_found' 		=> __('No sliders found', 'fapro'),
					'not_found_in_trash'=> __('No sliders in trash', 'fapro')
		   		),
		    	'public' 			=> false,
		   		'show_ui' 			=> true,
		   		'show_in_menu' 		=> true,
		   		'show_in_admin_bar' => true,
		   		'can_export'		=> true,
		   		'menu_position' 	=> 20,
		   		'menu_icon'			=> FA_URL.'/assets/admin/images/icon.png',
		   		'capabilities' 		=> $this->get_caps(),
		   		'map_meta_cap'		=> true,
		   		'hierarchical'		=> false,
		   		// to extend this, make sure to allow the meta box in FA_Admin::remove_meta_boxes()
		   		'supports'			=> array('title'),
		   		'register_meta_box_cb' => array( $this, 'meta_boxes' )
		    )
		);		
	}
	
	/**
	 * Custom post type messages on edit, update, create, etc.
	 * @param array $messages
	 */
	public function updated_messages( $messages ){
		global $post, $post_ID;
		
		if( $this->type_slider != $post->post_type ){
			return $messages;
		}
		
		$post_type = get_post_type_object( $post->post_type );
		$name = $post_type->labels->singular_name;
		
		$messages[ $post->post_type ] = array(
			0 => '', // Unused. Messages start at index 1.
	    	1 => sprintf( __('%s updated.', 'fapro'), $name ),
	    	2 => __('Custom field updated.', 'fapro'),
	    	3 => __('Custom field deleted.', 'fapro'),
	    	4 => sprintf(__('%s updated.', 'fapro'), $name),
	   		/* translators: %s: date and time of the revision */
	    	5 => isset($_GET['revision']) ? sprintf( __('%s restored to version %s', 'fapro'), $name, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
	    	6 => sprintf( __('%s published.', 'fapro'), $name ),
	    	7 => sprintf( __('%s saved.', 'fapro'), $name),
	    	8 => sprintf( __('%s submitted.', 'fapro'), $name ),
	    	9 => sprintf( __('%1$s will be published at: <strong>%2$s</strong>.', 'fapro'),
	      	// translators: Publish box date format, see http://php.net/date
	      	$name, date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )),
	    	10 => sprintf( __('%s draft saved.', 'fapro'), $name ),
	    );
	
		return $messages;
	}
	
	/**
	 * Get plugin capabilities mapping
	 */
	public function get_caps( $with_labels = false ){
		if( $with_labels ){
			return $this->capabilities;
		}
		
		$caps = array();
		foreach( $this->capabilities as $wp_cap => $cap ){
			$caps[ $wp_cap ] = $cap['cap'];
		}
		
		return $caps;	
	}
	
	/**
	 * Post types meta boxes callback. 
	 * To add meta boxes to custom post types hook to either actions:
	 * 
	 * fa_meta_box_cb_fa_slider - for slider edit screen
	 * fa_meta_box_cb_fa_slide	- for slide edit screen
	 * 
	 * The hooks return as parameter the current post being edited.
	 */
	public function meta_boxes( $post ){
		// to add meta boxes, hook to action fa_meta_box_cb_$post_type
		do_action( 'fa_meta_box_cb_' . $post->post_type, $post );	
	}
	
	/**
	 * Taxonomies meta box callback
	 * @param object $post
	 * @param array $tax
	 */
	public function taxonomy_meta_boxes( $post, $tax ){
		if( isset( $tax['args']['taxonomy'] ) ){
			do_action( 'fa_tax_meta_box_cb_' . $tax['args']['taxonomy'], $post, $tax );
		}
		post_categories_meta_box($post, $tax);		
	}
	
	/**
	 * Get slider post type
	 */
	public function get_type_slider(){
		return $this->type_slider;
	}
}