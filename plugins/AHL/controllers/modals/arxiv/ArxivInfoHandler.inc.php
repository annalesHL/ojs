<?php

import('plugins.AHL.classes.Arxiv');
import('classes.handler.Handler');
import('lib/pkp/classes/core/JSONMessage');

class ArxivInfoHandler extends Handler {
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

	function getTitle($args, $request) {
		$this->setupTemplate($request);
		$arxivId = $request->getUserVar('arxivId');
		$arxiv = new Arxiv($arxivId);
		if ($arxiv->isValid()) {
			return new JSONMessage(true, htmlentities($arxiv->getTitle()));
		} else {
			$errorMessage = '<span class="error">' . __("submission.arxiv.invalid") . '</span>';
			return new JSONMessage(true, $errorMessage);
		}
        }
}

?>
