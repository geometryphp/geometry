<?php
// Register Twig autoloader. (The actual setup and configuration of Twig is
// handled by the framework's Registry object. See the Registry for more
// details on the setup of Twig.)
require_once VENDOR_DIRECTORY . SLASH . 'twig' . SLASH . 'twig' . SLASH . 'lib' . SLASH . 'Twig' . SLASH . 'Autoloader.php';
\Twig_Autoloader::register();
