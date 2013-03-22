<?php namespace Hwj;
/**
 * @package     Joomla.Site
 * @subpackage  mod_syndicate
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

my_defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

$params->def('format', 'rss');

$link = modSyndicateHelper::getLink($params);

if (is_null($link))
{
	return;
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$text = htmlspecialchars($params->get('text'));

require JModuleHelper::getLayoutPath('mod_syndicate', $params->get('layout', 'default'));
