<?php

function bookyourtravel_register_accommodation_post_type() {
	
	$accommodations_permalink_slug = of_get_option('accommodations_permalink_slug', 'hotels');
	$slug = _x( $accommodations_permalink_slug, 'URL slug2', 'bookyourtravel' );
		
	$labels = array(
		'name'                => _x( 'Accommodations', 'Post Type General Name', 'bookyourtravel' ),
		'singular_name'       => _x( 'Accommodation', 'Post Type Singular Name', 'bookyourtravel' ),
		'menu_name'           => __( 'Accommodations', 'bookyourtravel' ),
		'all_items'           => __( 'All Accommodations', 'bookyourtravel' ),
		'view_item'           => __( 'View Accommodation', 'bookyourtravel' ),
		'add_new_item'        => __( 'Add New Accommodation', 'bookyourtravel' ),
		'add_new'             => __( 'New Accommodation', 'bookyourtravel' ),
		'edit_item'           => __( 'Edit Accommodation', 'bookyourtravel' ),
		'update_item'         => __( 'Update Accommodation', 'bookyourtravel' ),
		'search_items'        => __( 'Search Accommodations', 'bookyourtravel' ),
		'not_found'           => __( 'No Accommodations found', 'bookyourtravel' ),
		'not_found_in_trash'  => __( 'No Accommodations found in Trash', 'bookyourtravel' ),
	);
	$args = array(
		'label'               => __( 'accommodation', 'bookyourtravel' ),
		'description'         => __( 'Accommodation information pages', 'bookyourtravel' ),
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
 		'rewrite' => array('slug' => $slug),
	);
	register_post_type( 'accommodation', $args );	
}

function bookyourtravel_register_room_type_post_type() {
	
	$labels = array(
		'name'                => _x( 'Room types', 'Post Type General Name', 'bookyourtravel' ),
		'singular_name'       => _x( 'Room type', 'Post Type Singular Name', 'bookyourtravel' ),
		'menu_name'           => __( 'Room types', 'bookyourtravel' ),
		'all_items'           => __( 'Room types', 'bookyourtravel' ),
		'view_item'           => __( 'View Room type', 'bookyourtravel' ),
		'add_new_item'        => __( 'Add New Room type', 'bookyourtravel' ),
		'add_new'             => __( 'New Room type', 'bookyourtravel' ),
		'edit_item'           => __( 'Edit Room type', 'bookyourtravel' ),
		'update_item'         => __( 'Update Room type', 'bookyourtravel' ),
		'search_items'        => __( 'Search room_types', 'bookyourtravel' ),
		'not_found'           => __( 'No room types found', 'bookyourtravel' ),
		'not_found_in_trash'  => __( 'No room types found in Trash', 'bookyourtravel' ),
	);
	$args = array(
		'label'               => __( 'room type', 'bookyourtravel' ),
		'description'         => __( 'Room type information pages', 'bookyourtravel' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'author' ),
		'taxonomies'          => array( ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => 'edit.php?post_type=accommodation',
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'page',
		'rewrite' => false,
	);
	register_post_type( 'room_type', $args );	
}

function bookyourtravel_create_accommodation_extra_tables($installed_version) {

	if ($installed_version != BOOKYOURTRAVEL_VERSION) {
	
		global $wpdb;
		
		$table_name = BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE;
		$sql = "CREATE TABLE " . $table_name . " (
					Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					start_date datetime NOT NULL,
					end_date datetime NOT NULL,
					accommodation_id bigint(20) unsigned NOT NULL,
					room_type_id bigint(20) unsigned NOT NULL DEFAULT '0',
					room_count int(11) NOT NULL,
					price_per_day decimal(16,2) NOT NULL,
					price_per_day_child decimal(16,2) NOT NULL,
					PRIMARY KEY  (Id)
				);";

		// we do not execute sql directly
		// we are calling dbDelta which cant migrate database
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
		global $EZSQL_ERROR;
		$EZSQL_ERROR = array();
		
		$table_name = BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE;
		$sql = "CREATE TABLE " . $table_name . " (
					Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					first_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
					last_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
					email varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
					phone varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					address varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					town varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					zip varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					country varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					special_requirements text CHARACTER SET utf8 COLLATE utf8_bin,
					room_count int(11) NOT NULL DEFAULT '0',
					adults int(11) NOT NULL DEFAULT '0',
					children int(11) NOT NULL DEFAULT '0',
					total_price decimal(16,2) NOT NULL DEFAULT '0.00',
					accommodation_id bigint(20) unsigned NOT NULL,
					room_type_id bigint(20) unsigned NOT NULL,
					date_from datetime NOT NULL,
					date_to datetime NOT NULL,
					user_id bigint(20) unsigned DEFAULT NULL,
					created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					woo_order_id bigint(20) NULL,
					cart_key VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' NOT NULL,
					currency_code VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
					PRIMARY KEY  (Id)
				);";
		dbDelta($sql);
		
		$EZSQL_ERROR = array();
	}
}

