<?php get_header();

// Load current page postdata

	global $post;

	$pid			=	$post->ID; // Get the ID of the current post

	$children		=	wp_list_pages('title_li=&child_of='.$post->ID.'&exclude=964,971,1327,2032,2041&echo=0');

	$ancestors 		= 	get_post_ancestors($pid); // Get all ancestors of the current post

	if (empty($ancestors)){

		$top_ancestor =	$pid;

	} else {

	$top_ancestor 	=	end($ancestors); // Get the ID of the top ancestor

	}

//	function

/*	echo '<pre style="border-bottom: 1px solid red;">';
	print_r($pid);
	echo '<br />';
	print_r($children);
	echo '<br />';
	print_r($ancestors);
	echo '<br />';
	print_r($top_ancestor);
	echo '</pre>';

*/

// Check if the current page has children or is a child
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
<?php

	if ( (is_page()) && ($post->post_parent) || ($children))
	{
		echo '<div id="subnav">';
		echo '<ul>';
		wp_list_pages('title_li=&child_of='.$top_ancestor.'&exclude=964,971,1327,2032,2041');
		echo '</ul>';
		echo '</div>';
		echo '<div id="content-2col" class="content">';
	}
	else {

	echo '<div class="content">';

	}

	?>

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<div class="entry">
					<h1 class="title"><?php the_title(); ?></h1>
					<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
					</div>
					<?php endwhile; endif; ?>
				<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
		</div>
<?php get_footer(); ?>