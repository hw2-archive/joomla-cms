<?php namespace Hwj;
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_quickicon
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

my_defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$buttons = modQuickIconHelper::getButtons($params);

require JModuleHelper::getLayoutPath('mod_quickicon', $params->get('layout', 'default'));
