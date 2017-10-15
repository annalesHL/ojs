{**
 * templates/pages/submission_fast/index.tpl
 *
 * XAVIER
 *
 *
 *}
{include file="frontend/components/header.tpl" pageTitleTranslated=$currentJournal->getLocalizedName()}

  {call_hook name="Templates::Index::journal"}
  
<div class="fastSubmission">

  <div id="guidelines">
  <h3>{translate key="submission.guidelines"}</h3>
  <ul>
  {translate key="submission.guidelines.items"}
  </ul>
  </div>

  <form class="pkp_form" id="fastSubmissionForm" method="post" action="{url op="submit"}" enctype="multipart/form-data">

  {if !$isUserLoggedIn}

  <h3>{translate key="submission.section.yourIdentity"}</h3>

  {fbvFormSection}

  {fbvElement type="checkbox" id="hasAccount" name="hasAccount" checked=$hasAccount label="user.hasAccount"}

  <label></label>
  {fbvElement type="text" label="user.email" id="email" name="email" value=$email required=true}

  <table name="name">
  <tr>
  <td>{fbvElement type="text" label="user.firstName" id="firstName" name="firstName" value=$firstName required=true}</td>
  <td>{fbvElement type="text" label="user.lastName" id="lastName" name="lastName" value=$lastName required=true}</td>
  </tr>
  </table>

  <table name="passwd">
  <tr>
  <td>{fbvElement type="text" label="user.password" password="true" id="password" value=$password maxLength="32"}
  <td id="lostPassword"><span>{translate key="user.login.forgotPassword"}</span></td>
  </tr>
  </table>

  {/fbvFormSection}

  {/if}

  <h3>{translate key="submission.section.yourSubmission"}</h3>

  {fbvFormSection}

  {fbvElement type="checkbox" id="onArxiv" name="onArxiv" checked=$onArxiv label="submission.onArxiv"}

  <div class="arxiv">
  <label>{translate key="submission.arxiv"}</label>
  {fbvElement type="text" label="submission.arxiv.example" id="arxiv" name="arxiv" value=$arxiv required=true}
  <div id="arxivtitle"></div>
  <script type="text/javascript">
	balanceText($('#arxivtitle'), {ldelim} watch: true {rdelim});
	$("[name=arxiv]").change(function(event) {ldelim}
		$('#arxivtitle').pkpAjaxHtml(
			"{url router=$smarty.const.ROUTE_COMPONENT component="modals.arxiv.ArxivInfoHandler" op="getTitle"}?arxivId=" + escape(event.target.value),
			function() {ldelim} balanceText.updateWatched(); MathJax.Hub.Queue(["Typeset",MathJax.Hub]); {rdelim}
		);
	{rdelim});
  </script>
  </div>

  <div class="fieldset submission">
  <label>{translate key="metadata.property.displayName.article-title"}</label>
  {fbvElement type="text" id="title" value=$title maxLength="32" required=true}  

  <label>{translate key="submission.additionalAuthors"}</label>

  <table class="authors">
  <tr name="author2">
  <td>{fbvElement type="text" label="user.firstName" id="firstName2" name="firstName2" value=$firstName2}</td>
  <td>{fbvElement type="text" label="user.lastName" id="lastName1" name="lastName2" value=$lastName2}</td>
  <td>{fbvElement type="text" label="user.email" id="email2" name="email2" value=$email2}</td>
  </tr>
  <tr name="author3">
  <td>{fbvElement type="text" label="user.firstName" id="firstName3" name="firstName3" value=$firstName3}</td>
  <td>{fbvElement type="text" label="user.lastName" id="lastName1" name="lastName3" value=$lastName3}</td>
  <td>{fbvElement type="text" label="user.email" id="email3" name="email3" value=$email3}</td>
  </tr>
  <tr name="author4">
  <td>{fbvElement type="text" label="user.firstName" id="firstName4" name="firstName4" value=$firstName4}</td>
  <td>{fbvElement type="text" label="user.lastName" id="lastName1" name="lastName4" value=$lastName4}</td>
  <td>{fbvElement type="text" label="user.email" id="email4" name="email4" value=$email4}</td>
  </tr>
  <tr name="author5">
  <td>{fbvElement type="text" label="user.firstName" id="firstName5" name="firstName5" value=$firstName5}</td>
  <td>{fbvElement type="text" label="user.lastName" id="lastName1" name="lastName5" value=$lastName5}</td>
  <td>{fbvElement type="text" label="user.email" id="email5" name="email5" value=$email5}</td>
  </tr>
  <tr name="author6">
  <td>{fbvElement type="text" label="user.firstName" id="firstName6" name="firstName6" value=$firstName6}</td>
  <td>{fbvElement type="text" label="user.lastName" id="lastName1" name="lastName6" value=$lastName6}</td>
  <td>{fbvElement type="text" label="user.email" id="email6" name="email6" value=$email6}</td>
  </tr>
  <tr name="author7">
  <td>{fbvElement type="text" label="user.firstName" id="firstName7" name="firstName7" value=$firstName7}</td>
  <td>{fbvElement type="text" label="user.lastName" id="lastName1" name="lastName7" value=$lastName7}</td>
  <td>{fbvElement type="text" label="user.email" id="email7" name="email7" value=$email7}</td>
  </tr>
  <tr name="author8">
  <td>{fbvElement type="text" label="user.firstName" id="firstName8" name="firstName8" value=$firstName8}</td>
  <td>{fbvElement type="text" label="user.lastName" id="lastName1" name="lastName8" value=$lastName8}</td>
  <td>{fbvElement type="text" label="user.email" id="email8" name="email8" value=$email8}</td>
  </tr>
  <tr name="author9">
  <td>{fbvElement type="text" label="user.firstName" id="firstName9" name="firstName9" value=$firstName9}</td>
  <td>{fbvElement type="text" label="user.lastName" id="lastName1" name="lastName9" value=$lastName9}</td>
  <td>{fbvElement type="text" label="user.email" id="email9" name="email9" value=$email9}</td>
  </tr>
  </table>
  <button type="button" id="addAuthor" name="addAuthor" onclick="add_author();">Add another author</button>

  <label>{translate key="submission.uploadArticle"}</label>

  {fbvElement type="file" id="article" name="article" label="submission.uploadPDF" value=$_article fileId=$article_fileId required=true}

  </div>

  {/fbvFormSection}

  <h3>{translate key="submission.section.routeArticle"}</h3>

  <label>{translate key="submission.proposeSection"}</label>
  {fbvElement type="select" id="sectionId" name="sectionId" from=$sectionOptions selected=$sectionId translate=false}

  <label>{translate key="submission.proposeEditor"}</label>
  {fbvElement type="select" id="editorId" name="editorId" from=$editorOptions selected=$editorId translate=false required=false}

  <h3>{translate key="submission.section.comments"}</h3>

  {fbvElement type="textarea" id="comments" value=$comments}

  {include file="form/captcha.tpl"}

  {* Buttons *}
  {fbvFormButtons hideCancel=true id="step1Buttons" submitText="common.submit"}

  </form>

