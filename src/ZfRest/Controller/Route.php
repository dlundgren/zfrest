<?php
namespace ZfRest\Controller;
/**
 * @license   MIT license
 * @copyright Copyright (c) 2012. David Lundgren, All Rights Reserved.
 * @version   1.0
 * @package   ZfRest
 */

/**
 * The Route for the ZfRest library
 *
 * @author  David Lundgren
 * @package ZfRest
 */
class Route
	extends \Zend_Controller_Router_Route_Module
	implements \Zend_Controller_Router_Route_Interface
{
	/**
	 * List of the routes to use
	 * @var array
	 */
	protected $_subRoutes = null;

	/**
	 * The base prefix that all sub routes use
	 * @var string
	 */
	protected $_routePrefix = null;

	/**
	 * The route handler callback. Not needed if supplying routes.
	 * @var array
	 */
	protected $_routeHandler = null;

	/**
	 * The route to send to when we can't find any routes, under us.
	 * @var array
	 */
	protected $_errorRoute = null;

	/**
	 * The key name in the values array that is used by the module.
	 * @var string
	 */
	protected $_formatKey = 'format';

	/**
	 * The accepted extensions that determine the output format.
	 *
	 * This is done as part of the routing to remove the extensions from being matched.
	 * @var array
	 */
	protected $_formatExtensions = array(
		'xml',
		'json'
	);

	/**
	 * Constructor
	 *
	 * Reconfigures the "defaults" to be the specified module (or default) and the action to be the requestMethod or GET
	 *
	 * @param array $defaults
	 * @param Zend_Controller_Dispatcher_Interface $dispatcher
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function __construct(array $defaults = array(),
	                            \Zend_Controller_Dispatcher_Interface $dispatcher = null,
	                            \Zend_Controller_Request_Abstract $request = null)
	{
		$this->setDefaults($defaults);

		unset($defaults['routeHandler']);
		unset($defaults['subRoutes']);
		unset($defaults['routePrefix']);
		unset($defaults['errorRoute']);
		unset($defaults['formatKey']);
		unset($defaults['formatExtensions']);

		parent::__construct($defaults, $dispatcher, $request);
	}

	/**
	 * Sets the defaults
	 *
	 * @param  array $defaults
	 * @return ZfRest\Controller\Route Provides a fluent interface
	 */
	public function setDefaults(array $defaults)
	{
		if (isset($defaults['routeHandler'])) {
			$this->setRouteHandler($defaults['routeHandler']);
		}

		if (isset($defaults['subRoutes'])) {
			$this->setSubRoutes($defaults['subRoutes']);
		}

		if (isset($defaults['routePrefix'])) {
			$this->setRoutePrefix($defaults['routePrefix']);
		}

		if (isset($defaults['errorRoute'])) {
			$this->setErrorRoute($defaults['errorRoute']);
		}

		if (isset($defaults['formatKey'])) {
			$this->setFormatKey($defaults['formatKey']);
		}

		if (isset($defaults['formatExtensions'])) {
			$this->setFormatExtensions($defaults['formatExtensions']);
		}

		return $this;
	}

	/**
	 * Sets the route handler
	 *
	 * @param  array $handler
	 * @return ZfRest\Controller\Route provides a fluent interface
	 */
	public function setRouteHandler(array $handler)
	{
		$this->_routeHandler = $handler;
		return $this;
	}

	/**
	 * Returns the route handler
	 *
	 * @return array
	 */
	public function getRouteHandler()
	{
		return $this->_routeHandler;
	}

	/**
	 * Sets the routes
	 *
	 * @param  array $routes
	 * @return ZfRest\Controller\Route provides a fluent interface
	 */
	public function setSubRoutes(array $routes)
	{
		$this->_subRoutes = $routes;
		return $this;
	}

	/**
	 * Returns the routes
	 *
	 * @return array
	 */
	public function getSubRoutes($routes)
	{
		return $this->_subRoutes;
	}

	/**
	 * Sets the route prefix
	 *
	 * @param  string $prefix
	 * @return ZfRest\Controller\Route provides a fluent interface
	 */
	public function setRoutePrefix($prefix)
	{
		$this->_routePrefix = $prefix;
		return $this;
	}

	/**
	 * Returns the route prefix
	 *
	 * @return string
	 */
	public function getRoutePrefix($prefix)
	{
		return $this->_routePrefix;
	}

	/**
	 * Sets the erorr route params
	 *
	 * @param  array $params
	 * @return ZfRest\Controller\Route provides a fluent interface
	 */
	public function setErrorRoute(array $params)
	{
		$this->_errorRoute = $params;
		return $this;
	}

	/**
	 * Returns the error route params
	 *
	 * @return array
	 */
	public function getErrorRoute()
	{
		return $this->_errorRoute;
	}

	/**
	 * Sets the format key
	 *
	 * @param  string $key
	 * @return ZfRest\Controller\Route provides a fluent interface
	 */
	public function setFormatKey($key)
	{
		$this->_formatKey = $key;
		return $this;
	}

	/**
	 * Returns the format key
	 *
	 * @return string
	 */
	public function getFormatKey()
	{
		return $this->_formatKey;
	}

	/**
	 * Sets the format extensions
	 *
	 * @param  array $extensions
	 * @return ZfRest\Controller\Route provides a fluent interface
	 */
	public function setFormatExtensions(array $extensions)
	{
		$this->_formatExtensions = $extensions;
		return $this;
	}

	/**
	 * Returns the format extensions
	 *
	 * @return array
	 */
	public function getFormatExtensions()
	{
		return $this->_formatExtensions;
	}

	/**
	 * Processes the path for a possible match on the route
	 *
	 * @param  string  $path
	 * @param  boolean $partial
	 * @return boolean|array False if the route does not match, an array of values to parse to if it does.
	 */
	public function match($path, $partial = false)
	{
		if ( ! $this->_subRoutes && ! $this->_routeHandler) {
			throw new \RuntimeException('Either routes or a routeHandler must be specified.');
		}

		$this->_setRequestKeys();
		if ( ! isset($this->_defaults[$this->_moduleKey])) {
			$this->_defaults[$this->_moduleKey] = 'default';
		}
		$this->_defaults[$this->_actionKey] = $this->_request ? $this->_request->getMethod() : 'GET';

		$origPath = $path = trim($path, self::URI_DELIMITER);
		if (0 !== strpos($path, $this->_routePrefix . self::URI_DELIMITER)) {
			if ($path != $this->_routePrefix) {
				return false;
			}
		}

		$extensions = join('|', $this->_formatExtensions);
		if (preg_match("/(\.({$extensions}))\$/iu", $path, $match)) {
			$values[$this->_formatKey] = substr($match[2], 1);
			$path = str_replace($match, '', $path);
		}

		$values      = $this->_defaults;
		$matchedPath = null;
		$routes      = $this->_routeHandler ? call_user_func($this->_routeHandler) : $this->_subRoutes;
		$checkPath   = trim(str_replace($this->_routePrefix, '', $path), self::URI_DELIMITER);
		foreach($routes as $pattern => $controller) {
			if ($checkPath == $pattern || $checkPath == $pattern . self::URI_DELIMITER) {
				$matchedPath = $origPath;
				if (is_array($controller)) {
					if ( ! isset($controller['*'])) {
						throw new \RuntimeException("No default route specified for $pattern");
					}
					$values[$this->_controllerKey] = $controller['*'];
				}
				else {
					$values[$this->_controllerKey] = $controller;
				}
				break;
			}
			elseif (0 === strpos($path, $pattern . self::URI_DELIMITER)) {
				if (is_array($controller)) {
					if (isset($controller['*'])) {
						unset($controller['*']);
					}
					foreach($controller as $childPattern => $childController) {
						if (false !== ($tempValues = $this->_processRoute($childPattern, str_replace($pattern. self::URI_DELIMITER, '', $checkPath)))) {
							$values[$this->_controllerKey] = $childController;
							$values                        = $values + $tempValues;
							$matchedPath                   = $origPath;
							break;
						}
					}

					if ($matchedPath) {
						break;
					}
				}
			}
			elseif (false !== ($tempValues = $this->_processRoute($pattern, $checkPath))) {
				$values[$this->_controllerKey] = $controller;
				$values                        = $values + $tempValues;
				$matchedPath                   = $origPath;
				break;
			}
		}

		if ( ! $matchedPath) {
			if (empty($this->_errorRoute)) {
				return false;
			}
			$matchedPath = $origPath;
			$values      = $this->_errorRoute;
		}

		$this->setMatchedPath(rtrim($matchedPath, self::URI_DELIMITER));
		$this->_values = $values;
		return $values;
	}

	/**
	 * Processes the route for the given path
	 *
	 * @param  string $route
	 * @param  string $path
	 * @return array Any values found
	 */
	protected function _processRoute($route, $path)
	{
		$values = array();
		$path   = explode(self::URI_DELIMITER, $path);
		foreach (explode(self::URI_DELIMITER, $route) as $pos => $part) {
			if ( ! isset($path[$pos])) {
				return false;
			}

			if (':' === $part[0]) {
				$name  = substr($part, 1);
				$regex = isset($this->_defaults['regex'][$part]) ? $this->_defaults['regex'][$part] : '.*';
				if ( ! preg_match("/^{$regex}$/iu", $path[$pos])) {
					return false;
				}
				$values[$name] = $path[$pos];
			}
			elseif ($path[$pos] == $part) {
				continue;
			}
			else {
				return false;
			}
		}
		return $values;
	}
}
