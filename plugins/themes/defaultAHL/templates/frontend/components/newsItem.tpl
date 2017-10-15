<div class="newsItem">
	<div class="header">
		<span class="date">{$date}</span>
		<span class="title">{$title}</span>
	</div>
	<div class="message">
		<div class="messageText">{$message}</div>
	</div>
	<div class="more">{translate key="newsletter.showMore"}</div><div class="less">{translate key="newsletter.showLess"}</div>
</div>

<script>
$(".more").click(function(event) {ldelim}
        var $elt = $(event.target);
        $elt.hide();
	$elt.siblings(".less").show();
	var $message = $elt.siblings(".message");
	var h = $message.find(".messageText").height();
	$message.css("max-height", h + "px");
{rdelim});
$(".less").click(function(event) {ldelim}
        var $elt = $(event.target);
        $elt.hide();
	$elt.siblings(".more").show();
	var $message = $elt.siblings(".message");
	$message.css("max-height", "85px");
{rdelim});
</script>
