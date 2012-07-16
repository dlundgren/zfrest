<?php
namespace ZfRest\Controller\Plugin;
/**
 * @license   MIT license
 * @copyright Copyright (c) 2012. David Lundgren, All Rights Reserved.
 * @version   1.0
 * @package   ZfRest
 */

/**
 * An OAuth provider for controllers.
 *
 * It is recommended that any ACL's be placed on the Resources and not on the controllers in a RESTful application.
 *
 * @author     David Lundgren
 * @package    ZfRest
 * @subpackage Controller Plugin
 */
abstract class Oauth
	extends \Zend_Controller_Plugin_Abstract
{
	/**
	 * The controller to call on an error
	 * @var string
	 */
	protected $_errorController = 'error';

	/**
	 * The action to call when SSL is required and not used
	 * @var string
	 */
	protected $_sslRequiredAction = 'sslrequired';

	/**
	 * The action to call when OAuth fails
	 * @var string
	 */
	protected $_notAuthorizedAction = 'notauthorized';

	/**
	 * The request token endpoint
	 * @var string
	 */
	protected $_requestTokenEndpoint = null;

	/**
	 * Sets the error controller for dealing with errors.
	 *
	 * @param string $controller
	 * @return ZfRest\Controller\Plugin\ControllerOauth
	 */
	public function setErrorController($controller)
	{
		$this->_errorController = $controller;
		return $this;
	}

	/**
	 * Sets the action when SSL is required and not used
	 *
	 * @param string $action
	 * @return ZfRest\Controller\Plugin\ControllerOauth
	 */
	public function setSslRequiredAction($action)
	{
		$this->_sslRequiredAction = $action;
		return $this;
	}

	/**
	 * Sets the action when OAuth validation fails
	 *
	 * @param string $action
	 * @return ZfRest\Controller\Plugin\ControllerOauth
	 */
	public function setNotAuthorizedAction($action)
	{
		$this->_notAuthorizedAction = $action;
		return $this;
	}

	/**
	 * Sets the request token endpoint.
	 *
	 * In OAuth the request token endpoint needs to be ignored as it is a public resource.
	 *
	 * @param string $endpoint
	 * @return ZfRest\Controller\Plugin\ControllerOauth
	 */
	public function setRequestTokenEndpoint($endpoint)
	{
		$this->_requestTokenEndpoint = $endpoint;
		return $this;
	}

	/**
	 * Handle OAuth before any dispatching is done
	 *
	 * @see    Zend_Controller_Plugin_Abstract::dispatchLoopStartup()
	 * @param  Zend_Controller_Request_Abstract $request
	 * @return void
	 */
	public function dispatchLoopStartup(\Zend_Controller_Request_Abstract $request)
	{
		if ($request->getModuleName() !== $this->_getModuleName()) {
			return;
		}

		if ($this->_environmentRequiresSsl() && ! $request->isSecure()) {
			$request->setControllerName($this->_errorController)
			        ->setActionName($this->_sslRequiredAction)
			        ->setDispatched(true);
			return;
		}

		if ( ! empty($this->_requestTokenEndpoint) && $this->_requestTokenEndpoint === $request->getPathInfo()) {
			return;
		}

		try {
			$this->_validateRequest($request);
		}
		catch (\Exception $e) {
			$request->setActionName($this->_notAuthorizedAction)
			        ->setParam('exception', $e)
			        ->setDispatched(true);
		}
	}

	/**
	 * Validate request with OAuth
	 *
	 * Success is considered as having no exceptions being thrown.
	 *
	 * @param  Zend_Controller_Request_Abstract $request
	 * @throws mixed Typically OauthException, but should be bubbled up
	 */
	abstract protected function _validateRequest(\Zend_Controller_Request_Abstract $request);


	/**
	 * Returns the module name that this instance of ControllerOauth controls
	 *
	 * @return string the module name
	 */
	abstract protected function _getModuleName();
}
