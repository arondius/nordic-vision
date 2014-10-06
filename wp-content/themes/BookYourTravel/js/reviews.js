var jq = jQuery.noConflict();

jq(document).ready(function() {

	jq('.review-' + window.postType).on('click', function(event) {
		showReviewForm();
		event.preventDefault();
	});	
	
	jq('.cancel-' + window.postType + '-review').on('click', function(event) {
		hideReviewForm();
		event.preventDefault();
	});	
	
	jq('.review-' + window.postType + '-form').validate({
		onkeyup: false,
		rules: {
			likes: "required",
			dislikes: "required"
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
			likes: window.reviewFormLikesError,
			dislikes: window.reviewFormDislikesError
		},
		submitHandler: function() { processReview(); }
	});	
	
	function showReviewForm() {
		jq('.three-fourth').hide();
		jq('.right-sidebar').hide();
		jq('.full-width.review-' + window.postType + '-section').show();
	}
		
	function hideReviewForm() {
		jq('.three-fourth').show();
		jq('.right-sidebar').show();
		jq('.full-width.review-' + window.postType + '-section').hide();
	}
				
	function processReview() {
		var likes = jq('#likes').val();
		var dislikes = jq('#dislikes').val();

		var dataObj = {
				'action':'review_ajax_request',
				'likes' : likes,
				'dislikes' : dislikes,
				'userId' : window.currentUserId,
				'postId' : window.postId,
				'nonce' : BYTAjax.nonce
			}		
		
		for (var i = 0; i < window.reviewFields.length; i++) {
			var slug = window.reviewFields[i];
			dataObj["reviewField_" + slug] = jq("input[type='radio'][name='reviewField_" + slug + "']:checked").val();
		}
		
		jq.ajax({
			url: BYTAjax.ajaxurl,
			data: dataObj,
			success:function(data) {
				// This outputs the result of the ajax request
				jq('.review-' + window.postType).hide(); // hide the button
				hideReviewForm();
			},
			error: function(errorThrown){

			}
		}); 
	}
});