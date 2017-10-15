<?php

/**
 * @file SubmissionFastForm.inc.php
 *
 * XAVIER
 *
 */

import('lib.pkp.classes.i18n.PKPLocale');

import('lib.pkp.classes.form.Form');
import('lib.pkp.classes.form.validation.FormValidatorEmail');
import('plugins.AHL.form.validation.FormValidatorCaptcha');

import('lib.pkp.classes.submission.Genre');
import('lib.pkp.classes.submission.SubmissionFile');

import('classes.user.User');
import('lib.pkp.classes.security.Role');

import('lib.pkp.classes.file.FileManager');
import('lib.pkp.classes.file.TemporaryFileManager');
import('lib.pkp.classes.file.SubmissionFileManager');

import('plugins.generic.externalFeed.simplepie.SimplePie');

import('lib.pkp.classes.mail.SubmissionMailTemplate');

import('plugins.AHL.classes.Arxiv');


class SubmissionFastForm extends Form {
	var $context;

	var $_title;
	var $_abstract = null;
	var $_authors = array();
	var $_article;

	var $_prefix = array('le', 'de', 'du', 'von');


	function __construct($request) {
		$this->context = $context = $request->getContext();
		$contextId = $context->getId();

		parent::__construct();
		AppLocale::requireComponents(LOCALE_COMPONENT_APP_AUTHOR, LOCALE_COMPONENT_PKP_USER, LOCALE_COMPONENT_PKP_SUBMISSION);
		if (PluginRegistry::getPlugins('themes') === null) {
			$themes = PluginRegistry::loadCategory('themes', true);
		}
		$this->setTemplate('frontend/pages/submission_fast/index.tpl');

		// Captcha validation
		$this->addCheck(new FormValidatorCaptcha($this, 'captcha'));

		// Retrieve sections
		$subEditorsDao = DAORegistry::getDAO('SubEditorsDAO');
		$sectionDao = DAORegistry::getDAO('SectionDAO');
		$sectionOptions = array('0' => "Articles with no specific section");
		foreach($sectionDao->getTitles($contextId, true) as $sectionId => $sectionTitle) {
			$editors = $subEditorsDao->getBySectionId($sectionId, $contextId);
			if (count($editors)) {
				$sectionOptions[$sectionId] = $sectionTitle;
			}
		}
		$this->setData('sectionOptions', $sectionOptions);
		$this->setData('sectionId', '0');

		// Retrieve editors
		$editors = $subEditorsDao->getEditorsNotInSection($contextId, 0);
		$sectionsByEditor = $sectionDao->getEditorSections($contextId);
		$editorOptions = array('0' => '');
		foreach($editors as $editor) {
			$editorId = $editor->getId();
			$key = "0-";
			foreach ($sectionsByEditor[$editorId] as $section)
				$key .= ($section->getId() . "-");
			$editorOptions[$key . $editorId] = $editor->getFullName();
		}
		$this->setData('editorOptions', $editorOptions);
	}

	function fetch($request, $template = null, $display = false) {
		$this->setData('password', '');
		TemplateManager::getManager($request)->initialize();
		return parent::fetch($request, $template, $display);
	}

	function readInputData() {
		$this->readUserVars(array('onArxiv', 'sectionId', 'editorId', 'comments', 'captcha'));

		if ($this->getData('onArxiv') === 'on') {
			$this->readUserVars(array('arxiv'));
		} else {
			$this->readUserVars(array('title', 'article', 'article_fileId'));
			$this->_title = $this->getData('title');
			$noAuthor = 1;
			for ($i = 2; $i < 10; $i++) {
				$firstName = Request::getUserVar('firstName' . $i);
				$lastName = Request::getUserVar('lastName' . $i);
				$email = Request::getUserVar('email' . $i);
				if (preg_match("/[^\s]$/", $firstName . $lastName . $email)) {
					$noAuthor += 1;
					$this->setData('firstName' . $noAuthor, $firstName);
					$this->setData('lastName' . $noAuthor, $lastName);
					$this->setData('email' . $noAuthor, $email);
					$this->_authors[] = array(
						'firstName' => $firstName,
						'lastName' => $lastName,
						'email' => $email,
					);
				}
			}
		}

		if (!Validation::IsLoggedIn()) {
			$this->readUserVars(array('email', 'hasAccount'));
			if ($this->getData('hasAccount') === 'on') {
				$this->readUserVars(array('password'));
			} else {
				$this->readUserVars(array('firstName', 'lastName'));
			}
		}
	}

