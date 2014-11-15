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
	
});