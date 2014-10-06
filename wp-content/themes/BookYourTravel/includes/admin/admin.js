function confirm_delete(form_id, message) {
	var answer = confirm(message);
	if (answer){
		jQuery(form_id).submit();
		return true;
	}
	return false;  
}

function is_tour_type_repeated_changed(display){
	var sel = document.getElementById("term_meta[tour_type_is_repeated]");
	var val = parseInt(sel.options[sel.selectedIndex].value);
	if (val == 3) 
		document.getElementById("tr_tour_type_day_of_week").style.display = display;
	else
		document.getElementById("tr_tour_type_day_of_week").style.display = 'none';
}

function is_cruise_type_repeated_changed(display){
	var sel = document.getElementById("term_meta[cruise_type_is_repeated]");
	var val = parseInt(sel.options[sel.selectedIndex].value);
	if (val == 3) 
		document.getElementById("tr_cruise_type_day_of_week").style.display = display;
	else
		document.getElementById("tr_cruise_type_day_of_week").style.display = 'none';
}

jQuery.noConflict();

function showHideRoomTypes(checked) {
	if (checked) {
		jQuery('#accommodation_is_self_catered').closest('tr').next().hide();
	} else {
		jQuery('#accommodation_is_self_catered').closest('tr').next().show();
	}
}

function showHideCountChildrenStayFree(checked) {
	if (checked) {
		jQuery('#accommodation_is_price_per_person').closest('tr').next().show();
	} else {
		jQuery('#accommodation_is_price_per_person').closest('tr').next().hide();
	}
}

function accommodationFilterRedirect(accommodationId, roomTypeId, year, month) {
    document.location = 'edit.php?post_type=accommodation&page=theme_accommodation_vacancy_admin.php&accommodation_id=' + accommodationId + '&room_type_id=' + roomTypeId + '&year=' + year + '&month=' + month;
};

function tourFilterRedirect(id, year, month) {
    document.location = 'edit.php?post_type=tour&page=theme_tour_schedule_admin.php&tour_id=' + id + '&year=' + year + '&month=' + month;
};

function cruiseFilterRedirect(cruiseId, cabinTypeId, year, month) {
    document.location = 'edit.php?post_type=cruise&page=theme_cruise_schedule_admin.php&cruise_id=' + cruiseId + '&cabin_type_id=' + cabinTypeId + '&year=' + year + '&month=' + month;
};

function tourBookingTourFilterRedirect(bookingId, tourId) {
    document.location = 'edit.php?post_type=tour&page=theme_tour_schedule_booking_admin.php&sub=manage&edit=' + bookingId + '&tour_id=' + tourId;
};

function cruiseBookingCruiseFilterRedirect(bookingId, cruiseId) {
    document.location = 'edit.php?post_type=cruise&page=theme_cruise_schedule_booking_admin.php&sub=manage&edit=' + bookingId + '&cruise_id=' + cruiseId;
};

function carRentalBookingCarRentalFilterRedirect(bookingId, carRentalId) {
    document.location = 'edit.php?post_type=car_rental&page=theme_car_rental_booking_admin.php&sub=manage&edit=' + bookingId + '&car_rental_id=' + carRentalId;
};

function tourBookingTourScheduleFilterRedirect(bookingId, tourId, tourScheduleId) {
    document.location = 'edit.php?post_type=tour&page=theme_tour_schedule_booking_admin.php&sub=manage&edit=' + bookingId + '&tour_id=' + tourId + '&tour_schedule_id=' + tourScheduleId;
};

function cruiseBookingCruiseScheduleFilterRedirect(bookingId, cruiseId, cruiseScheduleId) {
    document.location = 'edit.php?post_type=cruise&page=theme_cruise_schedule_booking_admin.php&sub=manage&edit=' + bookingId + '&cruise_id=' + cruiseId + '&cruise_schedule_id=' + cruiseScheduleId;
};

function accommodationBookingAccommodationFilterRedirect(bookingId, accommodationId) {
    document.location = 'edit.php?post_type=accommodation&page=theme_accommodation_booking_admin.php&sub=manage&edit=' + bookingId + '&accommodation_id=' + accommodationId;
};

