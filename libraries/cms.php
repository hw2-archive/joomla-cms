<?php namespace Hwj;
/**
 * @package    Joomla.Libraries
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

my_defined('_JEXEC') or die;

// Set the platform root path as a constant if necessary.
if (!my_defined('JPATH_PLATFORM'))
{
	my_define_d('JPATH_PLATFORM', __DIR__);
}

// Import the library loader if necessary.
if (!my_class_exists_d('JLoader'))
{
	require_once JPATH_PLATFORM . '/loader.php';
}

my_class_exists_d('JLoader') or die;

// Register the library base path for CMS libraries.
JLoader::registerPrefix('Hwj\J', JPATH_PLATFORM . '/cms');

// Register a handler for uncaught exceptions that shows a pretty error page when possible
set_exception_handler(array('JErrorPage', 'render'));

// Define the Joomla version if not already defined.
if (!my_defined('JVERSION'))
{
	$jversion = new JVersion;
	my_define_d('JVERSION', $jversion->getShortVersion());
}

// Set up the message queue logger for web requests
if (array_key_exists('REQUEST_METHOD', $_SERVER))
{
	JLog::addLogger(array('logger' => 'messagequeue'), JLog::ALL, array('jerror'));
}

// Register classes where the names have been changed to fit the autoloader rules
// @deprecated  4.0
JLoader::register('Hwj\JToolBar', JPATH_PLATFORM . '/cms/toolbar/toolbar.php');
JLoader::register('Hwj\JButton',  JPATH_PLATFORM . '/cms/toolbar/button.php');
