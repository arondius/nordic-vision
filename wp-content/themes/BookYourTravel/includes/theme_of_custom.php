<?php

/*
 * Add custom, repeatable input fields to options framework thanks to HelgaTheViking
 * https://gist.github.com/helgatheviking/6022215
 */
function repeat_tab_option_type( $option_name, $option, $values ){

	$counter = 0;
	
	$default_values = get_default_tab_array($option['id']);
	
	if (!is_array( $values ) || count($values) == 0 ) {
		$values = $default_values;
	}
	
	$output = '<div class="of-repeat-loop">';
	$output .= '<ul class="sortable of-repeat-tabs">';
 
	if( is_array( $values ) ) { 
	
		foreach ( (array)$values as $value ){
			if (isset($value['label']) && 
				isset($value['id'])) {
				
				$output .= '<li class="ui-state-default of-repeat-group">';
				$output .= '<input class="of-input input-tab-label" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][label]' ) . '" type="text" value="' . esc_attr( $value['label'] ) . '" />';
				$output .= '<input name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][id]' ) . '" type="hidden" value="' . esc_attr( $value['id'] ) . '" />';
				$output .= '<div class="of-checkbox-wrap">';
				$output .= '<label class="of-label label-hide-tab" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][hide]' ) . '">' . __('Is hidden?', 'bookyourtravel') . '</label>';
				$output .= '<input class="of-checkbox checkbox-hide-tab" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][hide]' ) . '" type="checkbox" value="1" ' . (isset($value['hide']) && $value['hide'] == '1' ? 'checked' : '') . ' />';
				$output .= '</div>';
				if (isset($value['id']) && isset($value['label']) && count(byt_array_search($default_values, 'id', $value['id'])) == 0) {
					$output .= '<span class="ui-icon ui-icon-close"></span>';
				}

				$output .= '</li><!--.of-repeat-group-->';
				
				$counter++;
			}
		}
	}
 
	$output .= '<li class="to-copy ui-state-default of-repeat-group">';	
	$output .= '<input class="of-input input-tab-label" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="text" value="' . esc_attr( $option['std'] ) . '" />';
	$output .= '<div class="of-checkbox-wrap">';
	$output .= '<label class="of-label label-hide-tab" for="">' . __('Is hidden?', 'bookyourtravel') . '</label>';
	$output .= '<input class="of-checkbox checkbox-hide-tab" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="checkbox" value="1" />';
	$output .= '</div>';
    $output .= '<span class="ui-icon ui-icon-close"></span>';
	$output .= '</li><!--.of-repeat-group-->';
	$output .= '</ul>';	
	$output .= '<a href="#" class="docopy_tab button icon add">' . __('Add tab', 'bookyourtravel') . '</a>';
	$output .= '</div><!--.of-repeat-loop-->';
 
	return $output;
}
add_filter( 'optionsframework_repeat_tab', 'repeat_tab_option_type', 10, 3 );