	function validate($args, $request) {
                $userDao = DAORegistry::getDAO('UserDAO');
		$email = $this->getData('email');
		$user = $userDao->getUserByEmail($email, FALSE);
		$arXivPDF = null;

		$fileManager = new FileManager();
		$temporaryFileManager = new TemporaryFileManager();

		// Check identity
		if (Validation::isLoggedIn()) {

			$user = $request->getUser();
			$submitterFirstName = $user->getFirstName();
			$submitterLastName = $user->getLastName();

		} else if ($this->getData('hasAccount') === 'on') {

			if ($user) $user = Validation::login($user->getUsername(), $this->getData('password'), $reason);
			if ($user) {
				$submitterFirstName = $user->getFirstName();
				$submitterLastName = $user->getLastName();
			} else {
				$this->addError('email', __("user.login.emailError"));
				$this->addErrorField('email');
				$this->addErrorField('password');
			}

		} else {

			if ($user) {
				$url = $request->url(null, 'login', 'lostPassword', null, array('email' => htmlentities($email)));
				$this->addError('email', __("user.login.emailExists", array('url' => $url)));
				$this->addErrorField('email');
				$user = null;
			} else {
				$this->addCheck(new FormValidatorEmail($this, 'email', FORM_VALIDATOR_REQUIRED_VALUE));
			}
			$submitterFirstName = $this->getData('firstName');
			$submitterLastName = $this->getData('lastName');
			$this->addCheck(new FormValidator($this, 'firstName', FORM_VALIDATOR_REQUIRED_VALUE, 'user.profile.form.firstNameRequired'));
			$this->addCheck(new FormValidator($this, 'lastName', FORM_VALIDATOR_REQUIRED_VALUE, 'user.profile.form.lastNameRequired'));

		}

		// Set userId
		if ($user) {
			$userId = $user->getId();
		} else {
			$userId = 0;
		}

		// Check submission
		if ($this->getData('onArxiv') === 'on') {

			/* We check here that:
			 *  . the arXiv reference is correct
			 *  . the submitter is an author of the arXiv paper
			 */

			mb_internal_encoding('utf-8');

			$arxivId = trim($this->getData('arxiv'));
			$arxivId = preg_replace("/^arxiv:\s*/i", "", $arxivId);
			if (!preg_match("/^\d{4}\.\d+(v\d+)?$/", $arxivId)) {
				$this->addError('arxiv', __("submission.arxiv.invalid"));
				$this->addErrorField('arxiv');
			} else {
				$arxiv = new Arxiv($arxivId);
				if (!$arxiv->isValid()) {
					$this->addError('arxiv', __("submission.arxiv.invalid"));
					$this->addErrorField('arxiv');
				} else {
					// Title and abstract
					$this->_title = $arxiv->getTitle();
					$this->_abstract = $arxiv->getAbstract();

					// Authors
					$canonicalFirstName = $this->_canonicalize($submitterFirstName);
					$canonicalLastName = $this->_canonicalize($submitterLastName);
					$isAmongAuthors = false;
					$this->_authors = array();
					foreach ($arxiv->getAuthors() as $author) {
						$name = $author->get_name();
						$canonical = $this->_canonicalize($name);
						if (strpos($canonical, $canonicalFirstName) !== false
						 && strpos($canonical, $canonicalLastName) !== false) {
							$isAmongAuthors = true;
						} else {
							$name = $workingName = preg_replace("/\s+/", " ", $name);
							foreach($this->_prefix as $prefix) {
								$workingName = preg_replace('/(' . $prefix . ') /i', '\1~', $workingName);
							}
							$position = strrpos($workingName, ' ');
							if ($position === false) $position = 0;
							$firstName = trim(substr($name, 0, $position));
							$lastName = trim(substr($name, $position));
							$this->_authors[] = array('firstName' => $firstName, 'lastName' => $lastName);
						}
					}
					if (!$isAmongAuthors) {
						$this->addError('arxiv', __("submission.arxiv.notAuthor"));
						$this->addErrorField('arxiv');
					}

					// Article in PDF
					$arxivPDF = $arxiv->getPDFUrl();
					if ($arXivPDF === null) {
						$this->addError('arxiv', __("submission.arxiv.errorPDF"));
						$this->addErrorField('arxiv');
					}
				}
			}

		} else {

			$this->addCheck(new FormValidator($this, 'title', FORM_VALIDATOR_REQUIRED_VALUE, 'submission.titleRequired'));
			for ($i = 2; $i <= count($this->_authors) + 1; $i++) {
				$this->addCheck(new FormValidator($this, 'firstName' . $i, FORM_VALIDATOR_REQUIRED_VALUE, 'user.profile.form.firstNameRequired'));
				$this->addCheck(new FormValidator($this, 'lastName' . $i, FORM_VALIDATOR_REQUIRED_VALUE, 'user.profile.form.lastNameRequired'));
				$this->addCheck(new FormValidatorEmail($this, 'email' . $i));
			}

			if (isset($_FILES['article']) and $_FILES['article']['error'] !== UPLOAD_ERR_NO_FILE) {

				// The user tried to upload a file
				$this->setData('article_fileId', 0);
				if ($fileManager->uploadError('article')) {
					$this->addError('article', __("manager.plugins.uploadError"));
					$this->addErrorField('article');
				} else {
					$fileId = $this->getData('article_fileId');
					$mimetype = $fileManager->getUploadedFileType('article');
					$type = $fileManager->getDocumentType($mimetype);
					if ($type !== DOCUMENT_TYPE_PDF) {
						$this->addError('article', __("submission.pdfRequired"));
						$this->addErrorField('article');
					} else {
						// Everything's fine
						// We save the article as a temporary file
						$file = $temporaryFileManager->handleUpload('article', $userId);
						if ($file) {
							$this->_article = $file;
							$this->setData('article_fileId', $file->getFileId());
							$this->setData('_article', $fileManager->getUploadedFileName('article'));
						} else {
							$this->addError('article', __("manager.plugins.uploadError"));
							$this->addErrorField('article');
						}
					}
				}

			} else {

				// We check whether a file was already uploaded
				$fileId = $this->getData('article_fileId');
				$temporaryFileDao = DAORegistry::getDAO('TemporaryFileDAO');
				$file = $temporaryFileDao->getTemporaryFile($fileId, $userId);
				if ($file && $fileManager->getDocumentType($file->getFileType()) === DOCUMENT_TYPE_PDF) {
					$this->_article = $file;
					$this->setData('_article', $file->getOriginalFileName());
				} else {
					$this->addError('article', __("submission.articleRequired"));
					$this->addErrorField('article');
				}

			}

		}

		$isValid = parent::validate();
		if (!$isValid) return false;

		// Try to upload the paper from arXiv
		if ($arXivPDF === null) return true;
	        if (!$temporaryFileManager->fileExists($temporaryFileManager->filesDir, 'dir')) {
			// Try to create destination directory
			$temporaryFileManager->mkdirtree($temporaryFileManager->filesDir);
		}
		$basePath = $temporaryFileManager->getBasePath();
		$newFileName = tempnam($basePath, 'pdf');
                $baseName = basename($newFileName);
		// file_get_contents and wget don't work (arXiv answers 'Forbidden'); so we use links
		exec("/usr/bin/links -source " . $arXivPDF . " > " . $newFileName . " 2> /dev/null", $output, $retval);
		if ($retval > 0) {
			$this->addError('arxiv', "submission.arxiv.uploadError");
			$this->addErrorField('arxiv');
		} else {
			$temporaryFileDao = DAORegistry::getDAO('TemporaryFileDAO');
			$this->_article = $temporaryFileDao->newDataObject();
			$this->_article->setUserId($userId);
			$this->_article->setServerFileName(basename($newFileName));
			$this->_article->setFileType('pdf');
			$this->_article->setFileSize(filesize($newFileName));
			$this->_article->setOriginalFileName($temporaryFileManager->truncateFileName(basename($arXivPDF)));
			$this->_article->setDateUploaded(Core::getCurrentDate());
			$temporaryFileDao->insertObject($this->_article);
		}

		return $this->isValid();

	}


