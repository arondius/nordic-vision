var jq = jQuery.noConflict();
	
	jq('#media-items').bind('DOMNodeInserted',function(){
		jq('input[value="Insert into Post"]').each(function(){
				jq(this).attr('value','Use This Image');
		});
	});
	
	jq('.custom_upload_image_button').click(function() {
		formfield = jq(this).siblings('.custom_upload_image');
		preview = jq(this).siblings('.custom_preview_image');
		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		window.send_to_editor = function(html) {
			imgurl = jq('img',html).attr('src');
			classes = jq('img', html).attr('class');
			id = classes.replace(/(.*?)wp-image-/, '');
			formfield.val(id);
			preview.attr('src', imgurl);
			tb_remove();
		}
		return false;
	});
	
	jq('.custom_clear_image_button').click(function() {
		var defaultImage = jq(this).parent().siblings('.custom_default_image').text();
		jq(this).parent().siblings('.custom_upload_image').val('');
		jq(this).parent().siblings('.custom_preview_image').attr('src', defaultImage);
		return false;
	});
	
	jq('.repeatable-add').click(function() {
		field = jq(this).closest('td').find('.custom_repeatable li:last').clone(true);
		fieldLocation = jq(this).closest('td').find('.custom_repeatable li:last');
		jq('input', field).val('').attr('name', function(index, name) {
			return name.replace(/(\d+)/, function(fullMatch, n) {
				return Number(n) + 1;
			});
		})
		field.insertAfter(fieldLocation, jq(this).closest('td'))
		return false;
	});
	
	jq('.repeatable-remove').click(function(){
		jq(this).parent().remove();
		return false;
	});
		
	jq('.custom_repeatable').sortable({
		opacity: 0.6,
		revert: true,
		cursor: 'move',
		handle: '.sort'
	});

