<?php

/**
 * The front controller.
 *
 * The front controller serves as the single point of entry for all incoming
 * requests [except for existing files and directories] and allows the application
 * to delegate a controller the task of presenting a response.
 *
 * The front controller retrieves the request and passes it to the kernel.
 * The kernel handles the request and a response is returned from the kernel.
 * Finally the front controller sends back the response to the client.
 */

use Octagon\Core\Registry;
use Octagon\Core\Kernel;

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

    // Get the path of our files on the server.
    define ("ENVIRONMENT_PATH", realpath(dirname("..\..")));

    // Require global configurations and bootstrap the framework
    require ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . "config.php";
    require BOOTSTRAP_DIRECTORY . DIRECTORY_SEPARATOR . "autoload.php";

    // Require error reporting
    require BOOTSTRAP_DIRECTORY . DIRECTORY_SEPARATOR . "set_reporting.php";

    // Run the application.
    set_reporting();
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

    // Shutdown the application
    Kernel::shutdown();
}