function bookyourtravel_register_accommodation_type_taxonomy(){
	$labels = array(
			'name'              => _x( 'Accommodation types', 'taxonomy general name', 'bookyourtravel' ),
			'singular_name'     => _x( 'Accommodation type', 'taxonomy singular name', 'bookyourtravel' ),
			'search_items'      => __( 'Search Accommodation types', 'bookyourtravel' ),
			'all_items'         => __( 'All Accommodation types', 'bookyourtravel' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'         => __( 'Edit Accommodation type', 'bookyourtravel' ),
			'update_item'       => __( 'Update Accommodation type', 'bookyourtravel' ),
			'add_new_item'      => __( 'Add New Accommodation type', 'bookyourtravel' ),
			'new_item_name'     => __( 'New Accommodation type Name', 'bookyourtravel' ),
			'separate_items_with_commas' => __( 'Separate accommodation types with commas', 'bookyourtravel' ),
			'add_or_remove_items'        => __( 'Add or remove accommodation types', 'bookyourtravel' ),
			'choose_from_most_used'      => __( 'Choose from the most used accommodation types', 'bookyourtravel' ),
			'not_found'                  => __( 'No accommodation types found.', 'bookyourtravel' ),
			'menu_name'         => __( 'Accommodation types', 'bookyourtravel' ),
		);
		
	$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'update_count_callback' => '_update_post_term_count',
			'rewrite'           => false,
		);
		
	register_taxonomy( 'accommodation_type', array( 'accommodation' ), $args );
}

function list_room_types( $author_id = null, $statuses = array('publish') ) {

	$args = array(
	   'post_type' => 'room_type',
	   'post_status' => $statuses,
	   'posts_per_page' => -1,
	   'suppress_filters' => 0
	);
	
	if (isset($author_id) && $author_id > 0) {
		$args['author'] = intval($author_id);
	}
	
	$query = new WP_Query($args);

	return $query;
}
	
