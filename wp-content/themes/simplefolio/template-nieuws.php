<?php
/*
Template Name: Nieuws
*/
?>

			<?php get_header();			
			?>

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
					<div class="entry">
					<h1 class="title"><?php the_title(); ?></h1>
					<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
					</div>
					<?php endwhile; endif; ?>
					<?php
			/* Run the loop to output the posts.
			 * If you want to overload this in a child theme then include a file
			 * called loop-index.php and that will be used instead.
			 */
			 get_template_part( 'loop', 'index' );
			?>
						</div>
			<?php get_sidebar('nieuws'); ?>
			<?php get_footer(); ?>