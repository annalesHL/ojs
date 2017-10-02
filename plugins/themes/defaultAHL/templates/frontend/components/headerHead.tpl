{**
 * templates/frontend/components/headerHead.tpl
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2000-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Common site header <head> tag and contents.
 *}
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={$defaultCharset|escape}" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{$currentContext->getLocalizedName()}</title>

	{load_header context="frontend"}
	{load_stylesheet context="backend"}
	{if $isUserLoggedIn}
		{load_script context="backend"}
	{else}
		{load_script context="frontend"}
	{/if}
</head>
