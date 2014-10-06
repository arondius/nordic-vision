var jq = jQuery.noConflict();

	jq(document).ready(function() {

        jq("#gallery").lightSlider({
			slideWidth:850,
			gallery:true,
			thumbWidth:100,
			thumbMargin:6.25,
            minSlide:1,
            maxSlide:1,
            auto:true,
            mode:'slide',
            proportion:'100%',
            onSliderLoad: function() {
                jq('#gallery').removeClass('cS-hidden');
            }  
		});
	
		jq('.book_car_rental').on('click', function(event) {
		
			jq('#wait_loading').show();
			var carRentalId = window.carRentalId;
			jq('#car_booking_form_car_type').html(window.carRentalCarType).val();
			jq('#car_booking_form_car_price').html(window.carRentalPrice).val();
			jq('#car_booking_form_car_rental_title').html(window.carRentalTitle).val();
			jq('#car_booking_form_pick_up').html(window.carRentalPickUp).val();
			jq('#car_booking_form_car_rental_id').val(window.carRentalId);

			window.carRentalBookedOutDays = getCarRentalBookedOutDates(window.carRentalId, window.currentMonth, window.currentYear);
			
			showCarRentalForm();
			jq('#wait_loading').hide();
			event.preventDefault();
		});
		
		jq('.contact_car_rental').on('click', function(event) {
			showCarRentalInquiryForm();
			event.preventDefault();
		});		

		jq('#cancel-car_rental-booking').on('click', function(event) {
			hideCarRentalBookingForm();
			showCarRentalInfo();
			event.preventDefault();
		});	
		
		function showCarRentalInfo() {
			jq('.three-fourth .gallery').show();
			jq('.three-fourth .inner-nav').show();
			jq('.three-fourth .tab-content').show();
			jq(".tab-content").hide();
			jq(".tab-content:first").show();
			jq(".inner-nav li:first").addClass("active");
		}

		jq('#car_rental-booking-form').validate({
			onkeyup: false,
			ignore: [],
			errorPlacement: function(error, element) {
				if (element.attr('type') == 'hidden' && (element.attr('id') == 'car_booking_form_date_from' || element.attr('id') == 'car_booking_form_date_to'))
					error.appendTo( jq('#car_booking_form_datepicker') );
				else
					error.insertAfter(element);
			},
			rules: {
				car_booking_form_first_name: {
					required: true
				},
				car_booking_form_last_name: "required",
				car_booking_form_email: {
					required: true,
					email: true
				},
				car_booking_form_confirm_email: {
					required: true,
					equalTo: "#car_booking_form_email"
				},
				car_booking_form_phone: "required",
				car_booking_form_address: "required",
				car_booking_form_town: "required",
				car_booking_form_zip: "required",
				car_booking_form_country: "required",
				car_booking_form_date_from: "required",
				car_booking_form_date_to: "required",
				car_booking_form_drop_off: "required"
			},
			invalidHandler: function(e, validator) {
				var errors = validator.numberOfInvalids();
				if (errors) {
					var message = errors == 1
						? window.formSingleError
						: window.formMultipleError.format(errors);
					jq("div.error div p").html(message);
					jq("div.error").show();
				} else {
					jq("div.error").hide();
				}
			},
			messages: {
				car_booking_form_first_name: window.bookingFormFirstNameError,
				car_booking_form_last_name: window.bookingFormLastNameError,
				car_booking_form_email: window.bookingFormEmailError,
				car_booking_form_confirm_email: {
					required: window.bookingFormConfirmEmailError1,
					equalTo: window.bookingFormConfirmEmailError2
				},
				car_booking_form_phone: window.bookingFormPhoneError,
				car_booking_form_address: window.bookingFormAddressError,
				car_booking_form_town: window.bookingFormCityError,
				car_booking_form_zip: window.bookingFormZipError,
				car_booking_form_country: window.bookingFormCountryError,
				car_booking_form_date_from: window.bookingFormDateFromError,
				car_booking_form_date_to: window.bookingFormDateToError,
				car_booking_form_drop_off: window.bookingFormDropOffError
			},
			submitHandler: function() { 
				processCarRentalBooking(); 
			}
		});
		
	});

	function showCarRentalForm() {
	
		jq('#car_rental-booking-form').show();
		jq('.three-fourth .gallery').hide();
		jq('.three-fourth .inner-nav').hide();
		jq('.three-fourth .tab-content').hide();

		bindCarRentalDatePicker();
	}
	
	function bindCarRentalDatePicker() {
	
		if (typeof jq('#car_booking_form_datepicker') !== 'undefined') {
			var datepickerDateFormat = 'yy-mm-dd';
			jq('#car_booking_form_datepicker').datepicker({
				dateFormat: datepickerDateFormat,
				numberOfMonths: 1,
				minDate: 0,
				beforeShowDay: function(d) {
					var date1 = null;
					var date2 = null;
					if (jq("#car_booking_form_date_from").val())
						date1 = jq.datepicker.parseDate(datepickerDateFormat, jq("#car_booking_form_date_from").val());
					if (jq("#car_booking_form_date_to").val())
						date2 = jq.datepicker.parseDate(datepickerDateFormat, jq("#car_booking_form_date_to").val());

					if (window.carRentalBookedOutDays) {
						var dateTextForCompare1 = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2);
						var dateTextForCompare2 = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2) + " 00:00:00";
						if (jq.inArray(dateTextForCompare1, window.carRentalBookedOutDays) > -1 || jq.inArray(dateTextForCompare2, window.carRentalBookedOutDays) > -1)
							return [false, 'ui-datepicker-unselectable ui-state-disabled'];
					}
					
					return [true, date1 && ((d.getTime() == date1.getTime()) || (date2 && d >= date1 && d <= date2)) ? "dp-highlight" : ""];
				},
				onSelect: function(dateText, inst) {
					jq(".dates_row").show();
					var dateTextForParse = inst.currentYear + '-' + (inst.currentMonth + 1) + '-' + ("0" + inst.currentDay).slice(-2);
					var date1 = null;
					if (jq("#car_booking_form_date_from").val()) {
						date1 = jq.datepicker.parseDate(datepickerDateFormat, jq("#car_booking_form_date_from").val());
					}
					var date2 = null;
					if (jq("#car_booking_form_date_to").val()) {
						date2 = jq.datepicker.parseDate(datepickerDateFormat, jq("#car_booking_form_date_to").val());
					}

					if (!date1 || date2) {
						jq("#car_booking_form_date_from").val(dateText);
						jq("#date_from").html(dateText);
						jq("#car_booking_form_date_to").val("");
						jq("#date_to").html("");
						jq(".dates_row").hide();
					} else {
						var dateCompare = Date.parse(dateTextForParse);
						if (dateCompare < date1)
						{
							jq("#car_booking_form_date_from").val(dateText);
							jq("#date_from").html(dateText);
							jq("#car_booking_form_date_to").val("");
							jq("#date_to").html("");	
							jq(".dates_row").hide();							
						}
						else
						{
							date1 = jq.datepicker.parseDate(datepickerDateFormat, jq("#car_booking_form_date_from").val());
							date2 = jq.datepicker.parseDate(datepickerDateFormat, dateText);
							
							var allOk = true;
							for (var d = date1; d <= date2; d.setDate(d.getDate() + 1)) {
								var dateTextForCompare = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' +  ("0" + d.getDate()).slice(-2);
								if (jq.inArray(dateTextForCompare, window.carRentalBookedOutDays) > -1)
									allOk = false;
							}
							
							if (!allOk) {
								jq("#car_booking_form_date_from").val(dateText);
								jq("#date_from").html(dateText);
								jq("#car_booking_form_date_to").val("");									
								jq("#date_to").html("");	
								jq(".dates_row").hide();
							} else {
								jq("#car_booking_form_date_to").val(dateText);
								jq("#date_to").html(dateText);
							}
						}
					}
				},
				onChangeMonthYear: function (year, month, inst) {
					window.currentMonth = month;
					window.currentYear = year;
					window.carRentalBookedOutDays = getCarRentalBookedOutDates(window.carRentalId, window.currentMonth, window.currentYear);
					bindCarRentalDatePicker();
				}
			});
		}

	}

	function hideCarRentalBookingForm() {
		jq('#car_rental-booking-form').hide();
	}

	function processCarRentalBooking() {
	
		jq('#wait_loading').show();
	
		var firstName = jq('#car_booking_form_first_name').val();
		var lastName = jq('#car_booking_form_last_name').val();
		var email = jq('#car_booking_form_email').val();
		var phone = jq('#car_booking_form_phone').val();
		var address = jq('#car_booking_form_address').val();
		var town = jq('#car_booking_form_town').val();
		var zip = jq('#car_booking_form_zip').val();
		var country = jq('#car_booking_form_country').val();
		var requirements = jq('#car_booking_form_requirements').val();
		var carRentalId = jq('#car_booking_form_car_rental_id').val();
		var dateFrom = jq('#car_booking_form_date_from').val();
		var dateTo = jq('#car_booking_form_date_to').val();
		var pickUp = jq('#car_booking_form_pick_up').html();
		var dropOffText = jq('#car_booking_form_drop_off option:selected').text();
		var dropOff = jq('#car_booking_form_drop_off option:selected').val();
		var carRentalName = jq('#car_booking_form_car_rental_title').html();
		var cValS = jq('#c_val_s').val();
		var cVal1 = jq('#c_val_1').val();
		var cVal2 = jq('#c_val_2').val();
			
		jq("#car_confirm_first_name").html(firstName);
		jq("#car_confirm_last_name").html(lastName);
		jq("#car_confirm_email_address").html(email);
		jq("#car_confirm_phone").html(phone);
		jq("#car_confirm_street").html(address);
		jq("#car_confirm_town").html(town);
		jq("#car_confirm_zip").html(zip);
		jq("#car_confirm_country").html(country);
		jq("#car_confirm_requirements").html(requirements);
		jq("#car_confirm_date_from").html(dateFrom);
		jq("#car_confirm_date_to").html(dateTo);
		jq("#car_confirm_pick_up").html(pickUp);
		jq("#car_confirm_drop_off").html(dropOffText);
		jq('#car_confirm_car_rental_name').html(carRentalName);

		var d1=new Date(jq("#car_booking_form_date_from").val());
		var d2=new Date(jq("#car_booking_form_date_to").val());       
		var days = ( Math.abs( ( d2-d1 ) / 86400000 ) ); //days between 2 dates		
		var pricePerDay = window.carRentalPrice;
		var totalPrice = days * pricePerDay;
		
		jq('#car_confirm_total_price').html(window.currencySymbol + totalPrice);

		
		jq.ajax({
			url: BYTAjax.ajaxurl,
			data: {
				'action':'book_car_rental_ajax_request',
				'first_name' : firstName,
				'last_name' : lastName,
				'email' : email,
				'phone' : phone,
				'address' : address,
				'town' : town,
				'zip' : zip,
				'country' : country,
				'requirements' : requirements,
				'date_to' : dateTo,
				'date_from' : dateFrom,
				'car_rental_id' : carRentalId,
				'drop_off' : dropOff,
				'c_val_s' : cValS,
				'c_val_1' : cVal1,
				'c_val_2' : cVal2,
				'nonce' : BYTAjax.nonce
			},
			success:function(data) {
				// This outputs the result of the ajax request
				
				if (data == 'captcha_error') {
					jq("div.error div p").html(window.InvalidCaptchaMessage);
					jq("div.error").show();
				} else {
					var returnedId = data;
					jq("div.error div p").html('');
					jq("div.error").hide();
					
					var isReservationOnly = getCarRentalIsReservationOnly(carRentalId);
					
					if (window.useWoocommerceForCheckout && window.wooCartPageUri.length > 0 && !isReservationOnly) {
						addCRProdToCart(returnedId);
					} else {
						hideCarRentalBookingForm();
						showCarRentalConfirmationForm();
						jq('#wait_loading').hide();
					}
				}
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		}); 
	}

	function getCarRentalIsReservationOnly(carRentalId) {
		var isReservationOnly = 0;

		var dataObj = {
			'action':'car_rental_is_reservation_only_request',
			'car_rental_id' : carRentalId,
			'nonce' : BYTAjax.nonce
		}		

		jq.ajax({
			url: BYTAjax.ajaxurl,
			data: dataObj,
			async: false,
			success:function(data) {
				// This outputs the result of the ajax request
				isReservationOnly = data;
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		});

		return isReservationOnly;
	}
	
	function addCRProdToCart(p_id) {
		jq.get(window.site_url + '/?post_type=product&add-to-cart=' + p_id, function() {
			crRedirectToCart();
		});
	}

	function crRedirectToCart() {
		top.location.href = window.wooCartPageUri;
	}

	function showCarRentalConfirmationForm() {
		jq('#car_rental-confirmation-form').show();
	}

	function hideCarRentalConfirmationForm() {
		jq('#car_rental-confirmation-form').hide();
	}
	
	function getCarRentalBookedOutDates(carRentalId, month, year) {
		var dateArray = new Array();

		var dataObj = {
			'action':'car_rental_booked_dates_request',
			'car_rental_id' : carRentalId,
			'month' : month,
			'year' : year,
			'nonce' : BYTAjax.nonce
		}		

		jq.ajax({
			url: BYTAjax.ajaxurl,
			data: dataObj,
			async: false,
			success:function(json) {
				// This outputs the result of the ajax request
				var bookedDates = JSON.parse(json);
				var i = 0;
				for (i = 0; i < bookedDates.length; ++i) {
					dateArray.push(bookedDates[i].booking_date);
				}
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		});

		return dateArray;
	}
