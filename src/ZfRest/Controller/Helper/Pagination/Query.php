<?php
namespace ZfRest\Controller\Helper\Pagination;
/**
 * @license   MIT license
 * @copyright Copyright (c) 2012-2015. David Lundgren, All Rights Reserved.
 * @version   1.0
 * @package   ZfRest
 */

/**
 * Obtains the pagination parameters for a given request.
 *
 * Uses the give unit and size keys to get and set the pagination
 *
 * @author     David Lundgren
 * @package    ZfRest
 * @subpackage controller helpers
 */
class Query
	extends PaginationAbstract
	implements PaginationInterface
{
	/**
	 * Returns the pagination according to the units.
	 *
	 * @see    ZfRest\Controller\Helper\RestPaginationInterface::getPagination
	 * @param  boolean $returnArray
	 * @return object|array
	 */
	public function getPagination($returnArray = self::RETURN_ARRAY)
	{
		$request                 = $this->getRequest();
		$paging                  = array(
			$this->_unitKey => $this->_getParamByKey($this->_unitKey) ?: 0,
			$this->_sizeKey => $this->_getParamByKey($this->_sizeKey) ?: -1,
		);

		switch ($returnArray) {
			case self::RETURN_ARRAY:
				return $paging;
			case self::RETURN_OBJECT:
				return (object) $paging;
		}
	}
}
