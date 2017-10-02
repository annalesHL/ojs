{**
 * templates/frontend/pages/partners.tpl
 *
 * SAN	
 *
 * @brief Display AHL partners
 *
 *}
{include file="frontend/components/header.tpl" pageTitleTranslated=$currentJournal->getLocalizedName()}

<div class="page_partners_ahl">

  {call_hook name="Templates::Index::journal"}
  
  {* Partners *}
  <div class="partners">
    <p>Sustainability of the journal is made possible thanks to the financial and technical support from</p>
    <ul>
      <li>CHL (Centre Henri Lebesgue)</li>
      <li>CNRS (Centre National de la Recherche Scientifique)</li>
      <li>UR1 (Université de Rennes 1)</li>
      <li>ENS Rennes (École Normale Supérieure de Rennes)</li>
      <li>IRMAR (Institut de Recherche Mathématique de Rennes)</li>
      <li>LMJL (Laboratoire Mathématique Jean Leray)</li>
      <li>LMBA (Laboratoire de Mathématiques de Bretagne Atlantique)</li>
      <li>LAREMA (Laboratoire Angevin de REcherche en MAthématiques)</li>
    </ul>
  </div>
  
</div><!-- .page -->
