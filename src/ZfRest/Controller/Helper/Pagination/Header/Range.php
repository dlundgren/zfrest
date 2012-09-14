<?php
namespace ZfRest\Controller\Helper\Pagination\Header;
/**
 * @license   MIT license
 * @copyright Copyright (c) 2012. David Lundgren, All Rights Reserved.
 * @version   1.0
 * @package   ZfRest
 */

/**
 * Range Header Pagination
 *
 * Supports the HTTP Range header as a pagination variable. The set unit determines how it responds. On preDispatch it
 * parses the Range header and puts it in the Request. On postDispatch it will set Content-Range headers
 *
 * @author     David Lundgren
 * @package    ZfRest
 * @subpackage controller helpers
 */
class Range
	extends \ZfRest\Controller\Helper\Pagination\Query
	implements \ZfRest\Controller\Helper\Pagination\PaginationInterface
{
	/**
	 * Determines the "Query" pagination parameters from the Range header
	 *
	 * @return void
	 */
	public function preDispatch()
	{
		$request = $this->getRequest();
		if ( ! $request->isGet()) {
			return;
		}

		$range = $this->getRequest()->getHeader('Range');
		list($type, $params) = explode("=", trim($range));

		list($unit, $limit) = explode('/', trim($params));
		if (false !== strpos($params, '-')) {
			$unit = explode('-', $params);
		}
		$request->setParam($this->_unitKey, $unit);
		$request->setParam($this->_sizeKey, $size);
	}

	/**
	 * Sets the Content-Range header based on the parameters passed to the helper
	 *
	 * @return void
	 */
	public function postDispatch()
	{
		$request = $this->getRequest();
		if ( ! $request->isGet()) {
			return;
		}

		$controller = $this->getActionController();
		if ( ! isset($controller->response) && ! isset($control)) {
			return;
		}

		$range = "{$start}";
		if ( ! empty($stop)) {
			$range = "-{$stop}";
		}
		$this->getResponse()->setHeader('Content-Range', sprintf('%s %s/%s', $this->_unit, $range, $total));
	}

	/**
	 * Sets the pagination settings for postDispatch
	 *
	 * @param  integer $start
	 * @param  integer $stop
	 * @param  integer $total
	 * @return ZfRest\Controller\Helper\RestPagination\Header\Range Provides a fluent interface
	 */
	public static function setPagination($start, $stop, $total)
	{
		$this->_start = $start;
		$this->_stop  = $stop;
		$this->_total = $total;
		return $this;
	}
}
