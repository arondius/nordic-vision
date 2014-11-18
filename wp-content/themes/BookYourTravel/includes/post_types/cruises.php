<?php

global $wpdb, $byt_multi_language_count;

function bookyourtravel_register_cruise_post_type() {
	
	$cruises_permalink_slug = of_get_option('cruises_permalink_slug', 'cruises');
	
	$labels = array(
		'name'                => _x( 'Cruises', 'Post Type General Name', 'bookyourtravel' ),
		'singular_name'       => _x( 'Cruise', 'Post Type Singular Name', 'bookyourtravel' ),
		'menu_name'           => __( 'Cruises', 'bookyourtravel' ),
		'all_items'           => __( 'All Cruises', 'bookyourtravel' ),
		'view_item'           => __( 'View Cruise', 'bookyourtravel' ),
		'add_new_item'        => __( 'Add New Cruise', 'bookyourtravel' ),
		'add_new'             => __( 'New Cruise', 'bookyourtravel' ),
		'edit_item'           => __( 'Edit Cruise', 'bookyourtravel' ),
		'update_item'         => __( 'Update Cruise', 'bookyourtravel' ),
		'search_items'        => __( 'Search Cruises', 'bookyourtravel' ),
		'not_found'           => __( 'No Cruises found', 'bookyourtravel' ),
		'not_found_in_trash'  => __( 'No Cruises found in Trash', 'bookyourtravel' ),
	);
	$args = array(
		'label'               => __( 'cruise', 'bookyourtravel' ),
		'description'         => __( 'Cruise information pages', 'bookyourtravel' ),
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
 		'rewrite' => array('slug' => $cruises_permalink_slug),
	);
	register_post_type( 'cruise', $args );	
}

