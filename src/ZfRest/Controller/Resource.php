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
	 * Replaces Zend_Controller_Action::view with a stdClass object
	 * @var stdClass
	 */
	public $response;

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
	 * Constructor
	 *
	 * @see Zend_Controller_Action::__construct()
	 */
	public function __construct(\Zend_Controller_Request_Abstract $request,
	                            \Zend_Controller_Response_Abstract $response,
	                            array $invokeArgs = array())
	{
		parent::__construct($request, $response, $invokeArgs);
		$this->response = new \stdClass();

		if ($this->_helper->hasHelper('layout')) {
			$this->_helper->layout->disableLayout();
		}

		if ($this->_helper->hasHelper('viewRenderer')) {
			$this->_helper->viewRenderer->setNoRender();
		}
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
		     ->setHeader('Allow', strtoupper(implode(', ', $this->_allowedActions)))
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
		$this->_sendOptionsResponse();
		$this->getResponse()->setHttpResponseCode(405);
	}
}
