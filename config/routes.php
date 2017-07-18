<?php

/*
*-------------------------------------------------------------------------------
* Use
*-------------------------------------------------------------------------------
*
* Here we make it easy for you to register routes. Do not delete.
*
*/

/*
use Octagon\Routing\Route;
use Octagon\Routing\Router;
use Octagon\Routing\RouteCollection;
*/

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
 * Format (in EBNF):
 *   routename = http_method '.' app '.' namespace '.' name ;
 *   namespace = name {'.' name} ;
 *   name = (digit | letter | '_') {name} ;

 * Example:
 *   get.marketplace.static.policy.view_privacy
 *
 * Explanation:
 *   The method is 'get'; namespace is 'static.policy'; and 'privacy' is the id
 *   or template name.
 */

// Create route collection

$router->get('/', 'index@App:Controller:Home', array(
    "name"=>"get.home.index"
));
