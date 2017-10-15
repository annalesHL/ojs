{**
 * templates/frontend/components/subscribeNewsletter.tpl
 *
 * XAVIER
 *
 * 
 *
 *}

<script type="text/javascript">
	$(function() {ldelim}
		// Attach the form handler.
		$('#subscriptionForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>

{url|assign:subscribeUrl router=$smarty.const.ROUTE_COMPONENT component="modals.newsletter.subscriptionHandler" op="subscribe"}

{if $message}

	<p>{$message}</p>

{else}

	<form id="subscriptionForm" method="post" action="{$subscribeUrl}">
		{csrf}

		{if !$isUserLoggedIn}
			<fieldset>
			{if $error}
				<div class="pkp_form_error">
					{translate key=$error reason=$reason}
				</div>
			{/if}

			{include file="form/captcha.tpl"}

			<label>
				<span class="label">
					{translate key="user.email"}
					<span class="required">*</span>
					<span class="pkp_screen_reader">
						{translate key="common.required"}
					</span>
				</span>
			</label>
			<input type="text" name="email" id="email" value="{$email|escape}" maxlength="32">
			</fieldset>
		{/if}

		<div class="buttons">
			<button class="submit" type="submit">
				{translate key="newsletter.subscribe"}
			</button><span class="howmany">{$numberOfSubscribers}</span>
		</div>
	</form>

{/if}
