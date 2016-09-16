<?php

function set_reporting()
{
    if (DEVELOPMENT_ENVIRONMENT === true) {
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
    }
    else {
        error_reporting(E_ALL);
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', STORAGE_LOGS_DIRECTORY . DIRECTORY_SEPARATOR . ERROR_LOG_FILE);
    }
}
