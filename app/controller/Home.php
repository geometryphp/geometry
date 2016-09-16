<?php

use Octagon\View\View;
use Octagon\Http\Response;

class Home
{
    public function landing()
    {
        $view = new View("home.landing");
        $content = $view->render();
        return new Response($content);
    }
}
