<?php/** * 	Function that renders labeled field in the form of * 	<div class="container_css_class"> *		<span class="label_css_class">$label_text</span> $field_value *	</div> */function byt_render_field($container_css_class, $label_css_class, $label_text, $field_value, $header_text = '', $paragraph = false, $hide_empty = false, $container_is_tr = false) {	$render = !empty($field_value) || (!empty($label_text) && !$hide_empty);		if ($render) {		$ret_val = '';			if (!empty($header_text) && !$container_is_tr)			$ret_val = sprintf("<h1>%s</h1>", $header_text);				if (!empty($container_css_class)) {			if ($container_is_tr)				$ret_val .= sprintf("<tr class='%s'>", $container_css_class);			else				$ret_val .= sprintf("<div class='%s'>", $container_css_class);		}					if ($paragraph && !$container_is_tr)			$ret_val .= '<p>';		if (!empty($label_text) || !empty($label_css_class)) {			if ($container_is_tr)				$ret_val .= sprintf("<th class='%s'>%s</th>", $label_css_class, $label_text);			else 				$ret_val .= sprintf("<span class='%s'>%s</span>", $label_css_class, $label_text);		}		if (!empty($field_value)) {			if ($container_is_tr)				$ret_val .= sprintf('<td>%s</td>', $field_value);			else				$ret_val .= $field_value;		} else {			if ($container_is_tr)				$ret_val .= '<td></td>';		}				if ($paragraph && !$container_is_tr)			$ret_val .= '</p>';					if (!empty($container_css_class)) {			if ($container_is_tr)				$ret_val .= '</tr>';			else				$ret_val .= '</div>';		}		$ret_val = apply_filters('byt_render_field', $ret_val, $container_css_class, $label_css_class, $label_text, $field_value, $header_text, $paragraph);		echo $ret_val;	}}/** * 	Function that renders all extra fields tied to an entity tab, as labeled field in the form of * 	<div class="container_css_class"> *		<span class="label_css_class">$label_text</span> $field_value *	</div> */function byt_render_tab_extra_fields($extra_fields, $tab_id, $entity_obj, $container_class = "text-wrap", $label_is_header = true, $id_is_css_class = false, $container_is_tr = false) {		$extra_fields = byt_array_search($extra_fields, 'tab_id', $tab_id); 		if (is_array($extra_fields)) {			foreach ($extra_fields as $extra_field) {				$field_is_hidden = isset($extra_field['hide']) ? intval($extra_field['hide']) : 0;						if (!$field_is_hidden) {							$field_id = isset($extra_field['id']) ? $extra_field['id'] : '';				$field_label = isset($extra_field['label']) ? $extra_field['label'] : '';				$field_type = isset($extra_field['type']) ? $extra_field['type'] : ''; 								if ($field_type == 'text' ||$field_type == 'textarea') {					if (!empty($field_id) && !empty($field_label)) {						if ($id_is_css_class)							$container_class = $field_id;						if ($label_is_header)							byt_render_field($container_class, 	"", "", $entity_obj->get_custom_field($field_id), $field_label, false, true, $container_is_tr);						else							byt_render_field($container_class, 	"", $field_label, $entity_obj->get_custom_field($field_id), "", false, true, $container_is_tr);					}				} elseif ($field_type == 'image') {					$field_image_uri = $entity_obj->get_custom_field_image_uri($field_id, 'medium');					echo '<img src="' . $field_image_uri . '" alt="' . $field_label . '" />';				}			}		}	}}/** * Function that either renders or echos image tag in the form of * <img class="image_css_class" id="$image_id" src="$image_src" title="$image_title" alt="$image_alt" /> */function byt_render_image($image_css_class, $image_id, $image_src, $image_title, $image_alt, $echo = true) {	if ( !empty( $image_src) ) {		$ret_val = sprintf("<img class='%s' id='%s' src='%s' title='%s' alt='%s' />", $image_css_class, $image_id, $image_src, $image_title, $image_alt);		$ret_val = apply_filters('byt_render_image', $ret_val, $image_css_class, $image_id, $image_src, $image_title, $image_alt);		if ($echo)			echo $ret_val;		else			return $ret_val;	}	return "";}/** * Function that renders tab item in the form of * <li class="item_css_class" id="$item_id">$item_content</li> */function byt_render_tab($page_post_type, $item_css_class, $item_id, $item_content) {	$ret_val = sprintf("<li class='%s' id='%s'>%s</li>", $item_css_class, $item_id, $item_content);	$ret_val = apply_filters('byt_render_tab', $ret_val, $page_post_type, $item_css_class, $item_id, $item_content);	echo $ret_val;}/** * Function that renders link button in the form of * <a href="$href" class="$link_css_class" id="$link_id" title="$text">$text</a> */function byt_render_link_button($href, $link_css_class, $link_id, $text)  {	$ret_val = sprintf("<a href='%s' class='%s' ", $href, $link_css_class);	if (!empty($link_id))		$ret_val .= sprintf(" id='%s' ", $link_id);	$ret_val .= sprintf(" title='%s'>%s</a>", $text, $text);		$ret_val = apply_filters('byt_render_link_button', $ret_val, $href, $link_css_class, $link_id, $text);	echo $ret_val;}/** * Function that renders submit button in the form of * <input type="submit" value="$text" id="$submit_id" name="$submit_id" class="$submit_css_class" /> */function byt_render_submit_button($submit_css_class, $submit_id, $text)  {	$ret_val = sprintf("<input type='submit' class='%s' id='%s' name='%s' value='%s' />", $submit_css_class, $submit_id, $submit_id, $text);	$ret_val = apply_filters('byt_render_link_button', $ret_val, $submit_css_class, $submit_id, $submit_id, $text);	echo $ret_val;}/** * Remove password email text if option for users to set their own password is enabled in Theme settings. */function bookyourtravel_remove_password_email_text ( $text ) {	$let_users_set_pass = of_get_option('let_users_set_pass', 0);	if ($text == 'A password will be e-mailed to you.' && $let_users_set_pass)		$text = '';	return $text;}add_filter( 'gettext', 'bookyourtravel_remove_password_email_text' );function accommodation_columns( $columns ) {    $columns['accommodation_location_post_id'] = 'Location';    unset( $columns['date'] );    return $columns;}function sort_accommodation_columns( $columns ) {    $columns['accommodation_location_post_id'] = 'accommodation_location_post_id';    return $columns;}$enable_accommodations = of_get_option('enable_accommodations', 1);if ($enable_accommodations) {	add_filter( 'manage_edit-accommodation_columns', 'accommodation_columns' );	add_filter( 'manage_edit-accommodation_sortable_columns', 'sort_accommodation_columns' );}function car_rental_columns($columns) {    $columns['car_rental_location_post_id'] = 'Pick Up Location';    $columns['car_rental_location_post_id_2'] = 'Drop-Off Location';    unset( $columns['date'] );    return $columns;}function sort_car_rental_columns( $columns ) {    $columns['car_rental_location_post_id'] = 'car_rental_location_post_id';    $columns['car_rental_location_post_id_2'] = 'car_rental_location_post_id_2';    return $columns;}$enable_car_rentals = of_get_option('enable_car_rentals', 1);if ($enable_car_rentals) {	add_filter( 'manage_edit-car_rental_columns', 'car_rental_columns' );	add_filter( 'manage_edit-car_rental_sortable_columns', 'sort_car_rental_columns' );}function location_columns( $columns ) {    $columns['location_country'] = 'Country';    unset( $columns['date'] );    return $columns;}add_filter( 'manage_edit-location_columns', 'location_columns' );function sort_location_columns( $columns ) {    $columns['location_country'] = 'location_country';    return $columns;}add_filter( 'manage_edit-location_sortable_columns', 'sort_location_columns' );function review_columns( $columns ) {    $columns['review_post_id'] = __('Reviewed item', 'bookyourtravel');    return $columns;}add_filter( 'manage_edit-review_columns', 'review_columns' );