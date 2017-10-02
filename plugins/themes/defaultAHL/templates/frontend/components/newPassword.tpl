{if $message}
	<p>{$message}</p>
{else}

<form class="cmp_form cmp_form login" id="login" method="post" action="{$actionUrl}">
	{csrf}

	{if $error}
		<div class="pkp_form_error">{$error}</div>
	{/if}
	{if $success}
		<div class="pkp_form_success">{$success}</div>
	{/if}

	{if $invalidLength}
		<div class="pkp_form_error">
			{translate key=user.register.form.passwordLengthRestriction length=$length}
		</div>
	{else}
		<p>{translate key=user.register.form.passwordLengthRestriction length=$length}</p>
	{/if}

	<fieldset>
		<table class="fields">
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
				<td><input type="password" name="password" id="password" password="true" maxlength="32" required></td>
			</tr>
			<tr class="password">
				<td><label>
					<span class="label">
						{translate key="user.repeatPassword"}
						<span class="required">*</span>
						<span class="pkp_screen_reader">
							{translate key="common.required"}
						</span>
					</span>
				</label></td>
				<td><input type="password" name="password2" id="password2" password="true" maxlength="32" required></td>
			</tr>
		</table>
		{if $showRemember}
			<div class="remember checkbox">
				<label>
					<input type="checkbox" class="css-checkbox" name="remember" id="remember" value="1" checked="$remember">
					<label for="remember" class="css-label">
						{translate key="user.login.rememberUsernameAndPassword"}
					</label>
				</label>
			</div>
		{/if}
		<div class="buttons">
			<button class="submit" type="submit">
				{translate key=$submitText}
			</button>
		</div>
	</fieldset>
</form>

{/if}
