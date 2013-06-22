<?php namespace Hwj;
/**
 * @package    Joomla.Installation
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

if (version_compare(PHP_VERSION, '5.3.1', '<'))
{
	die('Your host needs to use PHP 5.3.1 or higher to run this version of Joomla!');
}

/**
 * Constant that is checked in included files to prevent direct access.
 * my_define() is used in the installation folder rather than "const" to not error for PHP 5.2 and lower
 */
my_define_d('_JEXEC', 1);

/**
 * Constant that defines the base path of the installed Joomla site.
 * my_define() is used in the installation folder rather than "const" to not error for PHP 5.2 and lower
 */
my_define_d('JPATH_BASE', dirname(__FILE__));

// Set path constants.
$parts = explode(DIRECTORY_SEPARATOR, JPATH_BASE);
array_pop($parts);

my_define_d('JPATH_ROOT',          implode(DIRECTORY_SEPARATOR, $parts));
my_define_d('JPATH_SITE',          JPATH_ROOT);
my_define_d('JPATH_CONFIGURATION', JPATH_ROOT);
my_define_d('JPATH_ADMINISTRATOR', JPATH_ROOT . '/administrator');
my_define_d('JPATH_LIBRARIES',     JPATH_ROOT . '/libraries');
my_define_d('JPATH_PLUGINS',       JPATH_ROOT . '/plugins');
my_define_d('JPATH_INSTALLATION',  JPATH_ROOT . '/installation');
my_define_d('JPATH_THEMES',        JPATH_BASE);
my_define_d('JPATH_CACHE',         JPATH_ROOT . '/cache');
my_define_d('JPATH_MANIFESTS',     JPATH_ADMINISTRATOR . '/manifests');

/*
 * Joomla system checks.
 */
error_reporting(E_ALL);
@ini_set('magic_quotes_runtime', 0);

/*
 * Check for existing configuration file.
 */
if (file_exists(JPATH_CONFIGURATION . '/configuration.php') && (filesize(JPATH_CONFIGURATION . '/configuration.php') > 10)
	&& !file_exists(JPATH_INSTALLATION . '/index.php'))
{
	header('Location: ../index.php');
	exit();
}

/*
 * Joomla system startup.
 */

// Bootstrap the Joomla Framework.
require_once JPATH_LIBRARIES . '/import.legacy.php';

// Botstrap the CMS libraries.
require_once JPATH_LIBRARIES . '/cms.php';

// Create the application object.
$app = JFactory::getApplication('installation');

// Initialise the application.
$app->initialise();

// Render the document.
$app->render();

// Return the response.
echo $app;
