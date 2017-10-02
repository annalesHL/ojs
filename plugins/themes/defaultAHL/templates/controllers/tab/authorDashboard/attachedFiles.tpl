{**
 * templates/submission/submissionAttachedFiles.tpl
 *
 * XAVIER
 *
 *}
{url|assign:submissionFilesGridUrl router=$smarty.const.ROUTE_COMPONENT component="grid.files.submission.SubmissionWizardFilesGridHandler" op="fetchGrid" submissionId=$submissionId escape=false}
{load_url_in_div id="submissionFilesGridDiv" url=$submissionFilesGridUrl}
