<?php

namespace Octagon\Routing;

/**
 * Route is a container for a route.
 */

class Route
{

    /**
     * @var The route path.
     */
    private $path;

    /**
     * @var The route defaults.
     */
    private $defaults = array();

    /**
     * @var The route options.
     */
    private $options = array();

    /**
     * @var The controller specifier.
     */
    private $controllerSpecifier;

    /**
     * @var The controller class specifier.
     */
    private $classSpecifier;

    /**
     * @var The controller action specifier.
     */
    private $actionSpecifier;
    
    /**
     * @var The request method.
     */
    private $requestMethod;

    /**
     * @var The name of the controller.
     */
    private $controller;

    /**
     * @var The name of the controller action method.
     */
    private $action;

    /**
     * @var The relative path of the controller class file in the application's controller directory.
     */
    private $controllerPath;

    /**
     * @var The name of the controller class.
     */
    private $class;
    
    /**
     * Construct new route.
     *
     * @param string $requestMethod The HTTP request method to which the route is mapped.
     * @param string $routePath The route path.
     * @param string $controllerSpecifier The controller specifier. The controller specifier must be syntactically valid, because upon its input we do not check to see whether or not it is valid.
     */
    public function __construct($path = '', $defaults = array(), $options = array())
    {
        $this->setPath($path);
        $this->setDefaults($defaults);
        $this->setOptions($options);
        $this->setControllerPath( self::decodeControllerSpecifier( $this->getController() ));
        $this->setClass( self::extractClass( $this->getController() ));
    }
    
    /**
     * Set route path.
     */    
    public function setPath($path)
    {
        $this->path = $path;
    }
    
    /**
     * Get route path.
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Set defaults.
     */    
    public function setDefaults($defaults)
    {
        $this->defaults = $defaults;
    }
    
    /**
     * Get defaults.
     */
    public function getDefaults()
    {
        return $this->defaults;
    }
    
    /**
     * Set options.
     */    
    public function setOptions($options)
    {
        $this->options = $options;
    }
    
    /**
     * Get options.
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * Get request method.
     */
    public function getRequestMethod()
    {
        $method = $this->defaults['method'];
        if (isset($method)) {
            return $method;
        }
        else {
            return false;
        }
    }
    
    /**
     * Get controller.
     */
    public function getController()
    {
        $controller = $this->defaults['controller'];
        if (isset($controller)) {
            return $controller;
        }
        else {
            return false;
        }
    }
    
    /**
     * Set action.
     */
    public function setAction($action)
    {
        $this->action = $action;
    }
    
    /**
     * Get action.
     */
    public function getAction()
    {
        $action = $this->defaults['action'];
        if (isset($action)) {
            return $action;
        }
        else {
            return false;
        }
    }
    
    /**
     * Set class.
     */
    public function setClass($class)
    {
        $this->class = $class;
    }
    
    /**
     * Set class.
     */
    public function getClass()
    {
        return $this->class;
    }
    
    /**
     * Get absolute path of class file.
     */
    public function controllerPath()
    {
        $path = APP_CONTROLLER_DIRECTORY . DIRECTORY_SEPARATOR . $this->getControllerPath() . FILE_EXTENSION;
        return $path;
    }
    
    /**
     * Set relative path of class file.
     *
     * @param string $controllerPath
     */
    public function setControllerPath($controllerPath)
    {
        $this->controllerPath = $controllerPath;
    }
    
    /**
     * Get relative path of class file.
     */
    public function getControllerPath()
    {
        return $this->controllerPath;
    }

    /**
     * Utility: Is controller specifier syntactically valid?
     *
     * @param string $controllerSpecifier
     * @return bool True if valid; false if invalid.
     */
    public static function isControllerSpecifier($controllerSpecifier)
    {
        // EBNF specification: {directory ":"} controller
        $pattern = '/(([0-9_A-Za-z]+):)*[0-9_A-Za-z]+$/';
        if (preg_match($pattern, $controllerSpecifier)) {
            return true;
        }
        else {
            return false;
        }
    }
    
    /**
     * Utility: Decode controller specifier to directory path.
     *
     * @param string $specifier
     */
    public static function decodeControllerSpecifier($controllerSpecifier)
    {
        $result = str_replace(':', DIRECTORY_SEPARATOR, $controllerSpecifier);
        return $result;
    }
    
    /** 
     * Utility: Get the name of the class from the class specifier.
     */
    public static function extractClass($controllerSpecifier)
    {
        $i = strrpos($controllerSpecifier, ':');
        if ($i === false) {
            return $controllerSpecifier;
        }
        else {
            return substr($controllerSpecifier,$i+1);
        }
    }

}