</div>

<!-- .page -->

<script type="text/javascript">
var hasAccount = $("[name=hasAccount]");
var field_name = $("[name=name]");
var field_password = $("[name=passwd]");
function switch_account() {ldelim}
	if (hasAccount.is(':checked')) {ldelim}
		field_name.hide();
		field_password.show();
	{rdelim} else {ldelim}
		field_name.show();
		field_password.hide();
	{rdelim}
{rdelim}
hasAccount.change(switch_account);
switch_account();

var onArxiv = $("[name=onArxiv]");
var field_arxiv = $(".arxiv");
var field_submission = $(".submission");
function switch_arxiv(t) {ldelim}
	if (onArxiv.is(':checked')) {ldelim}
		field_arxiv.show(t);
		field_submission.hide(t);
	{rdelim} else {ldelim}
		field_arxiv.hide(t);
		field_submission.show(t);
	{rdelim}
{rdelim}
onArxiv.change(function () {ldelim} switch_arxiv(200 + 30*noAuthor); {rdelim});
switch_arxiv(0);

$(".submitFormButton").click( function() {ldelim}
	if (hasAccount.is(':checked')) {ldelim}
		$("[name=firstName]").val(' ');
		$("[name=lastName]").val(' ');
	{rdelim}
	if (onArxiv.is(':checked')) {ldelim}
		$("[name=title]").val(' ');
	{rdelim} else {ldelim}
		$("[name=arxiv]").val(' ');
	{rdelim}
{rdelim});

for (i = 9; i > 1; i--) {ldelim}
	if ($("[name=firstName" + i + "]").val() == ''
	 && $("[name=lastName" + i + "]").val() == '') {ldelim}
		$("[name=author" + i + "]").hide();
	{rdelim} else {ldelim}
		break;
	{rdelim}
{rdelim}
var noAuthor = i;
function add_author() {ldelim}
	if (noAuthor < 9) {ldelim}
		noAuthor++;
		$("[name=author" + noAuthor + "]").show(200);
	{rdelim}
	if (noAuthor == 9) {ldelim}
		$("[name=addAuthor]").hide(200);
	{rdelim}
{rdelim}

$("#lostPassword").click(function() {ldelim}
	window.open("{url page="login" op="lostPassword"}?email=" + $("[name=email]").val());
{rdelim});

function editors_in_section(elt) {ldelim}
	var sectionId = elt.value;
	$.each( $("[name=editorId]").find("option"), function() {ldelim}
		var sections = this.value.split('-');
		sections.pop();
		$(this).toggleClass("notInSection", sections.indexOf(sectionId) == -1);
	{rdelim});
{rdelim};
$("[name=sectionId]").change(function() {ldelim} editors_in_section(this); {rdelim});
$.each($("[name=sectionId]"), function() {ldelim} editors_in_section(this); {rdelim});

</script>
