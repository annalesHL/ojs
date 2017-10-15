<?php

/**
 * @file plugins/themes/default/DefaultAHLChildThemePlugin.inc.php
 *
 * @class DefaultAHLChildThemePlugin
 * @ingroup plugins_themes_default
 *
 * @brief Default theme
 */
import('lib.pkp.classes.plugins.ThemePlugin');

class DefaultAHLChildThemePlugin extends ThemePlugin {
	/**
	 * Initialize the theme's styles, scripts and hooks. This is only run for
	 * the currently active theme.
	 *
	 * @return null
	 */
	public function init() {
	        //$this->addStyle('my-custom-style', 'styles/backend.less', array( 'contexts' => 'backend' ));
		$this->setParent('defaultthemeplugin');

	        $this->addScript('defaultAHL-test', 'js/dynamics.js', array( 'contexts' => array('frontend', 'backend') ));
	        $this->addScript('defaultAHL', 'js/main.js', array( 'contexts' => array('frontend', 'backend') ));
	        $this->addScript('defaultAHL-modal', 'js/modal.js', array( 'contexts' => 'backend' ));
	        $this->addScript('defaultAHL-modal', 'js/balancetext.min.js', array( 'contexts' => 'backend' ));

		// XAVIER: add style for backend
		$this->addStyle('pkp-lib', 'styles/ahl.less', array( 'contexts' => 'pkp-lib' ));
	}

	/**
	 * Get the display name of this plugin
	 * @return string
	 */
	function getDisplayName() {
		return __('plugins.themes.defaultAHL.name');
	}

	/**
	 * Get the description of this plugin
	 * @return string
	 */
	function getDescription() {
		return __('plugins.themes.defaultAHL.description');
	}
}

?>
