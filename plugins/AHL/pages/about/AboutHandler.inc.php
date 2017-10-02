<?php

/**
 * @file plugins/AHL/pages/about/AboutHandler.inc.php
 *
 * SAN & XAVIER
 *
 * @class AboutContextHandler
 * @ingroup pages_about
 *
 * @brief Display partners
 */

import('classes.handler.Handler');

class AboutHandler extends Handler {
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		// AppLocale::requireComponents(LOCALE_COMPONENT_APP_COMMON);
	}

	/**
	 * Display partners page.
	 * @param $args array
	 * @param $request PKPRequest
	 */
	function partners($args, $request) {
		$templateMgr = TemplateManager::getManager($request);
		$this->setupTemplate($request);
		$templateMgr->display('frontend/pages/partners.tpl');
	}

	function editorialTeam($args, $request) {
		$locale = AppLocale::getLocale();
		$context = $request->getContext();
		$contextId = $context->getId();

		$sectionDao = DAORegistry::getDAO('SectionDAO');
		$subEditorsDao = DAORegistry::getDAO('SubEditorsDAO');
		$userGroupDao = DAORegistry::getDAO('UserGroupDAO');

		$associate = $subEditorsDao->getEditorsNotInSection($contextId, 0);

		$chiefs = array();
		$userGroups = $userGroupDao->getByRoleId($contextId, ROLE_ID_MANAGER);
		while ($userGroup = $userGroups->next()) {
			if ($userGroup->getAbbrev()['en_US'] !== "ChE") continue;  // TODO: find something better
			$groupId = $userGroup->getId();
			$editors = $userGroupDao->getUsersById($groupId, $contextId);
			while ($editor = $editors->next()) {
				$chiefs[] = $editor->getFullName();
				unset($associate[$editor->getId()]);
			}
		}

		$sectionsIterator = $sectionDao->getByJournalId($contextId);
		$sections = array();
		while($section = $sectionsIterator->next()) {
			$sectionId = $section->getId();
			$editors = $subEditorsDao->getBySectionId($sectionId, $contextId);
			if (empty($editors)) continue;
			$editorsName = array();
			foreach($editors as $editor) {
				$editorsName[] = $editor->getFullName();
				unset($associate[$editor->getId()]);
			}
			$sections[] = array( 'name' => $section->getTitle($locale), 'editors' =>  $editorsName );
		}

		$templateMgr = TemplateManager::getManager($request);
		$this->setupTemplate($request);
		$templateMgr->assign('chiefs', $chiefs);
		$templateMgr->assign('sections', $sections);
		$templateMgr->assign('associate', array_map( function($editor) { return $editor->getFullName(); }, array_values($associate) ));
		$templateMgr->display('frontend/pages/editorialTeam.tpl');
	}

}

?>
