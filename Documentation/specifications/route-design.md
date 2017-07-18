# Route design


Models:

- Route
- RouteCollection
- RouteGroup

## Route

## Route group

## Route collection

A route collection represents a collection of routes. A route collection can manipulate a route group. The router takes the route collection and parses each route for a match.

# Example

```
Router::scope(['domain'=>'example.com'], function($routes) {
    Router::get('https://example.com/help','App:Controller:Product@details')
    ->name('get:/right')
    ->match('headad','[A-Za-z]+');
    ->
});
```
