<?php

/**
 * @file pages/user/RegistrationHandler.inc.php
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class RegistrationHandler
 * @ingroup pages_user
 *
 * @brief Handle requests for user registration.
 */


import('lib.pkp.pages.user.RegistrationHandler');
import('plugins.AHL.pages.user.RegistrationWithoutUsernameForm');

class RegistrationWithoutUsernameHandler extends RegistrationHandler {
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * Display registration form for new users.
	 * @param $args array
	 * @param $request PKPRequest
	 */
	function register($args, $request) {
		$this->validate($request);
		$this->setupTemplate($request);

		$regForm = new RegistrationWithoutUsernameForm($request->getSite());
		$regForm->initData($request);
		$regForm->display($request);
	}

	/**
	 * Validate user registration information and register new user.
	 * @param $args array
	 * @param $request PKPRequest
	 */
	function registerUser($args, $request) {
		$this->validate($request);
		$this->setupTemplate($request);

		$regForm = new RegistrationWithoutUsernameForm($request->getSite());
		$regForm->readInputData();
		if (!$regForm->validate()) {
			return $regForm->display($request);
		}

		$regForm->execute($request);

		// Inform the user of the email validation process. This must be run
		// before the disabled account check to ensure new users don't see the
		// disabled account message.
		if (Config::getVar('email', 'require_validation')) {
			$this->setupTemplate($request);
			$templateMgr = TemplateManager::getManager($request);
			$templateMgr->assign(array(
				'requireValidation' => true,
				'pageTitle' => 'user.login.registrationPendingValidation',
				'messageTranslated' => __('user.login.accountNotValidated', array('email' => $regForm->getData('email'))),
			));
			return $templateMgr->fetch('frontend/pages/message.tpl');
		}

		$reason = null;
		if (Config::getVar('security', 'implicit_auth')) {
			Validation::login('', '', $reason);
		} else {
			Validation::login($regForm->getData('username'), $regForm->getData('password'), $reason);
		}

		if ($reason !== null) {
			$this->setupTemplate($request);
			$templateMgr = TemplateManager::getManager($request);
			$templateMgr->assign(array(
				'pageTitle' => 'user.login',
				'errorMsg' => $reason==''?'user.login.accountDisabled':'user.login.accountDisabledWithReason',
				'errorParams' => array('reason' => $reason),
				'backLink' => $request->url(null, 'login'),
				'backLinkLabel' => 'user.login',
			));
			return $templateMgr->fetch('frontend/pages/error.tpl');
		}

		if ($source = $request->getUserVar('source')) {
			return $request->redirectUrlJson($source);
		} else {
			$request->redirect(null, 'user', 'registrationComplete');
		}
	}

}

?>
