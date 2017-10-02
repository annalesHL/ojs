{**
 * templates/frontend/pages/editorialTeam.tpl
 *
 * 
 * TODO ajouter chief editor et associate editors
 *
 * @brief Display the page to view the editorial team.
 *
 * @uses $currentContext Journal|Press The current journal or press
 *}
{include file="frontend/components/header.tpl" pageTitle="about.editorialTeam"}

<div class="additional_content editorial_team">

<div class="chief_editor">
<h3>chief editor</h3>
<ul>
  {section name=i loop=$chiefs}
  <li>{$chiefs[i]}</li>
  {/section}
</ul>
</div>

{assign var=number value=0}
{section name=i loop=$sections}	
{assign var=number value=$number+1}
<div class="section{$number}">
  <h3>{$sections[i].name}</h3>
  <ul>
    {section name=j loop=$sections[i].editors}
    <li>{$sections[i].editors[j]}</li>
    {/section}
  </ul>
</div>
{/section}

<div class="associate_editors">
<h3>associate editor</h3>
<ul>
  {section name=i loop=$associate}
  <li>{$associate[i]}</li>
  {/section}
</ul>
</div>

</div>

{include file="frontend/components/footer.tpl"}
