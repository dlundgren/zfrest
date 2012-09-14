<?php
namespace ZfRest\Controller\Helper;
/**
 * @license   MIT license
 * @copyright Copyright (c) 2012. David Lundgren, All Rights Reserved.
 * @version   1.0
 * @package   ZfRest
 */

/**
 * Helper abstract
 *
 * @author     David Lundgren
 * @package    ZfRest
 * @subpackage controller helpers
 */
abstract class HelperAbstract
	extends \Zend_Controller_Action_Helper_Abstract
{
	/**
	 * Returns the parameter
	 *
	 * @param  string $paramKey
	 * @return null|mixed
	 */
	protected function _getParamByKey($paramKey)
	{
		$param   = NULL;
		$request = $this->getRequest();
		if (is_array($paramKey)) {
			if ($param = $request->getParam(current($paramKey), NULL)) {
				while (next($paramKey) && isset($param[current($paramKey)])) {
					$param = $param[current($paramKey)];
				}
			}
		}
		else {
			$param = $request->getParam($paramKey, NULL);
		}

		return $param;
	}
}
