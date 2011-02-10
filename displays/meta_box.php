<div class="misc-pub-section">
<p>To set up a custom image for this post when you display it in Featured Articles Lite Slider, click the link below.</p>
<a href="../wp-content/plugins/featured-articles-lite/add_meta.php?height=300&width=800&post=<?php echo $post->ID;?>&TB_iframe=true" class="thickbox button" title="<?php _e('Add new image for Featured Articles','wp_featured_articles')?>">Set custom image for this post</a>
<?php if($current_image):?>
<p>
Current image is:
<img src="<?php echo $current_image;?>" alt="Current Featured Articles image set for this post." style="padding:2px; border:1px #000 solid" />
</p>
<p><label><input type="checkbox" value="1" name="fa_remove_meta_image" /> Remove this image</label></p>
<?php endif;?>
</div>
<div class="misc-pub-section last">
<?php wp_nonce_field('fa_article_featured', 'fa_nonce');?>
<label><input type="checkbox" value="1" name="_fa_featured"<?php if($featured):?> checked="checked"<?php endif?> /> Set this post as featured for <em>Featured Articles Lite</em> slider</label>
</div>