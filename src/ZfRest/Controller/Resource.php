<?php
namespace ZfRest\Controller;
/**
 * @license   MIT license
 * @copyright Copyright (c) 2012. David Lundgren, All Rights Reserved.
 * @version   1.0
 * @package   ZfRest
 */

/**
 * Represent the Controller as a resource
 *
 * @author  David Lundgren
 * @package ZfRest
 */
abstract class Resource
	extends \Zend_Controller_Action
{
	/**
	 * Accept: application/xml
	 * @var string
	 */
	const FORMAT_XML = 'xml';

	/**
	 * Accept: application/json
	 * @var string
	 */
	const FORMAT_JSON = 'json';

	/**
	 * List of the allowed actions
	 * @var array
	 */
	protected $_allowedActions = array(
		'GET',
		'PUT',
		'POST',
		'DELETE',
		'HEAD',
		'OPTIONS'
	);

	/**
	 * Initializes the defaults for the controllers.
	 *
	 * This disables the viewRenderer and layout if available. as well as setting the content type
	 */
	public function init()
	{
		try {
			$this->_helper->layout()->disableLayout();
		}
		catch (\Exception $e) {
			// nothing to see here, move along
		}
		$this->_helper->viewRenderer->setNoRender();

		// check if the format has been set in the parameters
		// XXX: the Accept header should overrides this
		$format = strtolower($this->getRequest()->getParam('format', 'json'));
		switch ($format) {
			case self::FORMAT_XML:
			case self::FORMAT_JSON;
				break;
			default:
				throw new \RuntimeException('Invalid output format specified: ' . $format);
		}

		$this->getResponse()
		     ->setHeader('Allow', strtoupper(implode(', ', $this->_allowedActions)));
	}

	/**
	 * Catch everything and send the not allowed response
	 *
	 * @param  string $method
	 * @param  array  $params
	 * @return void
	 */
	public function __call($method, $params)
	{
		$this->_sendNotAllowedResponse();
	}

	/**
	 * Proxies to the GET action
	 */
	public final function indexAction()
	{
		return $this->getAction();
	}

	/**
	 * Send the OPTIONS response
	 */
	protected function _sendOptionsResponse()
	{
		$this->getResponse()
		     ->clearHeader('Content-Type')
		     ->setHttpResponseCode(200);
	}

	/**
	 * Send the HEAD response
	 */
	protected function _sendHeadResponse()
	{
		$this->getResponse()
		     ->setHttpResponseCode(200);
	}

	/**
	 * Sends a not allowed response with the available allowed actions
	 */
	protected function _sendNotAllowedResponse()
	{
		$this->getResponse()
		     ->setHttpResponseCode(405);
	}
}
