{**
 * templates/frontend/pages/news.tpl
 *
 * XAVIER
 *
 *
 *}
{include file="frontend/components/header.tpl" pageTitleTranslated=$currentJournal->getLocalizedName()}

<div class="page_news_ahl">

	{call_hook name="Templates::Index::journal"}

	{if !$alreadySubscriber}
		<div id="subscriptionNewsletter">
			{include file="frontend/components/subscribeNewsletter.tpl"}
		</div>
	{/if}

	{url|assign:newsUrl router=$smarty.const.ROUTE_COMPONENT component="modals.newsletter.SubscriptionHandler" op="fetchNews" first="1"}
	{load_url_in_div id="news" url=$newsUrl}
</div>


</div><!-- .page -->
