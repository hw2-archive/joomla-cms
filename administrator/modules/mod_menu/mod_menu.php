<?php namespace Hwj;
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

my_defined('_JEXEC') or die;

// Include the module helper classes.
if (!my_class_exists_d('ModMenuHelper'))
{
	require __DIR__ . '/helper.php';
}

if (!my_class_exists_d('JAdminCssMenu'))
{
	require __DIR__ . '/menu.php';
}

$lang    = JFactory::getLanguage();
$user    = JFactory::getUser();
$input   = JFactory::getApplication()->input;
$menu    = new JAdminCSSMenu;
$enabled = $input->getBool('hidemainmenu') ? false : true;

// Render the module layout
require JModuleHelper::getLayoutPath('mod_menu', $params->get('layout', 'default'));
