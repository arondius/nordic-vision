<?php

global $wpdb, $byt_multi_language_count;

function bookyourtravel_register_tour_post_type() {
	
	$tours_permalink_slug = of_get_option('tours_permalink_slug', 'tours');
	
	$labels = array(
		'name'                => _x( 'Tours', 'Post Type General Name', 'bookyourtravel' ),
		'singular_name'       => _x( 'Tour', 'Post Type Singular Name', 'bookyourtravel' ),
		'menu_name'           => __( 'Tours', 'bookyourtravel' ),
		'all_items'           => __( 'All Tours', 'bookyourtravel' ),
		'view_item'           => __( 'View Tour', 'bookyourtravel' ),
		'add_new_item'        => __( 'Add New Tour', 'bookyourtravel' ),
		'add_new'             => __( 'New Tour', 'bookyourtravel' ),
		'edit_item'           => __( 'Edit Tour', 'bookyourtravel' ),
		'update_item'         => __( 'Update Tour', 'bookyourtravel' ),
		'search_items'        => __( 'Search Tours', 'bookyourtravel' ),
		'not_found'           => __( 'No Tours found', 'bookyourtravel' ),
		'not_found_in_trash'  => __( 'No Tours found in Trash', 'bookyourtravel' ),
	);
	$args = array(
		'label'               => __( 'tour', 'bookyourtravel' ),
		'description'         => __( 'Tour information pages', 'bookyourtravel' ),
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
 		'rewrite' => array('slug' => $tours_permalink_slug),
	);
	register_post_type( 'tour', $args );	
}

function bookyourtravel_register_tour_type_taxonomy(){
	$labels = array(
			'name'              => _x( 'Tour types', 'taxonomy general name', 'bookyourtravel' ),
			'singular_name'     => _x( 'Tour type', 'taxonomy singular name', 'bookyourtravel' ),
			'search_items'      => __( 'Search Tour types', 'bookyourtravel' ),
			'all_items'         => __( 'All Tour types', 'bookyourtravel' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'         => __( 'Edit Tour type', 'bookyourtravel' ),
			'update_item'       => __( 'Update Tour type', 'bookyourtravel' ),
			'add_new_item'      => __( 'Add New Tour type', 'bookyourtravel' ),
			'new_item_name'     => __( 'New Tour Type Name', 'bookyourtravel' ),
			'separate_items_with_commas' => __( 'Separate Tour types with commas', 'bookyourtravel' ),
			'add_or_remove_items'        => __( 'Add or remove Tour types', 'bookyourtravel' ),
			'choose_from_most_used'      => __( 'Choose from the most used Tour types', 'bookyourtravel' ),
			'not_found'                  => __( 'No Tour types found.', 'bookyourtravel' ),
			'menu_name'         => __( 'Tour types', 'bookyourtravel' ),
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
	
	$enable_tours = of_get_option('enable_tours', 1);

	if ($enable_tours) {
		register_taxonomy( 'tour_type', 'tour', $args );
	}
}

function bookyourtravel_create_tour_extra_tables($installed_version) {

	global $wpdb;

	if ($installed_version != BOOKYOURTRAVEL_VERSION) {
	
		// we do not execute sql directly
		// we are calling dbDelta which cant migrate database
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');		
		
		$table_name = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
		$sql = "CREATE TABLE " . $table_name . " (
					Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					tour_id bigint(20) NOT NULL,
					start_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					duration_days int NOT NULL DEFAULT 0,
					price decimal(16, 2) NOT NULL DEFAULT 0,
					price_child decimal(16, 2) NOT NULL DEFAULT 0, 
					max_people int(11) NOT NULL DEFAULT 0,
					created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					end_date datetime NULL,
					PRIMARY KEY  (Id)
				);";

		dbDelta($sql);
		
		$table_name = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
		$sql = "CREATE TABLE " . $table_name . " (
					Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					tour_schedule_id bigint(20) NOT NULL,
					tour_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, 
					first_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
					last_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
					email varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
					phone varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					address varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					town varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					zip varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					country varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					special_requirements text CHARACTER SET utf8 COLLATE utf8_bin NULL,
					adults bigint(20) NOT NULL,
					children bigint(20) NOT NULL,
					user_id bigint(20) NOT NULL DEFAULT 0,
					total_price_adults decimal(16, 2) NOT NULL,
					total_price_children decimal(16, 2) NOT NULL,
					total_price decimal(16, 2) NOT NULL,
					created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					woo_order_id bigint(20) NULL,
					cart_key VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' NOT NULL,
					currency_code VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
					PRIMARY KEY  (Id)
				);";

		dbDelta($sql);
		
		global $EZSQL_ERROR;
		$EZSQL_ERROR = array();
		
	}
}