	function execute($args, $request) {
		$returner = array();

		$contextId = $this->context->getId();
		$router = $request->getRouter();

		// DAOs
		$userGroupDao = DAORegistry::getDAO('UserGroupDAO');
		$userDao = DAORegistry::getDAO('UserDAO');
		$sectionDao = DAORegistry::getDAO('SectionDAO');
		$submissionDao = Application::getSubmissionDAO();
		$authorDao = DAORegistry::getDAO('AuthorDAO');
		$stageAssignmentDao = DAORegistry::getDAO('StageAssignmentDAO');
		$subEditorsDao = DAORegistry::getDAO('SubEditorsDAO');

		if (Validation::IsLoggedIn()) {

			$returner['newAccount'] = false;
			$user = $request->getUser();
			$userId = $user->getId();

		} else {

			// We create a new user
			$user = new User();
			$firstName = $this->getData('firstName');
			$user->setFirstName($firstName);
			$lastName = $this->getData('lastName');
			$user->setLastName($lastName);
			$username = Validation::suggestUsername($firstName, $lastName);
			$user->setUsername($username);
			$user->setEmail($this->getData('email'));
			$password = Validation::generatePassword(15);
			$encryptedPassword = Validation::encryptCredentials($username, $password);
			$user->setPassword($encryptedPassword);
			$user->setLocales(array(AppLocale::getLocale()));
			$userId = $userDao->insertObject($user);

			// We log in the newly created user
			$user = Validation::registerUserSession($user, $reason);

			$returner['newAccount'] = true;
			$returner['username'] = $username;
			$returner['password'] = $password;
			$returner['hash'] = Validation::generatePasswordResetHash($userId);
                        $returner['length'] = $request->getSite()->getMinPasswordLength();

		}

		// We upgrade the user as author (if necessary)
		$userGroupId = 0;
		$userGroups = $userGroupDao->getByRoleId($contextId, ROLE_ID_AUTHOR);
		while ($userGroup = $userGroups->next()) {
			$groupId = $userGroup->getId();
			if ($userGroup->getPermitSelfRegistration()) {
				$userGroupId = $groupId;
				break;
			}
		}
		if ($userGroupId == 0) fatal_error("No group with author role");
		$userGroupDao->assignUserToGroup($userId, $userGroupId, $contextId);


		/* We add the submission
		 * copied from lib.pkp.classes.submission.form.PKPSubmissionSubmitStep[1234]Form
		 */

		// Create new submission
		$submission = $submissionDao->newDataObject();
		$submission->setContextId($contextId);
		$submission->setDateSubmitted(Core::getCurrentDate());

		$locales = $user->getLocales();
		if (empty($locales)) {
			$locale = AppLocale::getLocale();
		} else {
			$locale = $locales[0];
		}
		$submission->setLocale($locale);
		$submission->setLanguage(PKPString::substr($locale, 0, 2));
		$submission->stampStatusModified();
		$submission->setSubmissionProgress(0);
		$submission->setStageId(WORKFLOW_STAGE_ID_SUBMISSION);
		$submission->setTitle($this->_title, $locale);
		if ($this->_abstract !== null) $submission->setAbstract($this->_abstract, $locale);
		$submission->setCopyrightNotice($this->context->getLocalizedSetting('copyrightNotice'), $this->getData('locale'));

		// If there is no section with the specified sectionId (case sectionId = 0 typically),
		// we pick the first available section
		$sectionId = $this->getData('sectionId');
		$section = $sectionDao->getById($sectionId, $contextId);
		if ($section === null) {
			$sections = $sectionDao->getByContextId($contextId);
			$section = $sections->next();
			if ($section === null) fatalError("No available section");
			$sectionId = $section->getId();
		}
		$submission->setSectionId($sectionId);

		// Insert the submission
		$submissionId = $submissionDao->insertObject($submission);
		$returner['submissionId'] = $submissionId;

		// Prepare mail for authors
		$mailForAuthors = new SubmissionMailTemplate($submission, 'SUBMISSION_ACK');
		$settings = $request->getContext()->getSettings();
		$mailForAuthors->setFrom($settings['contactEmail'], $settings['contactName']);
		$mailForAuthors->assignParams(array(
			'contextName' => $this->context->getName()[$locale],
			'submissionTitle' => $this->_title,
			'submissionUrl' => $router->url($request, null, 'authorDashboard', 'submission', $submissionId),
			'editorialContactSignature' => $settings['contactName'],
		));

		// Set user to initial author
		$author = $authorDao->newDataObject();
		$author->setFirstName($user->getFirstName());
		$author->setMiddleName($user->getMiddleName());
		$author->setLastName($user->getLastName());
		$author->setAffiliation($user->getAffiliation(null), null);
		$author->setCountry($user->getCountry());
		$author->setEmail($user->getEmail());
		$author->setUrl($user->getUrl());
		$author->setBiography($user->getBiography(null), null);
		$author->setPrimaryContact(1);
		$author->setIncludeInBrowse(1);
		$author->setUserGroupId($userGroupId);
		$author->setSubmissionId($submissionId);
		$authorDao->insertObject($author);
		$mailForAuthors->addRecipient($user->getEmail(), $user->getFullName());
		// Attach the initial author to the submission
		$stageAssignmentDao->build($submissionId, $userGroupId, $userId);

		// Add other authors
                $email = $this->getData('email');
		foreach($this->_authors as $auth) {
			$author = $authorDao->newDataObject();
			$author->setFirstName($auth['firstName']);
			$author->setLastName($auth['lastName']);
			$author->setPrimaryContact(0);
			if (isset($auth['email'])) {
				$author->setEmail($auth['email']);
				$author->setIncludeInBrowse(1);
				$mailForAuthors->addCc($auth['email'], $auth['firstName'] . " " . $auth['lastName']);
				// If the coauthor is registered, we attach it to the submission
				$userAuthor = $userDao->getUserByEmail($auth['email'], FALSE);
				if ($userAuthor !== null) {
					$userAuthorId = $userAuthor->getId();
					$userGroupDao->assignUserToGroup($userAuthorId, $userGroupId, $contextId);
					$stageAssignmentDao->build($submissionId, $userGroupId, $userAuthorId);
				}
			} else {
				$author->setIncludeInBrowse(0);
			}
			$author->setSubmissionId($submissionId);
			$author->setUserGroupId($userGroupId);
			$authorDao->insertObject($author);
		}

		// Add article
		$submissionFileManager = new SubmissionFileManager($contextId, $submissionId);
		$submissionFileManager->temporaryFileToSubmissionFile(
			$this->_article,
			SUBMISSION_FILE_SUBMISSION,
			$userId,
			$userGroupId,
			null,
			GENRE_CATEGORY_DOCUMENT,
			null, null);


		// Add comments to editor
		if ($this->getData('comments')){
			$queryDao = DAORegistry::getDAO('QueryDAO');
			$query = $queryDao->newDataObject();
			$query->setAssocType(ASSOC_TYPE_SUBMISSION);
			$query->setAssocId($submissionId);
			$query->setStageId(WORKFLOW_STAGE_ID_SUBMISSION);
			$query->setSequence(REALLY_BIG_NUMBER);
			$queryDao->insertObject($query);
			$queryDao->resequence(ASSOC_TYPE_SUBMISSION, $submissionId);
			$queryDao->insertParticipant($query->getId(), $userId);
			$queryId = $query->getId();

			$noteDao = DAORegistry::getDAO('NoteDAO');
			$note = $noteDao->newDataObject();
			$note->setUserId($userId);
			$note->setAssocType(ASSOC_TYPE_QUERY);
			$note->setTitle(__('submission.submit.coverNote'));
			$note->setContents($this->getData('comments'));
			$note->setDateCreated(Core::getCurrentDate());
			$note->setDateModified(Core::getCurrentDate());
			$note->setAssocId($queryId);
			$noteDao->insertObject($note);
		}


		/* We send notifications to editors and associate them to the submission
		 */

		$sectionId = $this->getData('sectionId');
		if ($sectionId) {
			$sectionsId = array($sectionId);
		} else {
			$sectionsId = array();
			$sectionsByEditor = $sectionDao->getEditorSections($contextId);
		}
		$proposedEditorId = $this->getData('editorId');

		// Prepare mail for editors
		$mailForEditors = new SubmissionMailTemplate($submission, 'NEW_SUBMISSION');
		$mailForEditors->setFrom($settings['supportEmail'], $settings['supportName']);
		$mailForEditors->assignParams(array(
			'contextName' => $this->context->getName()[$locale],
			'submissionTitle' => $this->_title,
			'submissionUrl' => $router->url($request, null, 'workflow', 'access', $submissionId),
			'comments' => $this->getData('comments'),
		));
		// Find editor(s) in chief
		$userGroups = $userGroupDao->getByRoleId($contextId, ROLE_ID_MANAGER);
		while ($userGroup = $userGroups->next()) {
			if ($userGroup->getAbbrev()['en_US'] !== "ChE") continue;  // TODO: find something better
			$groupId = $userGroup->getId();
			$users = $userGroupDao->getUsersById($groupId, $contextId);
			while ($userManager = $users->next()) {
				$mailForEditors->addRecipient($userManager->getEmail(), $userManager->getFullName());
				$stageAssignmentDao->build($submissionId, $groupId, $userManager->getId());
			}
		}
		// Assign editor proposed by the submitter
		$position = strrpos($proposedEditorId, "-");
		if ($position == -1) {
			$proposedEditor = null;
		} else {
			$proposedEditorId = substr($proposedEditorId, $position + 1);
			$proposedEditor = $userDao->getById($proposedEditorId);
			if ($proposedEditor !== null) {
				$mailForEditors->addRecipient($proposedEditor->getEmail(), $proposedEditor->getFullName());
				$groupId = $this->_retrieveUserGroupId($proposedEditorId, $contextId);
				if ($groupId) {
					$stageAssignmentDao->build($submissionId, $groupId, $proposedEditorId);
				}
				// We set the section if it has not been defined yet
				if (!$sectionId) {
					foreach ($sectionsByEditor[$proposedEditorId] as $section) {
						$sectionsId[] = $section->getId();
					}
				}
			}
		}
		// Assign sections editor
		foreach($sectionsId as $sectionId) {
			$sectionEditors = $subEditorsDao->getChiefsBySectionId($sectionId, $contextId);
			foreach($sectionEditors as $sectionEditorId => $sectionEditor) {
				$mailForEditors->addRecipient($sectionEditor->getEmail(), $sectionEditor->getFullName());
				$groupId = $this->_retrieveUserGroupId($sectionEditorId, $contextId);
				if ($groupId) {
					$stageAssignmentDao->build($submissionId, $groupId, $sectionEditorId);
				}
			}
		}

		// Everything went well: we send emails
		$mailForAuthors->send();
		$mailForEditors->send();
                // and log submission
		import('classes.log.SubmissionEventLogEntry'); // Constants
		import('lib.pkp.classes.log.SubmissionLog');
		SubmissionLog::logEvent($request, $submission, SUBMISSION_LOG_SUBMISSION_SUBMIT, "submission.event.submissionSubmitted");

		return $returner;
	}

	function _canonicalize($text) {
		$ans = htmlentities($text, ENT_NOQUOTES, 'utf-8');
		$ans = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $ans);
		$ans = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $ans);
		$ans = preg_replace('#&(\w+);#', '', $ans);
		$ans = preg_replace('#[-_]#', ' ', $ans);
		return strtolower($ans);
	}

	function _retrieveUserGroupId($editorId, $contextId) {
		$userGroupDao = DAORegistry::getDAO('UserGroupDAO');
		$userGroups = $userGroupDao->getByRoleId($contextId, ROLE_ID_SECTION_EDITOR);
		while ($userGroup = $userGroups->next()) {
			$groupId = $userGroup->getId();
			$users = $userGroupDao->getUsersById($groupId, $contextId);
			while ($user = $users->next()) {
				if ($user->getId() == $editorId) return $groupId;
			}
		}
		return 0;
	}

}

?>
