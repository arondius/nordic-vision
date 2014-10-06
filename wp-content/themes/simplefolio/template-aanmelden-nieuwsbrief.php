<?php
/*
Template Name: Aanmelden nieuwsbrief
*/
?>
<?php get_header();

// Load current page postdata

	global $post;

	$pid			=	$post->ID; // Get the ID of the current post

	$children		=	wp_list_pages('title_li=&child_of='.$post->ID.'&exclude=964,971,1327,2032,2041&echo=0');

	$ancestors 		= 	get_post_ancestors($pid); // Get all ancestors of the current post

	if (empty($ancestors)){

		$top_ancestor =	$pid;

	} else {

	$top_ancestor 	=	end($ancestors); // Get the ID of the top ancestor

	}

//	function

/*	echo '<pre style="border-bottom: 1px solid red;">';
	print_r($pid);
	echo '<br />';
	print_r($children);
	echo '<br />';
	print_r($ancestors);
	echo '<br />';
	print_r($top_ancestor);
	echo '</pre>';

*/

// Check if the current page has children or is a child
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<?php
					if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
					echo '<div id="visual">';
  					the_post_thumbnail();
					echo '</div>';
}
					endwhile;
					endif;
					wp_reset_query();
					?>

<div id="content-container">
<?php

	if ( (is_page()) && ($post->post_parent) || ($children))
	{
		echo '<div id="subnav">';
		echo '<ul>';
		wp_list_pages('title_li=&child_of='.$top_ancestor.'&exclude=964,971,1327,2032,2041');
		echo '</ul>';
		echo '</div>';
		echo '<div id="content-2col" class="content">';
	}
	else {

	echo '<div class="content">';

	}

	?>

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<div class="entry">
					<h1 class="title"><?php the_title(); ?></h1>
					<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
					</div>
					<?php endwhile; endif; ?>
					<!-- Begin MailChimp Signup Form -->
<script type="text/javascript">
// delete this script tag and use a "div.mce_inline_error{ XXX !important}" selector
// or fill this in and it will be inlined when errors are generated
var mc_custom_error_style = '';
</script>
<div id="mc_embed_signup">
<form action="http://nordicvision.us2.list-manage.com/subscribe/post?u=ef22a836daeb46856c0417050&amp;id=ec0ad2a51c" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
<div class="indicate-required">* verplichte velden</div>
<div class="mc-field-group">
<label for="mce-EMAIL">E-mail adres <strong class="note-required">*</strong>
</label>
<input type="text" value="" name="EMAIL" class="required email" id="mce-EMAIL">
</div>
<div class="mc-field-group">
<label for="mce-FNAME">Voornaam </label>
<input type="text" value="" name="FNAME" class="" id="mce-FNAME">
</div>
<div class="mc-field-group">
<label for="mce-LNAME">Achternaam </label>
<input type="text" value="" name="LNAME" class="" id="mce-LNAME">
</div>
		<div id="mce-responses">
			<div class="response" id="mce-error-response" style="display:none"></div>
			<div class="response" id="mce-success-response" style="display:none"></div>
		</div>
		<div><input type="submit" value="Verzenden" name="subscribe" id="mc-embedded-subscribe" class="btn"></div>
	<a href="#" id="mc_embed_close" class="mc_embed_close">Close</a>
</form>
</div>
<script type="text/javascript">
var fnames = new Array();var ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';
try {
    var jqueryLoaded=jQuery;
    jqueryLoaded=true;
} catch(err) {
    var jqueryLoaded=false;
}
var head= document.getElementsByTagName('head')[0];
if (!jqueryLoaded) {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js';
    head.appendChild(script);
    if (script.readyState && script.onload!==null){
        script.onreadystatechange= function () {
              if (this.readyState == 'complete') mce_preload_check();
        }
    }
}
var script = document.createElement('script');
script.type = 'text/javascript';
script.src = 'http://downloads.mailchimp.com/js/jquery.form-n-validate.js';
head.appendChild(script);
var err_style = '';
try{
    err_style = mc_custom_error_style;
} catch(e){
    err_style = 'margin: 1em 0 0 0; padding: 1em 0.5em 0.5em 0.5em; background: ERROR_BGCOLOR none repeat scroll 0% 0%; font-weight: bold; float: left; z-index: 1; width: 80%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: ERROR_COLOR;';
}
var head= document.getElementsByTagName('head')[0];
var style= document.createElement('style');
style.type= 'text/css';
if (style.styleSheet) {
  style.styleSheet.cssText = '.mce_inline_error {' + err_style + '}';
} else {
  style.appendChild(document.createTextNode('.mce_inline_error {' + err_style + '}'));
}
head.appendChild(style);
setTimeout('mce_preload_check();', 250);

