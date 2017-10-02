<?php

/**
 * @file plugins/AHL/pages/submission_fast/SubmissionFastHandler.inc.php
 *
 * XAVIER
 *
 * TODO: use the class Form
 *
 */

import('classes.handler.Handler');
import('classes.user.User');

import('plugins.AHL.pages.submission.form.SubmissionFastForm');


class SubmissionFastHandler extends Handler {
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}

	function index($args, $request) {
		$this->setupTemplate($request);
		$form = new SubmissionFastForm($request);
		$form->display();
	}

	function submit($args, $request) {
		$this->setupTemplate($request);
		$form = new SubmissionFastForm($request);
		$form->readInputData();
		if ($form->validate($args, $request)) {
			$values = $form->execute($args, $request);
			$templateMgr = TemplateManager::getManager($request);
			$templateMgr->initialize();
			foreach($values as $key => $value) $templateMgr->assign($key, $value);
			$templateMgr->display('frontend/pages/submission_fast/thanks.tpl');
		} else {
			$form->display();
		}
	}

}

?>
