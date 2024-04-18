# ZfRest #

**⚠️ No longer maintained**

ZfRest is a Zend Framework 1 extension. It is similar to Resauce but sufficiently different.

## Todo ##

* Use Accept: header for versioning and format syntax
* Look at Rob Allen's ZfApiVersioning repo for more plugin ideas

## Design Goals ##

* Treat Controllers as Resources
* Should not interfere with an existing application (Recommended to use ZF module layout, but other application layouts are supported)

# Introduction #

ZfRest is designed to make a REST API easier to implement. It started out as a way to generate a best practices for a
REST API design that I noticed was missing from the Zend Framework landscape. I have used several sources along the way
to help in the design portion of this extension.

## Responses ##

Unless otherwise noted every response sent should include the following headers:
* Allow:
* Content-Type:

## Resources ##

The controllers are responsible for one resource. This means that /users is different from /users/1 in terms of which
controller will be called. Like Resauce this is different from how the REST components of ZF works.

In order to leave the most room for query parameters in the URL ZfRest defaults to prefixing internal parameters with an
underscore.

## Routes ##

Routing is accomplished by use of the ZfRest\Controller\Route. This extends the ZF Module route and allows for a mapping
explicitly to the Resource Controllers.

The HTTP methods are used as the actions in the Resource Controllers:

* GET >> getAction()
* PUT >> putAction()
* POST >> postAction()
* DELETE >> deleteAction()
* HEAD >> headAction()
* OPTIONS >> optionsAction()

Like Resauce, ZfRest will return a 405 Method not allowed response for methods that are not defined.

## Plugins ##

In addition to the above core extensions. I've also found that the following plugins have been useful for helping to
develop REST APIs. Unless otherwise noted, all plugins interact with Zend_Registry to set data attributes for
themselves (and access by the action helpers)

### OAuth ###

The ZfRest\Controller\Plugin\Oauth should be extended for each version of the API that is to be access as it currently
relies on being used on a per module basis.

## Example Usage ##

### Bootstrap ###

```php
/**
 * Api version 1 bootstrap
 */
class Apiv1_Bootstrap extends \ZfRest\Application\Bootstrap
{
	public function _initRestRoutingPlugin()
	{
		$front = \Zend_Controller_Front::getInstance();
		$front->registerPlugin(new Apiv1_Plugin_Routing($front->getDispatcher(), $front->getRouter());
	}
}

/**
 * API version 1 Routing plugin
 */
class Apiv1_Plugin_Routing extends \Zend_Controller_Plugin_Abstract
{
	private $dispatcher;
	private $router;
	
	public function __construct(\Zend_Controller_Dispatcher_Interface $dispatcher, \Zend_Controller_Router_Rewrite $router)
	{
		$this->dispatcher = $dispatcher;
		$this->router     = $router;
	}
	
	public function routeStartup(\Zend_Controller_Request_Abstract $request)
	{
		$defaults = array(
			'routeHandler' => array($this, 'fetchRoutes'),
			'module'       => 'v1',
			'routePrefix'  => 'v1'
		);
		$route = new \ZfRest\Controller\Route($default, $this->dispatcher, $request);
		$route->setErrorRoute(array(
			'module'     => 'v1',
			'controller' => 'error',
			'action'     => 'error'
		));
		$this->router->addRoute('api-v1', $route); 
	}

	public function fetchRoutes()
	{
		return array(
			'users/:id/groups' => 'UserGroups'
			'users/:id'        => 'User',
			'users'            => 'Users',

			// alternate syntax
			'groups'           => array(
				':id/users' => 'GroupUsers',
				':id'       => 'Group'
				'*'         => 'Groups'
			)
		);
	}
}
```

## Controller ##

Controllers are created like normal.
```php
<?php
class Apiv1_Controller_UsersController extends \ZfRest\Controller\Resource
{
	/**
	 * Handle the GET request
	 */
	public function getAction()
	{
		// return a list of users
	}

	/**
	 * Handle the POST request
	 */
	public function postAction()
	{
		// create a user
	}
}
```

The other controllers from the Example bootstrap would be:

* UserController
* UserGroupsController
* GroupsController
* GroupController
* GroupUsersController

# Inspiration #

Thanks to the following projects that helped me design ZfRest

* "Resauce Framework":http://github.com/mikekelly/Resauce
* "ZfApiVersioning":http://github.com/zircote/ZfApiVersioning
* "ZF-REST-API":http://github.com/???/ZF-REST-API
