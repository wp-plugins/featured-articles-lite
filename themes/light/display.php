<div class="FA_overall_container">	
	<?php if ($options['section_display']==1): ?><h3 class="FA_title_section"><?php echo $options['section_title']?></h3><?php endif;?>
	<div class="FA_featured_articles">
	<?php foreach ($postslist as $post):?>
		<div class="FA_article">	
			<?php if( $image = FA_article_image($post) ):?>		
				<div class="image_container"><img src="<?php echo $image;?>" alt="<?php the_title('','');?>" class="FA_image" width="<?php echo $options['th_width'];?>" height="<?php echo $options['th_height'];?>" /></div>
			<?php endif;?>
			<?php if( $options['title_click'] ):?><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php endif;?>
				<?php the_title('<h2>','</h2>');?>
			<?php if( $options['title_click'] ):?></a><?php endif;?>
			<span class="FA_date"><?php the_time(get_option('date_format')); ?></span>
			<p><?php echo FA_truncate_text($post->post_content, $image ? $options['desc_truncate'] : $options['desc_truncate_noimg']);?></p>	
			<a class="FA_read_more" href="<?php the_permalink();?>" title="<?php the_title();?>"><?php echo $options['read_more'];?></a>			
		</div>	
	<?php endforeach;?>			
	</div>
	<?php if( $options['bottom_nav'] && count($postslist) > 1 ):?>
		<ul class="FA_navigation">
		<?php foreach ($postslist as $post):?>
			<li>
				<span class="FA_current"><?php the_title();?></span>
				<a href="#" title=""></a>
			</li>
		<?php endforeach;?>
		</ul>			
	<?php endif;?>
	<?php if( $options['sideways_nav'] && count($postslist) > 1 ):?>
		<a href="#" title="<?php __('Previous post');?>" class="FA_back"></a>
		<a href="#" title="<?php __('Next post');?>" class="FA_next"></a>
	<?php endif;?>	
</div>