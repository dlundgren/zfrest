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
 *
 * @author  David Lundgren
 * @package ZfRest
 */
class Response
	extends \Zend_Controller_Action_Helper_Abstract
{
	/**
	 * Responds with a xml format
	 * @const string
	 */
	const FORMAT_XML = 'xml';

	/**
	 * Responds with a json format
	 * @const string
	 */
	const FORMAT_JSON = 'json';

	/**
	 * Responds with a PHP serialized format
	 * @const string
	 */
	const FORMAT_SERIALIZE = 'php';

	/**
	 * The default format to respond in
	 * @var string
	 */
	protected $_defaultFormat = self::FORMAT_JSON;

	// plugin::dispatchLoopStartup()
	public function preDispatch()
	{
		$request = $this->getRequest();

		$format = $request->getParam($this->_formatKey, $this->_defaultFormat);
		switch ($format) {
			case self::FORMAT_JSON:
			case self::FORMAT_XML:
			case self::FORMAT_PHP:
				break;
			default:
				throw new \RuntimeException("Unsupported media type: $contentType");
		}

		$this->_format = $format;
	}

	/**
	 *
	 */
	public function postDispatch()
	{
		$request        = $this->getRequest();
		$response       = $this->getResponse();
		$actionResponse = $this->_actionController->response;
		switch($this->_format) {
			case FORMAT_XML:
				$body = 'need to serialize to XML';
				break;
			case FORMAT_JSON:
				$body = \Zend_Json::encode($actionResponse);
				if ($this->_allowJsonCallback && ($callback = $request->getParam($this->_jsonpKey, null))) {
					$body = "{$callback}({$body});";
				}
				break;
			case FORMAT_SERIALIZE:
				$body = serialize($actionResponse);
				break;
			default:
				throw new \RuntimeException('Could not negotiate the Content-Type.');
		}
		$response->setBody($body);
	}
}