function repeat_extra_field_option_type( $option_name, $option, $values ){

	global $repeatable_field_types, $default_accommodation_extra_fields, $default_tour_extra_fields, $default_car_rental_extra_fields, $default_location_extra_fields, $default_cruise_extra_fields;
	$counter = 0;
	
	$default_values = array();
	$tab_array = array();
	
	if ($option['id'] == 'accommodation_extra_fields') {
		$default_values = $default_accommodation_extra_fields;
		$tab_key = 'accommodation_tabs';
	} elseif ($option['id'] == 'tour_extra_fields') {
		$default_values = $default_tour_extra_fields;
		$tab_key = 'tour_tabs';
	} elseif ($option['id'] == 'car_rental_extra_fields') {
		$default_values = $default_car_rental_extra_fields;
		$tab_key = 'car_rental_tabs';
	} elseif ($option['id'] == 'location_extra_fields') {
		$default_values = $default_location_extra_fields;
		$tab_key = 'location_tabs';
	} elseif ($option['id'] == 'cruise_extra_fields') {
		$default_values = $default_cruise_extra_fields;
		$tab_key = 'cruise_tabs';
	}

	$tab_array = of_get_option($tab_key);
	if (!is_array( $tab_array ) || count($tab_array) == 0 ) {
		$tab_array = get_default_tab_array($tab_key);
	}
	
	if (!is_array( $values ) || count($values) == 0 ) {
		$values = $default_values;
	}

	$output = '<div class="of-repeat-loop">';
	
	if ($tab_array && count($tab_array) > 0) {

		$output .= '<ul class="sortable of-repeat-extra-fields">';

		if( is_array( $values ) && is_array($tab_array) ) {

			foreach ( (array)$values as $key => $value ){
				if (isset($value['label']) && 
					isset($value['type']) &&
					isset($value['tab_id']) &&
					isset($value['id'])) {
	 
					$output .= '<li class="ui-state-default of-repeat-group">';
					$output .= '<input name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][id]' ) . '" type="hidden" value="' . esc_attr( $value['id'] ) . '" />';					
					$output .= '<div class="of-input-wrap">';
					$output .= '<label class="of-label label-field-label" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][label]' ) . '">' . __('Field label', 'bookyourtravel') . '</label>';
					$output .= '<input class="of-input input-field-label" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][label]' ) . '" type="text" value="' . esc_attr( $value['label'] ) . '" />';
					$output .= '</div>';
					$output .= '<div class="of-select-wrap">';
					$output .= '<label class="of-label label-field-type" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][type]' ) . '">' . __('Field type', 'bookyourtravel') . '</label>';
					$output .= '<select class="of-select select-field-type" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][type]' ) . '">';
					
					foreach($repeatable_field_types as $input_type_key => $input_type_text) {
						$output .= '<option value="' . $input_type_key . '" ' . ($value['type'] == $input_type_key ? 'selected' : '') . '>' . $input_type_text . '</option>';
					}		
					
					$output .= '</select>';
					$output .= '</div>';
					$output .= '<div class="of-select-wrap">';
					$output .= '<label class="of-label label-field-tab" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][tab_id]' ) . '">' . __('Field tab', 'bookyourtravel') . '</label>';
					$output .= '<select class="of-select select-field-tab" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][tab_id]' ) . '">';
					foreach($tab_array as $tab) {
						if (isset($tab['id']) && isset($tab['label'])) {
							$tab_name = $tab['label'];
							$tab_id = $tab['id'];				
							$output .= '<option value="' . $tab_id . '" ' . (isset($value['tab_id']) && $value['tab_id'] == $tab_id ? 'selected' : '') . '>' . $tab_name . '</option>';
						}
					}		
					$output .= '</select>';
					$output .= '</div>';
					$output .= '<label class="of-label label-hide-field" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][hide]' ) . '">' . __('Is hidden?', 'bookyourtravel') . '</label>';
					$output .= '<input class="of-checkbox checkbox-hide-field" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][hide]' ) . '" type="checkbox" value="1" ' . (isset($value['hide']) && $value['hide'] == '1' ? 'checked' : '') . ' />';
					if (isset($value['id']) && isset($value['label']) && count(byt_array_search($default_values, 'id', $value['id'])) == 0) {
						$output .= '<span class="ui-icon ui-icon-close"></span>';
					}
					
					$output .= '</li><!--.of-repeat-group-->';
			 
					$counter++;
				}
			}
		}
	 
		$output .= '<li class="to-copy ui-state-default of-repeat-group">';
		$output .= '<div class="of-input-wrap">';
		$output .= '<label class="of-label label-field-label" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . __('Field label', 'bookyourtravel') . '</label>';
		$output .= '<input class="of-input input-field-label" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="text" value="' . esc_attr( $option['std'] ) . '" />';
		$output .= '</div>';
		$output .= '<div class="of-select-wrap">';
		$output .= '<label class="of-label label-field-type" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . __('Field type', 'bookyourtravel') . '</label>';
		$output .= '<select class="of-select select-field-type" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">';
		foreach($repeatable_field_types as $input_type_key => $input_type_text) {
			$output .= '<option value="' . $input_type_key . '">' . $input_type_text . '</option>';
		}
		$output .= '</select>';
		$output .= '</div>';
		$output .= '<div class="of-select-wrap">';
		$output .= '<label class="of-label label-field-tab" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . __('Field tab', 'bookyourtravel') . '</label>';
		$output .= '<select class="of-select select-field-tab" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">';
		if (is_array($tab_array) && count($tab_array)) {
			foreach($tab_array as $tab) {
				$tab_name = isset($tab['label']) ? $tab['label'] : '';
				$tab_id = isset($tab['id']) ? $tab['id'] : '';
				if (!empty($tab_name) && !empty($tab_id))
					$output .= '<option value="' . $tab_id . '">' . $tab_name . '</option>';
			}		
		}
		$output .= '</select>';
		$output .= '</div>';
		$output .= '<label class="of-label label-hide-field" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . __('Is hidden?', 'bookyourtravel') . '</label>';
		$output .= '<input class="of-checkbox checkbox-hide-field" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="checkbox" value="1" />';
		$output .= '<span class="ui-icon ui-icon-close"></span>';
		$output .= '</li><!--.of-repeat-group-->'; 
		$output .= '</ul><!--.sortable-->';
		$output .= '<a href="#" class="docopy_field button icon add">' . __('Add field', 'bookyourtravel') . '</a>';
	} else {
		$output .= '<p>' . __('Please hit the "Save Options" button to create the initial collection of tabs so that extra fields can be associated with tabs correctly.', 'bookyourtravel') . '</p>';
	}
	$output .= '</div><!--.of-repeat-loop-->';

	return $output;

}
add_filter( 'optionsframework_repeat_extra_field', 'repeat_extra_field_option_type', 10, 3 );


