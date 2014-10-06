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
	
		jq('.radio').bind('click.uniform',
			function (e) {
				if (jq(this).find("span").hasClass('checked')) 
					jq(this).find("input").attr('checked', true);
				else
					jq(this).find("input").attr('checked', false);
			}
		);
		
		jq('.book_tour').on('click', function(event) {
		
			jq('#wait_loading').show();

			jq('#tour_name').html(window.tourTitle);
			window.tourScheduleEntries = getTourScheduleEntries(window.tourId, window.currentDay, window.currentMonth, window.currentYear);
			bindTourDatePicker();						
			bindTourControls(window.tourId);
			
			showTourBookingForm();
			jq('#wait_loading').hide();

			event.preventDefault();
		});
		
		jq('#tour-booking-form').validate({
			onkeyup: false,
			ignore: [],
			rules: {
				first_name: {
					required: true
				},
				last_name: "required",
				email: {
					required: true,
					email: true
				},
				confirm_email: {
					required: true,
					equalTo: "#email"
				},
				phone: "required",
				address: "required",
				town: "required",
				zip: "required",
				country: "required",
				start_date: "required"
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
				first_name: window.bookingFormFirstNameError,
				last_name: window.bookingFormLastNameError,
				email: window.bookingFormEmailError,
				confirm_email: {
					required: window.bookingFormConfirmEmailError1,
					equalTo: window.bookingFormConfirmEmailError2
				},
				phone: window.bookingFormPhoneError,
				address: window.bookingFormAddressError,
				town: window.bookingFormCityError,
				zip: window.bookingFormZipError,
				country: window.bookingFormCountryError,
				start_date: window.bookingFormStartDateError
			},
			submitHandler: function() { processTourBooking(); }
		});
					
		jq('#cancel-tour-booking').on('click', function(event) {
			hideTourBookingForm();
			showTourInfo();
			event.preventDefault();
		});	
	});
		
	function showTourInfo() {
		jq('.three-fourth .gallery').show();
		jq('.three-fourth .inner-nav').show();
		jq('.three-fourth .tab-content').show();
		jq(".tab-content").hide();
		jq(".tab-content:first").show();
		jq(".inner-nav li:first").addClass("active");
	}	

	function showTourBookingForm() {
		jq('#tour-booking-form').show();
		jq('.three-fourth .gallery').hide();
		jq('.three-fourth .inner-nav').hide();
		jq('.three-fourth .tab-content').hide();
	}
	
	function hideTourBookingForm() {
		jq('#tour-booking-form').hide();
	}
	
	function showTourConfirmationForm() {
		jq('#tour-confirmation-form').show();
	}
	
	function hideTourConfirmationForm() {
		jq('#tour-confirmation-form').hide();
	}	
	
	function getMaxPeople(tourScheduleId) {
		var max_people = 0;
		var dataObj = {
			'action':'tour_max_people_ajax_request',
			'tour_schedule_id' : tourScheduleId,
			'nonce' : BYTAjax.nonce
		}		
		jq.ajax({
			url: BYTAjax.ajaxurl,
			data: dataObj,
			async: false,
			success:function(data) {
				// This outputs the result of the ajax request
				max_people = data;
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		}); 
		
		return max_people;
	}

	function processTourBooking() {
	
		jq('#wait_loading').show();
		
		var firstName = jq('#first_name').val();
		var lastName = jq('#last_name').val();
		var email = jq('#email').val();
		var phone = jq('#phone').val();
		var address = jq('#address').val();
		var town = jq('#town').val();
		var zip = jq('#zip').val();
		var country = jq('#country').val();
		var requirements = jq('#requirements').val();
		var tourScheduleId = getTourScheduleId(window.tourId, jq("#start_date").val());
		var tourStartDate = jq("#start_date").val();
		var adults = jq("#booking_form_adults").val();
		var children = jq("#booking_form_children").val();
		
		var cValS = jq('#c_val_s').val();
		var cVal1 = jq('#c_val_1').val();
		var cVal2 = jq('#c_val_2').val();
		
		jq("#confirm_first_name").html(firstName);
		jq("#confirm_last_name").html(lastName);
		jq("#confirm_email_address").html(email);
		jq("#confirm_phone").html(phone);
		jq("#confirm_street").html(address);
		jq("#confirm_town").html(town);
		jq("#confirm_zip").html(zip);
		jq("#confirm_country").html(country);
		jq("#confirm_requirements").html(requirements);
		jq("#confirm_tour_start_date").html(tourStartDate);
		jq("#confirm_tour_title").html(window.tourTitle);
		jq("#confirm_tour_adults").html(adults);
		jq("#confirm_tour_children").html(children);
		jq("#confirm_tour_total").html(window.currencySymbol + window.rateTableTotalPrice);
		
		jq.ajax({
			url: BYTAjax.ajaxurl,
			data: {
				'action':'book_tour_ajax_request',
				'first_name' : firstName,
				'last_name' : lastName,
				'email' : email,
				'phone' : phone,
				'address' : address,
				'town' : town,
				'zip' : zip,
				'country' : country,
				'requirements' : requirements,
				'tour_schedule_id' : tourScheduleId,
				'tour_start_date' : tourStartDate,
				'adults' : adults,
				'children' : children,				
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
					var returned_id = data;
					jq("div.error div p").html('');
					jq("div.error").hide();
					
					var isReservationOnly = getTourIsReservationOnly(window.tourId);
					
					if (window.useWoocommerceForCheckout && window.wooCartPageUri.length > 0 && !isReservationOnly) {
						addTrProdToCart(returned_id);
					} else {
						hideTourBookingForm();
						showTourConfirmationForm();
						jq('#wait_loading').hide();
					}
				}
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		}); 
	}
	
	function getTourIsReservationOnly(tourId) {
		var isReservationOnly = 0;

		var dataObj = {
			'action':'tour_is_reservation_only_request',
			'tour_id' : tourId,
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
	
	function addTrProdToCart(p_id) {
		jq.get(window.site_url + '/?post_type=product&add-to-cart=' + p_id, function() {
			trRedirectToCart();
		});
	}

	function trRedirectToCart() {
		top.location.href = window.wooCartPageUri;
	}	
	
	function  bindTourDatePicker() {	

		if (typeof jq('#tour_schedule_datepicker') !== 'undefined') {

			jq('#tour_schedule_datepicker').datepicker({
				dateFormat: window.datepickerDateFormat,
				numberOfMonths: 1,
				minDate: 0,
				beforeShowDay: function(d) {
					var date1 = null;
					if (jq("#start_date").val())
						date1 = jq.datepicker.parseDate(window.datepickerDateFormat, jq("#start_date").val());

					if (window.tourScheduleEntries) {
						var dateTextForCompare1 = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2) + " 00:00:00";
						var dateTextForCompare2 = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2);
						if (jq.inArray(dateTextForCompare1, window.tourScheduleEntries) == -1 && jq.inArray(dateTextForCompare2, window.tourScheduleEntries) == -1)
							return [false, 'ui-datepicker-unselectable ui-state-disabled'];
					}
					
					return [true, date1 && (d.getTime() == date1.getTime()) ? "dp-highlight" : ""];
				},
				onSelect: function(dateText, inst) {

					jq(".price_row").show();
				
					jq("#start_date_span").html(dateText);
					jq("#start_date").val(dateText);
					var startDateText = jq("#start_date").val();
					var date1 = jq.datepicker.parseDate(window.datepickerDateFormat, startDateText);
					window.startDate = date1;
					bindTourRatesTable();
				},
				onChangeMonthYear: function (year, month, inst) {
					window.currentMonth = month;
					window.currentYear = year;
					window.currentDay = 1;
					window.tourScheduleEntries = getTourScheduleEntries(window.tourId, window.currentDay, window.currentMonth, window.currentYear);
					bindTourDatePicker();
				}
			});
		}
	
	}

	function getTourScheduleId(tourId, date) {

		var scheduleId = 0;

		var dataObj = {
			'action':'tour_available_schedule_id_request',
			'tourId' : tourId,
			'dateValue' : date,
			'nonce' : BYTAjax.nonce
		}		

		jq.ajax({
			url: BYTAjax.ajaxurl,
			data: dataObj,
			async: false,
			success:function(data) {
				// This outputs the result of the ajax request
				scheduleId = data;
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		});

		return scheduleId;
	}
	
	function getTourScheduleEntries(tourId, day, month, year) {
		var dateArray = new Array();

		var dataObj = {
			'action':'tour_schedule_dates_request',
			'tourId' : tourId,
			'month' : month,
			'year' : year,
			'day' : day,
			'nonce' : BYTAjax.nonce
		}		

		jq.ajax({
			url: BYTAjax.ajaxurl,
			data: dataObj,
			async: false,
			success:function(json) {
				// This outputs the result of the ajax request
				var scheduleDates = JSON.parse(json);
				var i = 0;
				for (i = 0; i < scheduleDates.length; ++i) {
					if (scheduleDates[i].tour_date != null) {
						dateArray.push(scheduleDates[i].tour_date);
					}
				}
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		});

		return dateArray;
	}

	function bindTourRatesTable() {
		
		jq(".price_row").show();

		jq('table.breakdown thead').html('');
		jq('table.breakdown tfoot').html('');
		jq('table.breakdown tbody').html('');

		var adults = jq('#booking_form_adults').val();
		if (!adults)
			adults = 1;
			
		var children = jq('#booking_form_children').val();
		if (!children)
			children = 0;
			
		var colCount = 2;
		var headerRow = '<tr class="rates_head_row">';
		
		headerRow += '<th>' + window.dateLabel + '</th>';		
		
		if (!window.tourIsPricePerGroup) {
			headerRow += '<th>' + window.adultCountLabel + '</th>';
			headerRow += '<th>' + window.pricePerAdultLabel + '</th>';
			headerRow += '<th>' + window.childCountLabel + '</th>';
			headerRow += '<th>' + window.pricePerChildLabel + '</th>';
			colCount = 6;
		}
		
		headerRow += '<th>' + window.pricePerDayLabel + '</th>';		
		
		headerRow += '</tr>';

		jq('table.breakdown thead').append(headerRow);	
		
		var footerRow = '<tr>';
		footerRow += '<th colspan="' + (colCount - 1) + '">' + window.priceTotalLabel + '</th>';
		footerRow += '<td class="total_price">0</td>';
		footerRow += '</tr>';

		jq('table.breakdown tfoot').append(footerRow);
		
		if (window.startDate) {
		
			jq('#datepicker_loading').show();
		
			var startTime = window.startDate.valueOf();
			
			window.rateTableTotalPrice = 0;
			
			buildTourRateRow(startTime, adults, children);
		}
		
	}	
	
	function buildTourRateRow(startTime, adults, children) {
	
		var price = 0;
		
		var d = new Date(startTime);
		var day = d.getDate();
		var month = d.getMonth() + 1;
		var year = d.getFullYear();
		var dateValue = day + "-" + month + "-" + year; 

		var dataObj = {
			'action':'tour_get_price_request',
			'tourId' : window.tourId,
			'dateValue' : dateValue,
			'nonce' : BYTAjax.nonce
		}		

		jq.ajax({
			url: BYTAjax.ajaxurl,
			data: dataObj,
			dataType: 'json',
			success:function(prices) {
				var tableRow = '';
				// This outputs the result of the ajax request
				window.rateTableRowIndex++;
				var pricePerTour = parseFloat(prices.price);
				var pricePerChild = 0;
				var totalPrice = 0;
				
				tableRow += '<tr>';
				tableRow += '<td>' + dateValue + '</td>';
				
				if (!window.tourIsPricePerGroup) {
					pricePerChild = parseFloat(prices.child_price);
					tableRow += '<td>' + adults + '</td>';
					tableRow += '<td>' + window.currencySymbol + pricePerTour + '</td>';
					tableRow += '<td>' + children + '</td>';
					tableRow += '<td>' + window.currencySymbol + pricePerChild + '</td>';
					totalPrice = (pricePerTour * adults) + (pricePerChild * children);
				} else {
					totalPrice = pricePerTour;
				}					
				
				jq('.total_price').html(window.currencySymbol + ' ' + totalPrice);
				jq("#confirm_total").html(window.currencySymbol + ' ' + totalPrice)
				
				tableRow += '<td>' + window.currencySymbol + totalPrice + '</td>';
				window.rateTableTotalPrice = totalPrice;
				
				tableRow += '</tr>';
				
				jq('table.breakdown tbody').append(tableRow);
				
				jq('#datepicker_loading').hide();
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		});
	
	}

	function bindTourControls(tour_id) {

		if (jq('#booking_form_adults option').size() == 0) {
		
			var max_count = 5;
			var max_child_count = 5;
			
			for ( var i = 1; i <= max_count; i++ ) {
				jq('<option ' + (i == 1 ? 'selected' : '') + '>').val(i).text(i).appendTo('#booking_form_adults');
			}
			jq("#booking_form_adults").uniform();
			
			jq('#booking_form_adults').on('change', function (e) {
				var optionSelected = jq("option:selected", this);
				var valueSelected = this.value;
				bindTourRatesTable();				
			});

			if (max_child_count > 0) {
				jq('<option selected>').val(0).text(0).appendTo('#booking_form_children');
				for ( var i = 1; i <= max_child_count; i++ ) {
					jq('<option>').val(i).text(i).appendTo('#booking_form_children');
				}
				jq("#booking_form_children").uniform();
				
				jq('#booking_form_children').on('change', function (e) {
					var optionSelected = jq("option:selected", this);
					var valueSelected = this.value;
					bindTourRatesTable();
				});
			} else {
				jq('.booking_form_children').hide();
			}
		}
		
	}