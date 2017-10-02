{**
 * templates/common/footer.tpl
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Common site footer.
 *}

</div><!-- pkp_structure_main -->
</div><!-- pkp_structure_body -->

<script type="text/javascript">
	// Initialize JS handler
	$(function() {ldelim}
		$('#pkpHelpPanel').pkpHandler(
			'$.pkp.controllers.HelpPanelHandler',
			{ldelim}
				helpUrl: {url|json_encode page="help" escape=false},
				helpLocale: '{$currentLocale|substr:0:2}',
			{rdelim}
		);
	{rdelim});
</script>
<div id="pkpHelpPanel" class="pkp_help_panel" tabindex="-1">
	<div class="panel">
		<div class="header">
			<a href="#" class="pkpHomeHelpPanel home">
				{translate key="help.toc"}
			</a>
			<a href="#" class="pkpCloseHelpPanel close">
				{translate key="common.close"}
			</a>
		</div>
		<div class="content">
			{include file="common/loadingContainer.tpl"}
		</div>
		<div class="footer">
			<a href="#" class="pkpPreviousHelpPanel previous">
				{translate key="help.previous"}
			</a>
			<a href="#" class="pkpNextHelpPanel next">
				{translate key="help.next"}
			</a>
		</div>
	</div>
</div>

{load_script context="panel"}

</body>
</html>
