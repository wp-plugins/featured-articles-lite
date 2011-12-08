<?php
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @version 2.4
 */

// get the options
$options = FA_slider_options( $slider_id );
// get the themes
$themes = FA_themes();

/* if editing and the slider id isn't valid, display error message */
if( $slider_id ){
	$slider = get_post($slider_id);
	if( !$slider ){
		$error_message = "Sorry, there's no slider with that ID in here."; 
	}else{
		$current_page.="&action=edit&id=".$slider_id;
	}
}else{
	$current_page.='&action=new';
}	

/* Save the data */
if( isset($_POST['FA-save_wpnonce']) ){
	
	if( !wp_verify_nonce( $_POST['FA-save_wpnonce'], 'FA_saveOptions' ) ) {
		die('Sorry, it looks like your request is not valid. Please try again.');
	}
	// if it's a new slider, save it first
	if( !$slider_id ){
		global $user_ID;
		$post_data = array(
			'post_type'=>'fa_slider',
			'post_title'=>$_POST['section_title'],
			'post_author'=>$user_ID,
			'post_status'=>'publish',
			'comment_status'=>'closed',
			'ping_status'=>'closed'
		);
		$slider_id = wp_insert_post($post_data);
		$current_page.="&action=edit&id=".$slider_id;
	}else{
		$post_data = array(
			'post_title'=>$_POST['section_title'],
			'ID'=>$slider_id
		);
		wp_update_post($post_data);
	}
	// get the default options	
	$defaults = FA_slider_options();
	// save new options
	foreach( $defaults as $meta_key=>$values ){
		if( !is_array($values) || empty($values) ){
			$key = str_replace('_fa_lite_', '', $meta_key);
			if( isset($_POST[$key]) ){
				if(is_bool( $values )){
					$value = true;
				}else{
					$value = $_POST[$key];
				}
			}else{
				$value = false;
			}
			update_post_meta($slider_id, $meta_key, $value);
			continue;
		}		
		$fields = $values;
		foreach( $values as $key=>$value ){
			if( isset( $_POST[$key] ) ){
				if( is_numeric( $value ) ){
					if( is_numeric( $_POST[$key] ) )
						$fields[$key] = $_POST[$key];
				}else if (is_bool( $value )) {
					$fields[$key] = true;
				}else{
					$fields[$key] = $_POST[$key];
				}
			}else{
				$fields[$key] = false;
			}
			// for themes with background image ( in functions.php, key image has value background ) set display thumbnail to be true
			$theme = $_POST['active_theme'];
			if( $key == 'thumbnail_display' && $themes[$theme]['theme_config']['Image'] == 'background' ){
				$fields['thumbnail_display'] = true;
			}			
		}
		update_post_meta($slider_id, $meta_key, $fields);		
	}
	
	// make some verifications to set the slider as homepage slider or not
	$on_homepage = get_option('fa_lite_home', array());
	if( isset($_POST['home_display']) && !in_array($slider_id, $on_homepage) ){
		$on_homepage[$slider_id] = $slider_id;
	}else if(in_array($slider_id, $on_homepage) && !isset($_POST['home_display'])){
		unset($on_homepage[$slider_id]);
	}
	update_option('fa_lite_home', $on_homepage);
	// update categories where slider will display
	$new_categs = isset($_POST['categ_display']) && !empty($_POST['categ_display'][0]) ? $_POST['categ_display'] : false;
	FA_update_display('fa_lite_categories', $slider_id, $new_categs);
	// update pages where slider will display
	$new_pages = isset($_POST['page_display']) && !empty($_POST['page_display'][0]) ? $_POST['page_display'] : false;
	FA_update_display('fa_lite_pages', $slider_id, $new_pages);
	
	// set pages order
	if( isset($_POST['display_pages_ord']) && !empty($_POST['display_pages_ord']) ){
		foreach($_POST['display_pages_ord'] as $page_id=>$ord){
			$meta_key = '_fa_lite_'.$slider_id.'_page_ord';
			update_post_meta($page_id, $meta_key, $ord);
		}		
	}	
	
	// set featured order
	if( isset($_POST['display_featured_ord']) && !empty($_POST['display_featured_ord']) ){
		foreach($_POST['display_featured_ord'] as $page_id=>$ord){
			$meta_key = '_fa_lite_'.$slider_id.'_featured_ord';
			update_post_meta($page_id, $meta_key, $ord);
		}		
	}	
	
	// redirect to edit page
	wp_redirect( $current_page );	
	exit();	
}

