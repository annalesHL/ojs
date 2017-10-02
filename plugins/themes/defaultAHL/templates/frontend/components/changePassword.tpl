{**
 * templates/
 *
 * XAVIER
 *
 * 
 *
 *}

<script type="text/javascript">
	$(function() {ldelim}
		// Attach the form handler.
		$('#login').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>
{url|assign:savePasswordUrl router=$smarty.const.ROUTE_COMPONENT component="modals.user.changePasswordHandler" op="savePassword" username=$username hash=$hash escape=false}
{include file="frontend/components/newPassword.tpl" actionUrl=$savePasswordUrl submitText=user.password.changePassword}
