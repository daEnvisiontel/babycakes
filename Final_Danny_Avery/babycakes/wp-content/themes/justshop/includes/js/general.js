/*!
 * ScrollTo 1.4.3.1
 * http://flesler.blogspot.com
 */
;(function($){var h=$.scrollTo=function(a,b,c){$(window).scrollTo(a,b,c)};h.defaults={axis:'xy',duration:parseFloat($.fn.jquery)>=1.3?0:1,limit:true};h.window=function(a){return $(window)._scrollable()};$.fn._scrollable=function(){return this.map(function(){var a=this,isWin=!a.nodeName||$.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!isWin)return a;var b=(a.contentWindow||a).document||a.ownerDocument||a;return/webkit/i.test(navigator.userAgent)||b.compatMode=='BackCompat'?b.body:b.documentElement})};$.fn.scrollTo=function(e,f,g){if(typeof f=='object'){g=f;f=0}if(typeof g=='function')g={onAfter:g};if(e=='max')e=9e9;g=$.extend({},h.defaults,g);f=f||g.duration;g.queue=g.queue&&g.axis.length>1;if(g.queue)f/=2;g.offset=both(g.offset);g.over=both(g.over);return this._scrollable().each(function(){if(e==null)return;var d=this,$elem=$(d),targ=e,toff,attr={},win=$elem.is('html,body');switch(typeof targ){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(targ)){targ=both(targ);break}targ=$(targ,this);if(!targ.length)return;case'object':if(targ.is||targ.style)toff=(targ=$(targ)).offset()}$.each(g.axis.split(''),function(i,a){var b=a=='x'?'Left':'Top',pos=b.toLowerCase(),key='scroll'+b,old=d[key],max=h.max(d,a);if(toff){attr[key]=toff[pos]+(win?0:old-$elem.offset()[pos]);if(g.margin){attr[key]-=parseInt(targ.css('margin'+b))||0;attr[key]-=parseInt(targ.css('border'+b+'Width'))||0}attr[key]+=g.offset[pos]||0;if(g.over[pos])attr[key]+=targ[a=='x'?'width':'height']()*g.over[pos]}else{var c=targ[pos];attr[key]=c.slice&&c.slice(-1)=='%'?parseFloat(c)/100*max:c}if(g.limit&&/^\d+$/.test(attr[key]))attr[key]=attr[key]<=0?0:Math.min(attr[key],max);if(!i&&g.queue){if(old!=attr[key])animate(g.onAfterFirst);delete attr[key]}});animate(g.onAfter);function animate(a){$elem.animate(attr,f,g.easing,a&&function(){a.call(this,e,g)})}}).end()};h.max=function(a,b){var c=b=='x'?'Width':'Height',scroll='scroll'+c;if(!$(a).is('html,body'))return a[scroll]-$(a)[c.toLowerCase()]();var d='client'+c,html=a.ownerDocument.documentElement,body=a.ownerDocument.body;return Math.max(html[scroll],body[scroll])-Math.min(html[d],body[d])};function both(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);


// image preloader 
jQuery.fn.preloader=function(e){var t={delay:200,preload_parent:"a",check_timer:300,ondone:function(){},oneachload:function(e){},fadein:500};var e=jQuery.extend(t,e),n=jQuery(this),r=n.find("img").css({visibility:"hidden",opacity:0}),i,s=0,o=0,u=[],a=e.delay,f=function(){i=setInterval(function(){if(s>=u.length){clearInterval(i);e.ondone();return}for(o=0;o<r.length;o++){if(r[o].complete==true){if(u[o]==false){u[o]=true;e.oneachload(r[o]);s++;a=a+e.delay}jQuery(r[o]).css("visibility","visible").delay(a).animate({opacity:1},e.fadein,function(){jQuery(this).parent().removeClass("preloader")})}}},e.check_timer)};r.each(function(){if(jQuery(this).parent(e.preload_parent).length==0)jQuery(this).wrap("<a class='preloader' />");else jQuery(this).parent().addClass("preloader");u[o++]=false});r=jQuery.makeArray(r);var l=jQuery("<img />",{id:"loadingicon",src:"../wp-content/themes/justshop/images/preloader.gif"}).hide().appendTo("body");i=setInterval(function(){if(l[0].complete==true){clearInterval(i);f();l.remove();return}},100)}


