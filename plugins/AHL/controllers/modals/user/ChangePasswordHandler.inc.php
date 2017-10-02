<?php

import('classes.handler.Handler');
import('lib/pkp/classes/core/JSONMessage');

class ChangePasswordHandler extends Handler {
	/**
	 * Constructor.
	 */
	function __construct() {
		parent::__construct();
	}

	function savePassword($args, $request) {
		$this->setupTemplate($request);

		$confirmHash = $request->getUserVar('hash');
		$username = $request->getUserVar('username');
		$userDao = DAORegistry::getDAO('UserDAO');

                if ($username == null || ($user = $userDao->getByUsername($username)) == null) {
			return new JSONMessage(true, "error");
                }

		$password = $request->getUserVar('password');
		$repeatPassword = $request->getUserVar('password2');
		$length = $request->getSite()->getMinPasswordLength();

		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign(array(
			'username' => $username,
			'hash' => $confirmHash,
			'length' => $length,
		));

		if (!Validation::verifyPasswordResetHash($user->getId(), $confirmHash)) {
			$templateMgr->assign('message', __("user.login.lostPassword.invalidHash"));
		} else if ($password !== $repeatPassword) {
			$templateMgr->assign('error', __("user.login.password.notMatch"));
		} else if (strlen($password) < $length) {
			$templateMgr->assign('invalidLength', true);
		} else {
			$user->setPassword(Validation::encryptCredentials($username, $password));
			$userDao->updateObject($user);
			$templateMgr->assign('hash', Validation::generatePasswordResetHash($user->getId()));
			$templateMgr->assign('success', __("user.password.passwordChanged"));
                }
		return $templateMgr->fetchJSON('frontend/components/changePassword.tpl');
	}

}

?>
