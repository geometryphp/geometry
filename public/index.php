<?php

/**
 * The front controller, or inverse-of-control container.
 *
 * The front controller serves as the single point of entry for all incoming
 * requests (except request for existing files and directories, as defined in
 * the .htaccess), and allows the application to delegate a controller the task
 * of presenting a response.
 *
 * The front controller retrieves the request and passes it to the kernel.
 * The kernel handles the request; and a response is returned from the kernel.
 * Finally, the front controller sends the response back to the client.
 */

$__time_elapsed = microtime(true);

use Octagon\Core\Registry;
use Octagon\Core\Kernel;
use Octagon\Routing\RouteCompiler;  // FOR DEBUGGING
use Octagon\Routing\Route;  // FOR DEBUGGING
use Octagon\Exception\Handler;

if (phpversion() < 5.4) {
    echo "<h3>Octagon requires PHP/5.4 or greater.</h3>";
    echo "<p>You're currently running PHP/" . phpversion() . ".</p>";
    die();
}
else {
    // Start sessions
    if (session_id() === "") {
        session_start();
    }

    // Get the path of our files on the server
    define ("ENVIRONMENT_PATH", realpath(dirname("..\..")));

    // Require global configurations
    require ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . "config.php";

    //--------------------------------------------------------------------------
    // Bootstrap framework
    //--------------------------------------------------------------------------

    // Require the Composer autoload
    require BOOTSTRAP_DIRECTORY . SLASH . "autoload.php";

    // Require error reporting
    require BOOTSTRAP_DIRECTORY . SLASH . "set_reporting.php";

    // Require Twig
    require BOOTSTRAP_DIRECTORY . SLASH . "twig.php";

    //--------------------------------------------------------------------------
    // Run the application
    //--------------------------------------------------------------------------

    //$handler = new Handler();
    //$handler->register();

    $registry = Registry::getInstance();
    $request = $registry->getRequest();
    $response = Kernel::handle($request);
    $response->send();

    // I call session_write_close at the end of the operation to unlock the
    // session data. This is useful when you have many concurrent
    // connections such as using Ajax operations. Close session to speed up
    // the concurrent connections
    // http://php.net/manual/en/function.session-write-close.php
    session_write_close();


    echo "<script>console.log('Final memory usage: " . memory_get_usage() . " bytes')</script>";
    echo "<script>console.log('Peak memory usage: " . memory_get_peak_usage() . " bytes')</script>";

    // Display execution time
    echo "<script>console.log(\"" . (microtime(true) - $__time_elapsed) . " elapsed seconds\");</script>";

    // Shutdown the application
    Kernel::shutdown();
}
