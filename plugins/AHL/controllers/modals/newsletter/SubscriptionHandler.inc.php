<?php

import('classes.handler.Handler');
import('plugins.AHL.classes.Newsletter');

import('lib.pkp.classes.validation.ValidatorEmail');
import('lib.pkp.classes.mail.Mail');
import('lib/pkp/classes/core/JSONMessage');


class SubscriptionHandler extends Handler {
	/**
	 * Constructor.
	 */
	function __construct() {
		parent::__construct();
	}

	function authorize($request, &$args, $roleAssignments, $enforceRestrictedSite = true) {
		parent::authorize($request, $args, $roleAssignments, $enforceRestrictedSite);
		return true;
	}

	function subscribe($args, $request) {
		$this->setupTemplate($request);

		$email = $request->getUserVar('email');
		if (!$email && Validation::isLoggedIn()) {
			$email = $request->getUser()->getEmail();
		}
		$newsletter = new Newsletter();

		$error = "";
		$validator = new ValidatorEmail();
		if (!$validator->isValid($email)) {
			$error = "newsletter.invalidEmail";
		} else if ($newsletter->isSubscriber($email)) {
			$error = "newsletter.alreadySubscriber";
		}
		if ($error !== "") {
			$templateMgr = TemplateManager::getManager($request);
			$templateMgr->assign("numberOfSubscribers", $newsletter->numberOfSubscribers());
			$templateMgr->assign("error", $error);
			$templateMgr->assign("email", $email);
			return $templateMgr->fetchJSON('frontend/components/subscribeNewsletter.tpl');
		}

		// We send an email to SYMPA for requiring subscription
		$mail = new Mail();
		$mail->setFrom($email);
		$mail->addRecipient("news-request@annales.lebesgue.fr");
		$mail->setSubject("subscribe news");
		$mail->setBody(".");
		$mail->send();

		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign("email", $email);
		return $templateMgr->fetchJSON('frontend/components/subscriptionSuccess.tpl');
	}

	function fetchNews($args, $request) {
		return new JSONMessage(true, "Coming soon...");
		$templateMgr = TemplateManager::getManager($request);
		$this->setupTemplate($request);
		$no = $request->getUserVar('first');
		for ($i; $i < 10; $i++, $no++) {
			$message  = "Nous sommes heureux de vous annoncer le lancement des Annales Journal Lebesgue (AHL).<br>";
			$message .= "Les Annales Henri Lebesgue sont un journal de mathématiques généraliste aux <b>pratiques vraiment très vertueuses</b>... et avec <b>un site web vraiment super génial</b> !<br>";
			$message .= "Ce journal est entièrement en ligne et gratuit aussi bien pour l'auteur que pour le lecteur.<br>";
			$message .= "À bientôt les amis pour de nouvelles aventures...";
			$templateMgr->assign(array(
				'date' => '2017-10-12',
				'title' => 'Lancement des Annales Henri Lebesgue',
				'message' => $message,
			));
			$output .= $templateMgr->fetch('frontend/components/newsItem.tpl');
		}
		return new JSONMessage(true, $output);
	}
}

?>
