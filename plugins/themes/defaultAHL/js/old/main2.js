/**
 * @file plugins/themes/defaultAHL/js/main.js
 * 
 * SAN
 *
 * @brief Handle JavaScript functionality unique to this theme.
 */
"use strict";

var desktopMode = true;
var circleTimeout = null;

(function($) {

    // petit "module" pour activer une fonction après x ms de non-activité "souris"
    // TODO comparer performance avec:
    // https://github.com/kidh0/jquery.idle/blob/master/jquery.idle.js
    var idleStartingTime = 0; // le temps de dernière activité détéctée
    var idleStarted = false; // la fonction a démarré
    var idleDelay = 0; // how much time to go
    var idleTimeout = 0; // the Timeout id

    var idleInit = function (fn, timeout) {
	// démarre la fonction 'fn' après 'timeout'ms d'inactivité souris
	var d = new Date();
	idleStartingTime = d.getTime();
	idleTimeout = setTimeout(function () {
	    idleStart (fn, timeout);
	}, timeout);
	$(document).on("scroll mousemove mouseenter mouseleave", function ( event ) {
	    idleStop (timeout);
	});
    }
    var idleStop = function (timeout) {
	var d = new Date();
	idleStartingTime = d.getTime();
	idleStarted = false;
	idleDelay = timeout;
    }
    
    var idleStart = function (fn, timeout) {
	if (!idleStarted) {
	    var d = new Date();
	    var delay = d.getTime() - idleStartingTime;
	    if  (delay >= timeout - 5) {
		// délai dépassé, on invoque la fonction 'fn'
		// le '5' est une marge d'erreur de sécurité.
		idleStarted = true;
		$(document).off("scroll mousemove mouseenter mouseleave"); // do better: remove only our handler
		fn ();
	    } else {
		// le délai a été repoussé dû à une activité. On
		// lance un nouveau Timeout.
		idleDelay = timeout - delay;
		idleTimeout = setTimeout(function () {
		    idleStart (fn, timeout);
		}, idleDelay);
	    }
	}
    }
    // fin du module  "idle"

    $( document ).ready(function () {
	$('.pkp_navigation_primary .pkp_nav_list').fadeTo(0,0.01);
	setTimeout(function() {
	    
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
	    if ( menu.width() >= l1 + l2 + r1 + r2 + menu.find('li:nth-child(3)').width()) {
		// otherwise this would mean that the menus will take
		// several lines: we have a small screen, and we do
		// nothing.
		desktopMode = true;
		var d = l1+l2 -r1-r2;
		if (d>0) {
		    menu.find('li:nth-child(4)').css({ 'padding-left' : d/4,
						       'padding-right' : d/4 });
		    menu.find('li:nth-child(5)').css({ 'padding-left' : d/4,
						       'padding-right' : d/4 });
		} else {
		    menu.find('li:first-child').css({ 'padding-left' : d/4,
						      'padding-right' : d/4 });
		    menu.find('li:nth-child(2)').css({ 'padding-left' : d/4,
						       'padding-right' : d/4 });
		}
	    } else { desktopMode = false; }
	    menu.fadeTo(100,1);
	    
	    var resetCircles = function () {
		$('.pkp_navigation_primary .pkp_nav_list li > div .circle').css({
		    "margin-top" : "",
		    "margin-left" : "",
		    "margin-right" : "",
		    "position" : "",
		    "top" : "",
		    "left" : ""
		});
	    }
	    
	    var initCircles = function () {
		// we initialize all the circles and then animate them
		// TODO à réinitialiser si on passe en mode tablette...
		// https://api.jquery.com/resize/
		circleTimeout = null;
		var ctop = 14-3; // on le détecte ci-dessous: c'est @menu-padding - .circle.top
		if (desktopMode == false) {
		    ctop = 14-12;
		}
		var diam = 30;   // @circle
		var diamMax = 150;
		var opac = 0.3;
		var duration = 600; // durée des animations en ms (4 x plus long en quittant)
		$('.pkp_navigation_primary .pkp_nav_list li').each(function (i, li) {
		    var circle = $(li).find('div .circle');
		    if (circle.length != 0) {
			var l = circle.css("margin-left"); // attention! ce n'est pas
			// une valeur numérique, mais de la forme "19px" (ou
			// undefined) ATTENTION bug firefox:
			// https://bugzilla.mozilla.org/show_bug.cgi?id=381328 il
			// faut donc re-centrer à la main...
			if (l == '0px') { // indique le bug, a priori:
			    var liw = $(li).width();
			    if ( circle.css("margin-right") == '0px' ) { // on centre à la main
				l = (liw - diam)/2;
			    } else { // on respecte margin-right
				var mr = parseFloat(circle.css('margin-right')); //.replace(/[^-\d\.]/g, '');
				l = liw - mr - diam;
				l = (liw - diam)/2;
			    }
			}
			circle.css({
			    "margin-top" : "0",
			    "margin-left" : "0",
			    "margin-right" : "0",
			    "position" : "absolute",
			    "top" : ctop,
			    "left" : parseFloat(l) + parseFloat($(li).css('padding-left'))
			});
			circle.fadeTo(1000,opac);
			var ll = circle.position().left; // valeur numérique de l + padding
			$(li).find('a').mouseenter(function () {
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
			$(li).find('a').mouseleave(function () {
			    circle.stop();
			    circle.animate({
				opacity: opac,
				left: ll,
				width: diam,
				height: diam,
				top: ctop
			    }, 4 * duration);
			})
		    } // if (circle.length != 0)
		}); // end-each
	    }
	    initCircles();
	    window.addEventListener("resize", 
				    function () {
					console.log ('Resizing...');
					if ($('.annales_text').css('position') == 'relative') {
					    if (desktopMode == true) {
						desktopMode = false;
						console.log ('switching to tablet Mode');
					    }
					    if (circleTimeout == null) { resetCircles(); }
					    else { clearTimeout (circleTimeout); }
					    circleTimeout = setTimeout(initCircles, 500);
					} else if ($('.annales_text').css('position') == 'absolute'
						   && desktopMode == false) {
					    desktopMode = true;
					    console.log ('switching to desktop Mode');
					    resetCircles();
					    initCircles();
					}
				    });
	    
	    // Animation du logo pour la page d'accueil:
	    // on met la position en "absolue"
	    if ($('.pkp_page_index').length != 0 || $('.pkp_page_404').length != 0) {
		var aimg = $('.pkp_site_name a.is_img');
		var l =   aimg.css("margin-left");
		var top = aimg.css("padding-top");
		var w =   aimg.width();
		var h =   aimg.height();
		
		var img = $('.pkp_site_name img');
		console.log ("nombre d'éléments à animer:" + img.length);
		img.css({
		    "margin-top" : "0",
		    "margin-left" : "0",
		    "position" : "absolute",
		    "top" : top,
		    "left" : l
		});
		aimg.width(w);
		aimg.height(h);
	    
		// how to go back to the original position:
		var backHome = function ( ) {
		    img.stop();
		    img.animate({theTime : 0}, 0);
		img.animate({
		    top : top,
		    //width : w,
		    height: h,
		    left: l
		}, 1000);
		    img.animateRotate(0,1000);
		    
		}
		
		var randomAnimation = function () {
		    var p = [parseFloat(l),
			     parseFloat(top),
			     parseFloat(h),
			     0];
		    
		    // we choose randomly the animation function
		    switch (Math.floor((Math.random() * 7) + 1)) {
			//switch (7) {
		    case 1:
			img.simpleFall (h);
			break;
		    case 2:
			img.animateRotate(90,2000);
			break;
		    case 3:
			img.circle(parseFloat(top), 200, 2000, 3.5);
			break;
		    case 4:
			img.system(dynSystemeA, p, 3600);
			break;
		    case 5:
			img.system(dynRandom, p, 30000);
			break;
		    case 6:
			img.system(dynRandomPiecesfn(20,500), p, 30000);
			break;
		    case 7:
			let ww = ($(window).width() - parseFloat(w))/2;
			let sc = img.offset().top - $(window).scrollTop();
			img.system(dynBounce2D
				   ([5, -20, 10],
				    [-ww, ww,
				     -sc, $(window).height() - parseFloat(h) - sc]
				    ,dynGravity  // experimental
				   ),
				   p, 20000);
			break;
		    }
		}
		
		// maintenant on peut animer...
		var animateLogo = function () {
		    idleInit (
			function () {
			    $(window).on("scroll mousemove mouseenter mouseleave", function ( event ) {
				$(window).off();
				backHome ();
				$('body').css('overflow', 'unset');
				animateLogo ();
			    });
			    // animate here:
			    $('body').css('overflow', 'hidden');
			    randomAnimation();
			}, 3000);
		}
		animateLogo ();
	    }
	}, 400) // on met timeout pour que la page ait le temps de se charger avant de noter les positions des divers élements...
    }); // document ready
})(jQuery);
 
