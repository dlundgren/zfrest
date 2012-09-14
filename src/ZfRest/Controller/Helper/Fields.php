<?php
namespace ZfRest\Controller\Helper;
/**
 * @license   MIT license
 * @copyright Copyright (c) 2012. David Lundgren, All Rights Reserved.
 * @version   1.0
 * @package   ZfRest
 */

/**
 * Fields helper
 *
 * Obtains a list of fields from the request using the _fields parameter
 *
 * @author     David Lundgren
 * @package    ZfRest
 * @subpackage controller helpers
 */
class Fields
	extends HelperAbstract
{

	/**
	 * The key used in the query parameters to set the sort
	 * @var string
	 */
	protected $_key = '_fields';

	/**
	 * Returns a list of the fields and their sort order.
	 *
	 * @param  string $key the key in the query parameter to use as the sort key
	 * @return array
	 */
	public function getFields($key = NULL)
	{
		$var = $this->_getParamByKey($key ?: $this->_key);
		if (empty($var)) {
			return NULL;
		}

		return explode(',', $var);
	}

	/**
	 * Sets the fields key
	 *
	 * @param  string $key
	 * @return ZfRest\Controller\Helper\RestFields Provides a fluent interface
	 */
	public function setFieldsKey($key)
	{
		$this->_key = $key;
		return $this;
	}

	/**
	 * Proxy to {@see getFields()}
	 *
	 * @return array
	 */
	public function direct($key = NULL)
	{
		return $this->getFields($key);
	}
}
