<?php

/**
 * @file plugins/themes/default/AhlThemePlugin.inc.php
 *
 * @class AhlThemePlugin
 * @ingroup plugins_themes_default
 *
 * @brief Default theme
 */
import('lib.pkp.classes.plugins.ThemePlugin');

class AhlThemePlugin extends ThemePlugin {
	/**
	 * Initialize the theme's styles, scripts and hooks. This is only run for
	 * the currently active theme.
	 *
	 * @return null
	 */
	public function init() {
		$this->setParent('defaultthemeplugin');
		$this->modifyStyle('stylesheet', array('addLess' => array('styles/remove-borders.less')));
	}

	/**
	 * Get the display name of this plugin
	 * @return string
	 */
	function getDisplayName() {
		return __('plugins.themes.ahl.name');
	}

	/**
	 * Get the description of this plugin
	 * @return string
	 */
	function getDescription() {
		return __('plugins.themes.ahl.description');
	}
}

?>
