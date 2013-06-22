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
	my_define_d('IS_UNIX', (($os !== 'MAC') && ($os !== 'WIN')) ? true : false);
}

/**
 * @deprecated 13.3	Use IS_UNIX instead
 */
if (!my_defined('IS_MAC'))
{
	my_define_d('IS_MAC', (IS_UNIX === true && ($os === 'DAR' || $os === 'MAC')) ? true : false);
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

// Register the legacy library base path for deprecated or legacy libraries.
JLoader::registerPrefix('Hwj\J', JPATH_PLATFORM . '/legacy');

// Import the Joomla Factory.
JLoader::import('joomla.factory');

// Register classes that don't follow one file per class naming conventions.
JLoader::register('Hwj\JText', JPATH_PLATFORM . '/joomla/language/text.php');
JLoader::register('Hwj\JRoute', JPATH_PLATFORM . '/joomla/application/route.php');

// Register the folder for the moved JHtml classes
JHtml::addIncludePath(JPATH_PLATFORM . '/legacy/html');

// Register classes for compatability with PHP 5.3
if (version_compare(PHP_VERSION, '5.4.0', '<'))
{
        JLoader::import('compat.jsonserializable'); // hw2 workaround for register function that doesn't support not namespaced classes
	//JLoader::register('\JsonSerializable', __DIR__ . '/compat/jsonserializable.php');
}

// Add deprecated constants
// @deprecated 12.3
my_define_d('JPATH_ISWIN', IS_WIN);
my_define_d('JPATH_ISMAC', IS_MAC);

// Register classes where the names have been changed to fit the autoloader rules
// @deprecated  12.3
JLoader::register('Hwj\JSimpleCrypt', JPATH_PLATFORM . '/legacy/simplecrypt/simplecrypt.php');
JLoader::register('Hwj\JTree', JPATH_PLATFORM . '/legacy/base/tree.php');
JLoader::register('Hwj\JNode', JPATH_PLATFORM . '/legacy/base/node.php');
JLoader::register('Hwj\JObserver', JPATH_PLATFORM . '/legacy/base/observer.php');
JLoader::register('Hwj\JObservable', JPATH_PLATFORM . '/legacy/base/observable.php');
JLoader::register('Hwj\Log\Exception', JPATH_PLATFORM . '/legacy/log/logexception.php');
JLoader::register('Hwj\JXMLElement', JPATH_PLATFORM . '/legacy/utilities/xmlelement.php');
JLoader::register('Hwj\JRule', JPATH_PLATFORM . '/legacy/access/rule.php');
JLoader::register('Hwj\JRules', JPATH_PLATFORM . '/legacy/access/rules.php');
JLoader::register('Hwj\JCli', JPATH_PLATFORM . '/legacy/application/cli.php');
JLoader::register('Hwj\JDaemon', JPATH_PLATFORM . '/legacy/application/daemon.php');
JLoader::register('Hwj\JApplication', JPATH_LIBRARIES . '/legacy/application/application.php');