$current_theme = $options['_fa_lite_theme']['active_theme'];
$fields = FA_fields( (array)$themes[$current_theme]['theme_config']['Fields'] );
?>
<div class="wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
    <h2><?php if(!$slider_id):?>Add new<?php else:?>Edit<?php endif;?> Slider</h2>
	<?php if( isset($error_message) ):?>
        <?php echo $error_message;?>
    <?php  
        exit();
        endif;
    ?>
    
    <form method="post" action="<?php echo $current_page.'&amp;noheader=true';?>" id="FeaturedArticles_settings">
    	<?php wp_nonce_field('FA_saveOptions', 'FA-save_wpnonce');?>
        <div id="poststuff" class="metabox-holder has-right-sidebar">
        	<div id="side-info-column" class="inner-sidebar">
                <?php do_meta_boxes( $page_hook, 'side', null);?>
                <?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );?>
				<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );?>
            </div>
        	<div class="post-body" id="post-body">            
                <div id="post-body-content">
	                <div id="normal-sortables" class="meta-box-sortables">
		                <div id="fa-content-options" class="postbox <?php echo postbox_classes('fa-content-options', $page_hook);?>">
							<div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Slider Options</span></h3>
							<div class="inside panel">
	                            <label for="displayed_content" class="clear"><strong>Build slides based on</strong> <a href="#" class="FA_info" title="Choose between displaying posts, pages or Featured content into your slider. Each option displays different settings that allow to further customize the content displayed.">[ i ]</a></label> 
	                            <?php
	                                $selected_content = $options['_fa_lite_content']['displayed_content'];
	                                $content_types = array(
	                                    'Posts'=>1,
	                                    'Pages'=>2
	                                );
	                            ?>
	                            <select name="displayed_content" id="displayed_content">
	                                <?php 
	                                    foreach ($content_types as $title=>$val):
	                                        $sel = $val == $selected_content ? ' selected="selected"' : '';
	                                ?>
	                                <option value="<?php echo $val;?>"<?php echo $sel;?>><?php echo $title;?></option>
	                                <?php endforeach;?>
	                            </select>							
								<?php 
									// check if any categories are set. If none, all will be used
									$categs = $options['_fa_lite_content']['display_from_category'];
									$has_options = true;	
									if( !is_array($categs) || (count($categs) == 1 && empty($categs[0]) ) ){
										$has_options = false;
									}
									// get all categories from blog
									$all_categories = get_categories('child_of=0');
									// set all categories checkbox as checked if no categories option is set
									$all_selected = !$has_options ? ' checked="checked"' : '';
									// determine the content type chosen by the user to be displayed
									$content = $options['_fa_lite_content']['displayed_content'];
								?>
	                            <div id="FA_content_1" class="FA_content_display"<?php if( $content!=1 ):?> style="display:none;"<?php endif;?>>
	                            	<table class="FA_options_table">
	                                	<tr>
	                                    	<td class="label"><label for="num_articles" title="<?php _e('Maximum number of posts to display into the slider');?>"><?php _e('Use');?></label></td>
	                                        <td><input type="text" name="num_articles" id="num_articles" value="<?php echo $options['_fa_lite_content']['num_articles']; ?>" class="small-text FA_number" /> <?php _e('(numeric)');?></td>
	                                    </tr>
	                                    <tr>
	                                    	<td class="label"><label for="display_categs" title="<?php _e('Choose specific categories to display posts from.');?>"><?php _e('From');?></label></td>
	                                        <td>
	                                        	<input type="checkbox"<?php echo $all_selected;?> name="display_from_category[]" value=""> <label>All categories</label><br />
												<?php 
	                                                foreach($all_categories as $category):
	                                                    $cat_id = $category->term_id;
	                                                    $selected = is_array($categs) ? $categs : array();
	                                                    $s = in_array($cat_id, $selected) ? ' checked="checked"' : '';
	                                            ?>
	                                            <input type="checkbox"<?php echo $s;?> name="display_from_category[]" value="<?php echo $category->term_id;?>" /> <label><?php echo $category->name;?></label><br />
	                                            <?php endforeach;?>
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                    	<td class="label"><label for="author" title="<?php _e('Display posts only from this author')?>"><?php _e('From author');?></label></td>
	                                    	<td>
	                                    		<?php
	                                    			$args = array(
	                                    				'show_option_all'=>'All',
	                                    				'name'=>'author',
	                                    				'show'=>'user_login',
	                                    				'selected'=>$options['_fa_lite_content']['author']
	                                    			); 
	                                    			wp_dropdown_users($args);
	                                    		?>
	                                    	</td>
	                                    </tr>
	                                    <tr>
	                                    	<td class="label"><label for="" title="<?php _e('Choose display order');?>"><?php _e('Ordered');?></label></td>
	                                        <td>
	                                        	<?php $ord = $options['_fa_lite_content']['display_order'];?>
	                                        	<input type="radio" name="display_order" value="1"<?php if($ord=='1'): ?> checked="checked"<?php endif;?> id="FA_order_date" /> <label for="FA_order_date"><?php _e('Newest first');?></label><br />
	                                    		<input type="radio" name="display_order" value="2"<?php if($ord=='2'): ?> checked="checked"<?php endif;?> id="FA_comments_posts" /> <label for="FA_comments_posts"><?php _e('Most commented');?></label><br />
	                                    		<input type="radio" name="display_order" value="3"<?php if($ord=='3'): ?> checked="checked"<?php endif;?> id="FA_rand_posts" /> <label for="FA_rand_posts"><?php _e('Random order');?></label>
	                                        </td>
	                                    </tr>
	                                </table>
	                           	</div>
	                            <?php 
									$opt = $options['_fa_lite_content'];
									$opt['displayed_content'] = 2;
									$selected_pages = FA_get_pages($slider_id, $opt);
								?>
								<div id="FA_content_2" class="FA_content_display"<?php if( $content!=2 ):?> style="display:none;"<?php endif;?>> 
	                            	<ul class="FA_sortable" id="display_pages">
	                                <?php foreach($selected_pages as $k=>$page):?>
	                               		<li id="FA_page_<?php echo $page->ID;?>" class="<?php echo $page->post_type;?>">
	                                    	<a href="post.php?post=<?php echo $page->ID;?>&action=edit"><?php echo $page->post_title;?></a>
	                                    	<input type="hidden" name="display_pages[]" value="<?php echo $page->ID;?>" />
	                                        <input type="hidden" class="ord_save" name="display_pages_ord[<?php echo $page->ID;?>]" value="<?php echo $k?>" />
	                                        <a class="remove_item" href="#">Remove</a>
	                                    </li>
	                               	<?php endforeach;?>
	                                </ul><br />
	                            	<a href="admin.php?page=featured-articles-lite/add_content.php&id=<?echo $slider_id;?>&s=pages&noheader=true" class="button FA_dialog">Select Pages</a>
	                            </div>                
							</div>
	                        
	                        <div class="inside panel separator">
	                        	<strong class="section-title">Slider Theme</strong>
	                            <div class="columns">
	                            	<div class="left">
	                                	<div class="image-holder" id="preview-holder">
	                                    	<?php 
												$preview_image =  $themes[$current_theme]['preview'];
												if( $preview_image ){
													echo '<img src="'.$preview_image.'" alt="" />';	
												}else{
													echo '<span>Preview image not available</span>';
												}
											?>
	                                    </div>
	                                </div>
	                                <div class="right">
	                                
	                                	<label for="FA_active_theme" title="<?php _e('Base theme');?>"><?php _e('Base theme: ');?></label>
	                                    <select name="active_theme" id="FA_active_theme">
	                                    <?php foreach ($themes as $theme=>$params):?>
	                                    <option value="<?php echo $theme;?>"<?php if($options['_fa_lite_theme']['active_theme'] == $theme):?> selected="selected"<?php endif;?> /><?php echo ucfirst(str_replace('_', ' ', $theme));?></option>
	                                    <?php endforeach;?>
	                                    </select><br />
	                                    <?php 
	                                        foreach ($themes as $theme=>$params){
	                                            if( isset($params['colors']) && !empty($params['colors']) ):
	                                                $visibility = $options['_fa_lite_theme']['active_theme'] == $theme ? 'style="display:block"' : 'style="display:none"';
	                                            ?>
	                                            <div class="colors_selector" id="<?php echo $theme?>-colors" <?php echo $visibility;?>>
	                                                <label for="<?php echo $theme?>-colors">Color scheme: </label>
	                                                <select name="active_theme_color" id="<?php echo $theme?>-colors">
	                                                    <?php 
	                                                        foreach( $params['colors'] as $color):
	                                                            $selected = $options['_fa_lite_theme']['active_theme_color'] == $color ? ' selected="selected"' : '';
	                                                    ?>
	                                                    <option<?php echo $selected;?> value="<?php echo $color;?>"><?php echo ucfirst(str_replace('.css', '',$color));?></option>
	                                                    <?php endforeach;?>
	                                                </select>
	                                            </div>
	                                            <?php 
	                                            endif;            		
	                                        }
	                                    ?>
	                                    <div id="FA_theme_messages"></div>
	                                    <input type="checkbox" class="FA_optional" name="bottom_nav" id="bottom_nav" value="1"<?php if( $options['_fa_lite_aspect']['bottom_nav'] ):?> checked="checked"<?php endif;?><?php if($fields['bottom_nav'] == 0):?> disabled="disabled"<?php endif;?> />
	                                    <label for="bottom_nav" class="FA_inline FA_optional<?php if($fields['bottom_nav'] == 0):?> disabled<?php endif;?>"><?php _e('Display bottom navigation');?></label><br />            
	                                    <input type="checkbox" class="FA_optional" name="sideways_nav" id="sideways_nav" value="1"<?php if( $options['_fa_lite_aspect']['sideways_nav'] ):?> checked="checked"<?php endif;?><?php if($fields['sideways_nav'] == 0):?> disabled="disabled"<?php endif;?> />   
	                                    <label for="sideways_nav" class="FA_inline FA_optional<?php if($fields['sideways_nav'] == 0):?> disabled<?php endif;?>"><?php _e('Display sideways navigation');?></label><br />
	                                    <?php do_action('fa_extra_theme_fields', $options);?>
	                                </div>
	                            </div>
	                            <br class="clear" />
	                            
	                            <!-- Animation settings section -->
	                            <strong class="section-title separator">Animation</strong>
	                            
	                            <label for="fadePosition" class="FA_optional<?php if( $fields['fadePosition'] == 0 ):?> disabled<?php endif;?>" title="<?php _e('Choose between top sliding or left sliding entry point')?>"><?php _e('Effect direction:');?></label>
	                            <select name="fadePosition" id="fadePosition" class="FA_optional"<?php if( $fields['fadePosition'] == 0 ):?> disabled="disabled"<?php endif;?>>
	                            	<option value="left"<?php if( $options['_fa_lite_js']['fadePosition'] == 'left' ):?> selected="selected"<?php endif;?>>Slides enter from left</option>
	                                <option value="top"<?php if( $options['_fa_lite_js']['fadePosition'] == 'top' ):?> selected="selected"<?php endif;?>>Slides enter from top</option>
	                            </select><br />
	                            
	                            
	                            <label for="effectDuration" class="FA_optional<?php if( $fields['effectDuration'] == 0 ):?> disabled<?php endif;?>" title="<?php _e('Enter the number of seconds the transition effect should take when sliding')?>"><?php _e('Effect duration:');?></label>
	                            <input id="effectDuration" type="text" name="effectDuration" value="<?php echo $options['_fa_lite_js']['effectDuration']; ?>" class="small-text FA_number float FA_optional"<?php if( $fields['effectDuration'] == 0 ):?> disabled="disabled"<?php endif;?> /> sec. (numeric)<br />
	                            
	                            <label for="fadeDist" class="FA_optional<?php if( $fields['fadeDist'] == 0 ):?> disabled<?php endif;?>" title="<?php _e('Choose the distance to start the sliding effect from (in pixels)');?>"><?php _e('Fade distance:');?></label>
	                            <input type="text" id="fadeDist" name="fadeDist" value="<?php echo $options['_fa_lite_js']['fadeDist']; ?>" class="small-text FA_number FA_optional"<?php if( $fields['fadeDist'] == 0 ):?> disabled="disabled"<?php endif;?> /> <?php _e('pixels (numeric)');?><br />
	                            
	                            <?php do_action('fa_extra_animation_fields', $options);?>
	                            
	                            <!-- Autoplay settings section -->
	                            <strong class="section-title separator">Autoplay</strong>
	                            
	                            <input type="checkbox" id="autoSlide" name="autoSlide"<?php if($options['_fa_lite_js']['autoSlide']) echo ' checked="checked"';?> value="1" />
	                            <label for="autoSlide"><?php _e('Change slides automatically every');?></label>
	                            <input type="text" name="slideDuration" value="<?php echo $options['_fa_lite_js']['slideDuration']; ?>" class="small-text FA_number float" /> <?php _e('seconds');?><br />
	                            
	                            <input type="checkbox" id="stopSlideOnClick" name="stopSlideOnClick"<?php if($options['_fa_lite_js']['stopSlideOnClick']) echo ' checked="checked"';?> value="1" />
	                            <label for="stopSlideOnClick" title="<?php _e('When autoslide in effect, it will stop if user clicks navigation');?>"><?php _e('Navigation click stops Autoplay');?></label><br />
	                            
	                            <?php do_action('fa_extra_autoplay_fields', $options);?>
	                            
	                            <!-- User interaction section -->
	                            <strong class="section-title separator">User interactions</strong>
	                            
	                            <input type="checkbox" id="mouseWheelNav" name="mouseWheelNav"<?php if($options['_fa_lite_js']['mouseWheelNav']) echo ' checked="checked"';?> value="1" />
	                            <label for="mouseWheelNav" title="<?php _e('Enable/disable mouse wheel navigation in slider');?>"><?php _e('Enable mouse wheel navigation');?></label>
	                            
	                            <?php do_action('fa_extra_user_interaction_fields', $options);?>                           
	                            
	                        </div>
						</div>
						<!-- Content display options panel -->
						<div id="fa-text-options" class="postbox <?php echo postbox_classes('fa-text-options', $page_hook);?>">
							<div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Slide Content Options</span></h3>
							<div class="inside">
								<div class="section">
	                            	<strong>Titles</strong>
	                                <input type="checkbox" name="title_custom" id="title_custom" value="1"<?php if( $options['_fa_lite_aspect']['title_custom'] ):?> checked="checked"<?php endif;?> />
	                                <label for="title_custom" title="<?php _e('If a custom title is specified on posts or pages, it will be displayed into the slide');?>"><?php _e('Use custom defined titles (if available)');?></label><br />
	
	                            	<input type="checkbox" class="FA_optional" name="title_click" id="title_click" value="1"<?php if( $options['_fa_lite_aspect']['title_click'] ):?> checked="checked"<?php endif;?><?php if($fields['title_click'] == 0):?> disabled="disabled"<?php endif;?> />
	                                <label for="title_click" class="FA_optional<?php if($fields['title_click'] == 0):?> disabled<?php endif;?>" title="<?php _e('Article title becomes a link pointing to full article');?>"><?php _e('Title is clickable');?></label>
	                           	
	                           		<?php do_action('fa_extra_title_fields', $options);?>
	                           	</div>
	                            <div class="section">
	                            	<strong>Text</strong>
	                                
									<input type="checkbox" name="use_excerpt" id="use_excerpt" value="1"<?php if( $options['_fa_lite_aspect']['use_excerpt'] ):?> checked="checked"<?php endif;?> />
	                                <label for="use_excerpt" title="<?php _e('Display excerpts as slide text if available');?>"><?php _e('Use post excerpt (if defined)');?></label><br />
	                                
	                                <input type="checkbox" name="use_custom_text" id="use_custom_text" value="1"<?php if( $options['_fa_lite_aspect']['use_custom_text'] ):?> checked="checked"<?php endif;?> />
	                                <label for="use_custom_text" title="<?php _e('Always display custom text set for posts/pages');?>"><?php _e('If available, custom defined slide description overrides post excerpt and post content text');?></label><br />
	                                
	                                <input type="checkbox" name="strip_shortcodes" id="strip_shortcodes" value="1"<?php if( $options['_fa_lite_aspect']['strip_shortcodes'] ):?> checked="checked"<?php endif;?> />
	                                <label for="strip_shortcodes" title="<?php _e('Remove all schortcodes from item content.');?>"><?php _e('Remove all shortcodes from descriptions');?></label><br />
	                                
	                                <label for="desc_truncate" title="<?php _e('Posts or pages with image will have maximum this many characters');?>"><?php _e('Limit text in slides with image to');?></label>
	                                <input type="text" name="desc_truncate" id="desc_truncate" value="<?php echo $options['_fa_lite_aspect']['desc_truncate']; ?>" class="small-text FA_number" /> <?php _e('characters (numeric)');?><br />
									
	                                <label for="desc_truncate_noimg" class="FA_optional<?php if($fields['desc_truncate_noimg'] == 0):?> disabled<?php endif;?>" title="<?php _e('Posts or pages without image will have maximum this many characters');?>"><?php _e('Limit text in slides without image to');?></label>
	                                <input type="text" name="desc_truncate_noimg" id="desc_truncate_noimg" value="<?php echo $options['_fa_lite_aspect']['desc_truncate_noimg']; ?>" class="small-text FA_number FA_optional"<?php if($fields['desc_truncate_noimg'] == 0):?> disabled="disabled"<?php endif;?> /> <?php _e('characters (numeric)');?><br />
	                                
	                                <label for="end_truncate" title="<?php _e('');?>"><?php _e('End truncated text with');?></label>
	                                <input type="text" name="end_truncate" id="end_truncate" value="<?php echo $options['_fa_lite_aspect']['end_truncate']; ?>" class="small-text" /><br />
									
	                                <label for="read_more" class="FA_inline" title="<?php _e('Read more link on article will display this text.');?>"><?php _e('Link text');?></label>
	                                <input type="text" id="read_more" name="read_more" value="<?php echo $options['_fa_lite_aspect']['read_more']; ?>" /><br />
	                                
	                                <label for="allowed_tags" title="<?php _e('The tags you specify here will not be stripped from the description.');?>"><?php _e('Allow these HTML tags:');?></label>
	                                <input type="text" name="allowed_tags" id="allowed_tags" value="<?php echo $options['_fa_lite_aspect']['allowed_tags']; ?>" class="regular-text" />
	                                <span class="note">Example to allow links and paragraphs: &lt;a&gt;&lt;p&gt;</span><br />
	                                
	                           		<?php do_action('fa_extra_text_fields', $options);?>
	                            </div>
	                            <div class="section last">
	                            	<strong>Image</strong>
									
	                                <input type="checkbox" id="thumbnail_display" class="FA_optional" name="thumbnail_display"<?php if($options['_fa_lite_aspect']['thumbnail_display']) echo ' checked="checked"';?> value="1"<?php if($fields['thumbnail_display'] == 0):?> disabled="disabled"<?php endif;?> />
	                                <label class="FA_optional<?php if($fields['thumbnail_display'] == 0):?> disabled<?php endif;?>" for="thumbnail_display" title="<?php _e('Choose to display images or not')?>"><?php _e('Display item image');?></label><br />                                
	                                
									<?php if(current_theme_supports('post-thumbnails')):?>
	                                <p class="info">
	                                    Your theme supports thumbnails.<br>
	                                    By default the plugin will search images that are set using the Custom image for Featured Articles Lite feature. If no such image is found, it will display the post thumbnails (if any is set).
	                                </p>
	                                <?php endif;?>
	                                
	                                <label for="th_size" title="<?php _e('Set image maximum size if displayed');?>"><?php _e('Image size:');?></label>
	                                <?php 
	                                    $wp_th_sizes = get_intermediate_image_sizes();
	                                    $wp_th_sizes[] = 'full';
	                                    $sizes = array();
	                                    foreach ($wp_th_sizes as $s){
	                                        $sizes[$s] = FA_image_size_pixels($s);
	                                    }                            		
	                                ?>
	                                <select name="th_size" id="th_size">
	                                <?php foreach($sizes as $s=>$d):
	                                        $sel = $options['_fa_lite_aspect']['th_size'] == $s ? ' selected="selected"' : '';
	                                ?>
	                                    <option<?php echo $sel;?> value="<?php echo $s?>"><?php echo ucfirst($s);?><?php if ($d):?> - max. <?php echo implode('x', $d)?> px.<?php else:?> - max. uploaded size<?php endif;?></option>
	                                <?php endforeach;?>
	                                </select><br />
	                                
	                                <input type="checkbox" class="FA_optional" id="thumbnail_click" name="thumbnail_click"<?php if($options['_fa_lite_aspect']['thumbnail_click']) echo ' checked="checked"';?> value="1"<?php if($fields['thumbnail_click'] == 0):?> disabled="disabled"<?php endif;?> />
	                                <label for="thumbnail_click" class="FA_optional<?php if($fields['thumbnail_click'] == 0):?> disabled<?php endif;?>" title="<?php _e('Image is clickable')?>"><?php _e('Image is clickable');?></label>                              
	                                
	                                <?php do_action('fa_extra_image_fields', $options);?>
	                            </div>
							</div>
						</div>
	                    <?php 
	                    	$plugin_options = FA_plugin_options();
	                    	if( 1 == $plugin_options['auto_insert'] ):
	                    ?>
	                    <!-- Automatic display options panel -->
	                    <div id="fa-autoplacement-options" class="postbox <?php echo postbox_classes('fa-autoplacement-options', $page_hook);?>">
	                        <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Automatic placement Options</span></h3>
	                        <div class="inside">
	                            <div class="section">
	                                <p class="info">
	                                	Automatic placement works by placing the slider before the first loop it find into the pages you selected the slider to be displayed.<br />
	                                    Depending on your theme, the first loop can be, for example, into your sidebar, displaying, let's say, a list of your most recent posts. If that's not the place where you want the slider to show, increase the loop value below until the slider is displayed into the position you desire.<br />
	                                    To display the slider into the actual content section of your blog, the page must display a loop where the main content is.<br /> 
	                                    If you want to display a slider into your sidebar this plugin has a widget that does exactly this. Also, shortcodes are available and for manual placement into your theme files, see the last panel from the sidebar on this screen.  
	                                </p>
	                                <label for="loop_display" title="<?php _e('If you have multiple articles columns displaying in your page, change this value to the column number you want the slider to display on top of.');?>"><?php _e('Display on loop:');?></label>
	                                <input type="text" id="loop_display" name="loop_display" value="<?php echo $options['_fa_lite_display']['loop_display']; ?>" class="small-text FA_number" />
	                            </div>
	                            <div class="section last">
	                                <strong>Display slider on</strong>
	                                <input type="checkbox" name="home_display"<?php echo $options['_fa_lite_home_display']?' checked="checked"':'';?> value="1" id="FA_home"><label for="FA_home"> <?php _e('Display on home page');?></label><br />
	                                 <?php
	                                    $cats = get_categories(); 
	                                    foreach ($cats as $category):
	                                        $checked = in_array($category->term_id,(array)$options['_fa_lite_categ_display']) ? 'checked="checked"' : '';
	                                ?>
	                                <strong class="inline">Categories</strong>
	                                <input type="checkbox" name="categ_display[]" id="category-<?php echo $category->term_id;?>" value="<?php echo $category->term_id;?>"<?php echo $checked;?> /> <label for="category-<?php echo $category->term_id;?>"><?php echo $category->name;?></label><br />
	                                <?php endforeach;?>
	                                <strong class="inline">Pages</strong>
	                                <?php 
	                                    $pages = get_pages();
	                                    foreach ($pages as $page):
	                                        $checked = in_array($page->ID,(array)$options['_fa_lite_page_display']) ? 'checked="checked"' : '';
	                                ?>
	                                <input type="checkbox" name="page_display[]" id="page-<?php echo $page->ID;?>" value="<?php echo $page->ID;?>" <?php echo $checked;?>> <label for="page-<?php echo $page->ID;?>"><?php echo $page->post_title;?></label><br />
	                                <?php endforeach;?>
	                            </div>                            
	                        </div>
	                    </div>
	                    <?php endif;?>
	                </div>
                </div>
                <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
                    
        	</div>
        </div>
    </form>
