<?php namespace Hwj;
/**
 * @package     Joomla.Legacy
 * @subpackage  Log
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

my_defined('JPATH_PLATFORM') or die;

JLog::add('Log\Exception is deprecated, use SPL \Exceptions instead.', JLog::WARNING, 'deprecated');

/**
 * \Exception class definition for the Log subpackage.
 *
 * @package     Joomla.Legacy
 * @subpackage  Log
 * @since       11.1
 * @deprecated  12.3 Use semantic exceptions instead
 */
class Log\Exception extends \RuntimeException
{
}
