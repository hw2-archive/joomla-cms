<?php namespace Hwj;
/**
 * @package     Joomla.Site
 * @subpackage  mod_weblinks
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

my_defined('_JEXEC') or die;

// Include the weblinks functions only once
require_once __DIR__ . '/helper.php';

$list = modWeblinksHelper::getList($params);

if (!count($list))
{
	return;
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_weblinks', $params->get('layout', 'default'));
