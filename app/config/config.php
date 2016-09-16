<?php

/**
 * This file contains app settings and configurations.
 */

return [

    /*
    *------------------------------------------------------------------------------
    * Set the base URL
    *------------------------------------------------------------------------------
    *
    */

    "base_url" => "http://127.0.0.1",

    /*
    *------------------------------------------------------------------------------
    * Set the app name
    *------------------------------------------------------------------------------
    *
    */

    "app_name" => "Geometry",

    /*
    *------------------------------------------------------------------------------
    * Set the name of default not-found template
    *------------------------------------------------------------------------------
    *
    */

    "not_found_template" => "error.404",

    /*
    *------------------------------------------------------------------------------
    * Set the name of default error template
    *------------------------------------------------------------------------------
    *
    * Let the user know that something went wrong internally with a neat display.
    * Geometry requires the name of the default error template that is to be
    * displayed by the framework upon the occurrence of an internal error, such as
    * a missing controller or a missing file. The occurrence of a such an error may
    * be blamed on the application developer or the framework developer. Geometry
    * requires that you set this to an existing template.
    *
    */

    "fatal_error_template" => "error.503"

];
