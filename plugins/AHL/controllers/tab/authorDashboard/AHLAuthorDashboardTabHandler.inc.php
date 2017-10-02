<?php

/**
 * @defgroup controllers_tab_user
 */

/**
 * @file controllers/tab/user/ProfileTabHandler.inc.php
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class ProfileTabHandler
 * @ingroup controllers_tab_user
 *
 * @brief Handle requests for profile tab operations.
 */


import('classes.handler.Handler');
import('lib.pkp.classes.core.JSONMessage');

import('lib.pkp.controllers.tab.authorDashboard.AuthorDashboardTabHandler');

import('controllers.modals.submissionMetadata.form.IssueEntrySubmissionReviewForm');


class AHLAuthorDashboardTabHandler extends AuthorDashboardTabHandler {
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		$this->addRoleAssignment(array(ROLE_ID_AUTHOR), array('metadata', 'saveForm', 'attachedFiles', 'preReview'));
		AppLocale::requireComponents(LOCALE_COMPONENT_PKP_SUBMISSION);
	}

	//
	// Implement template methods from PKPHandler
	//
	/**
	 * @copydoc PKPHandler::authorize()
	 */
	function authorize($request, &$args, $roleAssignments) {
		// User must be logged in
		import('lib.pkp.classes.security.authorization.UserRequiredPolicy');
		$this->addPolicy(new UserRequiredPolicy($request));

		return parent::authorize($request, $args, $roleAssignments);
	}

	/**
	 * Display form to edit user's identity.
	 * @param $args array
	 * @param $request PKPRequest
	 * @return JSONMessage JSON-formatted response
	 */
	function metadata($args, $request) {
		$form = new IssueEntrySubmissionReviewForm($args['submissionId']);
		$form->initData($args, $request);
		return new JSONMessage(true, $form->fetch($request));
	}

	function saveForm($args, $request) {
                $submissionId = $request->getUserVar('submissionId');

                // Form handling
                $submissionMetadataViewForm = new IssueEntrySubmissionReviewForm($submissionId);

                // Try to save the form data.
                $submissionMetadataViewForm->readInputData($request);
                if($submissionMetadataViewForm->validate()) {
                        $submissionMetadataViewForm->execute($request);
                        // Create trivial notification.
                        $notificationManager = new NotificationManager();
                        $user = $request->getUser();
                        $notificationManager->createTrivialNotification($user->getId(), NOTIFICATION_TYPE_SUCCESS, array('contents' => __('notification.savedSubmissionMetadata')));
                }
		return new JSONMessage(true, $submissionMetadataViewForm->fetch($request));
	}

	function attachedFiles($args, $request) {
		$this->setupTemplate($request);
		$templateMgr = TemplateManager::getManager($request);

		$submission = $this->getAuthorizedContextObject(ASSOC_TYPE_SUBMISSION);
		$templateMgr->assign('submissionId', $submission->getId());

		return $templateMgr->fetchJson('controllers/tab/authorDashboard/attachedFiles.tpl');
	}

	function preReview($args, $request) {
		$this->setupTemplate($request);
		$templateMgr = TemplateManager::getManager($request);

		$submission = $this->getAuthorizedContextObject(ASSOC_TYPE_SUBMISSION);
		$templateMgr->assign('submission', $submission);

		return $templateMgr->fetchJson('controllers/tab/authorDashboard/preReview.tpl');
	}

}

?>
