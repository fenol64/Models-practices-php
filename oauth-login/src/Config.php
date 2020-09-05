<?php 

    define('SITE', [
        "name" => "Oauth login",
        "desc" => "mvc oauth login test",
        "domain"=> "test.com",
        "locale" => "pt_BR",
        "base_url" => "https://localhost/Projects/Models-practices-php/oauth-login"
    ]);


    /*
        MINIFY
    */


    if ($_SERVER["SERVER_NAME"] == "localhost") {
        require dirname(__FILE__) . "/Minify.php";
    }

    /*
        DATABASE CONNECTION
    */

    define("DATA_LAYER_CONFIG", [
        "driver" => "mysql",
        "host" => "localhost",
        "port" => "3306",
        "dbname" => "examples",
        "username" => "root",
        "passwd" => "",
        "options" => [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_CASE => PDO::CASE_NATURAL
        ]
    ]);

    define('SOCIAL', [
        "facebook_page" => "nox-0202",
        "facebook_author"=> "nox0102",
        "facebook_appId" => "923770348061346",
        "twitter_creator" => "@feferdinando03",
        "twitter_site" => "@feferdinando03",
    ]);


    /*
    *   MAIL CONNECT
    */

    define('MAIL', [
        "host" => "smtp.gmail.com",
        "port" => "587",
        "user" => "fernandinho.nco@gmail.com",
        "passwd" => "03F18L19F",
        "from_name" => "Fernando Oliveira",
        "from_email" => "fernandinho.nco@gmail.com"
    ]);

    /*
    *   FACEBOOK OAUTH | social login
    */

    define('FACEBOOK_LOGIN', [
        "clientId" => "923770348061346",
        "clientSecret" => "fbddfac892f8b608943b46054d26fd52",
        "redirectUri" => SITE["base_url"] . "/facebook",
        "graphApiVersion" => "v6.0"
    ]);

    /*
    *   GOOGLE OAUTH | social login
    */

    define('GOOGLE_LOGIN', [
        "clientId" => "689804817498-o0bapdpr4k736q7ftb6b0lvet80u9cqa.apps.googleusercontent.com",
        "clientSecret" => "qSlvTrBtS_6PqJSWSf0skpqc",
        "redirectUri" => SITE["base_url"] . "/google"
    ]);
    

    


    