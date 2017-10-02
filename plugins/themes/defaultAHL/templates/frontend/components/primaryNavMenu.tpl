{**
 * plugins/themes/defaultAHL/templates/frontend/components/primaryNavMenu.tpl
 *
 * SAN
 *
 * Primary navigation menu list for OJS
*}
<div class="pkp_navigation_primary">
<div class="hline top"></div>
<ul id="navigationPrimary" class="pkp_nav_list">

  {if $enableAnnouncements}
    <li>
      <a href="{url router=$smarty.const.ROUTE_PAGE page="announcement"}">
        {translate key="announcement.announcements"}
      </a>
    </li>
  {/if}

  {if $currentJournal}

    {if $currentJournal->getSetting('publishingMode') != $smarty.const.PUBLISHING_MODE_NONE}
    <li>
      <div>
	<a href="{url router=$smarty.const.ROUTE_PAGE page="issue" op="archive"}">
          {translate key="navigation.contents"}
        </a>
	<div class="circle"></div>
      </div>
    </li>
    {/if}
    {if $currentJournal->getLocalizedSetting('masthead')}
     <li>
       <div>
	 <a href="{url router=$smarty.const.ROUTE_PAGE page="about" op="editorialTeam"}">
           {translate key="about.editorialTeam"}
	 </a>
	 <div class="circle"></div>
       </div>
     </li>
     {/if}
     
    <li>
      <div>
	<a href="{$homeUrl}" class="is_text">henri lebesgue</a>
	<div class="annales_text">annales</div>
      </div>
    </li>
    
    <li {if $isUserLoggedIn} aria-haspopup="true" aria-expanded="false" {/if}>
      <div>
	<a href="{url router=$smarty.const.ROUTE_PAGE page="submission_fast"}">
          {if $subMenu}
            {translate key="about.submissions"}
          {else}
            {translate key="submission.new"}
          {/if}
	</a>
	<div class="circle"></div>
      </div>
      {if $isUserLoggedIn && $subMenu}
      <ul>
        <li>
	  <a href="{url router=$smarty.const.ROUTE_PAGE page="submission_fast"}">
            {translate key="submission.new"}
	  </a>
        </li>
        <li>
	  <a href="{url router=$smarty.const.ROUTE_PAGE page="submissions"}">
            {translate key="submission.view"}
	  </a>
        </li>
      </ul>
      {/if}
    </li>

    <li aria-haspopup="true" aria-expanded="false">
      <div>
        <a href="{url router=$smarty.const.ROUTE_PAGE page="about"}">
          {translate key="navigation.about"}
	</a>
	<div class="circle"></div>
      </div>
      {if $subMenu}
      <ul>
        <li>
          <a href="{url router=$smarty.const.ROUTE_PAGE page="about"}">
            {translate key="about.aboutContext"}
          </a>
        </li>
        <li>
          <a href="{url router=$smarty.const.ROUTE_PAGE page="about" op="partners"}">
            {translate key="about.partners"}
          </a>
        </li>
        {if $currentJournal->getSetting('mailingAddress') || $currentJournal->getSetting('contactName')}
          <li>
            <a href="{url router=$smarty.const.ROUTE_PAGE page="about" op="contact"}">
              {translate key="about.contact"}
            </a>
          </li>
        {/if}
      </ul>
      {/if}
    </li>
  {/if}
</ul>
<div class="hline bottom"></div>
</div>