var mce_preload_checks = 0;
function mce_preload_check(){
    if (mce_preload_checks>40) return;
    mce_preload_checks++;
    try {
        var jqueryLoaded=jQuery;
    } catch(err) {
        setTimeout('mce_preload_check();', 250);
        return;
    }
    try {
        var validatorLoaded=jQuery("#fake-form").validate({});
    } catch(err) {
        setTimeout('mce_preload_check();', 250);
        return;
    }
    mce_init_form();
}
function mce_init_form(){
    jQuery(document).ready( function($) {
      var options = { errorClass: 'mce_inline_error', errorElement: 'div', onkeyup: function(){}, onfocusout:function(){}, onblur:function(){}  };
      var mce_validator = $("#mc-embedded-subscribe-form").validate(options);
      $("#mc-embedded-subscribe-form").unbind('submit');//remove the validator so we can get into beforeSubmit on the ajaxform, which then calls the validator
      options = { url: 'http://nordicvision.us2.list-manage1.com/subscribe/post-json?u=ef22a836daeb46856c0417050&id=ec0ad2a51c&c=?', type: 'GET', dataType: 'json', contentType: "application/json; charset=utf-8",
                    beforeSubmit: function(){
                        $('#mce_tmp_error_msg').remove();
                        $('.datefield','#mc_embed_signup').each(
                            function(){
                                var txt = 'filled';
                                var fields = new Array();
                                var i = 0;
                                $(':text', this).each(
                                    function(){
                                        fields[i] = this;
                                        i++;
                                    });
                                $(':hidden', this).each(
                                    function(){
                                        var bday = false;
                                        if (fields.length == 2){
                                            bday = true;
                                            fields[2] = {'value':1970};//trick birthdays into having years
                                        }
                                    	if ( fields[0].value=='MM' && fields[1].value=='DD' && (fields[2].value=='YYYY' || (bday && fields[2].value==1970) ) ){
                                    		this.value = '';
									    } else if ( fields[0].value=='' && fields[1].value=='' && (fields[2].value=='' || (bday && fields[2].value==1970) ) ){
                                    		this.value = '';
									    } else {
	                                        this.value = fields[0].value+'/'+fields[1].value+'/'+fields[2].value;
	                                    }
                                    });
                            });
                        return mce_validator.form();
                    },
                    success: mce_success_cb
                };
      $('#mc-embedded-subscribe-form').ajaxForm(options);

    });
}
function mce_success_cb(resp){
    $('#mce-success-response').hide();
    $('#mce-error-response').hide();
    if (resp.result=="success"){
        $('#mce-'+resp.result+'-response').show();
        $('#mce-'+resp.result+'-response').html(resp.msg);
        $('#mc-embedded-subscribe-form').each(function(){
            this.reset();
    	});
    } else {
        var index = -1;
        var msg;
        try {
            var parts = resp.msg.split(' - ',2);
            if (parts[1]==undefined){
                msg = resp.msg;
            } else {
                i = parseInt(parts[0]);
                if (i.toString() == parts[0]){
                    index = parts[0];
                    msg = parts[1];
                } else {
                    index = -1;
                    msg = resp.msg;
                }
            }
        } catch(e){
            index = -1;
            msg = resp.msg;
        }
        try{
            if (index== -1){
                $('#mce-'+resp.result+'-response').show();
                $('#mce-'+resp.result+'-response').html(msg);
            } else {
                err_id = 'mce_tmp_error_msg';
                html = '<div id="'+err_id+'" style="'+err_style+'"> '+msg+'</div>';

                var input_id = '#mc_embed_signup';
                var f = $(input_id);
                if (ftypes[index]=='address'){
                    input_id = '#mce-'+fnames[index]+'-addr1';
                    f = $(input_id).parent().parent().get(0);
                } else if (ftypes[index]=='date'){
                    input_id = '#mce-'+fnames[index]+'-month';
                    f = $(input_id).parent().parent().get(0);
                } else {
                    input_id = '#mce-'+fnames[index];
                    f = $().parent(input_id).get(0);
                }
                if (f){
                    $(f).append(html);
                    $(input_id).focus();
                } else {
                    $('#mce-'+resp.result+'-response').show();
                    $('#mce-'+resp.result+'-response').html(msg);
                }
            }
        } catch(e){
            $('#mce-'+resp.result+'-response').show();
            $('#mce-'+resp.result+'-response').html(msg);
        }
    }
}

</script>
<!--End mc_embed_signup-->
				<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
		</div>
<?php get_footer(); ?>