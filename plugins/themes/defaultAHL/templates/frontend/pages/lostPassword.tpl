{**
 * templates/frontend/pages/userLogin.tpl
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2000-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * User login form.
 *
 *}
{include file="frontend/components/header.tpl" pageTitle="user.login"}

{load_script context="frontend"}

{$message}

<div class="page page_login">
	{include file="frontend/components/breadcrumbs.tpl" currentTitleKey="user.login"}

	<form class="cmp_form cmp_form login" id="login" method="post" action="{url page="login" op="lostPassword"}">
		{csrf}

		{if $error}
			<div class="pkp_form_error">
				{translate key=$error reason=$reason}
			</div>
		{/if}

		<input type="hidden" name="source" value="{$source|strip_unsafe_html|escape}" />

		{include file="form/captcha.tpl"}

		<table class="fields">
			<tr class="email">
				<td><label>
					<span class="label">
						{translate key="user.email"}
						<span class="required">*</span>
						<span class="pkp_screen_reader">
							{translate key="common.required"}
						</span>
					</span>
				</label></td>
				<td><input type="text" name="email" id="email" value="{$email|escape}" maxlength="32" required></td>
			</tr>
		</table>
		<div class="buttons">
			<button class="submit" type="submit">
				{translate key="user.login.resetPassword"}
			</button>
		</div>

	</form>
</div><!-- .page -->


{include file="frontend/components/footer.tpl"}
