<?php

global $wpdb, $byt_multi_language_count;

function bookyourtravel_register_car_rental_post_type() {
		
	$car_rentals_permalink_slug = of_get_option('car_rentals_permalink_slug', 'car-rentals');
	
	$labels = array(
		'name'                => _x( 'Car rentals', 'Post Type General Name', 'bookyourtravel' ),
		'singular_name'       => _x( 'Car rental', 'Post Type Singular Name', 'bookyourtravel' ),
		'menu_name'           => __( 'Car rentals', 'bookyourtravel' ),
		'all_items'           => __( 'All Car rentals', 'bookyourtravel' ),
		'view_item'           => __( 'View Car rental', 'bookyourtravel' ),
		'add_new_item'        => __( 'Add New Car rental', 'bookyourtravel' ),
		'add_new'             => __( 'New Car rental', 'bookyourtravel' ),
		'edit_item'           => __( 'Edit Car rental', 'bookyourtravel' ),
		'update_item'         => __( 'Update Car rental', 'bookyourtravel' ),
		'search_items'        => __( 'Search Car rentals', 'bookyourtravel' ),
		'not_found'           => __( 'No Car rentals found', 'bookyourtravel' ),
		'not_found_in_trash'  => __( 'No Car rentals found in Trash', 'bookyourtravel' ),
	);
	$args = array(
		'label'               => __( 'car rental', 'bookyourtravel' ),
		'description'         => __( 'Car rental information pages', 'bookyourtravel' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'author' ),
		'taxonomies'          => array( ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
 		'rewrite' =>  array('slug' => $car_rentals_permalink_slug),
	);
	
	register_post_type( 'car_rental', $args );	
}

function bookyourtravel_register_car_type_taxonomy(){

	$labels = array(
			'name'              => _x( 'Car types', 'taxonomy general name', 'bookyourtravel' ),
			'singular_name'     => _x( 'Car type', 'taxonomy singular name', 'bookyourtravel' ),
			'search_items'      => __( 'Search Car types', 'bookyourtravel' ),
			'all_items'         => __( 'All Car types', 'bookyourtravel' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'         => __( 'Edit Car type', 'bookyourtravel' ),
			'update_item'       => __( 'Update Car type', 'bookyourtravel' ),
			'add_new_item'      => __( 'Add New Car type', 'bookyourtravel' ),
			'new_item_name'     => __( 'New Car type Name', 'bookyourtravel' ),
			'separate_items_with_commas' => __( 'Separate car types with commas', 'bookyourtravel' ),
			'add_or_remove_items'        => __( 'Add or remove car types', 'bookyourtravel' ),
			'choose_from_most_used'      => __( 'Choose from the most used car types', 'bookyourtravel' ),
			'not_found'                  => __( 'No car types found.', 'bookyourtravel' ),
			'menu_name'         => __( 'Car types', 'bookyourtravel' ),
		);
		
	$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => false,
			'update_count_callback' => '_update_post_term_count',
			'rewrite'           => false,
		);
	
	$enable_car_rentals = of_get_option('enable_car_rentals', 1);

	if ($enable_car_rentals) {
		register_taxonomy( 'car_type', 'car_rental', $args );
	}
}

function bookyourtravel_create_car_rental_extra_tables($installed_version) {

	global $wpdb;

	if ($installed_version != BOOKYOURTRAVEL_VERSION) {
		
		// we do not execute sql directly
		// we are calling dbDelta which cant migrate database
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');		
		
		$table_name = BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE;
		$sql = "CREATE TABLE " . $table_name . " (
					Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					car_rental_id bigint(20) NOT NULL,
					first_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
					last_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
					email varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
					phone varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					address varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					town varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					zip varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					country varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					special_requirements text CHARACTER SET utf8 COLLATE utf8_bin NULL,
					drop_off bigint(10) NOT NULL DEFAULT 0,
					total_price decimal(16, 2) NOT NULL,
					user_id bigint(10) NOT NULL DEFAULT 0,
					created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					woo_order_id bigint(20) NULL,
					cart_key VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' NOT NULL,
					currency_code VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
					PRIMARY KEY  (Id)
				);";

		dbDelta($sql);
		
		$table_name = BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE;
		$sql = "CREATE TABLE " . $table_name . " (
					Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					car_rental_booking_id bigint(20) NOT NULL,
					booking_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY  (Id)
				);";
		
		dbDelta($sql);
		global $EZSQL_ERROR;
		$EZSQL_ERROR = array();
				
		$wpdb->query("DROP TRIGGER IF EXISTS byt_car_rental_bookings_delete_trigger;");
		$sql = "				
			CREATE TRIGGER byt_car_rental_bookings_delete_trigger AFTER DELETE ON `" . BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE . "` 
			FOR EACH ROW BEGIN
				DELETE FROM `" . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . "` 
				WHERE car_rental_booking_id = OLD.Id;
			END;
		";		
		$wpdb->query($sql);	
		
	}
}
	
