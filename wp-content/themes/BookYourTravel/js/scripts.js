var jq = jQuery.noConflict();

function handleRibbonClick(e) {
	if (jq(this).hasClass('fn')) {
		return true; // allow clicking of links like logout.
	} else {
		jq(".ribbon li").hide();
		if (jq(this).parent().parent().hasClass('open'))
			jq(this).parent().parent().removeClass('open');
		else {
			jq(".ribbon ul").removeClass('open');
			jq(this).parent().parent().addClass('open');
		}
		jq(this).parent().siblings().each(function() {
			jq(this).removeClass('active');
		});
		jq(this).parent().attr('class', 'active'); 
		jq('.ribbon li.active').show();
		jq('.ribbon ul.open li').show();
		
		if (jq(this).hasClass('currency')) {

			var currencyClass= jq(this).attr('class');
			currencyClass = currencyClass.replace('currency ', '').toUpperCase();
			if (window.currentCurrency && window.currentCurrency.length > 0 && window.currentCurrency != currencyClass) {
				var changed = change_currency(currencyClass);
				if (changed) {
					top.location.href = window.site_url;
				}
			}
		}	

		if (window.currentLanguage) {
			jq('.ribbon li.icl-' + window.currentLanguage).show();
		}
		
		return false;
	}
}

jq(window).load(function() {
	// Run code
	resizeFluidItems();
});

function resizeFluidItems() {
	resizeFluidItem(".one-fourth.accommodation_item");
	resizeFluidItem(".one-fourth.location_item");
	resizeFluidItem(".one-fourth.tour_item");
	resizeFluidItem(".one-fourth.car_rental_item");
	resizeFluidItem(".one-fourth.cruise_item");
}

function resizeFluidItem(filter) {
	var maxHeight = 0;            
	jq(filter + " .details").each(function(){
		if (jq(this).height() > maxHeight) { 
			maxHeight = jq(this).height(); 
		}
	});
	jq(filter + ":not(.fluid-item) .details").height(maxHeight);   
}

