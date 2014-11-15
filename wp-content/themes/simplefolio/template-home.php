<?php
/*
Template Name: Home
*/
?>

<?php get_header(); ?>
					<ul id="visual">
						<?php
						//Disabled slider b/c WP_Query didn't return posts anymore after 3.9 update. Also commented out functions in js/custom.js and js/plugins.js

						/*
							$category = sf_get_category_id(get_option('sf_portfolio_category'));
							$slide_count = (get_option('sf_slider_slides')) ? get_option('sf_slider_slides') : 5;
							$text_count = (get_option('sf_slider_chars')) ? get_option('sf_slider_chars') : 100;
							$my_query = new WP_Query('showposts='.$slide_count.'&cat='.$category);
							while ($my_query->have_posts()) : $my_query->the_post();
								$do_not_duplicate = $post->ID;
								$thumb = get_post_meta($post->ID, 'thumb-large', true);
								*/?>
									<li>
										<img src="http://fotoreizen.net/wp-content/uploads/2014/01/slider-homepage-IJsland-herfst.jpg" alt="Fotoreis Ijsland" />
							<span class="caption"></span>
				                        <div class="clear sliderImage"></div>
									</li>
						<?php
						    //endwhile;
						?>
                    </ul>
				<div id="content-container">
				<?php if (get_option('sf_slogan_status')) { ?>
				<div class="slogan">
					<div class="qbutton"><a href="<?php echo stripslashes(get_option('sf_slogan_url')); ?>"><?php echo stripslashes(get_option('sf_slogan_quote')); ?></a></div>
					<h2><?php echo stripslashes(get_option('sf_slogan_header')); ?></h2>
					<p><?php echo stripslashes(get_option('sf_slogan_text')); ?></p>
				</div>
				<?php } ?>
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<div class="entry">
					<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
					</div>
					<?php endwhile; endif; ?>
				<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
				<div class="home_widgets">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Homepage Widget') ) : ?>
					<?php endif; ?>
					</div>
<?php get_footer(); ?>