	var jq = jQuery.noConflict();

	jq(document).ready(function() {
	
		jq('.contact-' + window.postType).on('click', function(event) {
			showInquiryForm();
			event.preventDefault();
		});	

		jq('.cancel-' + window.postType + '-inquiry').on('click', function(event) {
			hideInquiryForm();
			event.preventDefault();
		});	
		
		jq('.' + window.postType + '-inquiry-form').validate({
			onkeyup: false,
			rules: {
				your_name: "required",
				your_email: { required:true, email:true },
				your_phone: "required",
				your_message: "required"
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
				your_message: window.inquiryFormMessageError,
				your_name: window.inquiryFormNameError,
				your_email: window.inquiryFormEmailError,
				your_phone: window.inquiryFormPhoneError
			},
			submitHandler: function() { processInquiry(); }
		});
	});
		
	function showInquiryForm() {
		jq('.three-fourth').hide();
		jq('.right-sidebar').hide();
		jq('.full-width.' + window.postType + '-inquiry-section').show();
	}

	function hideInquiryForm() {
		jq('.three-fourth').show();
		jq('.right-sidebar').show();
		jq('.full-width.' + window.postType + '-inquiry-section').hide();
	}

	function processInquiry() {
		var your_name = jq('#your_name').val();
		var your_email = jq('#your_email').val();
		var your_phone = jq('#your_phone').val();
		var your_message = jq('#your_message').val();

		var dataObj = {
				'action':'inquiry_ajax_request',
				'your_name' : your_name,
				'your_email' : your_email,
				'your_phone' : your_phone,
				'your_message' : your_message,
				'userId' : window.currentUserId,
				'postId' : window.postId,
				'nonce' : BYTAjax.nonce
			}		
		
		jq.ajax({
			url: BYTAjax.ajaxurl,
			data: dataObj,
			success:function(data) {
				// This outputs the result of the ajax request
				jq('.contact' + window.postType).hide(); // hide the button
				hideInquiryForm();
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		}); 
	}