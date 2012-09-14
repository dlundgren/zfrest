<?php
namespace ZfRest\Controller\Helper\Pagination;
/**
 * @license   MIT license
 * @copyright Copyright (c) 2012. David Lundgren, All Rights Reserved.
 * @version   1.0
 * @package   ZfRest
 */

/**
 * Interface for Pagination helpers
 *
 * @author     David Lundgren
 * @package    ZfRest
 * @subpackage controller helpers
 */
interface PaginationInterface
{
	/**
	 * Specifies the Pagination unit as page/size
	 * @const string
	 */
	const UNIT_PAGES  = 'page';

	/**
	 * Specifies the Pagination unit as offset/limit
	 * @const string
	 */
	const UNIT_ITEMS = 'offset';

	/**
	 * Specifies that UNIT_PAGE is the default. This is the most compatible with Zend_Paginator
	 * @const string
	 */
	const DEFAULT_UNIT = self::UNIT_PAGES;

	/**
	 * Specifes the default page key
	 * @const string
	 */
	const PAGE_DEFAULT_UNIT_KEY = '_page';

	/**
	 * Specifies the default size key
	 * @const string
	 */
	const PAGE_DEFAULT_SIZE_KEY = '_perpage';

	/**
	 * Specifes the default page key
	 * @const string
	 */
	const ITEMS_DEFAULT_UNIT_KEY = '_offset';

	/**
	 * Specifes the default item unit limit key
	 * @const string
	 */
	const ITEMS_DEFAULT_SIZE_KEY = '_limit';

	/**
	 * Specifies that the {@see getPagination()} return an array
	 * @const boolean
	 */
	const RETURN_ARRAY = true;

	/**
	 * Specifies that the {@see getPagination()} return an object
	 * @const boolean
	 */
	const RETURN_OBJECT = false;

	/**
	 * Sets the unit of measurement for the Pagination
	 *
	 * @param string $unit
	 */
	public function setUnit($unit);

	/**
	 * Sets the size key for parameters
	 *
	 * @param string $unit
	 */
	public function setSizeKey($key);

	/**
	 * Sets the unit key for parameters
	 *
	 * @param string $unit
	 */
	public function setUnitKey($key);

	/**
	 * Returns the pagination according to the units.
	 *
	 * @param  boolean $returnArray
	 * @return object|array
	 */
	public function getPagination($returnArray = self::RETURN_ARRAY);
}
