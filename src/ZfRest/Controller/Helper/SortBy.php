<?php
/**
 * Zend Framework 1 Rest Routing
 *
 * @license   MIT license
 * @copyright Copyright (c) 2012-2015. David Lundgren, All Rights Reserved.
 * @version   1.0
 * @package   ZfRest
 */
namespace ZfRest\Controller\Helper;

/**
 * Obtains a list to order by
 *
 * Uses _sortby as the default key in the request parameters. Uses the format _sortby=+id,-email. The + may be left off
 *
 * @author  David Lundgren
 * @package ZfRest
 */
class SortBy
	extends HelperAbstract
{
	/**
	 * Ascending field
	 * @const string
	 */
	const ASC = '+';

	/**
	 * Descending field
	 * @const string
	 */
	const DESC = '-';

	/**
	 * The key used in the query parameters to set the sort
	 * @var string
	 */
	protected $_key = '_sortby';

	/**
	 * Returns a list of the fields and their sort order.
	 *
	 * @param  string $key the key in the query parameter to use as the sort key
	 * @return array
	 */
	public function getSortBy($key = NULL)
	{
		$var = $this->_getParamByKey($key ?: $this->_key);
		if (empty($var)) {
			return NULL;
		}

		$sort   = explode(',', $var);
		$fields = array();
		foreach($sort as $field) {
			$direction = trim(substr($field, 0, 1));
			switch($direction) {
				case self::ASC:
				case self::DESC:
					$field = trim(substr($field, 1));
					break;
				default:
					$field = trim($field);
					$direction = self::ASC;
			}
			$fields[$field] = $direction;
		}

		return $fields;
	}

	/**
	 * Sets the sort key
	 *
	 * @param  string $key
	 * @return ZfRest\Controller\Helper\Sort Provides a fluent interface
	 */
	public function setSortByKey($key)
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
		return $this->getSortBy($key);
	}
}
