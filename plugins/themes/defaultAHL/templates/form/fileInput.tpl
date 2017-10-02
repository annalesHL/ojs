{**
 * templates/form/radioButton.tpl
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2000-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * form radio button
 *}

<input type="file" id="{$FBV_id}" name="{$FBV_name}" class="AHL_form_fileInput {if $FBV_disabled} disabled{/if}" />
<div class="AHL_form_file_input_container" id="{$FBV_id}_container">
	<table><tr>
	<td><input type="button" value="{translate key='navigation.browse'}..." class="button pkp_form_fakeButton"{if $FBV_disabled} disabled="disabled"{/if}/></td>
	<td class="AHL_form_fakeInput"><input class="AHL_form_fakeInput" id="{$FBV_id}_fake" disabled="disabled" value="{$FBV_value}" /></td>
	</tr></table>
	<input type="hidden" id="{$FBV_id}_fileId" name="{$FBV_id}_fileId" value="{$FBV_fileId}" />
	{$FBV_label_content}
</div>

<script type="text/javascript">
	$("#{$FBV_id}_container").click(function() {ldelim}
		$("#{$FBV_id}").trigger('click');
	{rdelim});
	$("#{$FBV_id}").change(function() {ldelim}
		var fileName = $("#{$FBV_id}").val();
		position = Math.max(fileName.lastIndexOf('/'), fileName.lastIndexOf('\\'));
		$("#{$FBV_id}_fake").val(fileName.substr(position+1));
	{rdelim});
</script>
