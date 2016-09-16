<?php

namespace Octagon\View;

use \Octagon\Error;
use \Octagon\Core\Registry;

/**
 * TODO: Replace $args property with a Bag adt so that there is a clean interface to setting and getting args.
 */
class View
{

    /**
     * @var string The template specifier.
     */
    private $specifier;

    /**
     * @var array An array of the variables to be extrapolated into the template.
     */
    private $args;

    /**
     * @var string Stores the rendered view.
     */
    private $content;

    /**
     * Create a new view instance.
     *
     * @param string $specifier
     * @param array $args
     * @return void
     */
    public function __construct($specifier, $args = array())
    {
        $this->setSpecifier($specifier);
        $this->setArgs($args);
    }

    /**
     * Set the template specifier.
     *
     * @param string $specifier
     */
    public function setSpecifier($specifier)
    {
        $this->specifier = $specifier;
    }

    /**
     * Get the template specifier.
     *
     * @return string  Returns the template specifier.
     */
    public function getSpecifier()
    {
        return $this->specifier;
    }

    /**
     * Set the variables.
     *
     * @param array $args
     * @return void
     */
    public function setArgs($args)
    {
        $this->args = $args;
    }
    /**
     * Get the variables.
     *
     * @return array  Returns the variables.
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Set the view content.
     *
     * @param string $content
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get the rendered view.
     *
     * @return string  Returns the rendered view as a string.
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Utility: Decode the template specifier.
     *
     * @param string $specifier
     * @return string Returns a decoded template specifier.
     */
    private static function decodeTemplateSpecifier($specifier)
    {
        $decodedSpecifier = str_replace('.', DIRECTORY_SEPARATOR, $specifier);
        return $decodedSpecifier;
    }

    /**
     * Utility: Normalize template specifier for a viw.
     *
     * @param string $specifier
     * @return string Returns a normalized template specifier.
     */
    private static function normalizeTemplateSpecifier($specifier)
    {
        // Remove surrounding whitespaces and periods
        $specifier = trim($specifier, '\x20.');
        return $specifier;
    }

    /**
     * Is template specifier syntactically valid?
     *
     * @param string $specifier
     * @return bool Returns `TRUE` if valid and `FALSE` if not valid.
     */
    public static function isTemplateSpecifier($specifier)
    {
        // EBNF specification: {directory "."} view
        $syntax = '/^(([\-0-9_A-Za-z])+\.)*[\-0-9_A-Za-z]+$/';

        if (preg_match($syntax, $specifier)) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * DRAFT: Load template
     *
     * Assumes that template specifier is legal.
     * Takes arguments passed to it.
     * NOTE: Was too lazy to replace the template loading in render() with this. Please do so later.
     *
     * I think this function is now useless as when templates are loaded their variables get the scope of the function and not the scope in which it ran. I kinda forgot about that. Its safer to use include().
     */
     public static function loadTemplate($specifier, $args = array())
     {
        $specifier = self::normalizeTemplateSpecifier($specifier);
        $path = self::decodeTemplateSpecifier($specifier);
        $template = APP_TEMPLATE_DIRECTORY . DIRECTORY_SEPARATOR . $path . FILE_EXTENSION;
        include $template;
     }

    /**
     * Get template path by specifier
     */
     public static function path($specifier)
     {
        $specifier = self::normalizeTemplateSpecifier($specifier);
        $path = self::decodeTemplateSpecifier($specifier);
        $templatePath = APP_TEMPLATE_DIRECTORY . DIRECTORY_SEPARATOR . $path . FILE_EXTENSION;
        return $templatePath;
     }

    /**
     * Render view
     *
     * @return string Returns a rendered content as a string if
     */
    public function render()
    {
        // Normalize template specifier
        $specifier = self::normalizeTemplateSpecifier($this->getSpecifier());

        if (self::isTemplateSpecifier($specifier)) {
            // Decode template specifier
            $path = self::decodeTemplateSpecifier($specifier);

            // Extract variables that are to be extrapolated
            extract($this->getArgs());

            // Start output buffering and let's be efficient if we can
            if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
                ob_start('ob_gzhandler');
            }
            else {
                ob_start();
            }

            // Load and render the template
            $template = APP_TEMPLATE_DIRECTORY . DIRECTORY_SEPARATOR . $path . FILE_EXTENSION;
            if (file_exists($template) && !is_dir($template)) {
                require ($template);
            }
            else {
                $registry = Registry::getInstance();
                $response = $registry->get503();
                $response->send();
                trigger_error("Something went wrong: Missing file; Octagon was unable to load template at " . $template, E_USER_ERROR);
            }

            // Get view content from buffer
            $content = ob_get_contents();

            // Turn off output buffering
            ob_end_clean();

            // Return content
            return $content;
        }
        else {
            // push error
            $registry = Registry::getInstance();
            $response = $registry->get503();
            $response->send();
            trigger_error("Something went wrong: Incorrect syntax. The view specifier '" . $specifier . "' is incorrect. Expected correct syntax.", E_USER_ERROR);
        }
    }

}
