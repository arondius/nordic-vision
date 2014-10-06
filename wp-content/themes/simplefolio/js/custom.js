(function($) {
$(document).ready(function() {
	equalHeight($(".item p"));
	$(".home_widgets .widget:first").addClass("first");
	$(".footernav  li:last").css("border-right", "0");
	$(".navigation .alignleft:empty").hide();
	$(".navigation .alignright:empty").hide();
	$(".comment-nav .alignright:empty").hide();
	$(".comment-nav .alignleft:empty").hide();
	$(".navigation:empty").hide();
	$(".comment-nav:empty").hide();
	$('ul.sf-menu').superfish();

	// Google Analytics event tracking for the booking form button
	$('.cta-boeking').click(function() {
	  ga('send', 'event', 'button', 'click', 'boekingsform');
	});

	/*$("#visual").myslider({

		timeOut: 5000

	});*/

	$(".stripeMe tr").mouseover(function() {
			$(this).addClass("over");})
			.mouseout(function() {
			$(this).removeClass("over")
		});
	$(".stripeMe tr:nth-child(even)").addClass("alt");


});

DD_roundies.addRule('.container', '0 0 10px 10px', true);
DD_roundies.addRule('.qbutton a, blockquote,.comment-reply-link', '10px', true);
DD_roundies.addRule('#sidebarsearch div, .navigation a, .comment-nav a', '20px', true);
DD_roundies.addRule('.wp-caption,.portfnav a', '5px', true);
DD_roundies.addRule('.commentlist li', '10px', true);

function equalHeight(group) {
	tallest = 0;
	group.each(function() {
		thisHeight = $(this).height();
		if(thisHeight > tallest) {
			tallest = thisHeight;
		}
	});
	group.height(tallest);
}

})(jQuery)