function bookyourtravel_register_cruise_type_taxonomy(){
	$labels = array(
			'name'              => _x( 'Cruise types', 'taxonomy general name', 'bookyourtravel' ),
			'singular_name'     => _x( 'Cruise type', 'taxonomy singular name', 'bookyourtravel' ),
			'search_items'      => __( 'Search Cruise types', 'bookyourtravel' ),
			'all_items'         => __( 'All Cruise types', 'bookyourtravel' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'         => __( 'Edit Cruise type', 'bookyourtravel' ),
			'update_item'       => __( 'Update Cruise type', 'bookyourtravel' ),
			'add_new_item'      => __( 'Add New Cruise type', 'bookyourtravel' ),
			'new_item_name'     => __( 'New Cruise Type Name', 'bookyourtravel' ),
			'separate_items_with_commas' => __( 'Separate Cruise types with commas', 'bookyourtravel' ),
			'add_or_remove_items'        => __( 'Add or remove Cruise types', 'bookyourtravel' ),
			'choose_from_most_used'      => __( 'Choose from the most used Cruise types', 'bookyourtravel' ),
			'not_found'                  => __( 'No Cruise types found.', 'bookyourtravel' ),
			'menu_name'         => __( 'Cruise types', 'bookyourtravel' ),
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
	
	$enable_cruises = of_get_option('enable_cruises', 1);

	if ($enable_cruises) {
		register_taxonomy( 'cruise_type', 'cruise', $args );
	}
}

function bookyourtravel_register_cabin_type_post_type() {
	
	$labels = array(
		'name'                => _x( 'Cabin types', 'Post Type General Name', 'bookyourtravel' ),
		'singular_name'       => _x( 'Cabin type', 'Post Type Singular Name', 'bookyourtravel' ),
		'menu_name'           => __( 'Cabin types', 'bookyourtravel' ),
		'all_items'           => __( 'Cabin types', 'bookyourtravel' ),
		'view_item'           => __( 'View Cabin type', 'bookyourtravel' ),
		'add_new_item'        => __( 'Add New Cabin type', 'bookyourtravel' ),
		'add_new'             => __( 'New Cabin type', 'bookyourtravel' ),
		'edit_item'           => __( 'Edit Cabin type', 'bookyourtravel' ),
		'update_item'         => __( 'Update Cabin type', 'bookyourtravel' ),
		'search_items'        => __( 'Search Cabin types', 'bookyourtravel' ),
		'not_found'           => __( 'No Cabin types found', 'bookyourtravel' ),
		'not_found_in_trash'  => __( 'No Cabin types found in Trash', 'bookyourtravel' ),
	);
	$args = array(
		'label'               => __( 'cabin type', 'bookyourtravel' ),
		'description'         => __( 'Cabin type information pages', 'bookyourtravel' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'author' ),
		'taxonomies'          => array( ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => 'edit.php?post_type=cruise',
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
	register_post_type( 'cabin_type', $args );	
}

function bookyourtravel_create_cruise_extra_tables($installed_version) {

	if ($installed_version != BOOKYOURTRAVEL_VERSION) {
		global $wpdb;
		
		$table_name = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
		$sql = "CREATE TABLE " . $table_name . " (
					Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					start_date datetime NOT NULL,
					duration_days int NOT NULL DEFAULT 0,
					end_date datetime NULL,
					cruise_id bigint(20) unsigned NOT NULL,
					cabin_type_id bigint(20) unsigned NOT NULL DEFAULT '0',
					cabin_count int(11) NOT NULL,
					price decimal(16,2) NOT NULL,
					price_child decimal(16,2) NOT NULL,
					created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY  (Id)
				);";
				
		// we do not execute sql directly
		// we are calling dbDelta which cant migrate database
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
		global $EZSQL_ERROR;
		$EZSQL_ERROR = array();
		
		$table_name = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
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
					adults int(11) NOT NULL DEFAULT '0',
					children int(11) NOT NULL DEFAULT '0',
					cruise_schedule_id bigint(20) NOT NULL,
					cruise_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					user_id bigint(20) unsigned DEFAULT NULL,
					total_price_adults decimal(16, 2) NOT NULL,
					total_price_children decimal(16, 2) NOT NULL,
					total_price decimal(16, 2) NOT NULL,
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

function list_cruises($paged = 0, $per_page = 0, $orderby = '', $order = '', $cruise_types_array = array(), $featured_only = false, $search_args = array()) {

	global $wpdb, $byt_multi_language_count;
	
	$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
	
	$select_sql = " SELECT DISTINCT	cruises.* ";
	$join_sql = " ";
	$where_sql = " WHERE 1=1 ";
	$having_sql = " HAVING 1=1 ";
	
	$cruise_ids = array();
	
	if ( count($search_args) > 0) {

		if ( isset($search_args['keyword'])&& strlen($search_args['keyword']) > 0  ) {
			$search_string = "%" . $search_args['keyword'] . "%";
			$search_sql = $wpdb->prepare("SELECT ID from $wpdb->posts WHERE (LOWER(post_title) LIKE '%s' OR LOWER(post_content) LIKE '%s') AND post_type='cruise' AND post_status='publish'", strtolower($search_string), strtolower($search_string));
			$temp_cruise_ids = $wpdb->get_col($search_sql);
			foreach ($temp_cruise_ids as $temp_cruise_id)  {
				$cruise_ids[] = $temp_cruise_id;
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
				INNER JOIN $table_name_schedule schedule ON bookings.cruise_schedule_id = schedule.Id 
				WHERE ";
		
			if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
				$select_sql .= " translations_default.element_id = schedule.cruise_id ";
			} else {
				$select_sql .= " cruises.ID = schedule.cruise_id ";
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
				$select_sql .= " cruise_id=translations_default.element_id ";
			} else {
				$select_sql .= " cruise_id=cruises.ID ";
			}
		
			$select_sql .= ") price ";
			
			$guests = (isset($search_args['guests']) && isset($search_args['guests'])) ? intval($search_args['guests']) : 0;
			
		}
		
	}

	$cruise_ids_string = implode(', ', $cruise_ids);
	
	$select_sql .= " FROM $wpdb->posts cruises ";
			
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$join_sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_cruise' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = cruises.ID ";
		$join_sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_cruise' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
	}
	
	if ($featured_only) {
		$join_sql .= " LEFT JOIN $wpdb->postmeta cruise_featured_meta ON (cruises.ID = cruise_featured_meta.post_id) ";
	}
	
	if (!empty($cruise_types_array)) {	
		$cruise_types_string = implode(",",$cruise_types_array);		
		$join_sql .= " LEFT JOIN $wpdb->term_relationships ON (cruises.ID = $wpdb->term_relationships.object_id) ";
		$join_sql .= " LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id) ";		
		$where_sql .= " AND $wpdb->term_taxonomy.taxonomy = 'cruise_type' AND $wpdb->term_taxonomy.term_id IN ($cruise_types_string) ";
	}

	$where_sql .= " AND cruises.post_type = 'cruise' AND cruises.post_status = 'publish' ";
	
	if (!empty($cruise_ids_string)) {
		$where_sql .= " AND (cruises.ID IN ($cruise_ids_string)) ";
	}

	if ( count($search_args) > 0) {
		if ( isset($search_args['keyword'])&& strlen($search_args['keyword']) > 0  ) {
			$search_string = "%" . $search_args['keyword'] . "%";
			$where_sql .= sprintf(" AND (LOWER(cruises.post_title) LIKE '%s' OR LOWER(cruises.post_content) LIKE '%s') ", strtolower($search_string), strtolower($search_string));
		}
	}
	
	if ($featured_only) {
		$where_sql .= " AND cruise_featured_meta.meta_key = 'cruise_is_featured' AND CAST(cruise_featured_meta.meta_value AS CHAR) = '1' ";
	}
	
	$group_by_sql = " GROUP BY cruises.ID ";
	
	$order_by_sql = '';
	if ( !empty( $orderby ) && !empty( $order ) ){ 
		$order_by_sql .= ' ORDER BY ' . $orderby . ' ' . $order; 
	} else {
		$order_by_sql .= ' ORDER BY cruises.post_date DESC ';
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

function list_cabin_types() {

	$args = array(
	   'post_type' => 'cabin_type',
	   'post_status' => 'publish',
	   'posts_per_page' => -1,
	   'suppress_filters' => 0
	);
	$query = new WP_Query($args);

	return $query;
}

function list_available_cruise_schedule_entries($cruise_id, $cabin_type_id, $from_date, $from_year, $from_month, $cruise_type_is_repeated, $cruise_type_day_of_week) {

	global $wpdb;

	$cruise_id = get_default_language_post_id($cruise_id, 'cruise');
	if ($cabin_type_id > 0)
		$cabin_type_id = get_default_language_post_id($cabin_type_id, 'cabin_type');
	
	$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
	
	$yesterday = date('Y-m-d',strtotime("-1 days"));

	if ($cruise_type_is_repeated == 0) {
		// oneoff cruises, must have start date in future in order for people to attend
		$sql = "
			SELECT schedule.*, schedule.start_date cruise_date, 0 numm
			FROM $table_name_schedule schedule 
			WHERE cruise_id=%d AND cabin_type_id=%d AND start_date >= %s ";
			
		$sql = $wpdb->prepare($sql, $cruise_id, $cabin_type_id, $from_date);
	} else if ($cruise_type_is_repeated == 1) {		
		// daily cruises
		
		$sql = $wpdb->prepare("
			SELECT schedule.*, date_range.single_date cruise_date, num
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
			WHERE cruise_id=%d AND cabin_type_id=%d AND ( ( schedule.end_date IS NULL OR schedule.end_date = '0000-00-00 00:00:00' ) OR date_range.single_date < schedule.end_date )
			HAVING schedule.cabin_count > (SELECT COUNT(*) ct FROM $table_name_bookings bookings WHERE bookings.cruise_schedule_id = schedule.Id AND bookings.cruise_date = date_range.single_date) ";
		
		$sql = $wpdb->prepare($sql, $from_year, $from_month, $from_date, $cruise_id, $cabin_type_id);

	} else if ($cruise_type_is_repeated == 2) {
	
		// weekday cruises
		$sql = $wpdb->prepare("
			SELECT schedule.*, date_range.single_date cruise_date, num
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
			WHERE cruise_id=%d AND cabin_type_id=%d AND ( ( schedule.end_date IS NULL OR schedule.end_date = '0000-00-00 00:00:00' ) OR date_range.single_date < schedule.end_date )	
			HAVING schedule.cabin_count > (SELECT COUNT(*) ct FROM $table_name_bookings bookings WHERE bookings.cruise_schedule_id = schedule.Id AND bookings.cruise_date = date_range.single_date) ";
		
		$sql = $wpdb->prepare($sql, $from_year, $from_month, $from_date, $cruise_id, $cabin_type_id);
	} else if ($cruise_type_is_repeated == 3) {
		
		// weekly cruises
		$sql = $wpdb->prepare("
			SELECT schedule.*, date_range.single_date cruise_date, num
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
			WHERE cruise_id=%d AND cabin_type_id=%d AND ( ( schedule.end_date IS NULL OR schedule.end_date = '0000-00-00 00:00:00' ) OR date_range.single_date < schedule.end_date ) 			
			HAVING schedule.cabin_count > (SELECT COUNT(*) ct FROM $table_name_bookings bookings WHERE bookings.cruise_schedule_id = schedule.Id AND bookings.cruise_date = date_range.single_date) ";
		
		$sql = $wpdb->prepare($sql, $cruise_type_day_of_week, $from_year, $from_month, $from_date, $cruise_id, $cabin_type_id);		
	}

	return $wpdb->get_results($sql);
}

function get_cruise_booking($booking_id) {

	global $wpdb, $byt_multi_language_count;

	$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
	
	$sql = "SELECT 	DISTINCT bookings.*, 
					cruises.post_title cruise_name, 
					cabin_types.post_title cabin_type, 
					schedule.duration_days,
					bookings.total_price,
					schedule.cruise_id,
					schedule.cabin_type_id
			FROM $table_name_bookings bookings 
			INNER JOIN $table_name_schedule schedule ON schedule.Id = bookings.cruise_schedule_id
			INNER JOIN $wpdb->posts cruises ON cruises.ID = schedule.cruise_id 
			INNER JOIN $wpdb->posts cabin_types ON cabin_types.ID = schedule.cabin_type_id ";
			
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_cruise' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = cruises.ID ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_cruise' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations2 ON translations2.element_type = 'post_cabin_type' AND translations2.language_code='" . ICL_LANGUAGE_CODE . "' AND translations2.element_id = cabin_types.ID ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default2 ON translations_default2.element_type = 'post_cabin_type' AND translations_default2.language_code='" . get_default_language() . "' AND translations_default2.trid = translations2.trid ";
	}

	$sql .= " WHERE cruises.post_status = 'publish' AND bookings.Id = %d ";

	return $wpdb->get_row($wpdb->prepare($sql, $booking_id));
}

function create_cruise_booking($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $cruise_schedule_id, $user_id, $total_price_adults, $total_price_children, $total_price, $start_date) {

	global $wpdb;
	
	$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;

	$sql = "INSERT INTO $table_name_bookings
			(first_name, last_name, email, phone, address, town, zip, country, special_requirements, adults, children, cruise_schedule_id, user_id, total_price_adults, total_price_children, total_price, cruise_date)
			VALUES 
			(%s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d, %f, %f, %f, %s);";
	$sql = $wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $cruise_schedule_id, $user_id, (float)$total_price_adults, (float)$total_price_children, (float)$total_price, $start_date);
	
	$wpdb->query($sql);
	
	return $wpdb->insert_id;
}

function update_cruise_booking($booking_id, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $cruise_schedule_id, $user_id, $total_price_adults, $total_price_children, $total_price, $start_date) {
	
	global $wpdb;
	
	$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;

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
				cruise_schedule_id = %d, 
				user_id = %d, 
				total_price_adults = %f, 
				total_price_children = %f, 
				total_price = %f, 
				cruise_date = %s
			WHERE Id=%d";
			
	$sql = $wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $cruise_schedule_id, $user_id, (float)$total_price_adults, (float)$total_price_children, (float)$total_price, $start_date, $booking_id);
	
	$wpdb->query($sql);
}

function delete_cruise_booking($booking_id) {

	global $wpdb;
	
	$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
	
	$sql = "DELETE FROM $table_name_bookings
			WHERE Id = %d";
	
	$wpdb->query($wpdb->prepare($sql, $booking_id));
}

function list_cruise_bookings($paged = null, $per_page = 0, $orderby = 'Id', $order = 'ASC', $search_term = null ) {

	global $wpdb, $byt_multi_language_count;
	
	$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;

	$sql = "SELECT 	DISTINCT bookings.*, 
					cruises.post_title cruise_name, 
					cabin_types.post_title cabin_type, 
					schedule.duration_days,
					bookings.total_price,
					schedule.cruise_id,
					schedule.cabin_type_id
			FROM $table_name_bookings bookings 
			INNER JOIN $table_name_schedule schedule ON schedule.Id = bookings.cruise_schedule_id
			INNER JOIN $wpdb->posts cruises ON cruises.ID = schedule.cruise_id 
			INNER JOIN $wpdb->posts cabin_types ON cabin_types.ID = schedule.cabin_type_id ";
			
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_cruise' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = cruises.ID ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_cruise' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations2 ON translations2.element_type = 'post_cabin_type' AND translations2.language_code='" . ICL_LANGUAGE_CODE . "' AND translations2.element_id = cabin_types.ID ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default2 ON translations_default2.element_type = 'post_cabin_type' AND translations_default2.language_code='" . get_default_language() . "' AND translations_default2.trid = translations2.trid ";
	}
	
	$sql .= " WHERE cruises.post_status = 'publish' ";
	
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

function create_cruise_schedule($cruise_id, $cabin_type_id, $cabin_count, $start_date, $duration_days, $price, $price_child, $end_date) {

	global $wpdb;
	
	$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
	
	$cruise_id = get_default_language_post_id($cruise_id, 'cruise');
	$cabin_type_id = get_default_language_post_id($cabin_type_id, 'cabin_type');
	
	if ($end_date == null) {
		$sql = "INSERT INTO $table_name_schedule
				(cruise_id, cabin_type_id, cabin_count, start_date, duration_days, price, price_child)
				VALUES
				(%d, %d, %d, %s, %d, %f, %f);";
		$sql = $wpdb->prepare($sql, $cruise_id, $cabin_type_id, $cabin_count, $start_date, $duration_days, $price, $price_child);
	} else {
		$sql = "INSERT INTO $table_name_schedule
				(cruise_id, cabin_type_id, cabin_count, start_date, duration_days, price, price_child, end_date)
				VALUES
				(%d, %d, %d, %s, %d, %f, %f, %s);";
		$sql = $wpdb->prepare($sql, $cruise_id, $cabin_type_id, $cabin_count, $start_date, $duration_days, $price, $price_child, $end_date);
	}
	
	$wpdb->query($sql);
}

function update_cruise_schedule($schedule_id, $cruise_id, $cabin_type_id, $cabin_count, $start_date, $duration_days, $price, $price_child, $end_date) {

	global $wpdb;
	
	$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
	
	$cruise_id = get_default_language_post_id($cruise_id, 'cruise');
	$cabin_type_id = get_default_language_post_id($cabin_type_id, 'cabin_type');

	if ($end_date == null) {
		$sql = "UPDATE " . BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE . "
				SET cruise_id=%d, cabin_type_id=%d, cabin_count=%d, start_date=%s, duration_days=%d, price=%f, price_child=%f
				WHERE Id=%d";
		$sql = $wpdb->prepare($sql, $cruise_id, $cabin_type_id, $cabin_count, $start_date, $duration_days, $price, $price_child, $schedule_id);
	} else {
		$sql = "UPDATE " . BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE . "
				SET cruise_id=%d, cabin_type_id=%d, cabin_count=%d, start_date=%s, duration_days=%d, price=%f, price_child=%f, end_date=%s
				WHERE Id=%d";
		$sql = $wpdb->prepare($sql, $cruise_id, $cabin_type_id, $cabin_count, $start_date, $duration_days, $price, $price_child, $end_date, $schedule_id);
	}
	
	$wpdb->query($sql);	
}

function delete_cruise_schedule($schedule_id) {

	global $wpdb;
	
	$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
	
	$sql = "DELETE FROM $table_name_schedule
			WHERE Id = %d";
	
	$wpdb->query($wpdb->prepare($sql, $schedule_id));	
}

function get_cruise_schedule($cruise_schedule_id) {

	global $wpdb;
		
	$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
		
	$sql = "SELECT 	schedule.*, 
					cruises.post_title cruise_name, 
					cabin_types.post_title cabin_type,
					(
						SELECT COUNT(*) ct 
						FROM $table_name_bookings bookings 
						WHERE bookings.cruise_schedule_id = schedule.Id 
					) has_bookings,
					IFNULL(cruise_price_meta.meta_value, 0) cruise_is_price_per_person
			FROM $table_name_schedule schedule 
			INNER JOIN $wpdb->posts cruises ON cruises.ID = schedule.cruise_id 
			INNER JOIN $wpdb->posts cabin_types ON cabin_types.ID = schedule.cabin_type_id 
			LEFT JOIN $wpdb->postmeta cruise_price_meta ON cruises.ID = cruise_price_meta.post_id AND cruise_price_meta.meta_key = 'cruise_is_price_per_person'
			WHERE schedule.Id=%d AND cruises.post_status = 'publish' AND cabin_types.post_status = 'publish'  ";
	
	$sql = $wpdb->prepare($sql, $cruise_schedule_id);
	return $wpdb->get_row($sql);
}

function delete_all_cruise_schedules() {

	global $wpdb;
	$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
	$sql = "DELETE FROM $table_name_schedule";
	$wpdb->query($sql);	
}

function list_cruise_schedules ($paged = null, $per_page = 0, $orderby = 'Id', $order = 'ASC', $day = 0, $month = 0, $year = 0, $cruise_id = 0, $cabin_type_id=0, $search_term = '') {

	global $wpdb;
	
	$cruise_id = get_default_language_post_id($cruise_id, 'cruise');
	$cabin_type_id = get_default_language_post_id($cabin_type_id, 'cabin_type');
	
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

	$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
	
	$sql = "SELECT 	schedule.*, 
					cruises.post_title cruise_name, 
					cabin_types.post_title cabin_type,
					(
						SELECT COUNT(*) ct 
						FROM $table_name_bookings bookings 
						WHERE bookings.cruise_schedule_id = schedule.Id 
					) has_bookings,
					IFNULL(cruise_price_meta.meta_value, 0) cruise_is_price_per_person
			FROM $table_name_schedule schedule 
			INNER JOIN $wpdb->posts cruises ON cruises.ID = schedule.cruise_id 
			INNER JOIN $wpdb->posts cabin_types ON cabin_types.ID = schedule.cabin_type_id 
			LEFT JOIN $wpdb->postmeta cruise_price_meta ON cruises.ID = cruise_price_meta.post_id AND cruise_price_meta.meta_key = 'cruise_is_price_per_person'
			WHERE cruises.post_status = 'publish' AND cabin_types.post_status = 'publish' ";
			
	if ($cruise_id > 0) {
		$sql .= $wpdb->prepare(" AND schedule.cruise_id=%d ", $cruise_id);
	}
	
	if ($cabin_type_id > 0) {
		$sql .= $wpdb->prepare(" AND schedule.cabin_type_id=%d ", $cabin_type_id);
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

function get_cruise_schedule_price($schedule_id, $is_child_price) {

	global $wpdb;
	
	$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;

	$sql = "SELECT " . ($is_child_price ? "schedule.price_child" : "schedule.price") . "
			FROM $table_name_schedule schedule 
			WHERE id=%d ";	
			
	$price = $wpdb->get_var($wpdb->prepare($sql, $schedule_id));
	
	global $current_currency, $default_currency;
	if ($current_currency && $current_currency != $default_currency)
		$price = currency_conversion($price, $default_currency, $current_currency);
	
	return $price;
}

function get_cruise_available_schedule_id($cruise_id, $cabin_type_id, $date) {

	global $wpdb;
	
	$cruise_obj = new byt_cruise(intval($cruise_id));
	$cruise_id = $cruise_obj->get_base_id();

	$cabin_type_obj = new byt_cabin_type(intval($cabin_type_id));
	$cabin_type_id = $cabin_type_obj->get_base_id();
	
	$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;

	$sql = "SELECT MIN(id) schedule_id
			FROM $table_name_schedule schedule 
			WHERE cruise_id=%d AND cabin_type_id=%d
			";	
			
	if ($cruise_obj->get_type_is_repeated() == 0) {
		$sql .= " AND schedule.start_date = %s ";
	}	

	$schedule_id = $wpdb->get_var($wpdb->prepare($sql, $cruise_id, $cabin_type_id, $date, $date));
	
	return $schedule_id;
}

function get_cruise_min_price($cruise_id, $cabin_type_id, $date) {

	global $wpdb;
	
	$cruise_obj = new byt_cruise(intval($cruise_id));
	$cruise_id = $cruise_obj->get_base_id();

	if ($cabin_type_id > 0) {
		$cabin_type_obj = new byt_cabin_type(intval($cabin_type_id));
		$cabin_type_id = $cabin_type_obj->get_base_id();
	}
	
	$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;

	$sql = "SELECT MIN(schedule.price) 
			FROM $table_name_schedule schedule 
			WHERE cruise_id=%d ";	
			
	if ($cabin_type_id > 0) 
		$sql .= $wpdb->prepare(" AND cabin_type_id=%d ", $cabin_type_id);
			
	if ($cruise_obj->get_type_is_repeated() == 0) {
		// this cruise is a one off and is not repeated. If start date is missed, person cannot participate.
		$sql .= $wpdb->prepare(" AND start_date > %s ", $date);
	} else {
		// daily, weekly, weekdays cruises are recurring which means start date is important only in the sense that cruise needs to have become valid before we can get min price.
	}

	$sql = $wpdb->prepare($sql, $cruise_id);

	$min_price = $wpdb->get_var($sql);
	
	global $current_currency, $default_currency;
	if ($current_currency && $current_currency != $default_currency)
		$min_price = currency_conversion($min_price, $default_currency, $current_currency);
	
	return $min_price;
}	