jQuery(document).ready(function() {
	
	showHideRoomTypes(jQuery('#accommodation_is_self_catered').is(':checked'));
	jQuery("#accommodation_is_self_catered").change(function() {
		showHideRoomTypes(jQuery(this).is(':checked'));
	});
	
	showHideCountChildrenStayFree(jQuery('#accommodation_is_price_per_person').is(':checked'));
	jQuery("#accommodation_is_price_per_person").change(function() {
		showHideCountChildrenStayFree(jQuery(this).is(':checked'));
	});
	
	jQuery('#accommodations_select').on('change', function() {
		var accommodationId = jQuery(this).val()
		
		var isSelfCatered = adminAccommodationIsSelfCatered(accommodationId);
		
		if (isSelfCatered) {
			jQuery('#room_types_row').hide();
			jQuery('#room_count_row').hide();
		} else {
		
			var roomTypes = listAccommodationRoomTypes(accommodationId);
			
			jQuery('select#room_types_select').find('option:gt(0)').remove();
			
			var room_type_options = "";

			jQuery.each(roomTypes,function(index){
				room_type_options += '<option value="'+ roomTypes[index].id +'">' + roomTypes[index].name + '</option>'; 
			});

			jQuery('select#room_types_select').append(room_type_options);
			
			jQuery('#room_types_row').show();
			jQuery('#room_count_row').show();
		}
		
		var isPricePerPerson = adminAccommodationIsPricePerPerson(accommodationId);
		if (isPricePerPerson) {
			jQuery('.per_person').show();
		} else {
			jQuery('.per_person').hide();
		}
	});
	
	jQuery('#tours_select').on('change', function() {

		var tourId = jQuery(this).val()
		
		var isPricePerGroup = adminTourIsPricePerGroup(tourId);
		var tourTypeIsRepeated = adminTourTypeIsRepeated(tourId);
		
		if (isPricePerGroup) {
			jQuery('.per_person').hide();
			jQuery('.per_group').show();
			jQuery('#price_child').val(0);
		} else {
			jQuery('.per_person').show();
			jQuery('.per_group').hide();
		}
		
		if (tourTypeIsRepeated > 0) {
			jQuery('.is_repeated').show();		
		} else {
			jQuery('.is_repeated').hide();		
		}
		
	});
	
	jQuery('#cruises_select').on('change', function() {

		var cruiseId = jQuery(this).val()
		
		var isPricePerPerson = adminCruiseIsPricePerPerson(cruiseId);
		var cruiseTypeIsRepeated = adminCruiseTypeIsRepeated(cruiseId);
		
		var cabinTypes = listCruiseCabinTypes(cruiseId);
		
		jQuery('select#cruise_types_select').find('option:gt(0)').remove();
		
		var cabin_type_options = "";

		jQuery.each(cabinTypes,function(index){
			cabin_type_options += '<option value="'+ cabinTypes[index].id +'">' + cabinTypes[index].name + '</option>'; 
		});

		jQuery('select#cabin_types_select').append(cabin_type_options);
		
		jQuery('#cabin_types_row').show();
		jQuery('#cabin_count_row').show();
		
		if (isPricePerPerson) {
			jQuery('.per_person').show();
		} else {
			jQuery('.per_person').hide();
			jQuery('#price_child').val(0);
		}
		
		if (cruiseTypeIsRepeated > 0) {
			jQuery('.is_repeated').show();		
		} else {
			jQuery('.is_repeated').hide();		
		}		
	});
});

function listAccommodationRoomTypes(accommodationId) {
	
	var retVal = null;
	var _wpnonce = jQuery('#_wpnonce').val();
		
	var dataObj = {
			'action':'admin_accommodation_list_room_types_ajax_request',
			'accommodationId' : accommodationId,
			'nonce' : _wpnonce
		}				  

	jQuery.ajax({
		url: window.adminAjaxUrl,
		data: dataObj,
		async: false,
		success:function(json) {
			// This outputs the result of the ajax request
			retVal = JSON.parse(json);
		},
		error: function(errorThrown){
			console.log(errorThrown);
		}
	}); 
	
	return retVal;
	
}

function adminAccommodationIsSelfCatered(accommodationId) {

	var retVal = 0;
	var _wpnonce = jQuery('#_wpnonce').val();
		
	var dataObj = {
			'action':'admin_accommodation_is_self_catered_ajax_request',
			'accommodationId' : accommodationId,
			'nonce' : _wpnonce
		}				  

	jQuery.ajax({
		url: window.adminAjaxUrl,
		data: dataObj,
		async: false,
		success:function(data) {
			// This outputs the result of the ajax request
			retVal = data;
		},
		error: function(errorThrown){
			console.log(errorThrown);
		}
	}); 
	
	return parseInt(retVal);
}

