<?php

namespace Octagon\Routing;

use Octagon\Routing\RouteCollection;
use Octagon\Core\ControllerHook;
use Octagon\Core\Registry;

/*
 * Router implements the singleton pattern.
 */

class Router
{

    /**
     * The route collection that contains the application routes.
     */
    private static $routeCollection;
    private static $baseUrl;

    public static function init($routeCollection)
    {
        self::setRouteCollection($routeCollection);
    }

    public static function getController($requestPath, $requestMethod)
    {
        $controller = self::find(self::getRouteCollection(), $requestPath, $requestMethod);
        return $controller;
    }

    public static function find(RouteCollection $routeCollection, $requestPath, $requestMethod)
    {
        // Get all route from the route collection
        $routes = $routeCollection->all();

        // Search each route for a match
        foreach($routes as $name=>$route) {
            // If the request method and current route method match then...
            if (strtolower($route->getRequestMethod()) == strtolower($requestMethod)) {
                // If the request path and current route path match then...
                if (self::match($requestPath, $route->getPath())) {
                    // Yay, we found a hook.
                    // Retrieve the args (if any) from the request path,
                    // and save them to the Request object.
                    $requestArgs = self::getArgs($requestPath, $route->getPath());
                    $registry = Registry::getInstance();
                    $request = $registry->getRequest();
                    $request->setParams($requestArgs);
                    $args = array('request'=>$request);

                    // Set the properties required by the hook
                    $controller = new ControllerHook();
                    $controller->setName($route->getClass());
                    $controller->setAction($route->getAction());
                    $controller->setArgs($args);
                    $controller->setPath($route->controllerPath());
                    $controller->setRoute($route);

                    // Return the hook to the caller
                    return $controller;
                }
            }
        }

        // No matches were found
        return null;
    }

    // Does URI path and route path match?

    public static function match($requestPath, $routePath)
    {
        // Split paths into parts. Parts are delimited by the forward-slash character
        $requestParts = explode("/", $requestPath);
        $routeParts = explode("/", $routePath);

        // If the parts are inequal in their count, there's no match
        if (count($requestParts) != count($routeParts)) {
            return false;
        }

        // Does request path and route path match? To see if both paths match
        // each other, compare their corresponding parts from start to end.
        //
        // The part for a request and route match if 1) boths strings are
        // the same, or 2) the route part is a placeholder.
        foreach ($routeParts as $index => $routePathPart) {
            if (strtolower($routePathPart) != strtolower($requestParts[$index])) {
                if (!self::isPlaceholder($routePathPart)) {
                    return false;
                }
            }
        }

        // Yay, they match!
        return true;
    }

    // Collect arguments from request path.

    private static function getArgs($requestPath, $routePath)
    {
        $requestParts = explode('/', $requestPath);
        $routeParts = explode('/', $routePath);

        $args = array();
        foreach ($routeParts as $index=>$routePathPart) {
            if (self::isPlaceholder($routePathPart)) {
                $argName = self::extractIdentifier($routePathPart);
                $argValue = $requestParts[$index];
                $args[$argName] = $argValue;
            }
        }
        return $args;
    }

    // Substitute placeholders with values.

    private static function subArgs($path, $args = array()) {
        $parts = explode('/', $path);
        foreach ($parts as $i=>$part) {
            if (self::isPlaceholder($part)) {
                $id = self::extractIdentifier($part);
                if (array_key_exists($id, $args)) {
                    $parts[$i] = $args[$id];
                }
            }
        }
        $path = implode('/', $parts);
        return $path;
    }

    // Utility: Is subject a valid placeholder?

    private static function isPlaceholder($subject)
    {
        $syntax = '/^:[A-Za-z_][A-Za-z0-9_]*$/';

        if (preg_match($syntax, $subject)) {
            return true;
        }
        else {
            return false;
        }
    }

    // Utility: Extract placeholder identifier from placeholder string.

    private static function extractIdentifier($placeholder)
    {
        $identifier = substr($placeholder, 1);
        return $identifier;
    }

    // Set route collection

    public static function setRouteCollection(RouteCollection $routeCollection)
    {
        self::$routeCollection = $routeCollection;
    }

    // Get route collection

    public static function getRouteCollection()
    {
        return self::$routeCollection;
    }

    // Get or set the full base URL to be used in generating the URL.
    // See CakePHP's fullBaseUrl() for inspiration.

    public static function fullBaseUrl()
    {
        $registry = Registry::getInstance();
        $config = $registry->getConfig();
        $baseUrl = $config->get('base_url');
        return $baseUrl;
    }

    /**
     * Generate URL by name with URL.
     *
     * The URL generator takes
     * - Should take the entire URL into consideration. It should consider:
     *   - protocol
     *   - base url
     *   - port
     *   - base path: This is a relative path. Example: `example.com/foo`, where URLS are generated as `example.com/foo/dashboard`.
     *   - path with arguments:
     *   - query (parameters)
     *   - hash fragment (anchor)
     *
     * Anatomy of the $url parameter:
     * - `name`
     * - `scheme`: indicates the protocol.
     * - `base`: relative to
     * - `ssl`
     * - `port`
     * - `query`
     * - `#`
     *
     * @param array|null $url The URL to generate.
     * @param bool       $full Generate full URL with with base.
     *
     * @return Returns string on success. Otherwise, returns either null on error or false if route cannot be found.
     *
     * @todo Allow the function to also generate URLS by 1) controller & action name,
     *  and allow function to generate 2) URL with strings. See CakePHP's url() for inspiration.
     *
     *  Example:
     *  1) self::url(['controller'=>'dashboard', 'action'=>'settings'], true) => http://example.com/admin/dashboard/settings
     *  2) self::url('path/to/data', true) => http://example.com/path/to/data
     */

    public static function url($name = null, $args = [], $query = [])
    {
        // Get route
        $routeCollection = self::getRouteCollection();
        $route = $routeCollection->get($name);

        // Return error if route cannot be found
        if ($route === false) {
            return false;
        }

        // Interpolate args into path
        $path = $route->getPath();
        $path = self::subArgs($path, $args);

        // Build query string
        if (empty($query)) {
            $params = "";
        }
        else if (count($query) == 1) {
            $params = '?'.key($query).'='.current($query);
        }
        else {
            $params = "";
            foreach ($query as $key=>$val) {
                $params .= '&'.$key.'='.$val;
            }
            $params[0] = '?'; // replace first '&' with '?'.
        }

        // Compose URL
        $url = self::fullBaseUrl() . $path . $params;

        // Return URL
        return $url;
    }

}