/*-----------------------------------------------------------------------------------*/
/* GENERAL SCRIPTS */
/*-----------------------------------------------------------------------------------*/
jQuery(document).ready(function($){
	"use strict";
	// Fix dropdowns in Android
	if ( /Android/i.test( navigator.userAgent ) && jQuery( window ).width() > 769 ) {
		$( '.nav li:has(ul)' ).doubleTapToGo();
	}

	// Table alt row styling
	jQuery( '.entry table tr:odd' ).addClass( 'alt-table-row' );

	// FitVids - Responsive Videos
	jQuery( '.post, .widget, .panel, .page, #featured-slider .slide-media' ).fitVids();

	// Add class to parent menu items with JS until WP does this natively
	jQuery("ul.sub-menu, ul.children").parents('li').addClass('parent');


	// Responsive Navigation (switch top drop down for select)
	jQuery('ul#top-nav').mobileMenu({
		switchWidth: 769,					//width (in px to switch at)
		topOptionText: 'Select a page',		//first option text
		indentString: '&nbsp;&nbsp;&nbsp;'	//string for indenting nested items
	});



	// Show/hide the main navigation
	jQuery('.nav-toggle').click(function() {
		jQuery('#navigation, .header-top .account, .header-top .cart').slideToggle('fast', function() {
			return false;
			// Animation complete.
	});
	});

	// Stop the navigation link moving to the anchor (Still need the anchor for semantic markup)
	jQuery('.nav-toggle a').click(function(e) {
        e.preventDefault();
    });

    jQuery(function(){
		jQuery('.star-rating, ul.cart a.cart-contents, .cart a.remove, .added_to_cart, a.tiptip').tipTip({
			defaultPosition: "top",
			delay: 0
		});
	});

	// Show / hide the shipping address header on the checkout
	$('#shiptobilling input').change(function(){
		$('#shiptobilling + h3').hide();
		if (!$(this).is(':checked')) {
			$('#shiptobilling + h3').slideDown();
		}
	}).change();

	// Only apply the fixed stuff to desktop devices, sticky menu

	if ( (! navigator.userAgent.match(/(iPod|iPhone|iPad|Android)/)) && $('.sticky-menu').length ) {
		// #navigation fixed
		if (jQuery(window).width() > 768) {
				$('#header').each(function(){ $(this).clone().removeClass('TThdr').addClass('fixed').appendTo('#wrapper'); });
						$('#header.fixed #navigation').addClass('fixed');
				$(window).scroll(function(){
					if (jQuery(window).width() > 768) {
					   if ($(window).scrollTop() > 300){
						$('body').addClass('scrolled');
						   if ($('.admin-bar').length){
							$('#header.fixed, #navigation.fixed').css('top',32);
						   } else {
							$('#header.fixed, #navigation.fixed').css('top',0);
						   }
					   } else {
						$('#header.fixed, #navigation.fixed').css('top',-100);
						$('body').removeClass('scrolled');
					   }
					} else {
					   $('#header.fixed, #navigation.fixed').removeAttr('style');
					   /*$('#navigation.fixed').removeClass('fixed');*/
					   $('body').removeClass('scrolled, sticky-menu');
					}
				});
		}
		$(window).resize(function(){
			if (jQuery(window).width() < 769) {
				$('#header.fixed').remove();
			}
		});
	}
});
/*!
 * Scripts
 */
