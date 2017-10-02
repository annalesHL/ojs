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

{translate key="common.dear"} {$fullName},

<p>
{translate key="user.login.resetInformation"}
</p>

<div class="page page_login">
	{include file="frontend/components/breadcrumbs.tpl" currentTitleKey="user.login"}
	{include file="frontend/components/newPassword.tpl" actionUrl=$loginUrl showRemember=true submitText="user.login"}
</div><!-- .page -->


{include file="frontend/components/footer.tpl"}