function repeat_review_field_option_type( $option_name, $option, $values ){

	$counter = 0;
	
	$default_values = get_default_review_fields_array($option['id']);
	if (!is_array( $values ) || count($values) == 0 ) {
		$values = $default_values;
	}
	
	$post_type = '';
	if ($option['id'] == 'accommodation_review_fields')
		$post_type = 'accommodation';
	elseif ($option['id'] == 'tour_review_fields')
		$post_type = 'tour';
	elseif ($option['id'] == 'cruise_review_fields')
		$post_type = 'cruise';
		
	$output = '<div class="of-repeat-loop">';
	
	$output .= '<ul class="sortable of-repeat-review-fields">';

	if ( is_array( $values ) ) {

		foreach ( (array)$values as $key => $value ){
			if (isset($value['label']) && 
				isset($value['post_type']) &&
				isset($value['id'])) {
 
				$output .= '<li class="ui-state-default of-repeat-group">';
				$output .= '<input name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][id]' ) . '" type="hidden" value="' . esc_attr( $value['id'] ) . '" />';					
				$output .= '<input name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][post_type]' ) . '" type="hidden" value="' . $post_type . '" />';									
				$output .= '<div class="of-input-wrap">';
				$output .= '<label class="of-label label-field-label" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][label]' ) . '">' . __('Field label', 'bookyourtravel') . '</label>';
				$output .= '<input class="of-input input-field-label" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][label]' ) . '" type="text" value="' . esc_attr( $value['label'] ) . '" />';
				$output .= '</div>';
				$output .= '<label class="of-label label-hide-field" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][hide]' ) . '">' . __('Is hidden?', 'bookyourtravel') . '</label>';
				$output .= '<input class="of-checkbox checkbox-hide-field" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][hide]' ) . '" type="checkbox" value="1" ' . (isset($value['hide']) && $value['hide'] == '1' ? 'checked' : '') . ' />';
				if (isset($value['id']) && isset($value['label']) && count(byt_array_search($default_values, 'id', $value['id'])) == 0) {
					$output .= '<span class="ui-icon ui-icon-close"></span>';
				}
				
				$output .= '</li><!--.of-repeat-group-->';
		 
				$counter++;
			}
		}
	}
 
	$output .= '<li class="to-copy ui-state-default of-repeat-group">';
	$output .= '<input class="input-post-type" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="hidden" value="' . $post_type . '" />';									
	$output .= '<div class="of-input-wrap">';
	$output .= '<label class="of-label label-field-label" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . __('Field label', 'bookyourtravel') . '</label>';
	$output .= '<input class="of-input input-field-label" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="text" value="' . esc_attr( $option['std'] ) . '" />';
	$output .= '</div>';
	$output .= '<label class="of-label label-hide-field" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . __('Is hidden?', 'bookyourtravel') . '</label>';
	$output .= '<input class="of-checkbox checkbox-hide-field" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="checkbox" value="1" />';
	$output .= '<span class="ui-icon ui-icon-close"></span>';
	$output .= '</li><!--.of-repeat-group-->'; 
	$output .= '</ul><!--.sortable-->';
	$output .= '<a href="#" class="docopy_review_field button icon add">' . __('Add review field', 'bookyourtravel') . '</a>';
	$output .= '</div><!--.of-repeat-loop-->';

	return $output;

}
add_filter( 'optionsframework_repeat_review_field', 'repeat_review_field_option_type', 10, 3 );

