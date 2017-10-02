{**
 * templates/frontend/components/registrationForm.tpl
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @brief Display the basic registration form fields
 *
 * @uses $locale string Locale key to use in the affiliate field
 * @uses $firstName string First name input entry if available
 * @uses $middleName string Middle name input entry if available
 * @uses $lastName string Last name input entry if available
 * @uses $countries array List of country options
 * @uses $country string The selected country if available
 * @uses $email string Email input entry if available
 * @uses $username string Username input entry if available
 *}
<fieldset class="identity">
	<h3>{translate key="user.profile"}</h3>

	<table class="fields">
		<tr class="first_name">
			<td><label>
				<span class="label">
					{translate key="user.firstName"}
					<span class="required">*</span>
					<span class="pkp_screen_reader">
						{translate key="common.required"}
					</span>
				</span>
			</label></td>
			<td><input type="text" name="firstName" id="firstName" value="{$firstName|escape}" maxlength="40" required></td>
		</tr>
		<tr class="middle_name">
			<td><label>
				<span class="label">
					{translate key="user.middleName"}
				</span>
			</label></td>
			<td><input type="text" name="middleName" value="{$middleName|escape}" maxlength="40"></td>
		</tr>
		<tr class="last_name">
			<td><label>
				<span class="label">
					{translate key="user.lastName"}
					<span class="required">*</span>
					<span class="pkp_screen_reader">
						{translate key="common.required"}
					</span>
				</span>
			</label></td>
			<td><input type="text" name="lastName" id="lastName" value="{$lastName|escape}" maxlength="40" required></td>
		</tr>
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
			<td><input type="text" name="email" id="email" value="{$email|escape}" maxlength="90" required></td>
		</tr>
		<tr class="affiliation">
			<td><label>
				<span class="label">
					{translate key="user.affiliation"}
				</span>
			</label></td>
			<td><input type="text" name="affiliation[{$primaryLocale|escape}]" id="affiliation" value="{$affiliation.$primaryLocale|escape}"></td>
		</tr>
		<tr class="country">
			<td><label>
				<span class="label">
					{translate key="common.country"}
				</span>
			</label></td>
			<td><select name="country" id="country" required>
				<option></option>
				{html_options options=$countries selected=$country}
			</select></td>
		</tr>
	</table>
</fieldset>

<fieldset class="login">
	<h3>{translate key="user.login"}</h3>
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
</fieldset>
