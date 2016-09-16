<?php

namespace Octagon\Core;

use Octagon\Http\Response;
use Octagon\View\View;
use Octagon\Core\Registry;

/**
 * Store information about an application controller for the dispatcher.
 *
 * ControllerHook contains information about an application controller.
 * The purpose of ControllerHook is to store controller information for
 * the dispatcher. The dispatcher will then use the information to dispatch
 * the controller.
 */

class ControllerHook
{

    /**
     * The name of the controller.
     *
     * @var string
     */
    private $name;

    /**
     * The name of the controller's action method.
     *
     * @var string
     */
    private $action;

    /**
     * The key-value pairs to be injected as variables into the controller.
     *
     * @var array
     */
    private $args;

    /**
     * Absolute path of the controller's class file.
     *
     * @var string
     */
    private $path;

    /**
     * Route mapped to the controller.
     *
     * @var \Octagon\Routing\Route
     */
    private $route;

    /**
     * Create a controller hook.
     *
     * @param string $name    Name of the controller.
     * @param string $action  Name of the action method.
     * @param array $args     Arguments to pass to the controller.
     * @param string $path    Path to the controller's class file.
     * @param mixed $route    The Route object mapped to the controller.
     *
     * @return void
     */
    public function __construct($name = '', $action = '', $args = array(), $path = '', $route = null)
    {
        $this->setName($name);
        $this->setAction($action);
        $this->setArgs($args);
        $this->setPath($path);
        $this->setRoute($route);
    }

    /**
     * Execute the controller.
     *
     * @return \Octagon\Http\Response The response from the controller.
     */
    public function run()
    {
        $instance = new $this->name();
        if (method_exists($instance, $this->action)) {
            $response = call_user_func_array( array($instance, $this->getAction()), $this->getArgs() );
        }
        else {
            // Push error
            $registry = Registry::getInstance();
            $response = $registry->get503();
            $response->send();

            $errmsg = 'Method "' . $this->getName() . '::' . $this->getAction() . '" does not exist in class file "' . $this->getPath() . '"';
            trigger_error($errmsg, E_USER_ERROR);
        }
        return $response;
    }

    /**
     * Set the name of the controller.
     *
     * @param string $name
     *
     * @return void
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get the name of the controller.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set the name of the action method.
     *
     * @param string $action
     *
     * @return void
     */
    public function setAction($action) {
        $this->action = $action;
    }

    /**
     * Get the controller's action method.
     *
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * Set the variables to be injected into the controller.
     *
     * @param array $args An array containing key-value pairs.
     *
     * @return void
     */
    public function setArgs($args) {
        $this->args = $args;
    }

    /**
     * Get the array that contains the variables.
     *
     * @return array
     */
    public function getArgs() {
        return $this->args;
    }

    /**
     * Set the path of the class file.
     *
     * @var string $path
     *
     * @return void
     */
    public function setPath($path) {
        $this->path = $path;
    }

    /**
     * Get the path of the controller's class file.
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Assign the given Route object to the controller hook.
     *
     * @param \Octagon\Routing\Route $route Route object to assign.
     *
     * @return void
     */
    public function setRoute($route) {
        $this->route = $route;
    }

    /**
     * Get the Route object.
     *
     * @return \Octagon\Routing\Route
     */
    public function getRoute() {
        return $this->route;
    }

}
