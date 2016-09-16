<?php

/**
 * This file contains framework settings and configurations.
 */

/*
*------------------------------------------------------------------------------
* Development environment
*------------------------------------------------------------------------------
*
* If you're in a development environment, turn on direct error reporting by
* setting the following to `true`. If you're in a production environment,
* turn off direct error reporting by setting the following to `false` and let
* errors be logged to the error log file.
*
*/

define ("DEVELOPMENT_ENVIRONMENT", true);

/*
*------------------------------------------------------------------------------
* Setup global directory paths
*------------------------------------------------------------------------------
*
*  ...
*
*/

define ("APP_DIRECTORY", ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . "app");
define ("APP_CONFIG_DIRECTORY", APP_DIRECTORY . DIRECTORY_SEPARATOR . "config");
define ("APP_CONTROLLER_DIRECTORY", APP_DIRECTORY . DIRECTORY_SEPARATOR . "controller");
define ("APP_MODEL_DIRECTORY", APP_DIRECTORY . DIRECTORY_SEPARATOR . "model");
define ("APP_TEMPLATE_DIRECTORY", APP_DIRECTORY . DIRECTORY_SEPARATOR . "template");
define ("APP_VIEW_DIRECTORY", APP_DIRECTORY . DIRECTORY_SEPARATOR . "view");
define ("BOOTSTRAP_DIRECTORY", ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . "bootstrap");
define ("STORAGE_DIRECTORY", ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . "storage");
define ("STORAGE_CACHE_DIRECTORY", STORAGE_DIRECTORY . DIRECTORY_SEPARATOR . "cache");
define ("STORAGE_LOGS_DIRECTORY", STORAGE_DIRECTORY . DIRECTORY_SEPARATOR . "logs");
define ("PUBLIC_DIRECTORY", ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . "public");
define ("VENDOR_DIRECTORY", ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . "vendor");

/*
*------------------------------------------------------------------------------
* Set the default file extension used by all framework and application files.
*------------------------------------------------------------------------------
*
* Geometry uses a single file extension for all framework and application files.
*
*/

define ("FILE_EXTENSION", ".php");

/*
*------------------------------------------------------------------------------
* Set the name of the app config file
*------------------------------------------------------------------------------
*
* ...
*
*/

define ("APP_CONFIG_FILE", "config".FILE_EXTENSION);

/*
*------------------------------------------------------------------------------
* Set the name of the app config file
*------------------------------------------------------------------------------
*
* ...
*
*/

define ("APP_ROUTE_FILE", "route".FILE_EXTENSION);

/*
*------------------------------------------------------------------------------
* Set the name of the framework's error log file.
*------------------------------------------------------------------------------
*
* Geometry avoids sending out internal error messages back to the client
* because, frankly, it's none of his business. Geometry instead logs internal
* errors to a log file, so that you can know what went wrong inside the
* framework. Provide the filename you'd prefer below.
*
*/

define ("ERROR_LOG_FILE", "error.log");

/*
*------------------------------------------------------------------------------
* Set the name of the application's debug log file.
*------------------------------------------------------------------------------
*
* ...
*
*/

define ("DEBUG_LOG_FILE", "debug.log");
