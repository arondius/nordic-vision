<?php 	
	global $site_url, $current_user, $currency_symbol, $current_currency, $default_currency, $enabled_currencies, $use_woocommerce_for_checkout, $woo_cart_page_uri;
	global $logo_src, $my_account_page, $cart_page, $custom_search_results_page, $enable_hotel_search, $enable_tour_search, $enable_self_catered_search, $enable_car_rental_search;
	global $price_decimal_places, $add_captcha_to_forms, $enable_reviews, $frontpage_show_slider;
	
	if (is_page_template('byt_home.php')) {
		get_sidebar('home-above-slider');
		$homepage_slider = of_get_option('homepage_slider', '-1');
		$homepage_slider_alias = '';
		if ($homepage_slider >= 0) {
			$sliders_array = array();
			if (class_exists ('RevSlider')) {
				try {
					$slider = new RevSlider();
					$sliders_array = $slider->getAllSliderAliases();
					$homepage_slider_alias = $sliders_array[$homepage_slider];
				}catch(Exception $e){}
			}
		}
		if (!empty($homepage_slider_alias) && $frontpage_show_slider && function_exists('putRevSlider')) {
			putRevSlider($homepage_slider_alias);
		}
	}

	if (is_front_page() && !is_home() && !empty($custom_search_results_page)) { 

		$whats_count = 0;
		$form_box_counter = 2;
			
		if ($enable_hotel_search)
			$whats_count++;
		if ($enable_self_catered_search)
			$whats_count++;
		if ($enable_car_rental_search)
			$whats_count++;	
		if ($enable_tour_search)
			$whats_count++;	

		if ($whats_count <= 1)
			$form_box_counter = 1;
	?>
	<?php if ($whats_count > 0) { ?>
	<!--search-->
	<div class="main-search">
		<form id="main-search" method="get" action="<?php echo $custom_search_results_page; ?>">
			<?php if ($whats_count > 1) { ?>
			<!--column-->
			<div class="column radios">
				<h4><span>01</span> <?php _e('What?', 'bookyourtravel'); ?></h4>
				<?php if ($enable_hotel_search) {?>
				<script>window.visibleSearchFormNumber = 1;</script>
				<div class="f-item checked" >
					<input type="radio" name="what" id="hotel" value="1" checked="checked" />
					<label for="hotel"> <?php _e('Hotel', 'bookyourtravel'); ?></label>
				</div>
				<?php } ?>
				<?php if ($enable_self_catered_search) { 
				if (!$enable_hotel_search) {
				?>
				<script>window.visibleSearchFormNumber = 2;</script>
				<?php } ?>
				<div class="f-item <?php echo $enable_hotel_search ? '' : 'active'?>" >
					<input type="radio" name="what" id="self_catered" value="2" <?php echo $enable_hotel_search ? '' : ' checked="checked"' ?> />
					<label for="self_catered"> <?php _e('Self Catering', 'bookyourtravel'); ?></label>
				</div>
				<?php } ?>
				<?php if ($enable_car_rental_search) {
				if (!$enable_hotel_search && !$enable_self_catered_search) {
				?>
				<script>window.visibleSearchFormNumber = 3;</script>
				<?php } ?>
				<div class="f-item <?php echo ($enable_hotel_search || $enable_self_catered_search) ? '' : 'active'?>">
					<input type="radio" name="what" id="car_rental" value="3" <?php echo ($enable_hotel_search || $enable_self_catered_search) ? '' : ' checked="checked"' ?> />
					<label for="car_rental"> <?php _e('Rent a Car', 'bookyourtravel'); ?></label>
				</div>
				<?php } ?>
				<?php if ($enable_tour_search) {
				if (!$enable_hotel_search && !$enable_self_catered_search && !$enable_car_rental_search) {
				?>
				<script>window.visibleSearchFormNumber = 4;</script>
				<?php } ?>
				<div class="f-item <?php echo ($enable_hotel_search || $enable_self_catered_search || $enable_car_rental_search) ? '' : 'active'?>" >
					<input type="radio" name="what" id="tour" value="4" <?php echo ($enable_hotel_search || $enable_self_catered_search || $enable_car_rental_search) ? '' : ' checked="checked"' ?> />
					<label for="tour"> <?php _e('Tour', 'bookyourtravel'); ?></label>
				</div>
				<?php } ?>
			</div>
			<!--//column-->
			<?php } else {
				if ($enable_hotel_search) {
					echo '<input type="hidden" id="what" name="what" value="1" />';
					echo '<script>window.visibleSearchFormNumber = 1;</script>';
				} elseif ($enable_self_catered_search) {
					echo '<input type="hidden" id="what" name="what" value="2" />';
					echo '<script>window.visibleSearchFormNumber = 2;</script>';
				} elseif ($enable_car_rental_search) {
					echo '<input type="hidden" id="what" name="what" value="3" />';
					echo '<script>window.visibleSearchFormNumber = 3;</script>';
				} elseif ($enable_tour_search) {
					echo '<input type="hidden" id="what" name="what" value="4" />';
					echo '<script>window.visibleSearchFormNumber = 4;</script>';
				}
			} ?>			
			<div class="forms <?php echo ($whats_count <= 1) ? 'first' : ''?>" >
				<!--form accommodation-->
				<div class="form" id="form1">
					<!--column-->
					<div class="column">
						<h4><span>0<?php echo $form_box_counter; ?></span>  <?php _e('Where?', 'bookyourtravel'); ?></h4>
						<div class="f-item">
							<label for="term"><?php _e('Your destination', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="<?php _e('City, region, district or specific accommodation', 'bookyourtravel'); ?>" id="term" name="term" />
						</div>
					</div>
					<!--//column-->
					
					<!--column-->
					<div class="column twins">
						<h4><span>0<?php echo $form_box_counter + 1; ?></span> <?php _e('When?', 'bookyourtravel'); ?></h4>
						<div class="f-item datepicker">
							<label for="from"><?php _e('Check-in date', 'bookyourtravel'); ?></label>
							<div class="datepicker-wrap"><input type="text" placeholder="" id="from" name="from" /></div>
						</div>
						<div class="f-item datepicker">
							<label for="to"><?php _e('Check-out date', 'bookyourtravel'); ?></label>
							<div class="datepicker-wrap"><input type="text" placeholder="" id="to" name="to" /></div>
						</div>
					</div>
					<!--//column-->
				
					<!--column-->
					<div class="column twins last">
						<h4><span>0<?php echo $form_box_counter + 2; ?></span> <?php _e('Who?', 'bookyourtravel'); ?></h4>
						<div class="f-item spinner">
							<label for="rooms"><?php _e('Rooms', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="" id="rooms" name="rooms" />
						</div>
					</div>
					<!--//column-->
				</div>	
				<!--//form accommodation-->
				
				<!--form self-catered-->
				<div class="form" id="form2">
					<!--column-->
					<div class="column">
						<h4><span>0<?php echo $form_box_counter; ?></span>  <?php _e('Where?', 'bookyourtravel'); ?></h4>
						<div class="f-item">
							<label for="term2"><?php _e('Your destination', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="<?php _e('City, region, district or specific accommodation', 'bookyourtravel'); ?>" id="term2" name="term" />
						</div>
					</div>
					<!--//column-->
					
					<!--column-->
					<div class="column twins">
						<h4><span>0<?php echo $form_box_counter + 1; ?></span> <?php _e('When?', 'bookyourtravel'); ?></h4>
						<div class="f-item datepicker">
							<label for="from2"><?php _e('Check-in date', 'bookyourtravel'); ?></label>
							<div class="datepicker-wrap"><input type="text" placeholder="" id="from2" name="from" /></div>
						</div>
						<div class="f-item datepicker">
							<label for="to2"><?php _e('Check-out date', 'bookyourtravel'); ?></label>
							<div class="datepicker-wrap"><input type="text" placeholder="" id="to2" name="to" /></div>
						</div>
					</div>
					<!--//column-->
					
					<div class="column twins last">
						<h4><span>0<?php echo $form_box_counter + 2; ?></span> <?php _e('Who?', 'bookyourtravel'); ?></h4>
						<div class="f-item spinner">
							<label for="guests"><?php _e('Guests', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="" id="guests" name="guests" />
						</div>
					</div>

				</div>	
				
				<!--form car rental-->
				<div class="form" id="form3">
					<!--column-->
					<div class="column">
						<h4><span>0<?php echo $form_box_counter; ?></span>  <?php _e('Where?', 'bookyourtravel'); ?></h4>
						<div class="f-item">
							<label for="term3"><?php _e('Pick Up', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="<?php _e('I want to pick up car in', 'bookyourtravel'); ?>" id="term3" name="term" />
						</div>
						<div class="f-item">
							<label for="term4"><?php _e('Drop Off', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="<?php _e('I want to drop off car at', 'bookyourtravel'); ?>" id="term4" name="term_to" />
						</div>
					</div>
					<!--//column-->
					
					<!--column-->
					<div class="column two-childs">
						<h4><span>0<?php echo $form_box_counter + 1; ?></span> <?php _e('When?', 'bookyourtravel'); ?></h4>
						<div class="f-item datepicker">
							<label for="from3"><?php _e('Pick-up date', 'bookyourtravel'); ?></label>
							<div class="datepicker-wrap"><input type="text" placeholder="" id="from3" name="from" /></div>
							<select id="time_from" name="time_from">
								<option>00:00</option>
								<option>01:00</option>
								<option>02:00</option>
								<option>03:00</option>
								<option>04:00</option>
								<option>05:00</option>
								<option>06:00</option>
								<option>07:00</option>
								<option>08:00</option>
								<option>09:00</option>
								<option selected="selected">10:00</option>
								<option>11:00</option>
								<option>12:00</option>
								<option>13:00</option>
								<option>14:00</option>
								<option>15:00</option>
								<option>16:00</option>
								<option>17:00</option>
								<option>18:00</option>
								<option>19:00</option>
								<option>20:00</option>
								<option>21:00</option>
								<option>22:00</option>
								<option>23:00</option>
							</select>
						</div>
						<div class="f-item datepicker">
							<label for="to3"><?php _e('Drop-off date', 'bookyourtravel'); ?></label>
							<div class="datepicker-wrap"><input type="text" placeholder="" id="to3" name="to" /></div>
							<select id="time_to" name="time_to">
								<option>00:00</option>
								<option>01:00</option>
								<option>02:00</option>
								<option>03:00</option>
								<option>04:00</option>
								<option>05:00</option>
								<option>06:00</option>
								<option>07:00</option>
								<option>08:00</option>
								<option>09:00</option>
								<option selected="selected">10:00</option>
								<option>11:00</option>
								<option>12:00</option>
								<option>13:00</option>
								<option>14:00</option>
								<option>15:00</option>
								<option>16:00</option>
								<option>17:00</option>
								<option>18:00</option>
								<option>19:00</option>
								<option>20:00</option>
								<option>21:00</option>
								<option>22:00</option>
								<option>23:00</option>
							</select>

						</div>
					</div>
					<!--//column-->
					<?php
						$car_types_args = array(
							'orderby'       => 'name', 
							'order'         => 'ASC',
							'hide_empty'    => true, 
							'fields'        => 'all', 
						); 
						$car_types = get_terms(array('car_type'), $car_types_args);
					?>
					<!--column-->
					<div class="column twins last">
						<h4><span>0<?php echo $form_box_counter + 2; ?></span> <?php _e('Who?', 'bookyourtravel'); ?></h4>
						<div class="f-item spinner small">
							<label for="age"><?php _e('Driver\'s age?', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="" id="age" name="age" />
						</div>
						<?php if ($car_types && count($car_types) > 0) { ?>
						<div class="f-item">
							<label for="car_types"><?php _e('Car type?', 'bookyourtravel'); ?></label>
							<select name="car_types" id="car_types">
								<option selected="selected" value=""><?php _e('No Preference', 'bookyourtravel'); ?></option>
								<?php foreach ($car_types as $car_type) {
									echo "<option value='{$car_type->term_id}'>{$car_type->name}</option>";
								}?>
							</select>
						</div>
						<?php } ?>
					</div>
					<!--//column-->
				</div>	
				<!--//form car rental-->				
				
				<!--form tour-->
				<div class="form" id="form4">
					<!--column-->
					<div class="column">
						<h4><span>0<?php echo $form_box_counter; ?></span>  <?php _e('Where?', 'bookyourtravel'); ?></h4>
						<div class="f-item">
							<label for="term5"><?php _e('Tour location', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="<?php _e('City, region, district or specific tour', 'bookyourtravel'); ?>" id="term5" name="term" />
						</div>
					</div>
					<!--//column-->
					
					<!--column-->
					<div class="column twins">
						<h4><span>0<?php echo $form_box_counter + 1; ?></span> <?php _e('When?', 'bookyourtravel'); ?></h4>
						<div class="f-item datepicker">
							<label for="from4"><?php _e('Start date', 'bookyourtravel'); ?></label>
							<div class="datepicker-wrap"><input type="text" placeholder="" id="from4" name="from" /></div>
						</div>
					</div>
					<!--//column-->
					
					<div class="column twins last">
						<h4><span>0<?php echo $form_box_counter + 2; ?></span> <?php _e('Who?', 'bookyourtravel'); ?></h4>
						<div class="f-item spinner">
							<label for="guests2"><?php _e('Guests', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="" id="guests2" name="guests" />
						</div>
					</div>

				</div>	
				
				<!--//form tour-->

			</div>
			
			
			<input type="submit" value="<?php _e('Proceed to results', 'bookyourtravel'); ?>" class="search-submit" id="search-submit" />
		</form>
	</div>
	<!--//search-->
	<?php } ?>
	<?php } ?>
	
	<?php if (is_front_page() && !is_home()) {
		get_sidebar('home-below-slider');
	} ?>