function list_car_rentals( $paged = 0, $per_page = 0, $orderby = '', $order = '', $location_id = 0, $car_types_array = array(), $featured_only = false, $search_args = array() ) {

	global $wpdb, $byt_multi_language_count;

	$select_sql = " SELECT 	car_rentals.*, 
							car_rentals_meta_number_of_cars.meta_value+0 number_of_cars, 
							car_rentals_meta_price.meta_value+0 price ";
	$join_sql = " LEFT JOIN $wpdb->postmeta car_rentals_meta_price ON car_rentals.ID=car_rentals_meta_price.post_id AND car_rentals_meta_price.meta_key='car_rental_price_per_day' ";
	$where_sql = " WHERE 1=1 ";
	$having_sql = " HAVING 1=1 ";
	
	$location_id = get_default_language_post_id($location_id, 'location');
	
	$location_args =  array( 'post_type' => 'location', 'posts_per_page' => -1, 'post_status' => 'publish' );
	$location_ids = array();
	$car_rental_ids = array();
	
	$location_ids_string = '';
	
	if ($location_id > 0) {
	
		$location_children = get_posts_children($location_id, $location_args);
		if ($location_id) {
			$location_ids[] = $location_id;
		}
		foreach ($location_children as $location) {
			$location_ids[] = $location->ID;
		}
		
	}
	
	if ( count($search_args) > 0) {
	
		if ( isset($search_args['keyword']) && strlen($search_args['keyword']) > 0 ) {
			$search_string = "%" . $search_args['keyword'] . "%";
			$search_sql = $wpdb->prepare("SELECT ID from $wpdb->posts where LOWER(post_title) LIKE '%s' AND post_type='location' AND post_status='publish'", strtolower($search_string));
			$temp_location_ids = $wpdb->get_col($search_sql);
			foreach ($temp_location_ids as $temp_location_id)  {
				$location_ids[] = $temp_location_id;
			}
			
			$search_sql = $wpdb->prepare("SELECT ID from $wpdb->posts WHERE (LOWER(post_title) LIKE '%s' OR LOWER(post_content) LIKE '%s') AND post_type='car_rental' AND post_status='publish'", strtolower($search_string), strtolower($search_string));
			$temp_car_rental_ids = $wpdb->get_col($search_sql);
			foreach ($temp_car_rental_ids as $temp_car_rental_id)  {
				$car_rental_ids[] = $temp_car_rental_id;
			}	
		}
		
		if ( isset($search_args['prices']) ) {
		
			$prices = (array)$search_args['prices'];
			
			if (count($prices) > 0) {
			
				$price_range_bottom = ( isset($search_args['price_range_bottom']) ) ? intval($search_args['price_range_bottom']) : 0;
				$price_range_increment = ( isset($search_args['price_range_increment']) ) ? intval($search_args['price_range_increment']) : 50;
				$price_range_count = ( isset($search_args['price_range_count']) ) ? intval($search_args['price_range_count']) : 5;

				$having_sql .= " AND ( 1!=1 ";
				
				$bottom = 0;
				$top = 0;
				
				for ( $i = 0; $i < $price_range_count; $i++ ) { 
					$bottom = ($i * $price_range_increment) + $price_range_bottom;
					$top = ( ( $i+1 ) * $price_range_increment ) + $price_range_bottom - 1;	

					if ( $i < ( $price_range_count ) ) {
						if ( in_array( $i + 1, $prices ) )
							$having_sql .= " OR (price >= $bottom AND price <= $top ) ";
					} else {
						$having_sql .= " OR (price >= $bottom ) ";
					}
				}
				
				$having_sql .= ")";
			}
			
		}	
		
		if ( isset($search_args['age']) ) {
		
			$age = intval($search_args['age']); 
			if ($age > 0) {
				$select_sql .= ", car_rentals_meta_min_age.meta_value+0 min_age ";
				$join_sql .= " LEFT JOIN $wpdb->postmeta car_rentals_meta_min_age ON car_rentals.ID=car_rentals_meta_min_age.post_id AND car_rentals_meta_min_age.meta_key='car_rental_min_age' ";
				$having_sql .= " AND min_age >= $age ";
			}

		}
		
		if ( isset($search_args['date_from']) && isset($search_args['date_to']) ) {
		
			$date_from = date('Y-m-d', strtotime($search_args['date_from']));
			$date_to = date('Y-m-d', strtotime($search_args['date_to'].' -1 day'));

			$select_sql .= ",
				(
					SELECT COUNT(DISTINCT car_rental_booking_id) cnt
					FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . " booking_days_table 
					INNER JOIN " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE . " booking_table ON booking_days_table.car_rental_booking_id = booking_table.Id 
					WHERE ";
			if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
				$select_sql .= " booking_table.car_rental_id = translations_default.element_id ";
			} else {
				$select_sql .= " booking_table.car_rental_id = car_rentals.ID ";
			}

			$select_sql .= $wpdb->prepare(" AND booking_days_table.booking_date BETWEEN %s AND %s ", $date_from, $date_to);
			$select_sql .= " ) bookings ";
				
			if ( isset($search_args['search_only_available_properties'])) {				
				$search_only_available_properties = $search_args['search_only_available_properties'];
				
				if ($search_only_available_properties)
					$having_sql .= ' AND bookings < number_of_cars AND price IS NOT NULL ';
			}
		
		}
	}
	
	$location_ids_string = implode(', ', $location_ids);
	$car_rental_ids_string = implode(', ', $car_rental_ids);

	$select_sql .= " FROM $wpdb->posts car_rentals ";
	
	$join_sql .= " INNER JOIN $wpdb->postmeta car_rentals_meta_number_of_cars ON car_rentals.ID=car_rentals_meta_number_of_cars.post_id AND car_rentals_meta_number_of_cars.meta_key='car_rental_number_of_cars' ";
			
	if (!empty($location_ids_string)) {		
		$join_sql .= " INNER JOIN $wpdb->postmeta location_meta ON (car_rentals.ID = location_meta.post_id) ";
	}
			
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$join_sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_car_rental' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = car_rentals.ID ";
		$join_sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_car_rental' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
	}
	
	if ($featured_only) {
		$join_sql .= " LEFT JOIN $wpdb->postmeta car_rental_featured_meta ON (car_rentals.ID = car_rental_featured_meta.post_id) ";
	}

	if (!empty($car_types_array)) {	
		$car_types_string = implode(",",$car_types_array);		
		$join_sql .= " LEFT JOIN $wpdb->term_relationships ON (car_rentals.ID = $wpdb->term_relationships.object_id) ";
		$join_sql .= " LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id) ";		
		$where_sql .= " AND $wpdb->term_taxonomy.taxonomy = 'car_type' AND $wpdb->term_taxonomy.term_id IN ($car_types_string) ";
	}
	
	$where_sql .= " AND car_rentals.post_type = 'car_rental' AND car_rentals.post_status = 'publish' ";
	
	if (!empty($location_ids_string)) {
		$where_sql .= " AND (location_meta.meta_key = 'car_rental_location_post_id' AND CAST(location_meta.meta_value AS UNSIGNED) IN ($location_ids_string)) ";
	}
	
	if (!empty($car_rental_ids_string)) {
		$where_sql .= " AND (car_rentals.ID IN ($car_rental_ids_string)) ";
	}
	
	if ( count($search_args) > 0) {
		if ( isset($search_args['keyword'])&& strlen($search_args['keyword']) > 0  ) {
			$search_string = "%" . $search_args['keyword'] . "%";
			$where_sql .= sprintf(" AND (LOWER(car_rentals.post_title) LIKE '%s' OR LOWER(car_rentals.post_content) LIKE '%s') ", strtolower($search_string), strtolower($search_string));
		}
	}

	if ($featured_only) {
		$where_sql .= " AND car_rental_featured_meta.meta_key = 'car_rental_is_featured' AND CAST(car_rental_featured_meta.meta_value AS CHAR) = '1' ";
	}
	
	$group_by_sql = " GROUP BY car_rentals.ID ";
	
	$order_by_sql = '';
	if ( !empty( $orderby ) && !empty( $order ) ){ 
		$order_by_sql .= ' ORDER BY ' . $orderby . ' ' . $order; 
	} else {
		$order_by_sql .= ' ORDER BY car_rentals.post_date DESC ';
	}
	
	$sql = $select_sql . $join_sql . $where_sql . $group_by_sql . $having_sql . $order_by_sql;
	
	$count_sql = $sql;
	
	if ( !empty( $paged ) && !empty( $per_page ) ) {
		$offset = ( $paged - 1 ) * $per_page;
		$sql .= ' LIMIT ' . (int) $offset . ',' . (int) $per_page;
	}
	
	$results = array(
		'total' => $wpdb->query($count_sql),
		'results' => $wpdb->get_results($sql)
	);
	
	return $results;
}