function list_tours($paged = 0, $per_page = 0, $orderby = '', $order = '', $location_id = 0, $tour_types_array = array(), $featured_only = false, $search_args = array()) {

	global $wpdb, $byt_multi_language_count;
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
	
	$select_sql = " SELECT DISTINCT	tours.* ";
	$join_sql = " ";
	$where_sql = " WHERE 1=1 ";
	$having_sql = " HAVING 1=1 ";
	
	$location_id = get_default_language_post_id($location_id, 'location');
	
	$location_args =  array( 'post_type' => 'location', 'posts_per_page' => -1, 'post_status' => 'publish' );
	$location_ids = array();
	$tour_ids = array();
	
	$location_ids_string = '';
	if ($location_id > 0) {
		$location_children = get_posts_children($location_id, $location_args);
		if ($location_id)
			$location_ids[] = $location_id;
		foreach ($location_children as $location) {
			$location_ids[] = $location->ID;
		}
	}
	
	if ( count($search_args) > 0) {

		if ( isset($search_args['keyword'])&& strlen($search_args['keyword']) > 0  ) {
			$search_string = "%" . $search_args['keyword'] . "%";
			$search_sql = $wpdb->prepare("SELECT ID from $wpdb->posts where LOWER(post_title) LIKE '%s' AND post_type='location' AND post_status='publish'", strtolower($search_string));
			$temp_location_ids = $wpdb->get_col($search_sql);
			foreach ($temp_location_ids as $temp_location_id)  {
				$location_ids[] = $temp_location_id;
			}
			
			$search_sql = $wpdb->prepare("SELECT ID from $wpdb->posts WHERE (LOWER(post_title) LIKE '%s' OR LOWER(post_content) LIKE '%s') AND post_type='tour' AND post_status='publish'", strtolower($search_string), strtolower($search_string));
			$temp_tour_ids = $wpdb->get_col($search_sql);
			foreach ($temp_tour_ids as $temp_tour_id)  {
				$tour_ids[] = $temp_tour_id;
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
		
		if ( isset($search_args['date_from']) ) {
		
			$date_from = date('Y-m-d', strtotime($search_args['date_from']));

			$select_sql .= ",
			(
				SELECT COUNT(*) cnt
				FROM $table_name_bookings bookings 
				INNER JOIN $table_name_schedule schedule ON bookings.tour_schedule_id = schedule.Id 
				WHERE ";
		
			if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
				$select_sql .= " translations_default.element_id = schedule.tour_id ";
			} else {
				$select_sql .= " tours.ID = schedule.tour_id ";
			}
	
			$select_sql .= "
				ORDER BY cnt ASC LIMIT 1
			) bookings ";
			
			$select_sql .= ",
			( 
				SELECT MIN(price) 
				FROM $table_name_schedule
				WHERE ";
		
			if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
				$select_sql .= " tour_id=translations_default.element_id ";
			} else {
				$select_sql .= " tour_id=tours.ID ";
			}
		
			$select_sql .= ") price ";
			
			$select_sql .= ",
			( 
				SELECT MIN(max_people) 
				FROM $table_name_schedule
				WHERE ";
	
			if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
				$select_sql .= " tour_id=translations_default.element_id ";
			} else {
				$select_sql .= " tour_id=tours.ID ";
			}
		
			$select_sql .= " 
			) max_people ";

			$guests = (isset($search_args['guests']) && isset($search_args['guests'])) ? intval($search_args['guests']) : 0;
				
			if ( isset($search_args['search_only_available_properties'])) {				
				$search_only_available_properties = $search_args['search_only_available_properties'];

				if ($search_only_available_properties) {
					$having_sql .= " AND ((bookings ";
					if ($guests && $guests > 0) {
						$having_sql .= $wpdb->prepare("+ %d) <= max_people ", $guests);
					} else {
						$having_sql .= ") < max_people ";
					}
					$having_sql .= " OR max_people IS NULL) AND price IS NOT NULL ";
				}
			}
			
		}
		
	}

	$location_ids_string = implode(', ', $location_ids);
	$tour_ids_string = implode(', ', $tour_ids);
	
	$select_sql .= " FROM $wpdb->posts tours ";
			
	if (!empty($location_ids_string)) {		
		$join_sql .= " INNER JOIN $wpdb->postmeta location_meta ON (tours.ID = location_meta.post_id) ";
	}
		
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$join_sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_tour' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = tours.ID ";
		$join_sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_tour' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
	}
	
	if ($featured_only) {
		$join_sql .= " LEFT JOIN $wpdb->postmeta tour_featured_meta ON (tours.ID = tour_featured_meta.post_id) ";
	}
	
	if (!empty($tour_types_array)) {	
		$tour_types_string = implode(",",$tour_types_array);		
		$join_sql .= " LEFT JOIN $wpdb->term_relationships ON (tours.ID = $wpdb->term_relationships.object_id) ";
		$join_sql .= " LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id) ";		
		$where_sql .= " AND $wpdb->term_taxonomy.taxonomy = 'tour_type' AND $wpdb->term_taxonomy.term_id IN ($tour_types_string) ";
	}

	$where_sql .= " AND tours.post_type = 'tour' AND tours.post_status = 'publish' ";
	
	if (!empty($location_ids_string)) {
		$where_sql .= " AND (location_meta.meta_key = 'tour_location_post_id' AND CAST(location_meta.meta_value AS UNSIGNED) IN ($location_ids_string)) ";
	}
	
	if (!empty($tour_ids_string)) {
		$where_sql .= " AND (tours.ID IN ($tour_ids_string)) ";
	}

	if ( count($search_args) > 0) {
		if ( isset($search_args['keyword'])&& strlen($search_args['keyword']) > 0  ) {
			$search_string = "%" . $search_args['keyword'] . "%";
			$where_sql .= sprintf(" AND (LOWER(tours.post_title) LIKE '%s' OR LOWER(tours.post_content) LIKE '%s') ", strtolower($search_string), strtolower($search_string));
		}
	}
	
	if ($featured_only) {
		$where_sql .= " AND tour_featured_meta.meta_key = 'tour_is_featured' AND CAST(tour_featured_meta.meta_value AS CHAR) = '1' ";
	}
	
	$group_by_sql = " GROUP BY tours.ID ";
	
	$order_by_sql = '';
	if ( !empty( $orderby ) && !empty( $order ) ){ 
		$order_by_sql .= ' ORDER BY ' . $orderby . ' ' . $order; 
	} else {
		$order_by_sql .= ' ORDER BY tours.post_date DESC ';
	}
	
	$sql = $select_sql . $join_sql . $where_sql . $group_by_sql . $having_sql . $order_by_sql;
	
	$count_sql = $sql;
	
	if(!empty($paged) && !empty($per_page)){
		$offset=($paged-1)*$per_page;
		$sql .=' LIMIT '.(int)$offset.','.(int)$per_page;
	}
	
	$results = array(
		'total' => $wpdb->query($count_sql),
		'results' => $wpdb->get_results($sql)
	);
	
	return $results;
}

