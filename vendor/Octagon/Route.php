<?php

namespace Octagon;

use Octagon\Routing\RouteTable;

class Route
{
    /**
     * The route table in which to store application routes.
     *
     * @param 
     */
    private $routeTable;
    
    /**
     * Construct a new route table.
     */
    public function __construct(\Octagon\Routing\RouteTable $routeTable)
    {
        $this->setRouteTable($routeTable);
    }
    
    // Register route and controller on GET method

    public function get($routePath, $controllerSpecifier)
    {
        if (Route::isControllerSpecifier($controllerSpecifier)) {
            $this->registerGet($routePath, $controllerSpecifier);
        }
        else {
            // push error
            $response = new \Octagon\Http\FatalErrorResponse(new \Octagon\View\View(DEFAULT_ERROR_TEMPLATE));
            $response->send();
            \Octagon\Error::trigger('Error registering route: \n' . ' GET ' . $routePath . '\n' . $controllerSpecifier);
        }
    }
    
    // Register route and controller on POST method

    public function post($routePath, $controllerSpecifier)
    {
        if (\Octagon\Routing\Route::isControllerSpecifier($controllerSpecifier)) {
            $this->registerPost($routePath, $controllerSpecifier);
        }
        else {
            // push error
            $response = new \Octagon\Http\FatalErrorResponse(new \Octagon\View\View(DEFAULT_ERROR_TEMPLATE));
            $response->send();
            \Octagon\Error::trigger('Error registering route: \n' . ' POST ' . $routePath . '\n' . $controllerSpecifier);
        }
    }
    
    // Register route and controller on PUT method

    public function put($routePath, $controllerSpecifier)
    {
        if (\Octagon\Routing\Route::isControllerSpecifier($controllerSpecifier)) {
            $this->registerPut($routePath, $controllerSpecifier);
        }
        else {
            // push error
            $response = new \Octagon\Http\FatalErrorResponse(new \Octagon\View\View(DEFAULT_ERROR_TEMPLATE));
            $response->send();
            \Octagon\Error::trigger('Error registering route: \n' . ' PUT ' . $routePath . '\n' . $controllerSpecifier);
        }
    }
    
    // Register route and controller on DELETE method

    public function delete($routePath, $controllerSpecifier)
    {
        if (\Octagon\Routing\Route::isControllerSpecifier($controllerSpecifier)) {
            $this->registerDelete($routePath, $controllerSpecifier);
        }
        else {
            // push error
            $response = new \Octagon\Core\HttpFatalErrorResponse(new \Octagon\Component\View(DEFAULT_ERROR_TEMPLATE));
            $response->send();
            \Octagon\Core\Error::trigger('Error registering route: \n' . ' DELETE ' . $routePath . '\n' . $controllerSpecifier);
        }
    }
    
    // Register a route on GET method
    
    private function registerGet($routePath, $controllerSpecifier)
    {
        $this->register('GET', $routePath, $controllerSpecifier);
    }
    
    // Register a route on POST method
    
    private function registerPost($routePath, $controllerSpecifier)
    {
        $this->register('POST', $routePath, $controllerSpecifier);
    }
    
    // Register a route on PUT method
    
    private function registerPut($routePath, $controllerSpecifier)
    {
        $this->register('PUT', $routePath, $controllerSpecifier);
    }
    
    // Register a route on DELETE method
    
    private function registerDelete($routePath, $controllerSpecifier)
    {
        $this->register('DELETE', $routePath, $controllerSpecifier);
    }
    
    // Register a route to the route table
    
    private function register($requestMethod, $routePath, $controllerSpecifier)
    {
        // Before we start, normalize the route path.
        $routePath = \Octagon\Http\Request::normalizeUriPath($routePath);
        
        // Register the route
        $route = new \Octagon\Routing\Route($requestMethod, $routePath, $controllerSpecifier);
        $routeTable = $this->getRouteTable();
        $routeTable->add($route);
    }
    
    // Set route table
    
    public function setRouteTable(\Octagon\Routing\RouteTable $routeTable)
    {
        $this->routeTable = $routeTable;
    }
    
    // Get route table
    
    public function getRouteTable()
    {
        return $this->routeTable;
    }

}