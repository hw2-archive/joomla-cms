<?php namespace Hwj;
/**
 * @package     Joomla.Administrator
 * @subpackage  com_newsfeeds
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

my_defined('JPATH_BASE') or die;

/**
 * Supports a modal newsfeeds picker.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_newsfeeds
 * @since       1.6
 */
class JFormFieldModal_Newsfeeds extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'Modal_Newsfeeds';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 * @since   1.6
	 */
	protected function getInput()
	{
		// Load the javascript
		JHtml::_('behavior.framework');
		JHtml::_('behavior.modal', 'input.modal');

		// Build the script.
		$script = array();
		$script[] = '	function jSelectChart_'.$this->id.'(id, name, object) {';
		$script[] = '		document.id("'.$this->id.'_id").value = id;';
		$script[] = '		document.id("'.$this->id.'_name").value = name;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Build the script.
		$script = array();
		$script[] = '	window.addEvent("domready", function() {';
		$script[] = '		var div = new Element("div").setStyle("display", "none").inject(document.id("menu-types"), "before");';
		$script[] = '		document.id("menu-types").inject(div, "bottom");';
		$script[] = '	});';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Get the title of the linked chart
		$db = JFactory::getDBO();
		$db->setQuery(
			'SELECT name' .
			' FROM #__newsfeeds' .
			' WHERE id = '.(int) $this->value
		);

		try
		{
			$title = $db->loadResult();
		}
		catch (\RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage);
		}

		if (empty($title))
		{
			$title = JText::_('COM_NEWSFEEDS_SELECT_A_FEED');
		}

		$link = 'index.php?option=com_newsfeeds&amp;view=newsfeeds&amp;layout=modal&amp;tmpl=component&amp;function=jSelectChart_'.$this->id;

		if (isset($this->element['language']))
		{
			$link .= '&amp;forcedLanguage='.$this->element['language'];
		}

		JHtml::_('behavior.modal', 'a.modal');
		$html = "\n".'<div class="input-append"><input type="text" class="input-medium" id="'.$this->id.'_name" value="'.htmlspecialchars($title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" />';
		$html .= '<a class="modal btn" title="'.JText::_('COM_NEWSFEEDS_CHANGE_FEED_BUTTON').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-feed" title="'.JText::_('COM_NEWSFEEDS_CHANGE_FEED_BUTTON').'"></i> '.JText::_('JSELECT').'</a></div>'."\n";
		// The active newsfeed id field.
		if (0 == (int) $this->value)
		{
			$value = '';
		}
		else
		{
			$value = (int) $this->value;
		}

		// class='required' for client side validation
		$class = '';
		if ($this->required)
		{
			$class = ' class="required modal-value"';
		}

		$html .= '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';

		return $html;
	}
}