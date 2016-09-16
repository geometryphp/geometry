<?php

namespace Octagon\Core;

use Octagon\Http\Request;
use Octagon\Http\Response;
use Octagon\Config\Config;
use Octagon\Core\Registry;
use Octagon\View\View;

/**
 * Registry implements the Registry pattern.
 *
 * The registry serves to provide global access to data through the layers
 * of the system. Two noncontiguous layers can share data using the registry.
 */

class Registry
{

    /**
     * Stores the Registry instance to create a singleton.
     *
     * @var \Octagon\Core\Registry
     */
    static private $instance = null;

    /**
     * Stores Request instance.
     *
     * @var \Octagon\Http\Request
     */
    private $request = null;

    /**
     * Stores Config instance.
     *
     * @var \Octagon\Config\Config
     */
    private $config = null;

    /**
     * Hide constructor.
     */
    private function __construct() { }

    /**
     * Get Registry instance.
     *
     * This method gets the Registry instance stored in the object.
     * If an instance doesn't exist, it creates a new one.
     *
     * @return \Octagon\Core\Registry Returns Registry instance.
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get Request instance.
     *
     * @return \Octagon\Http\Request
     */
    public function getRequest()
    {
        if ($this->request === null) {
            $this->request = Request::capture();
        }
        return $this->request;
    }

    /**
     * Get Config instance.
     *
     * @return Octagon\Config\Config
     */
    public function getConfig()
    {
        if ($this->config === null) {
            $this->config = new Config( APP_CONFIG_DIRECTORY . DIRECTORY_SEPARATOR . APP_CONFIG_FILE );
        }
        return $this->config;
    }

    /**
     * Get 404 Not Found message.
     *
     * @return string
     */
    public function get404()
    {
        $failSafe = '<h1>404 Not Found</h1><p>The resource you requested was not found at this URL.</p>';
        $content = $this->getContentByTemplate('not_found_template', $failSafe);
        $response = new Response($content, Response::HTTP_NOT_FOUND);
        return $response;
    }

    /**
     * Get fatal error message.
     *
     * @return string
     */
    public function get503()
    {
        $failSafe = '<h1>503 Service Unavailable</h1><p>Something went wrong.</p>';
        $content = $this->getContentByTemplate('fatal_error_template', $failSafe);
        $response = new Response($content, Response::HTTP_SERVICE_UNAVAILABLE);
        return $response;
    }

    /**
     * Render the given template or return the fail-safe message if the template doesn't exist.
     *
     * @param string $template Template to render.
     * @param string $failSafe Message to return if template doesn't exist.
     *
     * @return \Octagon\Http\Response
     */
    public function getContentByTemplate($template, $failSafe)
    {
        $registry = Registry::getInstance();
        $config = $registry->getConfig();
        if ($config->has($template)) {
            $view = new View($config->get($template));
            $content = $view->render();
        }
        else {
            $content = $failSafe;
        }
        return $content;
    }

    /**
     * Converts a file to URI data in base64
     *
     * @param string $mime The MIME for the data.
     * @param string $path Path of file to convert. This can be a local file or URL.
     *
     * @return $string File contents formatted as URI data.
     */
    public static function toUriData($mime, $path)
    {
        // TODO: Parse the MIME format; if invalid return null.
        $contents = file_get_contents($path);
        $base64_content = base64_encode($contents);
        $uriData = 'data:' . $mime . ';base64,' . $base64_content ;
        return $uriData;
    }

}
