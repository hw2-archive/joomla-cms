<?php namespace Hwj;
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

my_defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Platform.
 * Provides a list of content languages
 *
 * @package     Joomla.Libraries
 * @subpackage  Form
 * @see         JFormFieldLanguage for a select list of application languages.
 * @since       1.6
 */
class JFormFieldContentlanguage extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'ContentLanguage';

	/**
	 * Method to get the field options for content languages.
	 *
	 * @return  array  The options the field is going to show.
	 *
	 * @since   1.6
	 */
	protected function getOptions()
	{
		return array_merge(parent::getOptions(), JHtml::_('contentlanguage.existing'));
	}
}
