<?php namespace Hwj;
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

my_defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

$langs	= modLoginHelper::getLanguageList();
$return	= modLoginHelper::getReturnURI();
require JModuleHelper::getLayoutPath('mod_login', $params->get('layout', 'default'));