jQuery(document).ready(function($){
		"use strict";
		var Default = {
		utils : {
			miscellaneous : function(){
                setTimeout(function(){
                    $('body').addClass('loaded');
                }, 3000);
				$('ul.nav li:not(.megamenu)').css('position','relative');
				$('.gallery-a li img').wrap('<div class="wrapper"></div>');
				$('.image-a').append('<a class="close">Delete</a>');	
				$('.rating-d').wrapInner('<span></span>');
				$('#footer-wrap').append('<a href="#wrapper" class="totop">Scroll to top</a>');
				$('#footer-wrap a.totop[href^="#"]').on('click',function(){ if($(this).attr('href')[0] === '#'){ $.scrollTo($(this).attr('href'),500); } return false; });	

				$('#footer-widgets .widget_recent_comments ul li .shade-a').remove(); // ns
				$('.table-c').after('<div class="shade-b"></div>');
				$('#portfolio-gallery .portfolio-items, .testi_slide .testimonials-list').wrapInner('<div class="inner"></div>');
				$('.widescreen .double-a').wrap('<div class="innerd"></div>'); 
  			 	$('.woocommerce-product-search').attr('id', 'searchform');

				if ( ! $.browser.msie && (!navigator.userAgent.match(/Trident.*rv[ :]*11\./)) && ! $('.home-showcase').length && ! $('.page-template-template-portfolio-one-php').length ) { // if not IE, IE11 , load the nice img preloader.
				  $(".yith_magnifier_zoom_wrap, .portfolio figure").preloader();
				}
				$('.woocommerce[class*="columns"]').each(function(){ $(this).addClass('woocommerce-columns-'+$(this).attr('class').match('[0-9]+')); });

				$('.home-featured ul.products').wrapInner('<div class="innerfet"></div>'); //wc
				$('.wpb_row .home-featured ul.products .innerfet').removeClass('innerfet').addClass('innerfetvc'); // items placed using vc has width difference.

				if ($('body').hasClass('rtl')) { // if RTL, disable control as bx-cloning is not working in rtl mode
                    if ($('#main.col-left .home-featured ul.products > .innerfetvc').length ) {
                    $('.home-featured ul.products > .innerfetvc').bxSlider({ infiniteLoop: false, hideControlOnEnd: true, auto: true, pause: 6000, autoHover: true, pager: false, controls: true, useCSS: false, minSlides: 1, maxSlides: 3, moveSlides: 1, slideWidth: 198, slideMargin: 11 });
                    }
                    else {
                    $('.home-featured ul.products > .innerfetvc').bxSlider({ infiniteLoop: false, hideControlOnEnd: true, auto: true, pause: 6000, autoHover: true, pager: false, controls: true, useCSS: false, minSlides: 1, maxSlides: 4, moveSlides: 1, slideWidth: 218, slideMargin: 11 });
                    }
                }
                else {
                    if ($('#main.col-left .home-featured ul.products > .innerfetvc').length ) {
                    $('.home-featured ul.products > .innerfetvc').bxSlider({ auto: true, pause: 6000, autoHover: true, pager: false, controls: true, useCSS: false, minSlides: 1, maxSlides: 3, moveSlides: 1, slideWidth: 198, slideMargin: 11 });
                    }
                    else {
                    $('.home-featured ul.products > .innerfetvc').bxSlider({ auto: true, pause: 6000, autoHover: true, pager: false, controls: true, useCSS: false, minSlides: 1, maxSlides: 4, moveSlides: 1, slideWidth: 218, slideMargin: 11 });
                    }
                }
				if ($('body').hasClass('rtl')) { // if RTL, disable control as bx-cloning is not working in rtl mode
                    if ($('#main.col-left .home-featured ul.products > .innerfet').length ) {
                    $('.home-featured ul.products > .innerfet').bxSlider({ infiniteLoop: false, hideControlOnEnd: true, auto: true, pause: 6000, autoHover: true, pager: false, controls: true, useCSS: false, minSlides: 1, maxSlides: 3, moveSlides: 1, slideWidth: 198, slideMargin: 11 });
                    }
                    else {
                    $('.home-featured ul.products > .innerfet').bxSlider({ infiniteLoop: false, hideControlOnEnd: true, auto: true, pause: 6000,autoHover: true, pager: false, controls: true, useCSS: false, minSlides: 1, maxSlides: 4, moveSlides: 1, slideWidth: 228, slideMargin: 11 });
                    }
                }
                else {
                    if ($('#main.col-left .home-featured ul.products > .innerfet').length ) {
                    $('.home-featured ul.products > .innerfet').bxSlider({ auto: true, pause: 6000, autoHover: true, pager: false, controls: true, useCSS: false, minSlides: 1, maxSlides: 3, moveSlides: 1, slideWidth: 198, slideMargin: 11 });
                    }
                    else {
                    $('.home-featured ul.products > .innerfet').bxSlider({ auto: true, pause: 6000, autoHover: true, pager: false, controls: true, useCSS: false, minSlides: 1, maxSlides: 4, moveSlides: 1, slideWidth: 228, slideMargin: 11 });
                    }
                }

				if ($('.add_to_wishlist').length ) { $('.add_to_wishlist').each(function(){ $(this).attr('title',$(this).html()); }); }
				if ($('.yith-wcwl-wishlistexistsbrowse').length ) { $('.yith-wcwl-wishlistexistsbrowse a').each(function(){ $(this).attr('title',$(this).html()); }); }
				if ($('.yith-wcwl-wishlistaddedbrowse').length ) { $('.yith-wcwl-wishlistaddedbrowse a').each(function(){ $(this).attr('title',$(this).html()); }); }

				$('.home-featured ul.products li.last').removeClass('last'); //wc

				$('.testimonials-list > .inner').each(function(){ $(this).bxSlider({ pager: false,useCSS: false }); }); //ns wc
				$('.page-template-template-home-php .product-category .img-wrap, .cols-b, #header.c #navigation > ul > li > a, .nav-a > ul > li > a, #header.e ul#main-nav, .wpb_tour_tabs_wrapper > ul > li > a').append('<div class="shade-a"></div>');  //wc
				$('.page-template-template-home-php .product-category .img-wrap, .cols-b, #header.c #navigation > ul > li > a, .nav-a > ul > li.sub > a').append('<div class="shade-b"></div>'); //wc
				$('.portfolio-item .over a, #header.c #navigation > ul > li > a').append('<div class="shade-c"></div>'); //wc
				
				$('.cols-b').append('<div class="shade-c"></div>'); //wc
				$('.cols-b').append('<div class="shade-d"></div>'); //wc

				if ($('.yith_magnifier_thumbnail').length > 1) {
						$('.yith_magnifier_thumbnail').find('a').removeAttr('rel');
						$("body").removeClass("has-lightbox"); // Fix of first click lightbox open prob.we dont need lightbox on thumbs at all, if magnifier is on.
				}
				$('h1.page-title').each(function(){if($(this).is(':empty'))$(this).hide();});
				$('h2.page-title').each(function(){if($(this).is(':empty'))$(this).hide();});
				// top nav bar
				$('#top.b #nav > ul, #tools.oc, #language > ul > li > ul').append('<div class="fit-a"></div>');
				$(' #tools.no-oc').parent('#header').addClass('active');
				$('#tools.oc > .fit-a').append('<a>Open/close</a>').children('a').on('click',function(){
					if($('#header').hasClass('active')){
						$('#header').removeClass('active');
						$('#tools').css('top','-36px');
						return false;
					}
					else {
						$('#header').addClass('active');
						$('#tools').css('top','0');
						return false;
					}
				});
				$('#language > ul > li').on({
					mouseenter: function(){
						if($(this).children('ul').css('display') != 'block'){
							$(this).children('ul').slideDown('fast');
						}
					}, 						
					mouseleave: function(){
						if($(this).children('ul').css('display') != 'none'){
							$(this).children('ul').slideUp('fast');	
						}						
					}}
				);
				if ( ! $('.jsanim_no').length ) {
						$('.widget').addClass('js_animate');
						$('.widget_shopping_cart').removeClass('js_animate');
						$('.js_animate').addClass('wpb_animate_when_almost_visible wpb_bottom-to-top');
						$('.js_animate').waypoint(function() {
						$(this).addClass('wpb_start_animation');
					}, { offset: '80%' });
				}

			},
			nav : function(){
				$('.nav-a > ul > li').each( function(index) { if($(this).children('ul').size()){ $(this).addClass('sub'); }});	
				$('.nav-a > ul > li:not(.active) > ul').hide();
				$('.nav-a > ul > li:not(.active)').live('mouseenter',function(){
					if($(this).children('ul').css('display') != 'block'){
						$(this).children('ul').slideDown('slow',function(){
							$(this).css({'display':'block'});
						});
						if($(this).html().search(/ul/i)!=-1){
							$(this).addClass('hovered');
						}
					}
				}).live('mouseleave',function(){
					if($(this).children('ul').css('display') != 'none'){
						$(this).children('ul').slideUp('slow',function(){
							$(this).css({'display':'none'});
						});
						if($(this).html().search(/ul/i)!=-1){
							$(this).removeClass('hovered');
						}
					}
				});				
				if (jQuery(window).width() > 768) {
					$('ul#main-nav > li').children('ul, div').css('display', 'none');
						$('ul#main-nav > li').on({
							mouseenter: function(){
								if($(this).children('ul, div').css('display') != 'block')
									$(this).children('ul, div').slideDown('fast');
									$(this).children('ul, div').css({'display':'block'});
							}, 						
							mouseleave: function(){
								if($(this).children('ul, div').css('display') != 'none')
									$(this).children('ul, div').slideUp('fast');							
									$(this).children('ul, div').css({'display':'none'});
							}}
						);	
				} 
				$(window).resize(function(){
					if (jQuery(window).width() > 768) {
					$('ul#main-nav > li').children('ul, div').css('display', 'none');
						$('ul#main-nav > li').on({
							mouseenter: function(){
								if($(this).children('ul, div').css('display') != 'block')
									$(this).children('ul, div').slideDown('fast');
							}, 						
							mouseleave: function(){
								if($(this).children('ul, div').css('display') != 'none')
									$(this).children('ul, div').slideUp('fast');							
							}}
						);	
					} else {
						$('ul#main-nav > li').children('ul, div').removeAttr('style');
						$('ul#main-nav > li').off();
					}
				});
			},

		}

	};

	Default.utils.nav();
	Default.utils.miscellaneous();
});

/*!*/