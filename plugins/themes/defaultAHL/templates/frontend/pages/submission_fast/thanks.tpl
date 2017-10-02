{**
 * templates/
 *
 * XAVIER
 *
 * 
 *
 *}

{include file="frontend/components/header.tpl" pageTitleTranslated=$currentJournal->getLocalizedName()}

  {call_hook name="Templates::Index::journal"}
  
<div class="fastSubmission_thanks">

<h3>{translate key="submission.completed"}</h3>

<p>{translate key="submission.thankYou"}</p>
{if $newAccount}
	<p>{translate key="submission.checkEmail"}</p>
{/if}

{if $newAccount}
	<h3>{translate key="submission.newAccount"}</h3>
	<p>{translate key="submission.newAccountMessage"}</p>
	<p>{translate key="submission.temporaryPassword"} <span id="temporaryPassword">{$password}</span></p>

	<p>{translate key="submission.changePassword"}</p>
	<div class="changePassword">
		{include file="frontend/components/changePassword.tpl"}
	</div>
{/if}

<h3>{translate key="submission.whatsNext"}</h3>

<p>{translate key="submission.whatsNextMessage"}</p>
<ul>
<li><a href="{url router=$smarty.const.ROUTE_PAGE page="authorDashboard" op="submission" path=$submissionId}">{translate key="submission.viewEditSubmission"}</li>
{if $newAccount}
	<li><a href="{url router=$smarty.const.ROUTE_PAGE page="user" op="profile"}">{translate key="submission.viewEditProfile"}</a></li>
	<li><a href="{url router=$smarty.const.ROUTE_PAGE page="login" op="signOut"}">{translate key="user.logOut"}</a></li>
{/if}
</ul>

</div>
  
<!-- .page -->