/*
 * Sanitize Repeat review inputs
 */
function sanitize_repeat_review_field( $fields, $option ){	
	$results = array();
	if (is_array($fields)) {
		for ($i = 0; $i < count($fields); $i++) { 
			if (isset($fields[$i])) {
				$field = $fields[$i];
				if (!isset($field['id']) && isset($field['label'])) {
					$field['id'] = 'review_' . URLify::filter($field['label']);
				}
				$results[] = $field;
			}
		}
	}
	return $results;
}
add_filter( 'of_sanitize_repeat_review_field', 'sanitize_repeat_review_field', 10, 2 );

/*
 * Sanitize Repeat inputs
 */
function sanitize_repeat_extra_field( $fields, $option ){	
	$results = array();
	if (is_array($fields)) {
		for ($i = 0; $i < count($fields); $i++) { 
			if (isset($fields[$i])) {
				$field = $fields[$i];
				if (!isset($field['id']) && isset($field['label'])) {
					$field['id'] = URLify::filter($field['label']);
				}
				$results[] = $field;
			}
		}
	}
	return $results;
}
add_filter( 'of_sanitize_repeat_extra_field', 'sanitize_repeat_extra_field', 10, 2 );

/*
 * Sanitize Repeat tabs
 */
function sanitize_repeat_tab( $tabs, $option ){
	$results = array();
	if (is_array($tabs)) {
		for ($i = 0; $i < count($tabs); $i++) { 
			if (isset($tabs[$i])) {
				$tab = $tabs[$i];
				if (!isset($tab['id']) && isset($tab['label'])) {
					$tab['id'] = URLify::filter($tab['label']);
				}
				$results[] = $tab;
			}
		}
	}
	return $results;
}
add_filter( 'of_sanitize_repeat_tab', 'sanitize_repeat_tab', 10, 2 );

/*
 * Custom repeating field scripts
 * Add and Delete buttons
 */
