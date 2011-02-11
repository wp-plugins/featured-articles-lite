<div class="FA-wrapper" id="FA-wrapper">
	<form method="post" action="" id="FeaturedArticles_settings">
    <div class="FA-pannel">
        <h2><?php _e('Featured articles Settings');?></h2>		
        <?php wp_nonce_field('FA_saveOptions', 'FA-save_wpnonce'); ?>
        
        <label for="section_title" title="<?php _e('Title will be displayed above the slider.');?>"><?php _e('Section title:');?></label> 
        <div class="FA_input">
            <input type="text" id="section_title" name="section_title" value="<?php echo $saved_settings['section_title']; ?>" class="FA-large" />
            <label for="section_display" title="<?php _e('Choose whether to display the slider title or not');?>"><?php _e('Display title:');?> </label><input type="checkbox" name="section_display" id="section_display" value="1" <?php if($saved_settings['section_display']): ?> checked="checked"<?php endif;?> /> 
        </div>	
        
        <label for="loop_display" title="<?php _e('If you have multiple articles columns displaying in your page, change this value to the column number you want the slider to display on top of.');?>"><?php _e('Display on loop:');?></label> 
        <div class="FA_input">
            <input type="text" id="loop_display" name="loop_display" value="<?php echo $saved_settings['loop_display']; ?>" class="FA-small FA_number" /> 
        </div>
        
        <label for="desc_truncate" title="<?php _e('Posts or pages with image will have maximum this many characters');?>"><?php _e('Truncate descriptions to:');?></label> <div class="FA_input"><input type="text" name="desc_truncate" id="desc_truncate" value="<?php echo $saved_settings['desc_truncate']; ?>" class="FA-small FA_number" /> characters (numeric)</div>
        <label for="desc_truncate_noimg" title="<?php _e('Posts or pages without image will have maximum this many characters');?>"><?php _e('Truncate descriptions without image to:');?></label> <div class="FA_input"><input type="text" name="desc_truncate_noimg" id="desc_truncate_noimg" value="<?php echo $saved_settings['desc_truncate_noimg']; ?>" class="FA-small FA_number" /> <?php _e('characters (numeric)');?></div>
        
        <label for="allowed_tags" title="<?php _e('The tags you specify here will not be stripped from the description.');?>"><?php _e('Allow these HTML tags:');?></label> <div class="FA_input"><input type="text" name="allowed_tags" id="allowed_tags" value="<?php echo $saved_settings['allowed_tags']; ?>" class="FA-large" /><span class="note">Example to allow links and paragraphs: &lt;a&gt;&lt;p&gt;</span></div>
        
        <label for="num_articles" title="<?php _e('Maximum number of posts or pages to display into the slider');?>"><?php _e('Number of articles:');?></label> <div class="FA_input"><input type="text" name="num_articles" id="num_articles" value="<?php echo $saved_settings['num_articles']; ?>" class="FA-small FA_number" /> <?php _e('(numeric)');?></div>
        <label for="" title="<?php _e('For featured posts, set on posts or pages a custom field FA_featured having value 1');?>"><?php _e('Display order:');?></label>
        <div class="FA_input">
            <input type="radio" name="display_order" value="1"<?php if($saved_settings['display_order']=='1'): ?> checked="checked"<?php endif;?> id="FA_order_date" /> <label for="FA_order_date"><?php _e('Newest posts');?></label><br />
            <input type="radio" name="display_order" value="2"<?php if($saved_settings['display_order']=='2'): ?> checked="checked"<?php endif;?> id="FA_meta_posts" /> <label for="FA_meta_posts" title="<?php _e('To display posts using this feature, set a custom field on any post having as name FA_featured and as value 1 (ie. FA_featured=1)');?>"><?php _e('Featured posts');?></label><br />
            <input type="radio" name="display_order" value="3"<?php if($saved_settings['display_order']=='3'): ?> checked="checked"<?php endif;?> id="FA_comments_posts" /> <label for="FA_comments_posts"><?php _e('Most commented');?></label><br />
            <input type="radio" name="display_order" value="4"<?php if($saved_settings['display_order']=='4'): ?> checked="checked"<?php endif;?> id="FA_comments_posts" /> <label for="FA_comments_posts"><?php _e('Random order');?></label>
        </div>
        <label for="" title="<?php _e('Set thumbnails maximum size if displayed');?>"><?php _e('Thumbnail max size:');?></label> 
        <div class="FA_input">
            <label for="FA_th_width"><?php _e('width:');?> </label>
            <input type="text" name="th_width" id="FA_th_width" value="<?php echo $saved_settings['th_width']; ?>" class="FA-small FA_number" />px; 
            <label for="FA_th_height"><?php _e('height:');?> </label>
            <input type="text" name="th_height" id="FA_th_height" value="<?php echo $saved_settings['th_height']; ?>" class="FA-small FA_number" />px
        </div>
        
        <label for="" title="<?php _e('Set slider size');?>"><?php _e('Slider size:');?></label> 
        <div class="FA_input">
            <label for="slider_width"><?php _e('width:');?> </label>
            <input type="text" name="slider_width" id="slider_width" value="<?php echo $saved_settings['slider_width']; ?>" class="FA-small" />px; 
            <label for="slider_height"><?php _e('height:');?> </label>
            <input type="text" name="slider_height" id="slider_height" value="<?php echo $saved_settings['slider_height']; ?>" class="FA-small" />px<br />
			<span class="note">Use numbers for exact dimensions. For percents, simply add % ( ie: 800 - for 800px or 50% ). To disable size control, enter 0 for size.</span>
        </div>
        
        <label for="thumbnail_display" class="FA_inline" title="<?php _e('Choose to display thumbnails or not')?>"><?php _e('Display thumbnail:');?></label> <div class="FA_input FA_inline"><input type="checkbox" id="thumbnail_display" name="thumbnail_display"<?php if($saved_settings['thumbnail_display']) echo ' checked="checked"';?> value="1" /></div><br />
        <label for="drop_moo" class="FA_inline" title="<?php _e('If already loaded by other plugin, unload MooTools framework')?>"><?php _e('Unload MooTools framework');?></label> <div class="FA_input FA_inline"><input type="checkbox" id="drop_moo" name="drop_moo" value="1"<?php if( $saved_settings['drop_moo'] ): ?> checked="checked"<?php endif;?> /></div><br />
        <label for="styles_in_header" class="FA_inline" title="<?php _e('Manually placed sliders load stylesheet in footer. In some cases, due to page load, the slider design loads too slow and the design is broken for few seconds. To avoid that, allow the plugin to load all scripts in header. Please note that this will load slider stylesheet even in pages where it doesn\'t display.')?>"><?php _e('Always load styles in header');?></label> <div class="FA_input FA_inline"><input type="checkbox" id="styles_in_header" name="styles_in_header" value="1"<?php if( $saved_settings['styles_in_header'] ): ?> checked="checked"<?php endif;?> /></div><br />
        <label for="show_author" class="FA_inline" title="<?php _e('Display author link')?>"><?php _e('Show author link');?></label> <div class="FA_input FA_inline"><input type="checkbox" id="show_author" name="show_author" value="1"<?php if( $saved_settings['show_author'] ): ?> checked="checked"<?php endif;?> /></div>
    </div>
    
    <div class="FA-pannel">
    	<h2><?php _e('Content Settings');?></h2>
        
    	<label for="displayed_content" title="<?php _e('Choose between posts or pages. The next field will automatically display according to your choice.');?>"><?php _e('Slider will display:');?></label>
    	<div class="FA_input">
    		<input type="radio" name="displayed_content" value="1" id="display_posts"<?php if( $saved_settings['displayed_content']!==2 ):?> checked="checked"<?php endif;?>> <label for="display_posts"><?php _e('Posts');?></label>
    		<input type="radio" name="displayed_content" value="2" id="display_pages"<?php if( $saved_settings['displayed_content']==2 ):?> checked="checked"<?php endif;?>> <label for="display_pages"><?php _e('Pages');?></label>
    	</div>
    	
    	<div id="d_display_posts"<?php if( $saved_settings['displayed_content']==2 ):?> style="display:none;"<?php endif;?>>
	    	<label for="display_categs" title="<?php _e('Choose specific categories to display posts from. Hold down CTRL for multiple selections');?>"><?php _e('Display posts from categories:');?></label>
	        <div class="FA_input">
	            <select name="display_from_category[]" id="display_categs" multiple="multiple" size="7" style="height:auto;">
	                <option value="" <?php if(!$saved_settings['display_from_category'][0]):?>selected="selected"<?php endif;?>>All</option>
	                <?php 
	                    $cats = get_categories('child_of=0'); 
	                    foreach ($cats as $category):
	                        $selected = in_array($category->term_id,$saved_settings['display_from_category']) ? 'selected="selected"' : '';
	                ?>
	                <option value="<?php echo $category->term_id;?>" <?php echo $selected;?>><?php echo $category->name;?></option>
	                <?php endforeach;?>
	            </select>	
	        </div>
       </div>
       <div id="d_display_pages"<?php if( $saved_settings['displayed_content']!=2 ):?> style="display:none;"<?php endif;?>> 
	       <label for="display_pages" title="<?php _e('Choose pages to be displayed in slider. Hold down CTRL key to select multiple pages');?>"><?php _e('Display pages');?>:</label>
	       <div class="FA_input">
				<select name="display_pages[]" id="display_pages" multiple="multiple" size="7" style="height:auto;">			        
				    <option value="" <?php if(!$saved_settings['display_pages'][0]):?>selected="selected"<?php endif;?>>All</option>
					<?php 
				        $pages = get_pages();
				        foreach ($pages as $page):
				            $selected = in_array($page->ID,$saved_settings['display_pages']) ? 'selected="selected"' : '';
				    ?>
				    <option value="<?php echo $page->ID;?>" <?php echo $selected;?>><?php echo $page->post_title;?></option>
				    <?php endforeach;?>			   
				</select>
	       </div> 
        </div>
	    
	    <label for="read_more" class="FA_inline" title="<?php _e('Read more link on article will display this text.');?>"><?php _e('Read more link text:');?></label> 
        <div class="FA_input FA_inline">
            <input type="text" id="read_more" name="read_more" value="<?php echo $saved_settings['read_more']; ?>" />
        </div><br />
	    
	    <label for="title_click" class="FA_inline" title="<?php _e('Article title becomes a link pointing to full article');?>"><?php _e('Article title is clickable');?>:</label>
        <div class="FA_input FA_inline"><input type="checkbox" name="title_click" id="title_click" value="1"<?php if( $saved_settings['title_click'] ):?> checked="checked"<?php endif;?> /></div><br />
		
		<h2><?php _e('Display Settings');?></h2>
	       
        <label for="" title="<?php _e('Choose where to display the slider')?>"><?php _e('Display slider on');?>:</label>
        <div class="FA_input">
            <input type="checkbox" name="firstpage_display"<?php echo $saved_settings['firstpage_display']?' checked="checked"':'';?> value="1" id="FA_home"><label for="FA_home"><?php _e('display on home page');?></label><br />
            <select name="display_in_category[]" multiple="multiple" size="7" style="height:auto;">
                <optgroup label="Categories">
                    <option value="">None</option>
                <?php 
                    foreach ($cats as $category):
                        $selected = in_array($category->term_id,$saved_settings['display_in_category']) ? 'selected="selected"' : '';
                ?>
                <option value="<?php echo $category->term_id;?>" <?php echo $selected;?>><?php echo $category->name;?></option>
                <?php endforeach;?>
                </optgroup>
            </select>
            <select name="display_in_page[]" multiple="multiple" size="7" style="height:auto;">
                <optgroup label="Pages">
                    <option value="">None</option>
                <?php 
                    $pages = get_pages();
                    foreach ($pages as $page):
                        $selected = in_array($page->ID,$saved_settings['display_in_page']) ? 'selected="selected"' : '';
                ?>
                <option value="<?php echo $page->ID;?>" <?php echo $selected;?>><?php echo $page->post_title;?></option>
                <?php endforeach;?>
                </optgroup>
            </select>
        </div> 
        <label for="bottom_nav" class="FA_inline"><?php _e('Display bottom navigation');?>:</label>
        <div class="FA_input FA_inline"><input type="checkbox" name="bottom_nav" id="bottom_nav" value="1"<?php if( $saved_settings['bottom_nav'] ):?> checked="checked"<?php endif;?> /></div><br />
		<label for="sideways_nav" class="FA_inline"><?php _e('Display sideways navigation:');?></label>
        <div class="FA_input FA_inline"><input type="checkbox" name="sideways_nav" id="sideways_nav" value="1"<?php if( $saved_settings['sideways_nav'] ):?> checked="checked"<?php endif;?> /></div><br />
    </div>
    
    <div class="FA-pannel">
        <h2><?php _e('JavaScript Settings');?></h2>
        
        <label for="effectDuration" title="<?php _e('Enter the number of seconds the transition effect should take when sliding')?>"><?php _e('Effect duration:');?></label><div class="FA_input"><input id="effectDuration" type="text" name="effectDuration" value="<?php echo $saved_settings['effectDuration']; ?>" class="FA-small FA_number float" /> seconds (numeric)</div>
        <label for="fadeDist" title="<?php _e('Choose the distance to start the sliding effect from (in pixels)');?>"><?php _e('Fade distance:');?></label><div class="FA_input"><input type="text" id="fadeDist" name="fadeDist" value="<?php echo $saved_settings['fadeDist']; ?>" class="FA-small FA_number" /> <?php _e('pixels (numeric)');?></div>
        <label for="" title="<?php _e('Choose between top sliding or left sliding entry point')?>"><?php _e('Slides entering from:');?></label>
        <div class="FA_input">
            <input type="radio" id="fadeLeft" name="fadePosition" value="left"<?php if( $saved_settings['fadePosition'] == 'left' ):?> checked="checked"<?php endif;?> /> <label for="fadeLeft">left</label>
            <input type="radio" id="fadeTop" name="fadePosition" value="top"<?php if( $saved_settings['fadePosition'] == 'top' ):?> checked="checked"<?php endif;?> /> <label for="fadeTop">top</label>
        </div>
        <label for="stopSlideOnClick" title="<?php _e('When autoslide in effect, it will stop if user clicks navigation');?>"><?php _e('Navigation click stops auto sliding:');?></label><div class="FA_input"><input type="checkbox" id="stopSlideOnClick" name="stopSlideOnClick"<?php if($saved_settings['stopSlideOnClick']) echo ' checked="checked"';?> value="1" /></div>
        <label for="mouseWheelNav" title="<?php _e('Enable/disable mouse wheel navigation in slider');?>"><?php _e('Enable mouse wheel navigation:');?></label><div class="FA_input"><input type="checkbox" id="mouseWheelNav" name="mouseWheelNav"<?php if($saved_settings['mouseWheelNav']) echo ' checked="checked"';?> value="1" /></div>
        <label for="autoSlide"><?php _e('Autoslide:');?></label><div class="FA_input"><input type="checkbox" id="autoSlide" name="autoSlide"<?php if($saved_settings['autoSlide']) echo ' checked="checked"';?> value="1" /> <?php _e('every');?> <input type="text" name="slideDuration" value="<?php echo $saved_settings['slideDuration']; ?>" class="FA-small FA_number float" /> <?php _e('seconds (numeric)');?></div>
    </div>    
    
    <div class="FA-pannel">
    	<h2><?php _e('Theme settings')?></h2>
    	<label for="" title="<?php _e('Select slider theme');?>"><?php _e('Select a theme');?></label>
    	<div class="FA_input">
    		<?php foreach ($themes as $theme):?>
    		<input type="radio" name="active_theme" id="FA_<?php echo $theme;?>" value="<?php echo $theme;?>"<?php if($saved_settings['active_theme'] == $theme):?> checked="checked"<?php endif;?> /> <label for="FA_<?php echo $theme?>"><?php echo ucfirst(str_replace('_', ' ', $theme));?></label><br />
    		<?php endforeach;?>
    	</div>
    </div>
    
	<input type="submit" class="save-changes" value="<?php _e('Save Changes') ?>" />	
	</form>
</div>

<div class="wrap">
	<h2>Author info</h2>
	Plugin author: Constantin Boiangiu<br />
	E-mail: constantin[at]php-help.ro<br />
	<a href="http://www.php-help.ro" target="_blank" title="web developer resources">www.php-help.ro</a><br />
</div>