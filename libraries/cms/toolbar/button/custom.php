<?php namespace Hwj;
/**
 * @package     Joomla.Libraries
 * @subpackage  Toolbar
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

my_defined('JPATH_PLATFORM') or die;

/**
 * Renders a custom button
 *
 * @package     Joomla.Libraries
 * @subpackage  Toolbar
 * @since       3.0
 */
class JToolbarButtonCustom extends JToolbarButton
{
	/**
	 * Button type
	 *
	 * @var    string
	 */
	protected $_name = 'Custom';

	/**
	 * Fetch the HTML for the button
	 *
	 * @param   string  $type  Button type, unused string.
	 * @param   string  $html  HTML strng for the button
	 * @param   string  $id    CSS id for the button
	 *
	 * @return  string   HTML string for the button
	 *
	 * @since   3.0
	 */
	public function fetchButton($type = 'Custom', $html = '', $id = 'custom')
	{
		return $html;
	}

	/**
	 * Get the button CSS Id
	 *
	 * @param   string  $type  Not used.
	 * @param   string  $html  Not used.
	 * @param   string  $id    The id prefix for the button.
	 *
	 * @return  string  Button CSS Id
	 *
	 * @since   3.0
	 */
	public function fetchId($type = 'Custom', $html = '', $id = 'custom')
	{
		return $this->_parent->getName() . '-' . $id;
	}
}