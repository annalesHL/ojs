<?php

/**
 * @file classes/validation/ValidatorEmail.inc.php
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2000-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class ValidatorEmail
 * @ingroup validation
 * @see Validator
 *
 * @brief Validation check for email addresses.
 */

import('lib.pkp.classes.validation.Validator');

class ValidatorCaptcha extends Validator {
	/**
	 * Constructor.
	 */
	function __construct() {
		parent::__construct();
	}


	function isValid($value) {
		return $value === "15";
	}
}

?>
