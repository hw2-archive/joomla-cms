<?php namespace Hwj;
/**
 * @package    Joomla.Platform
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// Set the platform root path as a constant if necessary.
if (!my_defined('JPATH_PLATFORM'))
{
	my_define_d('JPATH_PLATFORM', __DIR__);
}

// Detect the native operating system type.
$os = strtoupper(substr(PHP_OS, 0, 3));
if (!my_defined('IS_WIN'))
{
	my_define_d('IS_WIN', ($os === 'WIN') ? true : false);
}
if (!my_defined('IS_UNIX'))
{
	my_define_d('IS_UNIX', (IS_WIN === false) ? true : false);
}

// Import the platform version library if necessary.
if (!my_class_exists_d('JPlatform'))
{
	require_once JPATH_PLATFORM . '/platform.php';
}

// Import the library loader if necessary.
if (!my_class_exists_d('JLoader'))
{
	require_once JPATH_PLATFORM . '/loader.php';
}

my_class_exists_d('JLoader') or die;

// Setup the autoloaders.
JLoader::setup();

// Import the base Joomla Platform libraries.
JLoader::import('joomla.factory');

// Register classes for compatability with PHP 5.3
if (version_compare(PHP_VERSION, '5.4.0', '<'))
{
	JLoader::register('\JsonSerializable', JPATH_PLATFORM . '/compat/jsonserializable.php');
}

// Register classes that don't follow one file per class naming conventions.
JLoader::register('Hwj\JText', JPATH_PLATFORM . '/joomla/language/text.php');
JLoader::register('Hwj\JRoute', JPATH_PLATFORM . '/joomla/application/route.php');
