<?php

    define("BASE_URL", "https://www.localhost:8181/Projects/Blog");
    define('TITLE', 'BLOG');

    define("DATA_LAYER_CONFIG", [
        "driver" => "mysql",
        "host" => "localhost",
        "port" => "3306",
        "dbname" => "blog",
        "username" => "root",
        "passwd" => "",
        "options" => [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_CASE => PDO::CASE_NATURAL
        ]
    ]);

    function url (string $uri = null): string
    {
        if ($uri){
            return BASE_URL . "/{$uri}";
        }else{
            return BASE_URL;
        }
    }