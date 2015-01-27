<?php wp_nonce_field('fa-slide-options-save', 'fa-slide-settings-nonce');?>
<table class="form-table">
	<tbody>
		<?php 
			// on posts other than plugin custom post type, add custom title and custom text fields
			if( !defined('FAPRO_IFRAME') ):
		?>
		<tr>
			<th><label for="fa-custom-title"><?php _e('Slide title', 'fapro');?>:</label></th>
			<td>
				<input type="text" name="fa_slide[title]" id="fa-custom-title" value="<?php echo $options['title'];?>" style="width:80%;" />
				<p class="description"><?php _e('custom title for slide made from this post', 'fapro');?></p>
			</td>
		</tr>
		<tr>
			<th><label for="fa-custom-content"><?php _e('Slide content', 'fapro');?>:</label></th>
			<td>
				<?php 
					wp_editor( $options['content'] , 'fa-custom-content-post', array(
						'teeny' => true,
						'media_buttons' => false,
						'textarea_name' => 'fa_slide[content]',
						'textarea_rows' => 10
					));
				?>
			</td>
		</tr>
		<?php endif;?>	
		<tr>
			<th><label for="fa-link_text"><?php _e('Read', 'fapro');?>:</label></th>
			<td>
				<input type="text" name="fa_slide[link_text]" id="fa-link_text" value="<?php echo $options['link_text'];?>" />
				<span class="description"><?php _e('read more text displayed on slide', 'fapro');?></span>
			</td>
		</tr>
		<tr>
			<th><label for="fa-class"><?php _e('Class', 'fapro');?>:</label></th>
			<td>
				<input type="text" name="fa_slide[class]" id="fa-class" value="<?php echo $options['class'];?>" />
				<span class="description"><?php _e('extra CSS class used to style the slide', 'fapro');?></span>
			</td>
		</tr>
		<tr>
			<th valign="top"><label for="fa-url"><?php _e('URL', 'fapro');?>:</label></th>
			<td>
				<?php fa_option_not_available();?>
			</td>
		</tr>		
		<tr>
			<th><label for="title_color"><?php _e('Title color', 'fapro');?>:</label></th>
			<td>
				<?php fa_option_not_available();?>
			</td>
		</tr>
		<tr>
			<th><label for="content_color"><?php _e('Content color', 'fapro');?>:</label></th>
			<td>
				<?php fa_option_not_available();?>
			</td>
		</tr>
		<tr>
			<th><label for="bg_color"><?php _e('Background color', 'fapro');?>:</label></th>
			<td>
				<?php fa_option_not_available();?>
			</td>
		</tr>
		<?php if( 'attachment' != $post->post_type ):?>
		<tr>
			<th valign="top"><label for="slide_image"><?php _e('Slide image', 'fapro');?>:</label></th>
			<td>
				<?php the_fa_slide_image( $post->ID );?>			
			</td>
		</tr>
		<?php endif;?>				
	</tbody>
</table>
