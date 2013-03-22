<?php namespace Hwj;
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

my_defined('_JEXEC') or die;

?>
<div class="contact-category<?php echo $this->pageclass_sfx;?>">
<?php if ($this->params->get('show_page_heading')) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php namespace Hwj; namespace Hwj; endif; ?>
<?php namespace Hwj; namespace Hwj; namespace Hwj; if($this->params->get('show_category_title', 1)) : ?>
<h2>
	<?php echo JHtml::_('content.prepare', $this->category->title, '', 'com_contact.category'); ?>
</h2>
<?php namespace Hwj; namespace Hwj; namespace Hwj; endif; ?>
<?php namespace Hwj; namespace Hwj; namespace Hwj; if ($this->params->def('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
	<div class="category-desc">
	<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
		<img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
	<?php endif; ?>
	<?php if ($this->params->get('show_description') && $this->category->description) : ?>
		<?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_contact.category'); ?>
	<?php endif; ?>
	<div class="clr"></div>
	</div>
<?php namespace Hwj; namespace Hwj; namespace Hwj; endif; ?>

<?php namespace Hwj; namespace Hwj; namespace Hwj; echo $this->loadTemplate('items'); ?>

<?php namespace Hwj; namespace Hwj; namespace Hwj; if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
<div class="cat-children">
	<h3><?php echo JText::_('JGLOBAL_SUBCATEGORIES'); ?></h3>
	<?php echo $this->loadTemplate('children'); ?>
</div>
<?php namespace Hwj; namespace Hwj; namespace Hwj; endif; ?>
</div>
