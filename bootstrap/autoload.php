<?php

use Octagon\Http\Response;
use Octagon\View\View;
use Octagon\Core\Registry;

spl_autoload_register(function($class) {
    // Setup some vars

    // Convert the namespace to the file path
    $class = preg_replace('{/|\\\}', DIRECTORY_SEPARATOR, $class);

    // Load class from vendor first. Vendor files take priority over other files.
    if (file_exists($path = VENDOR_DIRECTORY . DIRECTORY_SEPARATOR . $class . FILE_EXTENSION)) {
        require_once ($path);
    }
    // Load class from controller directory
    else if (file_exists($path = APP_CONTROLLER_DIRECTORY . DIRECTORY_SEPARATOR . $class . FILE_EXTENSION)) {
        require_once ($path);
    }
    // Load class from model
    else if (file_exists($path = APP_MODEL_DIRECTORY . DIRECTORY_SEPARATOR . $class . FILE_EXTENSION)) {
        require_once ($path);
    }
    /*
    // Load class from view
    else if (file_exists($path = APP_VIEW_DIRECTORY . DIRECTORY_SEPARATOR . $class . FILE_EXTENSION)) {
        require_once ($path);
    }
    */
    else {
        // If no class were found, push error message.
        $registry = Registry::getInstance();
        $response = $registry->get503();
        $response->send();

        // Let the user know what we could not find the class in any of the directories
        $errmsg =
            'The autoloader could not find the class "' . $class . '" ' .
            'as "' . $class . FILE_EXTENSION . '" ' .
            'in the directories "' . VENDOR_DIRECTORY . '" ' .
            'or "' . APP_CONTROLLER_DIRECTORY . '" ' .
            'or "' . APP_MODEL_DIRECTORY . '" ' .
            'or "' . APP_VIEW_DIRECTORY . '"'
        ;
        trigger_error($errmsg, E_USER_ERROR);
    }
});
