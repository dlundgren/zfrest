<?php
namespace ZfRest\Controller\Helper;
/**
 * @license   MIT license
 * @copyright Copyright (c) 2012. David Lundgren, All Rights Reserved.
 * @version   1.0
 * @package   ZfRest
 */

/**
 * Obtains the Search parameters for a given request.
 *
 * Ignores the set ignored parameters and any parameter prefixed with an underscore (_).
 *
 * @author     David Lundgren
 * @package    ZfRest
 * @subpackage controller helpers
 */
class SearchParams
	extends HelperAbstract
{
	/**
	 * List of parameters to ignore
	 * @var array
	 */
	protected $_ignoreParams = array(
		'module',
		'controller',
		'action'
	);

	/**
	 * Sets the parameters to be ignored.
	 *
	 * @param  array $params
	 * @return ZfRest\Controller\Helper\RestParams Provides a fluent interface
	 */
	public function setIgnoreParams($params)
	{
		$this->_ignoreParams = $params;
		return $this;
	}

	/**
	 * Adds a parameter to the list of ignored parameters
	 *
	 * @param  string $param
	 * @return ZfRest\Controller\Helper\RestParams Provides a fluent interface
	 */
	public function addIgnoreParam($param)
	{
		if ( ! in_array($param, $this->_ignoreParams)) {
			$this->_ignoreParams[] = $param;
		}
		return $this;
	}

	/**
	 * Returns a list of the fields and their values.
	 *
	 * @param  string $key the key in the query parameter to use as the sort key
	 * @return array
	 */
	public function getSearchParams()
	{
		$params = $this->getRequest()->getParams();
		foreach (array_keys($params) as $name) {
			if ('_' === $name[0] || in_array($name, $this->_ignoreParams)) {
				unset($params[$name]);
			}
		}

		return $params;
	}

	/**
	 *  Proxy to {@see getSearchParams()}
	 *
	 * @return array
	 */
	public function direct()
	{
		return $this->getSearchParams();
	}
}
