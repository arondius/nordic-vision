<?php

global $wpdb, $byt_multi_language_count;

function bookyourtravel_register_location_post_type() {
	
	$locations_permalink_slug = of_get_option('locations_permalink_slug', 'locations');
	
	$labels = array(
		'name'                => _x( 'Locations', 'Post Type General Name', 'bookyourtravel' ),
		'singular_name'       => _x( 'Location', 'Post Type Singular Name', 'bookyourtravel' ),
		'menu_name'           => __( 'Locations', 'bookyourtravel' ),
		'all_items'           => __( 'All Locations', 'bookyourtravel' ),
		'view_item'           => __( 'View Location', 'bookyourtravel' ),
		'add_new_item'        => __( 'Add New Location', 'bookyourtravel' ),
		'add_new'             => __( 'New Location', 'bookyourtravel' ),
		'edit_item'           => __( 'Edit Location', 'bookyourtravel' ),
		'update_item'         => __( 'Update Location', 'bookyourtravel' ),
		'search_items'        => __( 'Search locations', 'bookyourtravel' ),
		'not_found'           => __( 'No locations found', 'bookyourtravel' ),
		'not_found_in_trash'  => __( 'No locations found in Trash', 'bookyourtravel' ),
	);
	$args = array(
		'label'               => __( 'location', 'bookyourtravel' ),
		'description'         => __( 'Location information pages', 'bookyourtravel' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'author', 'page-attributes' ),
		'taxonomies'          => array( ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
		'rewrite' => array('slug' => $locations_permalink_slug)
	);
	register_post_type( 'location', $args );
	
}
	
function list_locations($location_id = 0, $paged = 0, $per_page = 0, $orderby = '', $order = '', $featured_only = false) {

	global $wpdb, $byt_multi_language_count;
	
	$location_id = get_default_language_post_id($location_id, 'location');
	
	$location_args =  array( 'post_type' => 'location', 'posts_per_page' => -1, 'post_status' => 'publish' );
	$location_ids = array();
	
	$location_ids_string = '';
	if ($location_id > 0) {
		$location_children = get_posts_children($location_id, $location_args);
		if ($location_id)
			$location_ids = array($location_id);
		foreach ($location_children as $location) {
			$location_ids[] = $location->ID;
		}
		$location_ids_string = implode(', ', $location_ids);
	}

	$sql = "SELECT locations.*
			FROM $wpdb->posts locations ";
			
	if (!empty($location_ids_string)) {		
		$sql .= " INNER JOIN $wpdb->postmeta location_meta ON (locations.ID = location_meta.post_id) ";
	}
		
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_location' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = locations.ID ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_location' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
	}
	
	if ($featured_only) {
		$sql .= " LEFT JOIN $wpdb->postmeta location_featured_meta ON (locations.ID = location_featured_meta.post_id) ";
	}

	$sql .= " WHERE locations.post_type = 'location' AND locations.post_status = 'publish' ";
	
	if (!empty($location_ids_string)) {
		$sql .= " AND (location_meta.meta_key = 'location_location_post_id' AND CAST(location_meta.meta_value AS UNSIGNED) IN ($location_ids_string)) ";
	}

	if ($featured_only) {
		$sql .= " AND location_featured_meta.meta_key = 'location_is_featured' AND CAST(location_featured_meta.meta_value AS CHAR) = '1' ";
	}
			
	$sql .= " GROUP BY locations.ID ";
	
	if(!empty($orderby) & !empty($order)){ 
		$sql .= ' ORDER BY ' . $orderby . ' ' . $order; 
	} else {
		$sql .= ' ORDER BY locations.post_date DESC ';
	}
	
	if(!empty($paged) && !empty($per_page)){
		$offset=($paged-1)*$per_page;
		$sql .=' LIMIT '.(int)$offset.','.(int)$per_page;
	}
	
	return $wpdb->get_results($sql);
}