function create_car_rental_booking ( $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $date_from, $date_to, $car_rental_id,  $user_id, $total_price, $drop_off_location_id ) {
	
	global $wpdb;
	
	$car_rental_id = get_default_language_post_id($car_rental_id, 'car_rental');
	
	// We are actually (in terms of db data) looking for date 1 day before the to date.
	// E.g. when you look to book a room from 19.12. to 20.12 you will be staying 1 night, not 2. 
	// The same goes for cars.
	$date_to = date('Y-m-d', strtotime($date_to.' -1 day'));
	
	$sql = "INSERT INTO " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE . "
			(first_name, last_name, email, phone, address, town, zip, country, special_requirements, car_rental_id, user_id, total_price, drop_off)
			VALUES 
			(%s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d);";
			
	$wpdb->query($wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $car_rental_id, $user_id, $total_price, $drop_off_location_id));

	$booking_id = $wpdb->insert_id;

	$dates = get_dates_from_range($date_from, $date_to);	
	foreach ($dates as $date) {
		$sql = "INSERT INTO " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . "
				(car_rental_booking_id, booking_date)
				VALUES
				(%d, %s);";
		$wpdb->query($wpdb->prepare($sql, $booking_id, $date));
	}
	
	return $booking_id;	
}

function update_car_rental_booking ( $booking_id, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $date_from, $date_to, $car_rental_id,  $user_id, $total_price, $drop_off_location_id ) {

	global $wpdb;
	
	// delete previous days from table
	$sql = "DELETE FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . "
			WHERE car_rental_booking_id = %d ";
	
	$wpdb->query($wpdb->prepare($sql, $booking_id));		

	$sql = "UPDATE " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE . "
			SET first_name = %s,
			last_name = %s,
			email = %s, 
			phone = %s, 
			address = %s, 
			town = %s, 
			zip = %s, 
			country = %s, 
			special_requirements = %s,
			car_rental_id = %d, 
			user_id = %d, 
			total_price = %f, 
			drop_off = %d
			WHERE Id=%d";
			
	$wpdb->query($wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $car_rental_id, $user_id, $total_price, $drop_off_location_id, $booking_id));
	
	$dates = get_dates_from_range($date_from, $date_to);	
	foreach ($dates as $date) {
		$sql = "INSERT INTO " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . "
				(car_rental_booking_id, booking_date)
				VALUES
				(%d, %s);";
		$wpdb->query($wpdb->prepare($sql, $booking_id, $date));
	}
	
	return $booking_id;
}

