<?php
namespace ZfRest\Application;
/**
 * @license   MIT license
 * @copyright Copyright (c) 2012. David Lundgren, All Rights Reserved.
 * @version   1.0
 * @package   ZfRest
 */

/**
 * ZfRest Application Bootstrap
 *
 * Handles the bootstrap of the ZfRest module
 *
 * @author  David Lundgren <dlundgren@syberisle.net>
 * @package ZfRest
 */
abstract class Bootstrap
	extends \Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Initializes the basics needed for a REST application
	 */
	public function _initRestDefaults()
	{
		$front = \Zend_Controller_Front::getInstance();
		if ( ! $front->hasPlugin('Zend_Controller_Plugin_PutHandler')) {
			$front->registerPlugin(new \Zend_Controller_Plugin_PutHandler());
		}
	}
}
