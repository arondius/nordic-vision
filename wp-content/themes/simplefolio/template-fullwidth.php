<?php
/*
Template Name: Full Width
*/
?>

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

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<div class="entry">
					<h1 class="title"><?php the_title(); ?></h1>
					<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
					</div>
					<?php endwhile; endif; ?>
				<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
		</div>

<?php get_footer(); ?>