function car_rental_get_booked_days($car_rental_id, $month, $year) {

	global $wpdb;

	$car_rental_id = get_default_language_post_id($car_rental_id, 'car_rental');
	
	$sql = "	SELECT DISTINCT booking_date, (car_rentals_meta_number_of_cars.meta_value+0) number_of_cars
				FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . " days
				INNER JOIN " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE . " bookings ON bookings.Id = days.car_rental_booking_id 
				INNER JOIN $wpdb->postmeta car_rentals_meta_number_of_cars ON bookings.car_rental_id=car_rentals_meta_number_of_cars.post_id AND car_rentals_meta_number_of_cars.meta_key='car_rental_number_of_cars' 
				WHERE bookings.car_rental_id=%d AND booking_date >= %s AND MONTH(booking_date) = %d AND YEAR(booking_date) = %d
				GROUP BY booking_date
				HAVING COUNT(DISTINCT car_rental_booking_id) >= number_of_cars";

	$today = date('Y-m-d H:i:s');
	
	$sql = $wpdb->prepare($sql, $car_rental_id, $today, $month, $year);
	
	return $wpdb->get_results($sql);
}

function list_car_rental_bookings($search_term = null, $orderby = 'Id', $order = 'ASC', $paged = null, $per_page = 0 ) {

	global $wpdb, $byt_multi_language_count;

	$table_name = BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE;
	$sql = "SELECT DISTINCT bookings.*, car_rentals.post_title car_rental_name,
			(
				SELECT MIN(booking_date) FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . " v2 
				WHERE v2.car_rental_booking_id = bookings.Id 
			) from_day,
			(
				SELECT MAX(booking_date) FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . " v3 
				WHERE v3.car_rental_booking_id = bookings.Id 
			) to_day, locations.post_title pick_up, locations_2.post_title drop_off
			FROM " . $table_name . " bookings 
			INNER JOIN $wpdb->posts car_rentals ON car_rentals.ID = bookings.car_rental_id ";
			
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_car_rental' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = car_rentals.ID ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_car_rental' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
	}
			
	$sql .= "LEFT JOIN $wpdb->postmeta car_rental_meta_location ON car_rentals.ID=car_rental_meta_location.post_id AND car_rental_meta_location.meta_key='car_rental_location_post_id'
			LEFT JOIN $wpdb->posts locations ON locations.ID = car_rental_meta_location.meta_value+0
			LEFT JOIN $wpdb->posts locations_2 ON locations_2.ID = bookings.drop_off
			WHERE car_rentals.post_status = 'publish' AND locations.post_status = 'publish' AND locations_2.post_status = 'publish' ";
	
	if ($search_term != null && !empty($search_term)) {
		$search_term = "%" . $search_term . "%";
		$sql .= $wpdb->prepare(" WHERE 1=1 AND (bookings.first_name LIKE '%s' OR bookings.last_name LIKE '%s') ", $search_term, $search_term);
	}
	
	if(!empty($orderby) & !empty($order)){ 
		$sql.=' ORDER BY '.$orderby.' '.$order; 
	}
	
	$sql_count = $sql;
	
	if(!empty($paged) && !empty($per_page)){
		$offset=($paged-1)*$per_page;
		$sql .= $wpdb->prepare(" LIMIT %d, %d ", $offset, $per_page); 
	}

	$results = array(
		'total' => $wpdb->query($sql_count),
		'results' => $wpdb->get_results($sql)
	);
	
	return $results;
}

