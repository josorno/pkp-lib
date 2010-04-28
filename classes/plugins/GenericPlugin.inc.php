<?php

/**
 * @file classes/plugins/GenericPlugin.inc.php
 *
 * Copyright (c) 2003-2010 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class GenericPlugin
 * @ingroup plugins
 *
 * @brief Abstract class for generic plugins
 */

// $Id$


import('plugins.LazyLoadPlugin');

class GenericPlugin extends LazyLoadPlugin {
	/**
	 * Constructor
	 */
	function GenericPlugin() {
		parent::LazyLoadPlugin();
	}

	/*
	 * Override protected methods from PKPPlugin
	 */

	/**
	 * Generic plug-ins implement the enabled/disabled logic
	 * by default. This is necessary so that we can make sure
	 * that disabled plug-ins will not have to be instantiated.
	 *
	 * Call this method with a list of management verbs (if any)
	 * generated by the custom plug-in.
	 *
	 * @see PKPPlugin::getManagementVerbs()
	 */
	function getManagementVerbs($verbs = array()) {
		assert(is_array($verbs));

		// Site plug-ins can only be administered by site admins
		if ($this->isSitePlugin() && !Validation::isSiteAdmin()) return array();

		if ($this->getEnabled()) {
			$verbs[] = array('disable', Locale::translate('common.disable'));
		} else {
			$verbs[] = array('enable', Locale::translate('common.enable'));
		}
		return $verbs;
	}

	/**
	 * @see PKPPlugin::manage()
	 */
	function manage($verb, $args, &$message) {
		if ($verb != 'enable' && !$this->getEnabled()) fatalError('Invalid management action on disabled plug-in!');

		switch ($verb) {
			case 'enable':
				$this->setEnabled(true);
				$message = Locale::translate('common.pluginEnabled', array('pluginName' => $this->getDisplayName()));
				return false;

			case 'disable':
				$this->setEnabled(false);
				$message = Locale::translate('common.pluginDisabled', array('pluginName' => $this->getDisplayName()));
				return false;
		}

		return true;
	}
}
?>
