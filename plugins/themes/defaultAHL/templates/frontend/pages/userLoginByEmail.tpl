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

<div class="page page_login">
	{include file="frontend/components/breadcrumbs.tpl" currentTitleKey="user.login"}

	{* A login message may be displayed if the user was redireceted to the
	   login page from another request. Examples include if login is required
	   before dowloading a file. *}
	{if $loginMessage}
		<p>
			{translate key=$loginMessage}
		</p>
	{/if}

	<form class="cmp_form cmp_form login" id="login" method="post" action="{$loginUrl}">
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
			<tr class="password">
				<td><label>
					<span class="label">
						{translate key="user.password"}
						<span class="required">*</span>
						<span class="pkp_screen_reader">
							{translate key="common.required"}
						</span>
					</span>
				</label></td>
				<td><input type="password" name="password" id="password" value="{$password|escape}" password="true" maxlength="50" required="$passwordRequired"></td>
			</tr>
		</table>
			<div class="remember checkbox">
				<input type="checkbox" class="css-checkbox" name="remember" id="remember" value="1" checked="$remember">
				<label for="remember" class="css-label">
					{translate key="user.login.rememberUsernameAndPassword"}
				</label>
			</div>
			<div class="buttons">
				<button class="submit" type="submit">
					{translate key="user.login"}
				</button>

				<span id="lostPassword">
					{translate key="user.login.forgotPassword"}
				</span>

				{if !$disableUserReg}
					{url|assign:registerUrl page="user" op="register" source=$source}
					<a href="{$registerUrl}" class="register">
						{translate key="user.login.registerNewAccount"}
					</a>
				{/if}
			</div>

	</form>
</div><!-- .page -->

{include file="frontend/components/footer.tpl"}

<script>
$("#lostPassword").click(function() {ldelim}
	window.location = "{url page="login" op="lostPassword"}?captcha={translate key="common.captchaField.result"}&email=" + $("#email").val();
{rdelim});
</script>