function adminAccommodationIsPricePerPerson(accommodationId) {

	var retVal = 0;
	var _wpnonce = jQuery('#_wpnonce').val();
		
	var dataObj = {
			'action':'admin_accommodation_is_price_per_person_ajax_request',
			'accommodationId' : accommodationId,
			'nonce' : _wpnonce
		}				  

	jQuery.ajax({
		url: window.adminAjaxUrl,
		data: dataObj,
		async: false,
		success:function(data) {
			// This outputs the result of the ajax request
			retVal = data;
		},
		error: function(errorThrown){
			console.log(errorThrown);
		}
	}); 
	
	return parseInt(retVal);
}

function adminTourIsPricePerGroup(tourId) {

	var retVal = 0;
	var _wpnonce = jQuery('#_wpnonce').val();
		
	var dataObj = {
			'action':'tour_is_price_per_group_ajax_request',
			'tourId' : tourId,
			'nonce' : _wpnonce
		}				  

	jQuery.ajax({
		url: window.adminAjaxUrl,
		data: dataObj,
		async: false,
		success:function(data) {
			// This outputs the result of the ajax request
			retVal = data;
		},
		error: function(errorThrown){
			console.log(errorThrown);
		}
	}); 
	
	return parseInt(retVal);
}

function adminTourIsPricePerGroup(tourId) {

	var retVal = 0;
	var _wpnonce = jQuery('#_wpnonce').val();
		
	var dataObj = {
			'action':'tour_is_price_per_group_ajax_request',
			'tourId' : tourId,
			'nonce' : _wpnonce
		}				  

	jQuery.ajax({
		url: window.adminAjaxUrl,
		data: dataObj,
		async: false,
		success:function(data) {
			// This outputs the result of the ajax request
			retVal = data;
		},
		error: function(errorThrown){
			console.log(errorThrown);
		}
	}); 
	
	return parseInt(retVal);
}

function adminTourTypeIsRepeated(tourId) {

	var retVal = 0;
	var _wpnonce = jQuery('#_wpnonce').val();
		
	var dataObj = {
			'action':'tour_type_is_repeated_ajax_request',
			'tourId' : tourId,
			'nonce' : _wpnonce
		}				  

	jQuery.ajax({
		url: window.adminAjaxUrl,
		data: dataObj,
		async: false,
		success:function(data) {
			// This outputs the result of the ajax request
			retVal = data;
		},
		error: function(errorThrown){
			console.log(errorThrown);
		}
	}); 
	
	return parseInt(retVal);
}

function adminCruiseTypeIsRepeated(cruiseId) {

	var retVal = 0;
	var _wpnonce = jQuery('#_wpnonce').val();
		
	var dataObj = {
			'action':'cruise_type_is_repeated_ajax_request',
			'cruiseId' : cruiseId,
			'nonce' : _wpnonce
		}				  

	jQuery.ajax({
		url: window.adminAjaxUrl,
		data: dataObj,
		async: false,
		success:function(data) {
			// This outputs the result of the ajax request
			retVal = data;
		},
		error: function(errorThrown){
			console.log(errorThrown);
		}
	}); 
	
	return parseInt(retVal);
}

function adminCruiseIsPricePerPerson(cruiseId) {

	var retVal = 0;
	var _wpnonce = jQuery('#_wpnonce').val();
		
	var dataObj = {
			'action':'cruise_is_price_per_person_ajax_request',
			'cruiseId' : cruiseId,
			'nonce' : _wpnonce
		}				  

	jQuery.ajax({
		url: window.adminAjaxUrl,
		data: dataObj,
		async: false,
		success:function(data) {
			// This outputs the result of the ajax request
			retVal = data;
		},
		error: function(errorThrown){
			console.log(errorThrown);
		}
	}); 
	
	return parseInt(retVal);
}

function listCruiseCabinTypes(cruiseId) {
	
	var retVal = null;
	var _wpnonce = jQuery('#_wpnonce').val();
		
	var dataObj = {
			'action':'cruise_list_cabin_types_ajax_request',
			'cruiseId' : cruiseId,
			'nonce' : _wpnonce }				  

	jQuery.ajax({
		url: window.adminAjaxUrl,
		data: dataObj,
		async: false,
		success:function(json) {
			// This outputs the result of the ajax request
			retVal = JSON.parse(json);
		},
		error: function(errorThrown){
			
		}
	}); 
	
	return retVal;	
}
