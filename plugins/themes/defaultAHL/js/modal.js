var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

var scrolls = [ ];

function AHLmodal_new(elt) {
	//alert("new");
	height_head = $(".pkp_structure_head").outerHeight();
	height_nav = $(".pkp_navigation_backend_primary").outerHeight();
	height_content = $(".pkp_structure_content").height();
	$(".pkp_modal").each(function() { height_content = Math.max(height_content, $(this).height()); });
	jElt = $(elt);
	height_content = Math.max(height_content, jElt.height());
	jElt.css("top", height_head + height_nav + 30);  // TODO: change 30
	jElt.css("min-height", height_content);
	scrollCurrent = $("body,html").scrollTop();
	scrolls.push(scrollCurrent);
	if (scrollCurrent > height_head) {
		$("body,html").animate({scrollTop: height_head});
	}
}
function AHLmodal_delete(elt) {
	//alert("delete");
	$("body,html").animate({scrollTop: scrolls.pop()});
}

var observer = new MutationObserver(function(mutations) {
   	mutations.forEach(function(mutation) {
		mutation.addedNodes.forEach(function(node) {
			if (node.classList.contains("pkp_modal")) {
				AHLmodal_new(node);
			}
		});
		mutation.removedNodes.forEach(function(node) {
			if (node.classList.contains("pkp_modal")) {
				AHLmodal_delete(node);
			}
		});
	});
});


// configuration of the observer:
var config = { childList: true }

// pass in the target node, as well as the observer options
$(document).ready(function() {
	observer.observe(document.querySelector('body'), config);
});
