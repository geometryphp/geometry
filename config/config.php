<?php

/**
 * This file contains app settings and configurations.
 */

return [

    /*
    *---------------------------------------------------------------------------
    * Set the default host
    *---------------------------------------------------------------------------
    *
    */

    "host" => "127.0.0.1",

    /*
    *---------------------------------------------------------------------------
    * Set the default scheme
    *---------------------------------------------------------------------------
    *
    */

    "https" => false,

    /*
    *---------------------------------------------------------------------------
    * Set the base URL (deprecated)
    *---------------------------------------------------------------------------
    *
    */

    "base_url" => "http://127.0.0.1",

    /*
    *---------------------------------------------------------------------------
    * Set the app name
    *---------------------------------------------------------------------------
    *
    */

    "app_name" => "Geometry",

    /*
    *---------------------------------------------------------------------------
    * Set the site title
    *---------------------------------------------------------------------------
    *
    */

    "site_name" => "Geometry",

    /*
    *---------------------------------------------------------------------------
    * Set the name of the default template for a 404 error
    *---------------------------------------------------------------------------
    *
    * ...
    *
    */

    "404_template" => "error:404.html.twig",

    /*
    *---------------------------------------------------------------------------
    * Set the name of the default template for a 503 error
    *---------------------------------------------------------------------------
    *
    * Let the user know that something went wrong internally with a neat display.
    * Geometry requires the name of the default error template that is to be
    * displayed by the framework upon the occurrence of an internal error, such as
    * a missing controller or a missing file. The occurrence of a such an error may
    * be blamed on the application developer or the framework developer. Geometry
    * requires that you set this to an existing template.
    *
    */

    "503_template" => "error:503.html.twig"

];
