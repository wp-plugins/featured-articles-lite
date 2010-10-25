<div id="FA_overall_container">	
	<?php if ($options['section_display']==1): ?><h3 class="FA_title_section"><?php echo $options['section_title']?></h3><?php endif;?>
	<div id="FA_featured_articles">
	<?php foreach ($postslist as $post):?>
		<div class="FA_article">	
			<?php 
				if( $options['thumbnail_display'] ):
					$meta_image = get_post_meta($post->ID, 'fa_image', true);
					$image = $meta_image ? $meta_image : FA_article_image($post);
			?>
				<?php if( $image ):?>			
				<div class="image_container"><img src="<?php echo $image;?>" alt="<?php the_title('','');?>" class="FA_image" width="<?php echo $options['th_width'];?>" height="<?php echo $options['th_height'];?>" /></div>
				<?php endif;?>
			<?php endif;?>
			<?php the_title('<h2>','</h2>');?>
			<span class="FA_date"><?php the_time('l, F jS, Y') ?></span>
			<p><?php echo truncate_text($post->post_content, $image ? $options['desc_truncate'] : $options['desc_truncate_noimg']);?></p>	
			<a class="FA_read_more" href="<?php the_permalink();?>" title="<?php the_title();?>"><?php _e('Read more');?></a>			
		</div>	
	<?php endforeach;?>			
	</div>
	<?php if( $options['bottom_nav'] && count($postslist) > 1 ):?>
		<ul class="FA_navigation">
		<?php foreach ($postslist as $post):?>
			<li><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a></li>
		<?php endforeach;?>
		</ul>			
	<?php endif;?>	
</div>