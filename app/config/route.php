<?php

/*
*-------------------------------------------------------------------------------
* Use
*-------------------------------------------------------------------------------
*
* Here we make it easy for you to register routes. Do not delete.
*
*/

use Octagon\Routing\Route;
use Octagon\Routing\RouteCollection;

/*
*-------------------------------------------------------------------------------
* Application Routes
*-------------------------------------------------------------------------------
*
* Here you tell Octagon what routes to use.
*
*/

/* Route naming convention
 *
 * Route names are formatted to give much information as possible by
 * just looking at its name. A route name has the following parts:
 * - method
 * - namespace
 * - name
 *
 * Syntax:
 *   <routename> = (get|post|put|delete):<namespace>/<id>

 * Example:
 *   get:static.policy/privacy
 *
 * Explanation:
 *   The method is 'get'; namespace is 'static.policy'; and 'privacy' is the id
 *   or template name.
 */

// Create route collection

$routeCollection = new RouteCollection();

// Test

$routeCollection->add('get:home/landing', new Route('/', [
        'method'     => 'GET',
        'controller' => 'Home',
        'action'     => 'landing'
    ]
));

return $routeCollection;
