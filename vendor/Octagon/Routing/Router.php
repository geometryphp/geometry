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
        // each other, compare their corresponding parts. The part for a request
        // and route match if 1) boths strings are the same, or 2) the route part
        // is a placeholder.
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

    // Set full base URL to be used in generating URL.

    public static function fullBaseUrl($base = null)
    {
        self::$baseUrl = $base;
    }

    // Generate URL by name with URL
    //
    // Todo:
    // - Should take the entire URL into consideration. It should consider: base, scheme, host, port, # fragment, protocol (ssl or not), query, and any other URL parts that I may didn't mention.

    public static function url($name, $args = array())
    {
        $routeCollection = self::getRouteCollection();
        $route = $routeCollection->get($name);

        if ($route === false) {
            return false;
        }

        $path = $route->getPath();
        $path = self::subArgs($path, $args);
        return $path;
    }

}
