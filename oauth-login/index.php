<?php

ob_start();
session_start();

require_once __DIR__ . "/vendor/autoload.php";

use CoffeeCode\Router\Router;

$router = new Router(site());
$router->namespace('Source\App');

/*
 * WEB
 */

$router->group(null);
$router->get("/", "Web:login", "web.login");
$router->get("/cadastrar", "Web:register", "web.register");
$router->get("/recuperar", "Web:forget", "web.forget");
$router->get("/senha/{email}/{forget_id}", "Web:reset", "web.reset");

/*
 * AUTH
 */
$router->group(null);
$router->post("/login", "Auth:login", "auth.login");
$router->post("/register", "Auth:register", "auth.register");
$router->post("/forget", "Auth:forget", "auth.forget");
$router->post("/reset", "Auth:reset", "auth.reset");

/*
 * SOCIAL
 */

$router->group(null);
$router->get("/facebook", "Auth:facebook", "auth.facebook");
$router->get("/google", "Auth:google", "auth.google");

/*
 * PROFILE
 */
$router->group("me");
$router->get("/", "Dashboard:home", "dashboard.home");
$router->get("/sair", "Dashboard:logoff", "dashboard.logoff");


/*
 * FOR ERRORS
 */
$router->group('ooops');
$router->get("/{errcode}", "Web:error", "web.error");

// executins routes
$router->dispatch();

if ($router->error()){
    $router->redirect("web.error", ["errcode" => $router->error()]);
} 

ob_end_flush();