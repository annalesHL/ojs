<?php

/**
 * @file pages/login/LoginHandler.inc.php
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class LoginHandler
 * @ingroup pages_login
 *
 * @brief Handle login/logout requests.
 */


import('lib.pkp.pages.login.PKPLoginHandler');

class LoginByEmailHandler extends PKPLoginHandler {

	/**
	 * Get the log in URL.
	 * @param $request PKPRequest
	 */
	function _getLoginUrl($request) {
		return $request->url(null, 'login', 'signIn');
	}

	/**
	 * Helper Function - set mail from address
	 * @param $request PKPRequest
	 * @param $mail MailTemplate
	 */
	function _setMailFrom($request, &$mail) {
		$site = $request->getSite();
		$journal = $request->getJournal();

		// Set the sender based on the current context
		if ($journal && $journal->getSetting('supportEmail')) {
			$mail->setReplyTo($journal->getSetting('supportEmail'), $journal->getSetting('supportName'));
		} else {
			$mail->setReplyTo($site->getLocalizedContactEmail(), $site->getLocalizedContactName());
		}
	}

	/**
	 * Configure the template for display.
	 * @param $request PKPRequest
	 */
	function setupTemplate($request) {
		AppLocale::requireComponents(LOCALE_COMPONENT_APP_MANAGER, LOCALE_COMPONENT_PKP_MANAGER);
		parent::setupTemplate($request);
	}

	function index($args, $request) {
		$this->setupTemplate($request);
		if (Validation::isLoggedIn()) {
			$this->sendHome($request);
		}

		$sessionManager = SessionManager::getManager();
		$session = $sessionManager->getUserSession();

		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign(array(
			'loginMessage' => $request->getUserVar('loginMessage'),
			'email' => $session->getSessionVar('email'),
			'remember' => $request->getUserVar('remember'),
			'source' => $request->getUserVar('source'),
			'showRemember' => Config::getVar('general', 'session_lifetime') > 0,
			'loginUrl' => $this->_getLoginUrl($request),
		));

		$templateMgr->display('frontend/pages/userLoginByEmail.tpl');
	}

	function signIn($args, $request) {
		if ($request->getUserVar('captcha') !== "15") {
			$templateMgr = TemplateManager::getManager($request);
			$templateMgr->assign(array(
				'email' => $request->getUserVar('email'),
				'remember' => $request->getUserVar('remember'),
				'source' => $request->getUserVar('source'),
				'showRemember' => Config::getVar('general', 'session_lifetime') > 0,
				'error' => "common.captcha.error.invalid-input-response",
				'loginUrl' => $this->_getLoginUrl($request),
			));
			return $templateMgr->fetch('frontend/pages/userLoginByEmail.tpl');
		}

		$this->setupTemplate($request);
		if (Validation::isLoggedIn()) {
			$this->sendHome($request);
		}

		$email = trim($request->getUserVar('email'));
		$userDao = DAORegistry::getDAO('UserDAO');
		$user = $userDao->getUserByEmail($email, false);

		if ($user) {
			$remember = $request->getUserVar('remember') != null;
			$user = Validation::login($user->getUsername(), $request->getUserVar('password'), $reason, $remember);
		}

		if ($user) {

			// Should we check MustChangePassword?
			$source = $request->getUserVar('source');
			if (isset($source) && !empty($source)) {
				$request->redirectUrl($source);
			} else {
				$this->sendHome($request);
			}

		} else {

			$templateMgr = TemplateManager::getManager($request);
			$templateMgr->assign(array(
				'email' => $request->getUserVar('email'),
				'remember' => $request->getUserVar('remember'),
				'source' => $request->getUserVar('source'),
				'showRemember' => Config::getVar('general', 'session_lifetime') > 0,
				'error' => $reason===null ? "user.login.emailError" : ($reason==='' ? "user.login.accountDisabled" : "user.login.accountDisabledWithReason"),
				'reason' => $reason,
				'loginUrl' => $this->_getLoginUrl($request),
			));
			$templateMgr->display('frontend/pages/userLoginByEmail.tpl');

		}

	}

