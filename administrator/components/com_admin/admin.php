<?php namespace Hwj;
/**
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

my_defined('_JEXEC') or die;

// No access check.

$controller	= JControllerLegacy::getInstance('Admin');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();