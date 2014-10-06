	<div class="sidebar">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar Widget') ) : 
	endif; 
	$pid	=	$post->ID;
	$pages = get_pages('child_of='.$pid.'');
	foreach($pages as $child) {
	return $child->ID;
	}
	?>
	</div>