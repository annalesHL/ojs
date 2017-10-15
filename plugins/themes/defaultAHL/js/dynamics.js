// quelques fonctions pour animer le logo

// rotation: 
// https://stackoverflow.com/questions/15191058/css-rotation-cross-browser-with-jquery-animate
// https://stackoverflow.com/questions/5462275/animate-element-transform-rotate

// on va animer 4 variables: left, top, height, angle.
//
// La dernière (angle) n'est pas une propriété CSS standard, donc on
// utilise la possibilité de créer des propriétés avec la méthode
// Jquery animate.

"use strict";

(function($) {
    
    //alert ('dynamics');
    console.log ("Jquery version:" + $.fn.jquery);
    
    // Rotation sens horaire
    $.fn.animateRotate = function(angle, duration) {
	var elem = $(this);
	var step = function(now) {
	    elem.each(function(i, e) {
		$.style(e, 'transform', 'rotate(' + now + 'deg)');
//		console.log ( e );
//		console.log (now);
	    });
	};
	
	elem.animate({theAngle: angle}, {
	    queue: false,
	    duration: duration,
	    step: step });
    };

    $.fn.simpleFall = function (h) {
	$(this).animate({
	    top : 450,
	    height: h/4,
	    left: 30
	}, 2000);
    }

    // n = nombre de tours, duration = durée / tour
    $.fn.circle = function (tp, r, duration, n) {
	var elem = $(this);
	var step = function (now) {
	    $.style(this, 'transform', 'rotate(' + now + 'deg)');
	    var t = now * Math.PI / 180;
	    elem.css('top', (tp + r - r*Math.cos(t)));
	    elem.css('left', (r*Math.sin(t)));
	};
	elem.animate({theAngle: n * 360}, {
	    queue : false,
	    duration : n * duration,
	    step: step,
	    easing : "linear"});
    }

    // Système dynamique général, pour un temps allant de t=0 à
    // t=duration (en ms). On donne le flot f:
    // f(p,t0, t1) donne la position du système au temps t1 en partant
    // de la position p au temps t0.
    // p = [left, top, size, angle (en degrés)]
    $.fn.system = function (f, p0, duration) {
	var elem = $(this);
	var t0 = null;
	var p = p0;
	
	var step = function (now) {
	    if (t0 == null) {
		t0 = now;
	    } else {
		p = f(p, t0, now);
		//console.log (p);
		t0 = now;
		elem.css({
		    'left' : p[0],
		    'top'  : p[1],
		    'transform' : 'rotate(' + p[3] + 'deg)'
		});
		//elem.width(p[2]);
		elem.height(p[2]);
	    }
	};
	
	var always = function (anim, jump) {
	    // this is executed when the animation is stopped or finished
	    elem.animate({theAngle : p[3]}, 0); // we update the angle 'pseudo property'
	}
	
	elem.animate({theTime : duration}, {
	    queue: false,
	    step : step,
	    duration : duration,
	    always : always,
	    easing : "linear"});
    }
		
    
})(jQuery);

var dynSystemeA = function (p, t0, t1) {
    var dt = (t1 - t0)/400;
    return ([ p[0] + dt*p[1],
	      p[1] - dt*p[0],
	      80 - 40 * Math.sin(Math.PI*t0/180/10),
	      t0/10 ]);
}

var dynRandom = function (p, t0, t1) {
    var dt = (t1 - t0)/40;
    var distrib = function () {
	return (Math.random() - 0.5);
    }
    return ([ p[0] + dt * distrib(),
	      p[1] + dt * distrib(),
	      p[2] + dt * distrib(),
	      p[3] + dt * distrib(),
	    ]);
}

// aléatoire par morceaux affines
var dynRandomPiecesfn = function (size, duration) {
    // size= "taille" max de chaque segment
    // duration = durée de chaque segment
    var p0 = null; // position initiale
    var p1 = null; // position finale
    var tStart = 0;
    var distrib = function () {
	return (2*(Math.random() - 0.5));
    }

    return (function (p, t0, t1) {
	if ( t0 - tStart > duration || p0 == null) {
	    // nouveau segment
	    if (p0 != null) { tStart = t0; }
	    p0 = p;
	    p1 = [ p[0] + size * distrib(),
		   p[1] + size * distrib(),
		   p[2] + size * distrib(),
		   p[3] + size * distrib(),
		 ];
	}
	var tt = (t1 - tStart)/duration;
	return ([ p0[0] + tt * (p1[0] - p0[0]),
		  p0[1] + tt * (p1[1] - p0[1]),
		  Math.max(1, p0[2] + tt * (p1[2] - p0[2])),
		  p0[3] + tt * (p1[3] - p0[3])
		]);
    });
}

