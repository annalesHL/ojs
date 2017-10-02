<?php

/**
 * @defgroup plugins_themes_default_ahl Default theme plugin
 */

/**
 * @file plugins/themes/defaultAHL/index.php
 *
 * @ingroup plugins_themes_defaultAHL
 * @brief Wrapper for ahl theme plugin.
 *
 * SAN
 * 
 * ATTENTION pour faire fonctionner la page 'partners' le fichier
 * /home/san/Annales/ojs/pages/about/index.php doit être modifié
 *
 */

require_once('DefaultAHLChildThemePlugin.inc.php');

return new DefaultAHLChildThemePlugin();

?>
