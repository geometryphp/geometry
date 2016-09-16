<?php

namespace Octagon\Routing;

/**
 * RouteTable stores the route entries for the application.
 */

class RouteTable 
{
    
    /**
     * Stores routes.
     */
    private $routes = array();
    
    /**
     * Add new route.
     */
    public function add(\Octagon\Routing\Route $route)
    {
        $this->routes[] = $route;
    }
    
    /**
     * Get all routes.
     */
    public function all()
    {
        return $this->routes;
    }

}