// bbox = [ left0, left1, top0, top1 ]
// force est une fonction de la position
var dynBounce2D = function (v0, bbox, force) {
    var mass = 1;
    var radius = 45;
    var sqr2 = Math.sqrt(2);
    var deg2Rad = Math.PI/180;
    var v = null;
    var pos = null;
    if ( force == undefined ) { force = function (x) {
	return ([ 0, 10 ]);} }  // gravity

    return (function (p, t0, t1) {
	if (pos == null) { pos = [p[0], p[1], p[3]]; }
	var dt = (t1 - t0)/100;
	if ( v == null ) { v = v0; }
	else { v[0] = v[0] + dt * force(pos)[0] / mass;
	       v[1] = v[1] + dt * force(pos)[1] / mass; }
	pos = [ p[0] + dt * v[0],
		p[1] + dt * v[1],
		p[3] + dt * v[2]// angle, sens horaire
	      ];

	// treat collisions with no sliding: angular rotation is
	// treated as if the ball were rolling for an infinitesimal
	// time before bouncing. This may anihilate kinetic energy in
	// case initial rotation speed exactly compensates for
	// translation speed. To avoid this we could add some
	// sliding/friction.
	if (pos[1] > bbox[3]) { // bottom
	    pos[1] = bbox[3]-1;
	    v[1] = - v[1];
	    v[0] = v[0]/sqr2 + v[2]*radius*deg2Rad/sqr2;
	    v[2] = v[0]/(deg2Rad*radius);
	}
	if (pos[1] < bbox[2]) { // top
	    pos[1] = bbox[2]+1;
	    v[1] = - v[1];
	    v[0] = v[0]/sqr2 - v[2]*radius*deg2Rad/sqr2;
	    v[2] = - v[0]/(deg2Rad*radius);
	}
	if (pos[0] > bbox[1]) { // right
	    pos[0] = bbox[1]-1;
	    v[0] = - v[0];
	    v[1] = v[1]/sqr2 - v[2]*radius*deg2Rad/sqr2;
	    v[2] = - v[1]/(deg2Rad*radius);
	}
	if (pos[0] < bbox[0]) { // left
	    pos[0] = bbox[0]+1;
	    v[0] = - v[0];
	    v[1] = v[1]/sqr2 + v[2]*radius*deg2Rad/sqr2;
	    v[2] = v[1]/(deg2Rad*radius);
	}


	return ([pos[0], pos[1], p[2], pos[2]]);
    });
}
	   
// experimental:
var gravity = [0, 10];
var counter = 0;

window.addEventListener("deviceorientation", handleOrientation, true);
function handleOrientation(event) {
    var absolute = event.absolute; // boolean
    var alpha    = event.alpha;    // angle around z axis (normal to the device)
    var beta     = event.beta;     // angle around x axis (horizontal
				   // lorsque le tél est en position
				   // "portrait"). positif en position habituelle
    var gamma    = event.gamma;    // angle around y axis (vertical .. .. ) positif quand ça penche vers la droite.

    counter++;
    if (counter==1000) {
	counter = 0;
//	alert (gravity);
    }
    if ( alpha != null ) { 
	{ var winAngle = window.orientation * Math.PI / 180;
	  var x = 10 * Math.sin(gamma * Math.PI / 180);
	  var y = 10 * Math.sin(beta * Math.PI / 180);
	  gravity = [ x * Math.cos(winAngle) + y * Math.sin(winAngle),
		      -x * Math.sin(winAngle) + y * Math.cos(winAngle) ];
	  // console.log(absolute, alpha, beta, gamma, winAngle, gravity);
	}
    }
}

var dynGravity = function () {
    return (gravity);
}


// window.addEventListener("orientationchange", function() {
//     if (window.orientation == 90 || window.orientation == -90) {
// 	alert ('landscape ' + window.orientation);
//     } else {
// 	alert ('portrait ' + window.orientation);
//     }
// });
