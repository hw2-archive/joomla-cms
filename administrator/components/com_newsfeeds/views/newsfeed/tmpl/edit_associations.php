<?php namespace Hwj;
/**
 * @package     Joomla.Administrator
 * @subpackage  com_newsfeeds
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

my_defined('_JEXEC') or die;

$fields = $this->form->getFieldset('item_associations');
?>
<div class="tab-pane" id="associations">
	<fieldset>
		<?php foreach ($fields as $field) : ?>
		<div class="control-group">
			<div class="control-label">
				<?php echo $field->label ?>
			</div>
			<div class="controls">
				<?php echo $field->input; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</fieldset>
</div>