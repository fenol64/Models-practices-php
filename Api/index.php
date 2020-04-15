<?php

use CoffeeCode\Router\Router;

require_once __DIR__ . "/vendor/autoload.php";

$router = new Router(BASE_URL);

$router->namespace('Source\App');

$router->group('clients');
$router->get('/{page}', 'Users:read');
$router->get('/find/{idUser}', 'Users:findUser');


$router->group('ooops');
$router->get('/{errcode}', "Users:error");

$router->dispatch();

if ($router->error()) {
    $router->redirect("/clients/1");
    //$router->redirect("/ooops/{$router->error()}");
}