</div>
<script language="javascript" type="text/javascript">
	jQuery(document).ready(function(){
		var cond = {};
		var messages = {};
		var previews = {};
		<?php 
			foreach($themes as $theme_name=>$theme){
				// disabled fields
				$fields = FA_fields( (array)$theme['theme_config']['Fields'] );
				$js_cond = array();
				foreach($fields as $field=>$value){
					$js_cond[] = $field.':'.$value;
				}
				echo 'cond.'.$theme_name.' ={'.implode(',', $js_cond).'}'.";\n";
				
				// theme message for user
				echo 'messages.'.$theme_name.' = \''.addslashes($theme['theme_config']['Message']).'\''.";\n";
				
				// preview images
				echo 'previews.'.$theme_name.' = \''.$theme['preview'].'\';'."\n";
				
			}		
		?>

		// enable all enabled fields on page load
		// this is needed because theme fields are created disabled by default
		var theme = jQuery('#FA_active_theme').val(),
			conditions = cond[theme];
		jQuery.each(conditions, function(field_id, enabled){
			if( enabled ){
				jQuery('#FeaturedArticles_settings .FA_optional[name='+field_id+']').removeAttr('disabled');
				jQuery('#FeaturedArticles_settings label[for='+field_id+']').removeClass('disabled');
			}
		})
		
		// manage the themes drop-down change event
		jQuery('#FA_active_theme').change(function(){
			var v = jQuery(this).val();
			var fields = jQuery('#FeaturedArticles_settings .FA_optional');

			fields.removeAttr('disabled');
			jQuery.each(fields, function(){
				var id = jQuery(this).attr('id');
				jQuery('#FeaturedArticles_settings label').removeClass('disabled');
			})
			
			jQuery.each( cond[v], function(i,e){				
				if( e!==0 ){
					return;
				}	
				
				var fields = jQuery('#FeaturedArticles_settings .FA_optional[name='+i+']');
				fields.attr({'disabled': 'disabled'});
				
				if( fields.length == 1 ){			
					jQuery('#FeaturedArticles_settings label[for='+i+']').addClass('disabled');
				}else{
					jQuery.each( fields, function(){
						var id = jQuery(this).attr('id');
						jQuery('#FeaturedArticles_settings label[for='+id+']').addClass('disabled');
					})
				}	
				
			});

			jQuery('#FA_theme_messages').html( messages[v] );
			
			var preview = previews[v];
			if( preview == '0' ){
				jQuery('#preview-holder').html('<span>Preview image not available</span>');
			}else{
				jQuery('#preview-holder').html('<img src="'+preview+'" alt="" />');
			}	
								
			
		})
		
	});
</script>