jq(document).ready(function () {

	//SCROLL TO TOP BUTTON
	jq('.scroll-to-top').click(function () {
		jq('body,html').animate({
			scrollTop: 0
		}, 800);
		return false;
	});

	//HEADER RIBBON NAVIGATION
	jq('.ribbon li').hide();
	jq('.ribbon li.active').show();
	if (window.currentLanguage) {
		jq('.ribbon li.icl-' + window.currentLanguage).show();
	}
	jq(".ribbon li:not([class^='icl-']) a").click(handleRibbonClick);
	if (window.currentLanguage) {
		jq(".ribbon li.icl-" + window.currentLanguage + " a").click(handleRibbonClick);
	}
	
	//LIGHTBOX
	jq("a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square'});
	
	//TABS
	jq(".tab-content").hide();
	jq(".tab-content.initial").show();
	var activeIndex = jq('.inner-nav li.active').index();
	if (activeIndex == -1)
		jq(".inner-nav li:first").addClass("active");

	jq(".inner-nav a").click(function(e){
		jq(".inner-nav li").removeClass("active");
		jq(this).parent().addClass("active");
		var currentTab = jq(this).attr("href");
		jq(".tab-content").hide();
		jq(currentTab).show();
		if (currentTab == "#location") {
			window.InitializeMap();
		}
		e.preventDefault();
	});
	
    var hash = window.location.hash;
	if (hash.length > 0) {
		var hashbang = hash.replace('#', '');
		if (hashbang.length > 0) {
			var anchor = jq('.inner-nav li a[href="#' + hashbang + '"]');
			if (anchor.length > 0) {
				var li = anchor.parent();
				if (li.length > 0) {
					jq(".inner-nav li").removeClass('active');		
					li.addClass('active');	
					jq(".tab-content").hide();
					jq(".tab-content#" + hashbang).show();
				}
			}
		}
	}	
	
	//CSS
	jq('.top-right-nav li:last-child,.social li:last-child,.twins .f-item:last-child,.ribbon li:last-child,.room-types li:last-child,.three-col li:nth-child(3n),.reviews li:last-child,.three-fourth .deals .one-fourth:nth-child(3n),.full .one-fourth:nth-of-type(4n),.locations .one-fourth:nth-child(3n),.pager span:last-child,.get_inspired li:nth-child(5n)').addClass('last');
	jq('.bottom nav li:first-child,.pager span:first-child').addClass('first');
	
	//ROOM TYPES MORE BUTTON
	jq(".more-information").slideUp();
	jq(".more-info").click(function(e) {
		var moreinformation = jq(this).closest("li").find(".more-information");
		var txt = moreinformation.is(':visible') ? '+ more info' : ' - less info';
		jq(this).text(txt);
		moreinformation.stop(true, true).slideToggle("slow");
		e.preventDefault();
	});
	
	jq(".f-item .radio").click(function(e) {
		jq(".f-item").removeClass("active");
		jq(this).parent().addClass("active");
	});	
		
	jq('.grid-view').click(function(e) {
		var currentClass = jq(".three-fourth article").attr("class");
		if (typeof currentClass != 'undefined' && currentClass.length > 0) {
			currentClass = currentClass.replace('last', '');
			currentClass = currentClass.replace('full-width', 'one-fourth');
			jq(".three-fourth article").attr("class", currentClass);
			jq(".three-fourth article:nth-child(3n)").addClass("last");
			jq(".view-type li").removeClass("active");
			jq(this).addClass("active");
			
			resizeFluidItems();
		}
		e.preventDefault();
	});
	
	jq('.list-view').click(function(e) {
		var currentClass = jq(".three-fourth article").attr("class");
		if (typeof currentClass != 'undefined' && currentClass.length > 0) {
			currentClass = currentClass.replace('last', '');
			currentClass = currentClass.replace('one-fourth', 'full-width');
			jq(".three-fourth article").attr("class", currentClass);
			jq(".view-type li").removeClass("active");
			jq(this).addClass("active");
		}
		e.preventDefault();
	});
	
	// LIST AND GRID VIEW TOGGLE
	if (window.defaultResultsView === 0)
		jq('.view-type li.grid-view').trigger('click');
	else
		jq('.view-type li.list-view').trigger('click');

	
	// ACCOMMODATION PAGE GALLERY
	jq('.gallery img:first-child').css('opacity',1);
	
	var i=0,p=1,q=function(){return document.querySelectorAll(".gallery>img")};

	function s(e){
	for(c=0;c<q().length;c++){q()[c].style.opacity="0";q()[e].style.opacity="1"}
	}

	setInterval(function(){
	if(p){i=(i>q().length-2)?0:i+1;s(i)}
	},5000);

});
	
function change_currency(new_currency) {
	var changed = false;

	var dataObj = {
			'action':'change_currency_ajax_request',
			'new_currency' : new_currency,
			'user_id' : window.currentUserId,
			'nonce' : BYTAjax.nonce
		}		
	
	jq.ajax({
		url: BYTAjax.ajaxurl,
		data: dataObj,
		async: false,
		success:function(data) {
			// This outputs the result of the ajax request
			changed = data;
		},
		error: function(errorThrown){
			console.log(errorThrown);
		}
	}); 
	
	return changed;
}

//first, checks if it isn't implemented yet
if (!String.prototype.format) {
  String.prototype.format = function() {
	var args = arguments;
	return this.replace(/{(\d+)}/g, function(match, number) { 
	  return typeof args[number] != 'undefined'
		? args[number]
		: match
	  ;
	});
  };
}

function toggleLightbox(id) {
	if (id != 'login_lightbox' && jq('#login_lightbox').is(":visible"))
		jq('#login_lightbox').hide();
	else if (id != 'register_lightbox' && jq('#register_lightbox').is(":visible"))
		jq('#register_lightbox').hide();
	jq('#' + id).toggle(500);
}	

// Initiate selectnav function
selectnav();
