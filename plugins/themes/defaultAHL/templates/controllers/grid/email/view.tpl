<div class="email">

<div class="header">
<span class="label">{translate key="email.from"}:</span>{$from|escape}
<br /><span class="label">{translate key="email.to"}:</span>{$to|escape}
{if $cc != ""}
	<br /><span class="label">{translate key="email.cc"}:</span>{$cc|escape}
{/if}
<br /><span class="label">{translate key="email.subject"}:</span>{$subject|escape}
</div>

<div class="body">
{$body}
</div>

</div>
