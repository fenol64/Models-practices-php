<?php

    require dirname(__FILE__) . "/vendor/autoload.php";

    $router = new \CoffeeCode\Router\Router(BASE_URL);

    $router->namespace('source\App');

    $router->group(null);
    $router->get("/", "Web:home");

    $router->group('payment');
    $router->post('/', "Web:Payment");
    $router->post("/send", "Payment:receivedata");

    /*
     * for errors
     */
    $router->group("ooops");
    $router->get("/{errcode}", "Web:error");

    $router->dispatch();

    if ($router->error()) {
        $router->redirect("/ooops/{$router->error()}");
    }

