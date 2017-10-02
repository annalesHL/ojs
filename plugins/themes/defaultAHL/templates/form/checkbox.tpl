{**
 * templates/form/checkbox.tpl
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2000-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * form checkbox
 *}

<div class="AHL_checkbox {if $FBV_layoutInfo} {$FBV_layoutInfo}{/if}">
	<input type="checkbox" id="{$FBV_id|escape}" {$FBV_checkboxParams} class="field css-checkbox{if $FBV_validation} {$FBV_validation|escape}{/if}{if $FBV_required} required{/if}"{if $FBV_checked} checked="checked"{/if}{if $FBV_disabled} disabled="disabled"{/if}{if $FBV_required} required aria-required="true"{/if}/>
	<label for="{$FBV_id|escape}" class="css-label">
	{if $FBV_translate}
		{translate key=$FBV_label}
	{else}
		{if $FBV_keepLabelHtml}
			{$FBV_label}
		{else}
			{$FBV_label}
		{/if}
	{/if}
	</label>
</div>
