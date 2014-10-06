<?php 
get_header('tour'); 
byt_breadcrumbs();
get_sidebar('under-header');

global $post, $tour_date_from, $current_user, $tour_obj, $entity_obj, $currency_symbol, $price_decimal_places, $score_out_of_10, $enable_reviews, $default_tour_tabs, $default_tour_extra_fields;

$tour_extra_fields = of_get_option('tour_extra_fields');
if (!is_array($tour_extra_fields) || count($tour_extra_fields) == 0)
	$tour_extra_fields = $default_tour_extra_fields;

$tab_array = of_get_option('tour_tabs');
if (!is_array($tab_array) || count($tab_array) == 0)
	$tab_array = $default_tour_tabs;

if ( have_posts() ) {

	the_post();
	$tour_obj = new byt_tour($post);
	$entity_obj = $tour_obj;
	$tour_map_code = $tour_obj->get_custom_field( 'map_code' );

	$tour_location = $tour_obj->get_location();
	$tour_location_title = '';
	if ($tour_location)
		$tour_location_title = $tour_location->get_title();

	$tour_date_from = date('Y-m-d', strtotime("+0 day", time()));
	$tour_date_from_year = date('Y', strtotime("+0 day", time()));
	$tour_date_from_month = date('n', strtotime("+0 day", time()));
	
	if ($enable_reviews) {
		get_template_part('includes/parts/review', 'form'); 
	}
	get_template_part('includes/parts/inquiry', 'form');
	?>
	<!--tour three-fourth content-->
	<section class="three-fourth">
		<?php
		get_template_part('includes/parts/tour', 'booking-form');
		get_template_part('includes/parts/tour', 'confirmation-form');
		?>	
		<script>
			window.formSingleError = <?php echo json_encode(__('You failed to provide 1 field. It has been highlighted below.', 'bookyourtravel')); ?>;
			window.formMultipleError = <?php echo json_encode(__('You failed to provide {0} fields. They have been highlighted below.', 'bookyourtravel'));  ?>;
			window.tourId = <?php echo $tour_obj->get_id(); ?>;
			window.tourIsPricePerGroup = <?php echo $tour_obj->get_is_price_per_group(); ?>;
			window.tourDateFrom = <?php echo json_encode($tour_date_from); ?>;
			window.tourTitle = <?php echo json_encode($tour_obj->get_title()); ?>;
			window.currentMonth = <?php echo date('n'); ?>;
			window.currentYear = <?php echo date('Y'); ?>;
			window.currentDay = <?php echo date('j'); ?>;
		</script>
		<?php $tour_obj->render_image_gallery(); ?>
		<!--inner navigation-->
		<nav class="inner-nav">
			<ul>
				<?php
				do_action( 'byt_show_single_tour_tab_items_before' );
				$first_display_tab = '';			
				$i = 0;
				if (is_array($tab_array) && count($tab_array) > 0) {
					foreach ($tab_array as $tab) {
						if (!isset($tab['hide']) || $tab['hide'] != '1') {
							if($i==0)
								$first_display_tab = $tab['id'];
							if ($tab['id'] == 'reviews' && $enable_reviews) {
								byt_render_tab("tour", $tab['id'], '',  '<a href="#' . $tab['id'] . '" title="' . $tab['label'] . '">' . $tab['label'] . '</a>');
							} elseif ($tab['id'] == 'location' && !empty($tour_map_code)) {
								byt_render_tab("tour", $tab['id'], '',  '<a href="#' . $tab['id'] . '" title="' . $tab['label'] . '">' . $tab['label'] . '</a>');
							} else {
								byt_render_tab("tour", $tab['id'], '',  '<a href="#' . $tab['id'] . '" title="' . $tab['label'] . '">' . $tab['label'] . '</a>');
							}
						}
						$i++;
					}
				} 				
				do_action( 'byt_show_single_tour_tab_items_after' ); 
				?>
			</ul>
		</nav>
		<!--//inner navigation-->
		<?php do_action( 'byt_show_single_tour_tab_content_before' ); ?>
		<!--description-->
		<section id="description" class="tab-content <?php echo $first_display_tab == 'description' ? 'initial' : ''; ?>">
			<article>
				<?php do_action( 'byt_show_single_tour_description_before' ); ?>
				<?php byt_render_field("text-wrap", "", "", $tour_obj->get_description(), __('General', 'bookyourtravel')); ?>
				<?php byt_render_tab_extra_fields($tour_extra_fields, 'description', $tour_obj); ?>
				<?php do_action( 'byt_show_single_tour_description_after' ); ?>
			</article>
		</section>
		<!--//description-->
		<!--availability-->
		<section id="availability" class="tab-content <?php echo $first_display_tab == 'availability' ? 'initial' : ''; ?>">
			<article>
				<?php do_action( 'byt_show_single_tour_availability_before' ); ?>
				<h1><?php _e('Available departures', 'bookyourtravel'); ?></h1>
				<?php byt_render_field("text-wrap", "", "", $tour_obj->get_custom_field('availability_text'), '', false, true); ?>
				<form id="launch-tour-booking" action="#" method="POST">
					<div class="text-wrap">
						<?php 
						
						if ($tour_obj->get_type_is_repeated() == 1) {
							echo __('<p>This is a daily tour.</p>', 'bookyourtravel'); 
						} else if ($tour_obj->get_type_is_repeated() == 2) {
							echo __('<p>This tour is repeated every weekday (working day).</p>', 'bookyourtravel'); 
						} else if ($tour_obj->get_type_is_repeated() == 3) {
							echo sprintf(__('<p>This tour is repeated every week on a %s.</p>', 'bookyourtravel'), $tour_obj->get_type_day_of_week_day()); 
						}
						
						$schedule_entries = list_available_tour_schedule_entries($tour_obj->get_id(), $tour_date_from, $tour_date_from_year, $tour_date_from_month, $tour_obj->get_type_is_repeated(), $tour_obj->get_type_day_of_week_index());
						if (count($schedule_entries) > 0) {
							byt_render_link_button("#", "clearfix gradient-button book_tour", "", __('Book now', 'bookyourtravel'));
						} else { 
							echo __('Unfortunately, no places are available on this tour at the moment', 'bookyourtravel');			
						}
						?>
					</div>
				</form>
				<?php byt_render_tab_extra_fields($tour_extra_fields, 'availability', $tour_obj); ?>
				<?php do_action( 'byt_show_single_tour_availability_after' ); ?>
			</article>
		</section>
		<!--//availability-->
			
		<?php if (!empty($tour_map_code)) { ?>
		<!--location-->
		<section id="location" class="tab-content <?php echo $first_display_tab == 'location' ? 'initial' : ''; ?>">
			<article>
				<?php do_action( 'byt_show_single_tour_map_before' ); ?>
				<!--map-->
				<div class="gmap"><?php echo $tour_map_code; ?></div>
				<!--//map-->
				<?php byt_render_tab_extra_fields($tour_extra_fields, 'location', $tour_obj); ?>
				<?php do_action( 'byt_show_single_tour_map_after' ); ?>
			</article>
		</section>
		<!--//location-->
		<?php } // endif (!empty($tour_map_code)) ?>
		<?php if ($enable_reviews) { ?>
		<!--reviews-->
		<section id="reviews" class="tab-content <?php echo $first_display_tab == 'reviews' ? 'initial' : ''; ?>">
			<?php do_action( 'byt_show_single_tour_reviews_before' ); ?>
			<?php
			$reviews_total = intval( $tour_obj->get_custom_field( 'review_count', false ) );
			if ($reviews_total > 0) {
			?>
			<article>		
				<h1><?php _e('Tour review scores and score breakdown', 'bookyourtravel'); ?></h1>
				<div class="score">
				<?php 
					$reviews_score = $tour_obj->get_custom_field( 'review_score', false );				
					$score_out_of_10 = ceil($reviews_score * 10);
				?>
					<span class="achieved"><?php echo $score_out_of_10; ?></span><span> / 10</span>
					<p class="info"><?php echo sprintf(__('Based on %d reviews', 'bookyourtravel'), $reviews_total); ?></p>
					<p class="disclaimer"><?php sprintf(__('Guest reviews are written by our customers <strong>after their tour</strong> of %s.', 'bookyourtravel'), $tour_obj->get_title()); ?></p>
				</div>
				<dl class="chart">
					<?php 
					$total_possible = $reviews_total * 10;	
					$base_tour_id = $tour_obj->get_base_id();				
					
					$review_fields = list_review_fields('tour', true);
					foreach ($review_fields as $review_field) {
						$field_id = $review_field['id'];
						$field_label = $review_field['label'];
						$field_value = intval($total_possible > 0 ? (sum_review_meta_values($base_tour_id, $field_id) / $total_possible) * 10 : 0);
					?>
					<dt><?php echo $field_label; ?></dt>
					<dd><span style="width:<?php echo $field_value * 10; ?>%;"><?php echo $field_value; ?>&nbsp;&nbsp;&nbsp;</span></dd>
					<?php
					}
					?>
				</dl>
			</article>
			<article>
				<h1><?php _e('Guest reviews', 'bookyourtravel');?></h1>
				<ul class="reviews">
					<!--review-->
					<?php
					$base_tour_id = $tour_obj->get_base_id();
					$reviews_query = list_reviews($base_tour_id);
					while ($reviews_query->have_posts()) : 
						global $post;
						$reviews_query->the_post();
					?>
					<li>
						<figure class="left"><?php echo get_avatar( get_the_author_meta( 'ID' ), 70 ); ?></figure>
						<address><span><?php the_author(); ?></span><br /><?php echo get_the_date('Y-m-d'); ?><br /><br /></address>
						<div class="pro"><p><?php echo get_post_meta($post->ID, 'review_likes', true); ?></p></div>
						<div class="con"><p><?php echo get_post_meta($post->ID, 'review_dislikes', true); ?></p></div>
					</li>
					<!--//review-->
					<?php endwhile; 
						// Reset Second Loop Post Data
						wp_reset_postdata(); 
					?>
				</ul>
			</article>
			<?php } else { ?>
				<article>
				<h3><?php _e('We are sorry, there are no reviews yet for this tour.', 'bookyourtravel'); ?></h3>
				</article>
		<?php } ?>
		<?php byt_render_tab_extra_fields($tour_extra_fields, 'reviews', $tour_obj); ?>
		<?php do_action( 'byt_show_single_tour_reviews_after' ); ?>
		</section>
		<!--//reviews-->
		<?php } // if ($enable_reviews) ?>
		<?php
		foreach ($tab_array as $tab) {
			if (count(byt_array_search($default_tour_tabs, 'id', $tab['id'])) == 0) {
			?>
				<section id="<?php echo $tab['id']; ?>" class="tab-content <?php echo ($first_display_tab == $tab['id'] ? 'initial' : ''); ?>">
					<article>
						<?php do_action( 'byt_show_single_tour_' . $tab['id'] . '_before' ); ?>
						<?php byt_render_tab_extra_fields($tour_extra_fields, $tab['id'], $tour_obj); ?>
						<?php do_action( 'byt_show_single_tour_' . $tab['id'] . '_after' ); ?>
					</article>
				</section>
			<?php
			}
		}	
		do_action( 'byt_show_single_tour_tab_content_after' ); ?>
	</section>
	<!--//tour content-->	
<?php
	get_sidebar('right-tour'); 
} // end if
get_footer(); 