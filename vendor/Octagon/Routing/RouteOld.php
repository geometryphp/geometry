<?php

namespace Octagon\Routing;

/**
 * Route is a container for a route.
 */

class Route
{

    /**
     * @var The request method.
     */
    private $requestMethod;

    /**
     * @var The route path.
     */
    private $routePath;

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
     * @var The name of the controller class.
     */
    private $class;

    /**
     * @var The name of the controller action method.
     */
    private $action;

    /**
     * @var The relative path of the controller class file in the application's controller directory.
     */
    private $classPath;
    
    /**
     * Construct new route.
     *
     * @param string $requestMethod The HTTP request method to which the route is mapped.
     * @param string $routePath The route path.
     * @param string $controllerSpecifier The controller specifier. The controller specifier must be syntactically valid, because upon its input we do not check to see whether or not it is valid.
     */
    public function __construct($requestMethod, $routePath, $controllerSpecifier)
    {
        // Set the request method and route path
        $this->setRequestMethod($requestMethod);
        $this->setRoutePath($routePath);
        
        // Set controller specifier
        $this->setControllerSpecifier($controllerSpecifier);
        
        // Decode the controller specifier
        $controllerSpecifierParts = self::decodeControllerSpecifier($this->getControllerSpecifier()); 
        
        // Set action specifier and class specifier
        $this->setActionSpecifier($controllerSpecifierParts['action']);
        $this->setClassSpecifier($controllerSpecifierParts['class']);
        
        // Set the class name and action name
        $class = self::extractClass($this->getClassSpecifier());
        $this->setClass($class);
        $this->setAction($this->getActionSpecifier());
        
        // Set the path of the class file
        $path = self::decodeClassSpecifier($this->getClassSpecifier());
        $this->setClassPath($path);
    }
    
    /**
     * Set request method.
     */
    public function setRequestMethod($requestMethod)
    {
        $this->requestMethod = $requestMethod;
    }
    
    /**
     * Get request method.
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }
    
    /**
     * Set route path.
     */    
    public function setRoutePath($routePath)
    {
        $this->routePath = $routePath;
    }
    
    /**
     * Get route path.
     */
    public function getRoutePath()
    {
        return $this->routePath;
    }
    
    /**
     * Utility: Decode controller specifier.
     */
    public static function decodeControllerSpecifier($controllerSpecifier)
    {
        // Split controller specifier into parts: the action part and class part.
        $parts = explode('@', $controllerSpecifier);
        return array( 'action'=>$parts[0] , 'class'=>$parts[1] );
    }

    /**
     * Utility: Is controller specifier syntactically valid?
     *
     * @param string $controllerSpecifier
     * @return bool True if valid; false if invalid.
     */
    public static function isControllerSpecifier($controllerSpecifier)
    {
        // EBNF specification: action "@" {directory "."} controller
        $pattern = '/^[0-9_A-Za-z]+@(([0-9_A-Za-z]+)\.)*[0-9_A-Za-z]+$/';
        if (preg_match($pattern, $controllerSpecifier)) {
            return true;
        }
        else {
            return false;
        }
    }
    
    /**
     * Set controller specifier.
     */    
    public function setControllerSpecifier($controllerSpecifier)
    {
        $this->controllerSpecifier = $controllerSpecifier;
    }
    
    /**
     * Get controller specifier.
     */    
    public function getControllerSpecifier()
    {
        return $this->controllerSpecifier;
    }
    
    /**
     * Utility: Decode class specifier to directory path.
     *
     * @param string $specifier
     */
    public static function decodeClassSpecifier($classSpecifier)
    {
        $result = str_replace('.', DIRECTORY_SEPARATOR, $classSpecifier);
        return $result;
    }
    
    /**
     * Set controller class.
     */
    public function setClassSpecifier($classSpecifier)
    {
        $this->classSpecifier = $classSpecifier;
    }
    
    /**
     * Get controller class.
     */
    public function getClassSpecifier()
    {
        return $this->classSpecifier;
    }
    
    /**
     * Set controller action.
     */
    public function setActionSpecifier($actionSpecifier)
    {
        $this->actionSpecifier = $actionSpecifier;
    }
    
    /**
     * Get controller action
     */
    public function getActionSpecifier()
    {
        return $this->actionSpecifier;
    }
    
    /** 
     * Utility: Get the name of the class from the class specifier.
     */
    public static function extractClass($classSpecifier)
    {
        $i = strrpos($classSpecifier, '.');
        if ($i === false) {
            return $classSpecifier;
        }
        else {
            return substr($classSpecifier,$i+1);
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
     * Get class.
     */
    public function getClass()
    {
        return $this->class;
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
        return $this->action;
    }
    
    /**
     * Get absolute path of class file.
     */
    public function fullClassPath()
    {
        $path = APP_CONTROLLER_DIRECTORY . DIRECTORY_SEPARATOR . $this->getClassPath() . FILE_EXTENSION;
        return $path;
    }
    
    /**
     * Set relative path of class file.
     *
     * @param string $classPath
     */
    public function setClassPath($classPath)
    {
        $this->classPath = $classPath;
    }
    
    /**
     * Get relative path of class file.
     */
    public function getClassPath()
    {
        return $this->classPath;
    }

}
