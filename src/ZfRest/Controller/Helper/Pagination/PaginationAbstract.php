<?php
namespace ZfRest\Controller\Helper\Pagination;
/**
 * @license   MIT license
 * @copyright Copyright (c) 2012. David Lundgren, All Rights Reserved.
 * @version   1.0
 * @package   ZfRest
 */

use ZfRest\Controller\Helper\HelperAbstract;

/**
 * Obtains the Pagination parameters for a given request.
 *
 * Units are ITEMS (_offset/_limit)or PAGES (_page/_perpage). Defaults to UNIT_PAGES which is most compatible with
 * Zend_Paginator.
 *
 * @author     David Lundgren
 * @package    ZfRest
 * @subpackage controller helpers
 */
abstract class PaginationAbstract
	extends HelperAbstract
	implements PaginationInterface
{
	/**
	 * The unit type for this pagination instance
	 */
	protected $_unit = self::UNIT_PAGES;

	/**
	 * The key used in the query to represent the main type (page/offset)
	 * @var string
	 */
	protected $_unitKey = self::PAGE_DEFAULT_UNIT_KEY;

	/**
	 * The key used in the query to represent the limit of the main type (size/limit)
	 * @var string
	 */
	protected $_sizeKey = self::PAGE_DEFAULT_SIZE_KEY;

	/**
	 * Sets the main type key
	 *
	 * @param  string $key
	 * @return ZfRest\Controller\Helper\RestPaginationAbstract Provides a fluent interface
	 */
	public function setUnitKey($key)
	{
		$this->_unitKey = $key;
		return $this;
	}

	/**
	 * Sets the size key
	 *
	 * @param  string $key
	 * @return ZfRest\Controller\Helper\RestPaginationAbstract Provides a fluent interface
	 */
	public function setSizeKey($key)
	{
		$this->_sizeKey = $key;
		return $this;
	}

	/**
	 * Sets the unit type
	 *
	 * @param  string $unit
	 * @param  string $unitKey
	 * @param  string $sizeKey
	 * @return ZfRest\Controller\Helper\RestPaginationAbstract Provides a fluent interface
	 */
	public function setUnit($unit, $unitKey = null, $sizeKey = null)
	{
		$this->_unit = $unit;
		switch($unit) {
			case self::UNIT_ITEMS:
				$unitKey = $unitKey ?: self::ITEMS_DEFAULT_UNIT_KEY;
				$sizeKey = $sizeKey ?: self::ITEMS_DEFAULT_UNIT_KEY;
				break;
			case self::UNIT_PAGE:
			default:
				$unitKey = $unitKey ?: self::PAGE_DEFAULT_UNIT_KEY;
				$sizeKey = $sizeKey ?: self::PAGE_DEFAULT_SIZE_KEY;
				break;
		}

		$this->setUnitKey($unitKey)->setSizeKey($sizeKey);
		return $this;
	}

	/**
	 * Proxy to {@see getPagination()}
	 *
	 * @return array|object
	 */
	public function direct($returnArray = self::RETURN_ARRAY)
	{
		return $this->getPagination($returnArray);
	}
}
