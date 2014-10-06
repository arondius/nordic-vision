<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<?php 
					if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
					echo '<div id="visual">';
  					the_post_thumbnail();
					echo '</div>';
} 
					endwhile; 
					endif;
					wp_reset_query();
					?>

<div id="content-container">
			<div id="content-2col" class="content">				
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<div class="blogpost">
						<div class="comments"><?php comments_number('0', '1', '%'); ?></div>
						<h2 class="title"><?php the_title(); ?></h2>
						<div class="meta">Geplaatst om <?php the_time('F jS, Y') ?> in <?php the_category(', ') ?> <?php the_tags( 'and tagged ', ', ', ' '); ?> door <?php the_author() ?></div>
						<div class="entry">
							<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo rawurlencode(get_permalink()); ?>&amp;layout=box&amp;show-faces=false&amp;width=450&amp;height=24&amp;action=like&amp;font=arial&amp;colorscheme=light" scrolling="no" frameborder="0" allowTransparency="true" id="facebook-like"></iframe>
							<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
						</div>
					</div>
					<?php comments_template(); ?>
					<?php endwhile; else: ?>
					
							<?php include_once(TEMPLATEPATH."/page-error.php"); ?>
					
					<?php endif; ?>

				</div>

<?php get_sidebar('nieuws');?>
<?php get_footer(); ?>