function list_accommodations ( $paged = 0, $per_page = 0, $orderby = '', $order = '', $location_id = 0, $featured_only = false, $is_self_catered = null, $accommodation_types_array = array(), $search_args = array(), $author_id = null, $include_private = false ) {

	global $wpdb, $byt_multi_language_count;
	
	$select_sql = " SELECT accommodations.* ";
	
	$select_sql .= ",  
		( 
			SELECT meta_value+0 FROM $wpdb->postmeta WHERE $wpdb->postmeta.meta_key = 'accommodation_star_count' AND $wpdb->postmeta.post_id=accommodations.ID LIMIT 1
		) star_count,  
		( 	
			SELECT meta_value+0 FROM $wpdb->postmeta WHERE $wpdb->postmeta.meta_key = 'review_score' AND $wpdb->postmeta.post_id=accommodations.ID LIMIT 1
		) review_score ";		
	
	$join_sql = " ";
	$where_sql = " WHERE 1=1 ";
	$having_sql = " HAVING 1=1 ";
	
	$location_args =  array( 'post_type' => 'location', 'posts_per_page' => -1, 'post_status' => 'publish' );
	$location_ids = array();
	$accommodation_ids = array();
	
	$location_id = get_default_language_post_id($location_id, 'location');
	
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
	
		if ( isset($search_args['keyword']) && strlen($search_args['keyword']) > 0 ) {
			$search_string = "%" . $search_args['keyword'] . "%";
			$search_sql = $wpdb->prepare("SELECT ID from $wpdb->posts where LOWER(post_title) LIKE '%s' AND post_type='location' AND post_status='publish'", strtolower($search_string));
			$temp_location_ids = $wpdb->get_col($search_sql);
			foreach ($temp_location_ids as $temp_location_id)  {
				$location_ids[] = $temp_location_id;
			}

			$search_sql = $wpdb->prepare("SELECT ID from $wpdb->posts WHERE (LOWER(post_title) LIKE '%s' OR LOWER(post_content) LIKE '%s') AND post_type='accommodation' AND post_status='publish'", strtolower($search_string), strtolower($search_string));
			$temp_accommodation_ids = $wpdb->get_col($search_sql);
			foreach ($temp_accommodation_ids as $temp_accommodation_id)  {
				$accommodation_ids[] = $temp_accommodation_id;
			}			
		}
		
		if ( isset($search_args['stars']) && strlen($search_args['stars']) > 0 ) {
			$stars = intval($search_args['stars']);
			if ($stars && $stars > 0 & $stars <=5) {
				$having_sql .= $wpdb->prepare(" AND star_count >= %d ", $stars);
			}
		}
		
		if ( isset($search_args['rating']) && strlen($search_args['rating']) > 0 ) {
			$rating = intval($search_args['rating']);			
			if ($rating && $rating > 0 & $rating <=10) {
				$having_sql .= $wpdb->prepare(" AND CEIL(review_score*10) >= %d ", $rating);
			}
		}
		
		if ( isset($search_args['date_from']) || isset($search_args['date_to']) ) {
		
			$date_from = null;
			if ( isset($search_args['date_from']) )
				$date_from = date('Y-m-d', strtotime($search_args['date_from']));
			
			$date_to = null;
			if ( isset($search_args['date_to']) )
				$date_to = date('Y-m-d', strtotime($search_args['date_to'].' -1 day'));

			if ($date_from == $date_to)
				$date_to = date('Y-m-d', strtotime($search_args['date_from'].' +7 day'));
				
			if ( isset($search_args['search_only_available_properties'])) {				
				$search_only_available_properties = $search_args['search_only_available_properties'];

				if ($search_only_available_properties) {
					$select_sql .= ",
					(
						SELECT SUM(booked_room_count)
						FROM
						(
							SELECT booked_dates.booking_date, SUM(IFNULL(bookings.room_count, 0)) booked_room_count
							FROM wp_byt_accommodation_bookings bookings
							INNER JOIN 
							(
								SELECT ADDDATE('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) booking_date 
								FROM
								(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
								(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
								(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
								(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
								(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4 
							) booked_dates ON booked_dates.booking_date BETWEEN bookings.date_from AND bookings.date_to
							WHERE booking_date IN 
							(
								SELECT booking_search_dates.single_date
								FROM 
								(
									SELECT ADDDATE('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date 
									FROM
									(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
									(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
									(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
									(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
									(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4 
								HAVING 1=1
						";
						
						if ($date_from != null && $date_to != null) {
							$select_sql .= $wpdb->prepare(" AND single_date BETWEEN %s AND %s ", $date_from, $date_to);
						} else if ($date_from != null) {
							$select_sql .= $wpdb->prepare(" AND single_date > %s ", $date_from);
						} else if ($date_to != null) {
							$select_sql .= $wpdb->prepare(" AND single_date < %s ", $date_to);
						}
						
						$select_sql .= "
								) booking_search_dates
							)
							GROUP BY booking_date
						) booking_sums 
					) total_booked_rooms ";
						

					$select_sql .= ",
					(
						SELECT SUM(vacant_room_count)
						FROM
						(
							SELECT vacant_dates.vacant_date, SUM(IFNULL(vacancies.room_count, 0)) vacant_room_count, vacancies.accommodation_id 
							FROM wp_byt_accommodation_vacancies vacancies
							INNER JOIN 
							(
								SELECT ADDDATE('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) vacant_date 
								FROM
								(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
								(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
								(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
								(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
								(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4 
							) vacant_dates ON vacant_dates.vacant_date BETWEEN vacancies.start_date AND vacancies.end_date
							WHERE vacant_date IN 
							(
								SELECT vacancy_search_dates.single_date
								FROM 
								(
									SELECT ADDDATE('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date 
									FROM
									(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
									(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
									(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
									(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
									(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4 
									HAVING 1=1
						";
						
						if ($date_from != null && $date_to != null) {
							$select_sql .= $wpdb->prepare(" AND single_date BETWEEN %s AND %s ", $date_from, $date_to);
						} else if ($date_from != null) {
							$select_sql .= $wpdb->prepare(" AND single_date > %s ", $date_from);
						} else if ($date_to != null) {
							$select_sql .= $wpdb->prepare(" AND single_date < %s ", $date_to);
						}
						
						$select_sql .= "
								) vacancy_search_dates
							)
							GROUP BY vacant_date, vacancies.accommodation_id
						) vacancy_sums
						GROUP BY accommodation_id
						HAVING accommodation_id=accommodations.ID
					) total_vacant_rooms
					
					";
						
					$having_sql .= " AND total_vacant_rooms > 0 AND total_vacant_rooms > total_booked_rooms ";
					
					$rooms = 0;
					if ( isset($search_args['rooms']) && strlen($search_args['rooms']) > 0 ) {
						$rooms = intval($search_args['rooms']);
						$having_sql .= $wpdb->prepare(" AND (total_vacant_rooms - total_booked_rooms) > %d ", $rooms);
					}
				}
			}
		}
	}
	
	$location_ids_string = implode(', ', $location_ids);
	$accommodation_ids_string = implode(', ', $accommodation_ids);
	
	$select_sql .= ", 0 min_price ";
	
	$select_sql .= " FROM $wpdb->posts accommodations ";
			
	if (!empty($location_ids_string)) {		
		$join_sql .= " INNER JOIN $wpdb->postmeta location_meta ON (accommodations.ID = location_meta.post_id) ";
	}
			
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$join_sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_accommodation' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = accommodations.ID ";
		$join_sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_accommodation' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
	}
	
	if ($featured_only) {
		$join_sql .= " LEFT JOIN $wpdb->postmeta accommodation_featured_meta ON (accommodations.ID = accommodation_featured_meta.post_id) ";
	}

	$where_sql = " WHERE 1=1 ";
	
	if (isset($is_self_catered)) {
		if ($is_self_catered) {
			$join_sql .= " INNER JOIN $wpdb->postmeta accommodation_meta_is_self_catered ON accommodations.ID=accommodation_meta_is_self_catered.post_id AND accommodation_meta_is_self_catered.meta_key='accommodation_is_self_catered' ";
			$where_sql .= " AND accommodation_meta_is_self_catered.meta_value = 1 ";
		} else {
			$join_sql .= " LEFT JOIN $wpdb->postmeta accommodation_meta_is_self_catered ON accommodations.ID=accommodation_meta_is_self_catered.post_id AND accommodation_meta_is_self_catered.meta_key='accommodation_is_self_catered' ";
			$where_sql .= " AND (accommodation_meta_is_self_catered.meta_value = 0 OR accommodation_meta_is_self_catered.meta_value IS NULL) ";
		}
	}
	
	if (!empty($accommodation_types_array)) {	
		$accommodation_types_string = implode(",",$accommodation_types_array);		
		$join_sql .= "  LEFT JOIN $wpdb->term_relationships ON (accommodations.ID = $wpdb->term_relationships.object_id)
				   LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id) ";		
		$where_sql .= " AND $wpdb->term_taxonomy.taxonomy = 'accommodation_type' AND $wpdb->term_taxonomy.term_id IN ($accommodation_types_string) ";
	}
	
	$where_sql .= " AND accommodations.post_type = 'accommodation' AND ( accommodations.post_status = 'publish' ";
	if ($include_private)
		$where_sql .= " OR accommodations.post_status = 'private' ";
	$where_sql .= ") ";
	
	if (isset($author_id)) {
		$author_id = intval($author_id);
		if ($author_id > 0) {
			$where_sql .= $wpdb->prepare( " AND accommodations.post_author = %d ", $author_id );
		}
	}
	
	if (!empty($location_ids_string)) {
		$where_sql .= " AND (location_meta.meta_key = 'accommodation_location_post_id' AND CAST(location_meta.meta_value AS UNSIGNED) IN ($location_ids_string)) ";
	}
	
	if (!empty($accommodation_ids_string)) {
		$where_sql .= " AND (accommodations.ID IN ($accommodation_ids_string)) ";
	}
	
	if ($featured_only) {
		$where_sql .= " AND accommodation_featured_meta.meta_key = 'accommodation_is_featured' AND CAST(accommodation_featured_meta.meta_value AS CHAR) = '1' ";
	}
	
	$group_by_sql = " GROUP BY accommodations.ID ";
	
	$order_by_sql = '';
	if ( !empty( $orderby ) && !empty( $order ) ){ 
		$order_by_sql .= ' ORDER BY ' . $orderby . ' ' . $order; 
	} else {
		$order_by_sql .= ' ORDER BY accommodations.post_date DESC ';
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

function get_accommodation_total_price($accommodation_id, $date_from, $date_to, $room_type_id, $room_count, $adults, $children) {

	global $wpdb;
	
	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	$accommodation_is_price_per_person = get_post_meta($accommodation_id, 'accommodation_is_price_per_person', true);
	$accommodation_count_children_stay_free = get_post_meta($accommodation_id, 'accommodation_count_children_stay_free', true );
	if (!isset($accommodation_count_children_stay_free))
		$accommodation_count_children_stay_free = 0;
	$accommodation_count_children_stay_free = intval($accommodation_count_children_stay_free);
	
	$children = $children - $accommodation_count_children_stay_free;
	$children = $children >= 0 ? $children : 0;

	// we are actually (in terms of db data) looking for date 1 day before the to date
	// e.g. when you look to book a room from 19.12. to 20.12 you will be staying 1 night, not 2
	$date_to = date('Y-m-d', strtotime($date_to.' -1 day'));
	
	$dates = get_dates_from_range($date_from, $date_to);

	$total_price = 0;
	
	foreach ($dates as $date) {
		
		$price_per_day = get_accommodation_price($date, $accommodation_id, $room_type_id, false);
		$child_price_per_day = get_accommodation_price($date, $accommodation_id, $room_type_id, true);
		
		if ($accommodation_is_price_per_person) {
			$total_price += (($adults * $price_per_day) + ($children * $child_price_per_day)) * $room_count;
		} else {
			$total_price += ($price_per_day * $room_count);
		}
	}
	
	$total_price = $total_price * $room_count;

	return $total_price;
}

function list_accommodation_vacancies($date, $accommodation_id, $room_type_id=0, $is_child_price=false) {
	
	global $wpdb;
	
	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	
	$sql = $wpdb->prepare("SELECT " . ($is_child_price ? "vacancies.price_per_day_child" : "vacancies.price_per_day") . " price, vacancies.room_count, 
			(
				SELECT IFNULL(SUM(bookings.room_count), 0)
				FROM " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . " bookings
				WHERE bookings.accommodation_id=vacancies.accommodation_id AND %s BETWEEN bookings.date_from AND bookings.date_to
			) booked_rooms 
			FROM " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . " vacancies 
			WHERE 1=1 ", $date);

	$sql .= $wpdb->prepare(" 	AND vacancies.accommodation_id=%d 
								AND (%s BETWEEN vacancies.start_date AND vacancies.end_date) ", $accommodation_id, $date);

	if ($room_type_id > 0) 
		$sql .= $wpdb->prepare(" AND room_type_id=%d ", $room_type_id);

	$sql .= " ORDER BY " . ($is_child_price ? "vacancies.price_per_day_child" : "vacancies.price_per_day");

	return $wpdb->get_results($sql);
}

function list_accommodation_vacancy_dates($accommodation_id, $room_type_id=0, $month=0, $year=0, $available_only = false) {

	global $wpdb;
	
	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	
	$current_date = date('Y-m-d', time());
	
	$end_date = null;
	if ($month == 0 && $year == 0)
		$end_date = date('Y-m-d', strtotime($current_date . ' + 199 days'));

	$sql = "
	SELECT dates.single_date
			FROM 
			(
				SELECT ADDDATE('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date 
				FROM
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4 
				HAVING";
	
	if (isset($end_date))
		$sql .= $wpdb->prepare(" single_date BETWEEN %s AND %s ", $current_date, $end_date);
	else
		$sql .= $wpdb->prepare(" single_date >= %s ", $current_date);
		
	$sql .= "				
			) dates, " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . " vacancies 
			WHERE 1=1 ";

	if ($month > 0)
		$sql .=  $wpdb->prepare(" AND MONTH(dates.single_date) = %d ", $month);

	if ($year > 0)
		$sql .=  $wpdb->prepare(" AND YEAR(dates.single_date) = %d ", $year);

	$sql .= $wpdb->prepare(" AND
			dates.single_date 
			BETWEEN
			( 
				SELECT MIN(start_date) start_date 
				FROM " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . " 
				WHERE accommodation_id = %d ", $accommodation_id);
				
	if ($room_type_id > 0) 
		$sql .= $wpdb->prepare(" AND room_type_id=%d ", $room_type_id);
	$sql .= $wpdb->prepare("
			) 
			AND
			( 
				SELECT MAX(end_date) end_date 
				FROM " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . " 
				WHERE accommodation_id = %d ", $accommodation_id);

	if ($room_type_id > 0) 
		$sql .= $wpdb->prepare(" AND room_type_id=%d ", $room_type_id);

	$sql .= $wpdb->prepare("
			) 
			AND vacancies.accommodation_id=%d AND dates.single_date BETWEEN vacancies.start_date AND vacancies.end_date ", $accommodation_id);

	if ($room_type_id > 0) 
		$sql .= $wpdb->prepare(" AND room_type_id=%d ", $room_type_id);

	$sql .= " GROUP BY dates.single_date ";
	
	$date_results = $wpdb->get_results($sql);
	
	if ($available_only) {
	
		$available_dates = array();
		
		foreach ($date_results as $date_result) {
		
			$vacancy_results = list_accommodation_vacancies($date_result->single_date, $accommodation_id, $room_type_id);
			$room_count = 0;
			$booked_rooms = 0;
			foreach($vacancy_results as $vacancy_result) {
				$room_count += $vacancy_result->room_count;
				if ($booked_rooms == 0)
					$booked_rooms = $vacancy_result->booked_rooms;
			}
			
			if ($room_count > $booked_rooms)
				$available_dates[] = $date_result;
		}
		
		return $available_dates;
	} 
	
	return $date_results;
}

function get_accommodation_price($date, $accommodation_id, $room_type_id=0, $is_child_price=false) {

	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	
	$accommodation_is_self_catered = get_post_meta($accommodation_id, 'accommodation_is_self_catered', true);

	$price = 0;
	$min_price = 0;
	
	$vacancy_results = list_accommodation_vacancies($date, $accommodation_id, $room_type_id, $is_child_price);
	
	$room_count = 0;
	foreach($vacancy_results as $vacancy_result) {
		$room_count += $vacancy_result->room_count;
		if ($vacancy_result->booked_rooms < $room_count) {
			if ($min_price == 0 || $min_price > $vacancy_result->price) {
				$min_price = $vacancy_result->price;
				break;
			}
		}
	}
	$price = $min_price;
	
	global $current_user, $currency_symbol, $current_currency, $enabled_currencies, $default_currency;
	
	if ($price > 0) { 
		if ($current_currency && $current_currency != $default_currency)
			$price = currency_conversion($min_price, $default_currency, $current_currency);
	}

	return $price;
}

function get_accommodation_min_price($accommodation_id=0, $room_type_id=0, $location_id=0) {

	if ($accommodation_id > 0)
		$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	if ($location_id > 0) 
		$location_id = get_default_language_post_id($location_id, 'location'); 
		
	$min_price = 0;
	
	$accommodation_ids = array();
	if ($accommodation_id > 0) {
		$accommodation_ids[] = $accommodation_id;
	} else if ($location_id > 0) {
		$accommodation_results = list_accommodations(0, 0, '', '', $location_id);
		
		if ( count($accommodation_results) > 0 && $accommodation_results['total'] > 0 ) {
			foreach ($accommodation_results['results'] as $accommodation_result) {
				$accommodation_ids[] = $accommodation_result->ID;
			}
		}
	}
	
	if (count($accommodation_ids) > 0) {
		foreach ($accommodation_ids as $accommodation_id) {	
			$date_results = list_accommodation_vacancy_dates($accommodation_id, $room_type_id, 0, 0, true);
			foreach ($date_results as $date_result) {
				$vacancy_results = list_accommodation_vacancies($date_result->single_date, $accommodation_id, $room_type_id);				
				$room_count = 0;
				foreach($vacancy_results as $vacancy_result) {				
					$room_count += $vacancy_result->room_count;

					if ($vacancy_result->booked_rooms < $room_count) {
						if ($min_price == 0 || ($min_price > $vacancy_result->price && $vacancy_result->price > 0)) {
							$min_price = $vacancy_result->price;
							break;
						}
					}
				}
			}
		}
	}
	
	global $current_user, $currency_symbol, $current_currency, $enabled_currencies, $default_currency;
	
	if ($min_price > 0) { 
		if ($current_currency && $current_currency != $default_currency)
			$min_price = currency_conversion($min_price, $default_currency, $current_currency);
	}

	return $min_price;
}

function delete_all_accommodation_vacancies() {

	global $wpdb;
	$table_name = BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE;
	$sql = "DELETE FROM $table_name";
	$wpdb->query($sql);
	
}

function get_accommodation_vacancy($vacancy_id ) {
	global $wpdb;

	$table_name = BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE;
	$sql = "SELECT vacancies.*, accommodations.post_title accommodation_name, room_types.post_title room_type
			FROM " . $table_name . " vacancies 
			INNER JOIN $wpdb->posts accommodations ON accommodations.ID = vacancies.accommodation_id 
			LEFT JOIN $wpdb->posts room_types ON room_types.ID = vacancies.room_type_id 
			WHERE vacancies.Id=%d AND accommodations.post_status = 'publish' AND 
					(room_types.post_status IS NULL OR room_types.post_status = 'publish') ";

	return $wpdb->get_row($wpdb->prepare($sql, $vacancy_id));
}

function list_all_accommodation_vacancies($accommodation_id, $room_type_id, $orderby = 'Id', $order = 'ASC', $paged = null, $per_page = 0 ) {

	global $wpdb;

	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	
	$table_name = BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE;
	$sql = "SELECT DISTINCT vacancies.*, accommodations.post_title accommodation_name, room_types.post_title room_type, IFNULL(accommodation_meta_is_per_person.meta_value, 0) accommodation_is_per_person, IFNULL(accommodation_meta_is_self_catered.meta_value, 0) accommodation_is_self_catered
			FROM " . $table_name . " vacancies 
			INNER JOIN $wpdb->posts accommodations ON accommodations.ID = vacancies.accommodation_id 
			LEFT JOIN $wpdb->postmeta accommodation_meta_is_per_person ON accommodations.ID=accommodation_meta_is_per_person.post_id AND accommodation_meta_is_per_person.meta_key='accommodation_is_price_per_person'
			LEFT JOIN $wpdb->postmeta accommodation_meta_is_self_catered ON accommodations.ID=accommodation_meta_is_self_catered.post_id AND accommodation_meta_is_self_catered.meta_key='accommodation_is_self_catered'
			LEFT JOIN $wpdb->posts room_types ON room_types.ID = vacancies.room_type_id 
			WHERE 	accommodations.post_status = 'publish' AND 
					(room_types.post_status IS NULL OR room_types.post_status = 'publish') ";
			
	if ($accommodation_id > 0) {
		$sql .= $wpdb->prepare(" AND vacancies.accommodation_id=%d ", $accommodation_id);
	}
	
	if ($room_type_id > 0) {
		$sql .= $wpdb->prepare(" AND vacancies.room_type_id=%d ", $room_type_id);
	}

	if(!empty($orderby) & !empty($order)){ 
		$sql.=' ORDER BY ' . $orderby . ' ' . $order; 
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

function get_accommodation_booking($booking_id) {
	global $wpdb, $byt_multi_language_count;

	$sql = "SELECT DISTINCT bookings.*, accommodations.post_title accommodation_name, room_types.post_title room_type
			FROM " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . " bookings 
			INNER JOIN $wpdb->posts accommodations ON accommodations.ID = bookings.accommodation_id ";
			
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_accommodation' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = accommodations.ID ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_accommodation' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
	}
			
	$sql .= " LEFT JOIN $wpdb->posts room_types ON room_types.ID = bookings.room_type_id 
			WHERE accommodations.post_status = 'publish' AND (room_types.post_status IS NULL OR room_types.post_status = 'publish') 
			AND bookings.Id = $booking_id ";

	return $wpdb->get_row($sql);
}

function list_accommodation_bookings($paged = null, $per_page = 0, $orderby = 'Id', $order = 'ASC', $search_term = null, $user_id = 0) {
	global $wpdb, $byt_multi_language_count;

	$table_name = BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE;
	$sql = "SELECT DISTINCT bookings.*, accommodations.post_title accommodation_name, room_types.post_title room_type
			FROM " . $table_name . " bookings 
			INNER JOIN $wpdb->posts accommodations ON accommodations.ID = bookings.accommodation_id ";
			
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_accommodation' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = accommodations.ID ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_accommodation' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
	}
			
	$sql .= " LEFT JOIN $wpdb->posts room_types ON room_types.ID = bookings.room_type_id ";
	
	$sql .= " WHERE accommodations.post_status = 'publish' AND (room_types.post_status IS NULL OR room_types.post_status = 'publish') ";
	
	
	if ($user_id > 0) {
		$sql .= $wpdb->prepare(" AND bookings.user_id = %d ", $user_id) ;
	}
	
	if ($search_term != null && !empty($search_term)) {
		$search_term = "%" . $search_term . "%";
		$sql .= $wpdb->prepare(" AND 1=1 AND (bookings.first_name LIKE '%s' OR bookings.last_name LIKE '%s') ", $search_term, $search_term);
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

function create_accommodation_booking($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $date_from, $date_to, $accommodation_id, $room_type_id, $user_id, $is_self_catered, $total_price, $adults, $children) {

	global $wpdb;
	
	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	
	$errors = array();

	$sql = "INSERT INTO " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
			(first_name, last_name, email, phone, address, town, zip, country, special_requirements, room_count, user_id, total_price, adults, children, date_from, date_to, accommodation_id, room_type_id)
			VALUES 
			(%s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d, %d, %s, %s, %d, %d);";

	$result = $wpdb->query($wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $user_id, $total_price, $adults, $children, $date_from, $date_to, $accommodation_id, $room_type_id));

	if (is_wp_error($result))
		$errors[] = $result;

	$booking_id = $wpdb->insert_id;
	
	if (count($errors) > 0)
		return $errors;
	return $booking_id;
}

function update_accommodation_booking($booking_id, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $date_from, $date_to, $accommodation_id, $room_type_id, $user_id, $is_self_catered, $total_price, $adults, $children) {

	global $wpdb;
	
	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 

	$sql = "UPDATE " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
			SET
				first_name = %s, 
				last_name = %s, 
				email = %s, 
				phone = %s, 
				address = %s, 
				town = %s, 
				zip = %s, 
				country = %s, 
				special_requirements = %s, 
				room_count = %d, 
				user_id = %d, 
				total_price = %f, 
				adults = %d, 
				children = %d, 
				date_from = %s, 
				date_to = %s, 
				accommodation_id = %d, 
				room_type_id = %d
			WHERE Id = %d;";

	$result = $wpdb->query($wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $user_id, $total_price, $adults, $children, $date_from, $date_to, $accommodation_id, $room_type_id, $booking_id));

	return $result;
}

function create_accommodation_vacancy($start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child) {

	global $wpdb;
	
	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	
	$sql = "INSERT INTO " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . "
			(start_date, end_date, accommodation_id, room_type_id, room_count, price_per_day, price_per_day_child)
			VALUES
			(%s, %s, %d, %d, %d, %f, %f);";
	
	$wpdb->query($wpdb->prepare($sql, $start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child));	
	return $wpdb->insert_id;
}

function update_accommodation_vacancy($vacancy_id, $start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child) {

	global $wpdb;
	
	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	
	$sql = "UPDATE " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . "
			SET start_date=%s, end_date=%s, accommodation_id=%d, room_type_id=%d, room_count=%d, price_per_day=%f, price_per_day_child=%f
			WHERE Id=%d";
	
	$wpdb->query($wpdb->prepare($sql, $start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child, $vacancy_id));	
}

function delete_accommodation_vacancy($vacancy_id) {
	
	global $wpdb;
	
	$sql = "DELETE FROM " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . "
			WHERE Id = %d";
	
	$wpdb->query($wpdb->prepare($sql, $vacancy_id));
	
}

function delete_accommodation_booking($booking_id) {
	global $wpdb;
	
	$sql = "DELETE FROM " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
			WHERE Id = %d";
	
	$wpdb->query($wpdb->prepare($sql, $booking_id));	
}