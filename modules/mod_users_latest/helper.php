<?php namespace Hwj;
/**
 * @package     Joomla.Site
 * @subpackage  mod_users_latest
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

my_defined('_JEXEC') or die;

/**
 * Helper for mod_users_latest
 *
 * @package     Joomla.Site
 * @subpackage  mod_users_latest
 */
class modUsersLatestHelper
{
	// get users sorted by activation date
	public static function getUsers($params)
	{
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select($db->quoteName(array('a.id', 'a.name', 'a.username', 'a.registerDate')));
		$query->order($db->quoteName('a.registerDate') . ' DESC');
		$query->from('#__users AS a');
		$user = JFactory::getUser();
		if (!$user->authorise('core.admin') && $params->get('filter_groups', 0) == 1)
		{
			$groups = $user->getAuthorisedGroups();
			if (empty($groups))
			{
				return array();
			}
			$query->leftJoin('#__user_usergroup_map AS m ON m.user_id = a.id');
			$query->leftJoin('#__usergroups AS ug ON ug.id = m.group_id');
			$query->where('ug.id in (' . implode(',', $groups) . ')');
			$query->where('ug.id <> 1');
		}
		$db->setQuery($query, 0, $params->get('shownumber'));
		$result = $db->loadObjectList();
		return (array) $result;
	}
}
