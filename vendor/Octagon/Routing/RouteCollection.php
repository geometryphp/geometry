<?php

namespace Octagon\Routing;

use Octagon\Routing\Route;

/**
 * RouteCollection stores the route entries for the application.
 */

class RouteCollection
{
    
    /**
     * Stores routes.
     */
    private $routes = array();
    
    /**
     * Add new route.
     */
    public function add($name = "", Route $route)
    {
        if (empty($name)) {
            $this->routes[] = $route;
        }
        else {
            $this->routes[$name] = $route;
        }
    }
    
    /**
     * Get route by name.
     */
    public function get($name)
    {
        if ($this->exists($name)) {
            return $this->routes[$name];
        }
        else {
            return false;
        }
    }
    
    /**
     * Get all routes.
     */
    public function all()
    {
        return $this->routes;
    }
    
    /**
     * Does route exist?
     */
    public function exists($key)
    {
        return array_key_exists($key, $this->routes);
    }

}
