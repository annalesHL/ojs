/**
 * @file plugins/themes/defaultAHL/js/main.js
 * 
 * SAN
 *
 * @brief Handle JavaScript functionality unique to this theme.
 */
(function($) {
    
    $( document ).ready(function() {
	
	// we reduce image size depending on screen height
	// we dont set height directly so that it can still be reduced dynamically by window width
	// var img = $(".homepage_image img");
	// var h = img.height();
	// var newh = Math.min(h, screen.height/2);
	// var neww = img.width() * newh/h;
	// img.width(neww);
	// img.css({ "height": "auto"});
	//alert (img.height());
	
	// on équilibre le menu pour que le logo soit au milieu
	var menu = $('.pkp_navigation_primary .pkp_nav_list');
	var l1 = menu.find('li:first-child').width();
	var l2 = menu.find('li:nth-child(2)').width();
	var r1 = menu.find('li:nth-child(4)').width();
	var r2 = menu.find('li:nth-child(5)').width();
	var d = l1+l2 -r1-r2;
	if (d>0) {
	    menu.find('li:nth-child(4)').width(r1+d/2);
	    menu.find('li:nth-child(5)').width(r2+d/2);
	} else {
	    menu.find('li:first-child').width(l1-d/2);
	    menu.find('li:nth-child(2)').width(l2-d/2);
	}
	
	// we initialize all the circles and then animate them
	var ctop = 4;
	var diam = 30;
	var diamMax = 150;
	var opac = 0.3;
	var duration = 600; // durée des animations en ms (4 x plus long en quittant)
	$('.pkp_navigation_primary .pkp_nav_list li > div').each(function (i, li) {
	    var circle = $(li).find('.circle');
	    var l = circle.css("margin-left"); // attention! ce n'est pas
	    // une valeur numérique, mais de la forme "19px" (ou
	    // undefined)
	    // ATTENTION bug firefox: https://bugzilla.mozilla.org/show_bug.cgi?id=381328
	    // il faut donc re-centrer à la main...
	    if (l == '0px') { // indique le bug, a priori:
		var liw = $(li).width();
		if ( circle.css("margin-right") == '0px' ) { // on centre à la main 
		    l = (liw - diam)/2;
		} else { // on respecte margin-right
		    var mr = parseFloat(circle.css('margin-right')); //.replace(/[^-\d\.]/g, '');
		    l = liw - mr - diam; 
		}
	    }
	    circle.css({
		"margin-top" : "0",
		"margin-left" : "0",
		"margin-right" : "0",
		"position" : "absolute",
		"top" : ctop,
		"left" : l
	    });
	    circle.fadeTo(1000,opac);
	    if (circle.length != 0) {
		var ll = circle.position().left; // valeur numérique de l
		$(li).mouseenter(function () {
		    //alert (l + "," + ll + "," + circle.css("left"));
		    circle.stop();
		    circle.animate({
			opacity: 1,
			left: ll - (diamMax - diam)/2,
			width: diamMax,
			height: diamMax,
			top: -(diamMax - diam)/2 + ctop
		    }, duration, "linear");
		});
		$(li).mouseleave(function () {
		    circle.stop();
		    circle.animate({
			opacity: opac,
			left: l,
			width: diam,
			height: diam,
			top: ctop
		    }, 4 * duration);
		})
	    }
	}); // end-each
	
	// Animation du logo pour la page d'accueil:
	// on met la position en "absolue"
	if ($('.pkp_page_index').length != 0) {
	    var l = $('.pkp_site_name a.is_img').css("margin-left");
	    var top = $('.pkp_site_name a.is_img').css("padding-top");
	    var w = $('.pkp_site_name a.is_img').width();
	    var h = $('.pkp_site_name a.is_img').height();
	    
	    $('.pkp_site_name img').css({
		"margin-top" : "0",
		"margin-left" : "0",
		"position" : "absolute",
		"top" : top,
		"left" : l
	    });
	    $('.pkp_site_name a.is_img').width(w);
	    $('.pkp_site_name a.is_img').height(h);
	}
	// maintenant on peut animer...
	
    });
    
})(jQuery);