	function signOut($args, $request) {
		if (Validation::isLoggedIn()) {
			Validation::logout();
		}

		$source = $request->getUserVar('source');
		if (isset($source) && !empty($source)) {
			$request->redirectUrl($source);
		} else {
			$request->redirectUrl("/");
		}
	}

	function lostPassword($args, $request) {
		$this->setupTemplate($request);
		$templateMgr = TemplateManager::getManager($request);

		if ($request->getUserVar('captcha') !== "15") {
			$templateMgr->assign(array(
				'email' => $request->getUserVar('email'),
				'error' => "common.captcha.error.invalid-input-response",
			));
			return $templateMgr->fetch('frontend/pages/lostPassword.tpl');
		}

		$email = trim($request->getUserVar('email'));
		$userDao = DAORegistry::getDAO('UserDAO');
		$user = $userDao->getUserByEmail($email, false);

		if ($user && ($hash = Validation::generatePasswordResetHash($user->getId())) !== false) {

			// Send email confirming password reset
			import('lib.pkp.classes.mail.MailTemplate');
			$mail = new MailTemplate('PASSWORD_RESET_CONFIRM');
			$mail->assignParams(array(
				'url' => $request->url(null, 'login', 'resetPassword', $user->getUsername(), array('confirm' => $hash)),
			));
			$settings = $request->getContext()->getSettings();
			$mail->setFrom($settings['supportEmail'], $settings['supportName']);
			$mail->addRecipient($email, $user->getFullName());
			$mail->send();

			$templateMgr->assign(array(
				'pageTitle' => 'user.login.resetPassword',
				'message' => 'user.login.lostPassword.confirmationSent',
			));
			$templateMgr->display('frontend/pages/message.tpl');

		} else {

			$templateMgr->assign(array(
				'email' => $request->getUserVar('email'),
				'error' => $email === "" ? "" : "user.login.lostPassword.invalidUser",
			));
			$templateMgr->display('frontend/pages/lostPassword.tpl');

		}
	}

	function resetPassword($args, $request) {
		$this->setupTemplate($request);

		$username = isset($args[0]) ? $args[0] : null;
		$userDao = DAORegistry::getDAO('UserDAO');
		$confirmHash = $request->getUserVar('confirm');

		if ($username == null || ($user = $userDao->getByUsername($username)) == null) {
			$request->redirect(null, null, 'lostPassword');
		}

		$password = $request->getUserVar('password');
		$repeatPassword = $request->getUserVar('password2');
		$length = $request->getSite()->getMinPasswordLength();

		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('fullName', $user->getFullName());
		$templateMgr->assign('length', $length);

		if (!Validation::verifyPasswordResetHash($user->getId(), $confirmHash)) {
			$templateMgr->assign('message', __("user.login.lostPassword.invalidHash"));
			$templateMgr->display('frontend/pages/lostPassword.tpl');
		} else if ($password === null) {
			$templateMgr->display('frontend/pages/resetPassword.tpl');
		} else if ($password !== $repeatPassword) {
			$templateMgr->assign('error', __("user.login.password.notMatch"));
			$templateMgr->display('frontend/pages/resetPassword.tpl');
		} else if (strlen($password) < $length) {
			$templateMgr->assign('invalidLength', true);
			$templateMgr->display('frontend/pages/resetPassword.tpl');
		} else {
			$user->setPassword(Validation::encryptCredentials($user->getUsername(), $password));
			$userDao->updateObject($user);
			$user = Validation::registerUserSession($user, $reason);
			if ($user !== null) $this->sendHome($request);
			$templateMgr->assign(array(
				'email' => $request->getUserVar('email'),
				'error' => $reason===null ? "user.login.emailError" : ($reason==='' ? "user.login.accountDisabled" : "user.login.accountDisabledWithReason"),
				'reason' => $reason,
				'loginUrl' => $this->_getLoginUrl($request),
			));
			$templateMgr->display('frontend/pages/userLoginByEmail.tpl');
		}
	}

}

?>
