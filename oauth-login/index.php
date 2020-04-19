<?php

ob_start();
session_start();

require_once __DIR__ . "/vendor/autoload.php";

use CoffeeCode\Router\Router;

$router = new Router(site());
$router->namespace('Source\App');

$router->group(null);
$router->get("/", "Web:login", "web.login");
$router->get("/cadastrar", "Web:register", "web.register");
$router->get("/recuperar", "Web:forget", "web.forget");
$router->get("/senha/{email}/{forget_id}", "Web:reset", "web.reset");

$router->group('ooops');
$router->get("/{errcode}", "Web:error", "web.error");


$router->dispatch();


if ($router->error()){
    $router->redirect("web.error", ["errcode" => $router->error()]);
}

ob_end_flush();