function of_byt_options_script(){	?>
	<style>
		#optionsframework .to-copy {display: none;}
	</style>

	<script type="text/javascript">
	jQuery(function($){
 
 		$('.of-repeat-review-fields').sortable();
 
		$(".docopy_review_field").on("click", function(e){
 
		  // the loop object
		  $section = $(this).closest(".section");
		  $field_loop = $section.find('.of-repeat-review-fields');
 
		  // the group to copy
 		  $to_copy = $field_loop.find('.to-copy');
		  $group = $to_copy.clone();
		  $group.removeClass('to-copy');
		  $group.insertBefore($to_copy);
 
		  // the new input
		  $input_field_label = $group.find('input.input-field-label');
		  $input_post_type = $group.find('input.input-post-type');
		  $label_hide_field = $group.find('label.label-hide-field');
		  $checkbox_hide_field = $group.find('input.checkbox-hide-field');
 
		  input_field_label_name = $input_field_label.attr('data-rel');
		  input_post_type_name = $input_post_type.attr('data-rel');
		  checkbox_hide_field_name = $checkbox_hide_field.attr('data-rel');
		  
		  count = $field_loop.children('.of-repeat-group').not('.to-copy').length;
 
		  $input_field_label.attr('name', input_field_label_name + '[' + ( count - 1 ) + '][label]');
		  $input_post_type.attr('name', input_post_type_name + '[' + ( count - 1 ) + '][post_type]'); 
		  $checkbox_hide_field.attr('name', checkbox_hide_field_name + '[' + ( count - 1 ) + '][hide]'); 
		  
		  label_hide_field_for = checkbox_hide_field_name + '[' + ( count - 1 ) + '][hide]';
		  $label_hide_field.attr('for', label_hide_field_for);
		  
 		  bindIconCloseEvent();
		  
		  e.preventDefault();
 
		});

		$('.of-repeat-extra-fields').sortable();
 
		$(".docopy_field").on("click", function(e){
 
		  // the loop object
		  $section = $(this).closest(".section");
		  $field_loop = $section.find('.of-repeat-extra-fields');
 
		  // the group to copy
 		  $to_copy = $field_loop.find('.to-copy');
		  $group = $to_copy.clone();
		  $group.removeClass('to-copy');
		  $group.insertBefore($to_copy);
 
		  // the new input
		  $input_field_label = $group.find('input.input-field-label');
		  $select_field_type = $group.find('select.select-field-type');
		  $select_field_tab = $group.find('select.select-field-tab');
		  $label_hide_field = $group.find('label.label-hide-field');
		  $checkbox_hide_field = $group.find('input.checkbox-hide-field');
 
		  input_field_label_name = $input_field_label.attr('data-rel');
		  select_field_type_name = $select_field_type.attr('data-rel');
		  select_field_tab_name = $select_field_tab.attr('data-rel');
		  checkbox_hide_field_name = $checkbox_hide_field.attr('data-rel');
		  
		  count = $field_loop.children('.of-repeat-group').not('.to-copy').length;
 
		  $input_field_label.attr('name', input_field_label_name + '[' + ( count - 1 ) + '][label]');
		  $select_field_type.attr('name', select_field_type_name + '[' + ( count - 1 ) + '][type]'); 
		  $select_field_tab.attr('name', select_field_tab_name + '[' + ( count - 1 ) + '][tab_id]'); 
		  $checkbox_hide_field.attr('name', checkbox_hide_field_name + '[' + ( count - 1 ) + '][hide]'); 
		  
		  label_hide_field_for = checkbox_hide_field_name + '[' + ( count - 1 ) + '][hide]';
		  $label_hide_field.attr('for', label_hide_field_for);
		  
 		  bindIconCloseEvent();
		  
		  e.preventDefault();
 
		});
 		
		$(".of-repeat-tabs").sortable();
 
		$(".docopy_tab").on("click", function(e){
 
		  // the loop object
		  $section = $(this).closest(".section");
		  $tab_loop = $section.find('.of-repeat-tabs');		  
 
		  // the group to copy
		  $to_copy = $tab_loop.find('.to-copy');
		  $group = $to_copy.clone();
		  $group.removeClass('to-copy');
		  $group.insertBefore($to_copy);
 
		  // the new input
		  $input_tab_label = $group.find('input.input-tab-label');
		  $checkbox_hide_tab = $group.find('input.checkbox-hide-tab');
		  $label_hide_tab = $group.find('label.label-hide-tab');
		  
		  input_tab_label_name = $input_tab_label.attr('data-rel');
		  checkbox_hide_tab_name = $checkbox_hide_tab.attr('data-rel');
		  
		  count = $tab_loop.children('.of-repeat-group').not('.to-copy').length;
 
		  $input_tab_label.attr('name', input_tab_label_name + '[' + ( count - 1 ) + '][label]'); 
		  $checkbox_hide_tab.attr('name', checkbox_hide_tab_name + '[' + ( count - 1 ) + '][hide]'); 

		  label_hide_tab_for = checkbox_hide_tab_name + '[' + ( count - 1 ) + '][hide]';
		  $label_hide_tab.attr('for', label_hide_tab_for);
		  
		  bindIconCloseEvent();
		  
		  e.preventDefault();
		});
		
		bindIconCloseEvent();	
		
		function bindIconCloseEvent() {
			/* Bind the X behavior to the original elements*/
			$(".ui-icon-close").click(function() {
				$(this).parent().remove();
				return false;
			});
		}
		
		bindTabVisibility('accommodations', 'enable_accommodations');
		bindTabVisibility('tours', 'enable_tours');
		bindTabVisibility('carrentals', 'enable_car_rentals');
		bindTabVisibility('cruises', 'enable_cruises');
		bindTabVisibility('reviews', 'enable_reviews');
		
		function bindTabVisibility(groupClass, checkboxId) {
			toggleTabVisibility($("#" + checkboxId).is(':checked'), groupClass, checkboxId);
			
			$("#" + checkboxId).change(function() {
				toggleTabVisibility(this.checked, groupClass, checkboxId);
			});
		}
		
		function toggleTabVisibility(show, groupClass, checkboxId) {
			if (show){
				$(".group." + groupClass).children().show();
			} else {
				$(".group." + groupClass).children().hide();
				$("#section-" + checkboxId).show();
				$(".group." + groupClass + " > h3").show();
				$("#section-" + checkboxId).children().show();	
			}		
		}
		
	});
	 
	</script>
<?php
}
add_action( 'optionsframework_custom_scripts', 'of_byt_options_script' );

function byt_array_search($array, $key, $value)
{
    $results = array();

    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, byt_array_search($subarray, $key, $value));
        }
    }

    return $results;
}
