<?php wp_nonce_field('fa-slide-options-save', 'fa-slide-settings-nonce');?>
<table class="form_table">
	<tbody>
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
