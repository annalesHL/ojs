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

import('lib.pkp.classes.mail.MailTemplate');

class TestHandler extends Handler {
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		// AppLocale::requireComponents(LOCALE_COMPONENT_APP_COMMON);
	}

	function index($args, $request) {
		$user = $request->getUser();
		if ($user === null) {
			$request->redirect("AHL", "login");
		}
		$templateMgr = TemplateManager::getManager($request);
		$this->setupTemplate($request);
		$templateMgr->assign(array(
			'newAccount' => true,
			'username' => $user->getUsername(),
			'password' => Validation::generatePassword(15),
			'hash' => Validation::generatePasswordResetHash($user->getId()),
			'length' => $request->getSite()->getMinPasswordLength(),
		));
		$templateMgr->display('frontend/pages/submission_fast/thanks.tpl');
	}

}

?>
