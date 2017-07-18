<?php

namespace App\Controller;

use Octagon\View\View;
use Octagon\Http\Response;
use Octagon\Core\Registry;

class Home
{
    public function index()
    {
        $registry = Registry::getInstance();
        $config = $registry->getConfig();
        $view = new View("index.html.twig");
        $view->setArgs([
            "site_title" => $config->get('app_name')
        ]);
        $content = $view->renderTwig();
        return new Response($content);
    }
}
