<?php

    require dirname(__FILE__) . "/vendor/autoload.php";

    use CoffeeCode\Router\Router;

    $router = new Router(BASE_URL);

    $router->namespace('source\App');

    $router->group(null);
    $router->get('/', "Web:home");

    $router->dispatch();
