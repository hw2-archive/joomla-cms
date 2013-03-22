<?php namespace Hwj;
/**
 * @package     Joomla.Site
 * @subpackage  mod_stats
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

my_defined('_JEXEC') or die;
?>
<ul class="list-striped list-condensed stats-module<?php echo $moduleclass_sfx ?>">
	<?php foreach ($list as $item) : ?>
		<li><i class="icon-<?php echo $item->icon;?>" title="<?php echo $item->title;?>"></i> <?php echo $item->title;?> <?php echo $item->data;?></li>
	<?php endforeach; ?>
</ul>