function get_car_rental_booking($booking_id) {

	global $wpdb, $byt_multi_language_count;
	
	$table_name = BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE;
	$sql = "SELECT 	DISTINCT bookings.*, 
					car_rentals.post_title car_rental_name,
					(
						SELECT MIN(booking_date) FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . " v2 
						WHERE v2.car_rental_booking_id = bookings.Id 
					) from_day,
					(
						SELECT MAX(booking_date) FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . " v3 
						WHERE v3.car_rental_booking_id = bookings.Id 
					) to_day, 
					locations.ID pick_up_location_id, 
					locations_2.ID drop_off_location_id,					
					locations.post_title pick_up, 
					locations_2.post_title drop_off
			FROM " . $table_name . " bookings 
			INNER JOIN $wpdb->posts car_rentals ON car_rentals.ID = bookings.car_rental_id ";
			
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_car_rental' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = car_rentals.ID ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_car_rental' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
	}
			
	$sql .= "LEFT JOIN $wpdb->postmeta car_rental_meta_location ON car_rentals.ID=car_rental_meta_location.post_id AND car_rental_meta_location.meta_key='car_rental_location_post_id'
			LEFT JOIN $wpdb->posts locations ON locations.ID = car_rental_meta_location.meta_value+0
			LEFT JOIN $wpdb->posts locations_2 ON locations_2.ID = bookings.drop_off
			WHERE car_rentals.post_status = 'publish' AND locations.post_status = 'publish' AND locations_2.post_status = 'publish' AND bookings.Id = $booking_id ";
			
	return $wpdb->get_row($sql);
}

function delete_car_rental_booking($booking_id) {

	global $wpdb;
	
	$sql = "DELETE FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE . "
			WHERE Id = %d";
	
	$wpdb->query($wpdb->prepare($sql, $booking_id));	
}