function list_available_tour_schedule_entries($tour_id, $from_date, $from_year, $from_month, $tour_type_is_repeated, $tour_type_day_of_week) {

	global $wpdb;

	$tour_id = get_default_language_post_id($tour_id, 'tour');
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

	$yesterday = date('Y-m-d',strtotime("-1 days"));

	if ($tour_type_is_repeated == 0) {
		// oneoff tours, must have start date in future in order for people to attend
		$sql = "
			SELECT *, schedule.start_date tour_date, 0 num
			FROM $table_name_schedule schedule 
			WHERE tour_id=%d AND start_date >= %s 
			HAVING max_people > (SELECT COUNT(*) ct FROM $table_name_bookings bookings WHERE bookings.tour_schedule_id = schedule.Id) ";
			
		$sql = $wpdb->prepare($sql, $tour_id, $from_date);
	} else if ($tour_type_is_repeated == 1) {		
		// daily tours
		$sql = $wpdb->prepare("
			SELECT schedule.Id, schedule.price, schedule.price_child, schedule.duration_days, schedule.max_people, date_range.single_date tour_date, num
			FROM $table_name_schedule schedule
			LEFT JOIN 
			(
				SELECT ADDDATE(%s,t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date, (t1.i*10 + t0.i) num ", $yesterday);
				
		$sql .= "
				FROM
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
				HAVING  YEAR(single_date) = %d AND MONTH(single_date) = %d
			) date_range ON date_range.single_date >= %s
			WHERE tour_id=%d AND ( schedule.end_date IS NULL OR date_range.single_date < schedule.end_date )
			HAVING schedule.max_people > (SELECT COUNT(*) ct FROM $table_name_bookings bookings WHERE bookings.tour_schedule_id = schedule.Id AND bookings.tour_date = date_range.single_date) ";
		
		$sql = $wpdb->prepare($sql, $from_year, $from_month, $from_date, $tour_id);

	} else if ($tour_type_is_repeated == 2) {
	
		// weekday tours
		$sql = $wpdb->prepare("
			SELECT schedule.Id, schedule.price, schedule.price_child, schedule.duration_days, schedule.max_people, date_range.single_date tour_date, num
			FROM $table_name_schedule schedule
			LEFT JOIN 
			(
				SELECT ADDDATE(%s,t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date, (t1.i*10 + t0.i) num ", $yesterday);
		
		$sql .= "
				FROM
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
				HAVING WEEKDAY(single_date) BETWEEN 0 AND 4 AND YEAR(single_date) = %d AND MONTH(single_date) = %d
			) date_range ON date_range.single_date >= %s
			WHERE tour_id=%d AND ( schedule.end_date IS NULL OR date_range.single_date < schedule.end_date )	
			HAVING schedule.max_people > (SELECT COUNT(*) ct FROM $table_name_bookings bookings WHERE bookings.tour_schedule_id = schedule.Id AND bookings.tour_date = date_range.single_date) ";
		
		$sql = $wpdb->prepare($sql, $from_year, $from_month, $from_date, $tour_id);
	} else if ($tour_type_is_repeated == 3) {
		
		// weekly tours
		$sql = $wpdb->prepare("
			SELECT schedule.Id, schedule.price, schedule.price_child, schedule.duration_days, schedule.max_people, date_range.single_date tour_date, num
			FROM $table_name_schedule schedule
			LEFT JOIN 
			(
				SELECT ADDDATE(%s,t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date, (t1.i*10 + t0.i) num ", $yesterday);
				
		$sql .= "
				FROM
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
				HAVING WEEKDAY(single_date) = %d AND YEAR(single_date) = %d AND MONTH(single_date) = %d
			) date_range ON date_range.single_date >= %s 
			WHERE tour_id=%d AND ( schedule.end_date IS NULL OR date_range.single_date < schedule.end_date ) 			
			HAVING schedule.max_people > (SELECT COUNT(*) ct FROM $table_name_bookings bookings WHERE bookings.tour_schedule_id = schedule.Id AND bookings.tour_date = date_range.single_date) ";
		
		$sql = $wpdb->prepare($sql, $tour_type_day_of_week, $from_year, $from_month, $from_date, $tour_id);		
	}

	return $wpdb->get_results($sql);
}

function get_tour_booking($booking_id) {

	global $wpdb, $byt_multi_language_count;

	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
	
	$sql = "SELECT 	DISTINCT bookings.*, 
					tours.post_title tour_name, 
					schedule.duration_days,
					bookings.total_price,
					schedule.tour_id
			FROM $table_name_bookings bookings 
			INNER JOIN $table_name_schedule schedule ON schedule.Id = bookings.tour_schedule_id
			INNER JOIN $wpdb->posts tours ON tours.ID = schedule.tour_id ";
			
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_tour' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = tours.ID ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_tour' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
	}
			
	$sql .= " WHERE tours.post_status = 'publish' AND bookings.Id = %d ";

	$sql = $wpdb->prepare($sql, $booking_id);
	return $wpdb->get_row($sql);
}

function create_tour_booking($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $user_id, $total_price_adults, $total_price_children, $total_price, $start_date) {

	global $wpdb;
	
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

	$sql = "INSERT INTO $table_name_bookings
			(first_name, last_name, email, phone, address, town, zip, country, special_requirements, adults, children, tour_schedule_id, user_id, total_price_adults, total_price_children, total_price, tour_date)
			VALUES 
			(%s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d, %f, %f, %f, %s);";
	$sql = $wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $user_id, (float)$total_price_adults, (float)$total_price_children, (float)$total_price, $start_date);
	
	$wpdb->query($sql);
	
	return $wpdb->insert_id;
}

function update_tour_booking($booking_id, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $user_id, $total_price_adults, $total_price_children, $total_price, $start_date) {
	
	global $wpdb;
	
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

	$sql = "UPDATE $table_name_bookings
			SET first_name = %s,
				last_name = %s, 
				email = %s, 
				phone = %s, 
				address = %s, 
				town = %s, 
				zip = %s, 
				country = %s, 
				special_requirements = %s,
				adults = %d, 
				children = %d, 
				tour_schedule_id = %d, 
				user_id = %d, 
				total_price_adults = %f, 
				total_price_children = %f, 
				total_price = %f, 
				tour_date = %s
			WHERE Id=%d";
			
	$sql = $wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $user_id, (float)$total_price_adults, (float)$total_price_children, (float)$total_price, $start_date, $booking_id);
	
	$wpdb->query($sql);
}

function delete_tour_booking($booking_id) {

	global $wpdb;
	
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
	
	$sql = "DELETE FROM $table_name_bookings
			WHERE Id = %d";
	
	$wpdb->query($wpdb->prepare($sql, $booking_id));
}

function list_tour_bookings($paged = null, $per_page = 0, $orderby = 'Id', $order = 'ASC', $search_term = null ) {

	global $wpdb, $byt_multi_language_count;
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

	$sql = "SELECT 	DISTINCT bookings.*, 
					tours.post_title tour_name, 
					schedule.start_date,
					schedule.duration_days,
					bookings.total_price
			FROM $table_name_bookings bookings 
			INNER JOIN $table_name_schedule schedule ON schedule.Id = bookings.tour_schedule_id
			INNER JOIN $wpdb->posts tours ON tours.ID = schedule.tour_id ";
			
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_tour' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = tours.ID ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_tour' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
	}
	
	$sql .= " WHERE tours.post_status = 'publish' ";
	
	if ($search_term != null && !empty($search_term)) {
		$search_term = "%" . $search_term . "%";
		$sql .= $wpdb->prepare(" AND (bookings.first_name LIKE '%s' OR bookings.last_name LIKE '%s') ", $search_term, $search_term);
	}
	
	if(!empty($orderby) && !empty($order)){ 
		$sql.= "ORDER BY $orderby $order"; 
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

function create_tour_schedule($tour_id, $start_date, $duration_days, $price, $price_child, $max_people, $end_date) {

	global $wpdb;
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	
	$tour_id = get_default_language_post_id($tour_id, 'tour');
	
	if ($end_date == null) {
		$sql = "INSERT INTO $table_name_schedule
				(tour_id, start_date, duration_days, price, price_child, max_people)
				VALUES
				(%d, %s, %d, %f, %f, %d);";
		$sql = $wpdb->prepare($sql, $tour_id, $start_date, $duration_days, $price, $price_child, $max_people);
	} else {
		$sql = "INSERT INTO $table_name_schedule
				(tour_id, start_date, duration_days, price, price_child, max_people, end_date)
				VALUES
				(%d, %s, %d, %f, %f, %d, %s);";
		$sql = $wpdb->prepare($sql, $tour_id, $start_date, $duration_days, $price, $price_child, $max_people, $end_date);
	}
	
	$wpdb->query($sql);
}

function update_tour_schedule($schedule_id, $start_date, $duration_days, $tour_id, $price, $price_child, $max_people, $end_date) {

	global $wpdb;
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	
	$tour_id = get_default_language_post_id($tour_id, 'tour');

	if ($end_date == null) {
		$sql = "UPDATE " . BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE . "
				SET start_date=%s, duration_days=%d, tour_id=%d, price=%f, price_child=%f, max_people=%d
				WHERE Id=%d";
		$sql = $wpdb->prepare($sql, $start_date, $duration_days, $tour_id, $price, $price_child, $max_people, $schedule_id);
	} else {
		$sql = "UPDATE " . BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE . "
				SET start_date=%s, duration_days=%d, tour_id=%d, price=%f, price_child=%f, max_people=%d, end_date=%s
				WHERE Id=%d";
		$sql = $wpdb->prepare($sql, $start_date, $duration_days, $tour_id, $price, $price_child, $max_people, $end_date, $schedule_id);
	}
	
	$wpdb->query($sql);	
}

function delete_tour_schedule($schedule_id) {

	global $wpdb;
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	
	$sql = "DELETE FROM $table_name_schedule
			WHERE Id = %d";
	
	$wpdb->query($wpdb->prepare($sql, $schedule_id));	
}

function get_tour_schedule($tour_schedule_id) {

	global $wpdb;
		
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
		
	$sql = "SELECT 	schedule.*, tours.post_title tour_name, 
					(
						SELECT COUNT(*) ct 
						FROM $table_name_bookings bookings 
						WHERE bookings.tour_schedule_id = schedule.Id 
					) has_bookings,
					IFNULL(tour_price_meta.meta_value, 0) tour_is_price_per_group
			FROM $table_name_schedule schedule 
			INNER JOIN $wpdb->posts tours ON tours.ID = schedule.tour_id 
			LEFT JOIN $wpdb->postmeta tour_price_meta ON tours.ID = tour_price_meta.post_id AND tour_price_meta.meta_key = 'tour_is_price_per_group'
			WHERE schedule.Id=%d ";
	
	$sql = $wpdb->prepare($sql, $tour_schedule_id);
	return $wpdb->get_row($sql);
}

function delete_all_tour_schedules() {

	global $wpdb;
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	$sql = "DELETE FROM $table_name_schedule";
	$wpdb->query($sql);	
}

function list_tour_schedules ($paged = null, $per_page = 0, $orderby = 'Id', $order = 'ASC', $day = 0, $month = 0, $year = 0, $tour_id = 0, $search_term = '') {

	global $wpdb;
	
	$tour_id = get_default_language_post_id($tour_id, 'tour');
	
	$filter_date = '';
	if ($day > 0 || $month > 0 || $year) { 
		$filter_date .= ' AND ( 1=1 ';
		if ($day > 0)
			$filter_date .= $wpdb->prepare(" AND DAY(start_date) = %d ", $day);			
		if ($month > 0)
			$filter_date .= $wpdb->prepare(" AND MONTH(start_date) = %d ", $month);			
		if ($year > 0)
			$filter_date .= $wpdb->prepare(" AND YEAR(start_date) = %d ", $year);			
		$filter_date .= ')';		
	}

	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
	
	$sql = "SELECT 	schedule.*, tours.post_title tour_name, 
					(
						SELECT COUNT(*) ct 
						FROM $table_name_bookings bookings 
						WHERE bookings.tour_schedule_id = schedule.Id 
					) has_bookings,
					IFNULL(tour_price_meta.meta_value, 0) tour_is_price_per_group
			FROM $table_name_schedule schedule 
			INNER JOIN $wpdb->posts tours ON tours.ID = schedule.tour_id 
			LEFT JOIN $wpdb->postmeta tour_price_meta ON tours.ID = tour_price_meta.post_id AND tour_price_meta.meta_key = 'tour_is_price_per_group'
			WHERE tours.post_status = 'publish' ";
			
	if ($tour_id > 0) {
		$sql .= $wpdb->prepare(" AND schedule.tour_id=%d ", $tour_id);
	}

	if ($filter_date != null && !empty($filter_date)) {
		$sql .= $filter_date;
	}
	
	if(!empty($orderby) & !empty($order)){ 
		$sql .= $wpdb->prepare(" ORDER BY %s %s ", $orderby, $order); 
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

function get_tour_schedule_price($schedule_id, $is_child_price) {

	global $wpdb;
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;

	$sql = "SELECT " . ($is_child_price ? "schedule.price_child" : "schedule.price") . "
			FROM $table_name_schedule schedule 
			WHERE id=%d ";	
			
	$price = $wpdb->get_var($wpdb->prepare($sql, $schedule_id));
	
	global $current_currency, $default_currency;
	if ($current_currency && $current_currency != $default_currency)
		$price = currency_conversion($price, $default_currency, $current_currency);
	
	return $price;
}

function get_tour_available_schedule_id($tour_id, $date) {

	global $wpdb;
	
	$tour_obj = new byt_tour(intval($tour_id));

	$tour_id = $tour_obj->get_base_id();
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

	$sql = "SELECT MIN(id) schedule_id
			FROM $table_name_schedule schedule 
			WHERE tour_id=%d AND schedule.max_people > (
				SELECT COUNT(*) ct 
				FROM $table_name_bookings bookings 
				WHERE bookings.tour_schedule_id = schedule.Id AND bookings.tour_date = %s
			) 
			";	
			
	if ($tour_obj->get_type_is_repeated() == 0) {
		$sql .= " AND schedule.start_date = %s ";
	}	

	$schedule_id = $wpdb->get_var($wpdb->prepare($sql, $tour_id, $date, $date));
	
	return $schedule_id;
}

function get_tour_min_price($tour_id, $date) {

	global $wpdb;
	
	$tour_obj = new byt_tour(intval($tour_id));

	$tour_id = $tour_obj->get_base_id();
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;

	$sql = "SELECT MIN(schedule.price) 
			FROM $table_name_schedule schedule 
			WHERE tour_id=%d ";	
			
	if ($tour_obj->get_type_is_repeated() == 0) {
		// this tour is a one off and is not repeated. If start date is missed, person cannot participate.
		$sql .= $wpdb->prepare(" AND start_date > %s ", $date);
	} else {
		// daily, weekly, weekdays tours are recurring which means start date is important only in the sense that tour needs to have become valid before we can get min price.
	}

	$sql = $wpdb->prepare($sql, $tour_id);
	$min_price = $wpdb->get_var($sql);
	
	global $current_currency, $default_currency;
	if ($current_currency && $current_currency != $default_currency)
		$min_price = currency_conversion($min_price, $default_currency, $current_currency);
	
	return $min_price;
}
