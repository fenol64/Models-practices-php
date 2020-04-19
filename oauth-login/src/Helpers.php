<?php 

function site(String $param = null): string
{
    if ($param && !empty(SITE[$param])) {
        return SITE[$param];    
    } else {
        return SITE["base_url"];
    }
    
}

function asset(String $path, $time = true): string
{
    $file = SITE['base_url'] . "/views/assets/{$path}"; 
    $dir_file = dirname(__DIR__, 1) . "/views/assets/{$path}";

    if ($time && file_exists($dir_file)) {
        $file .= "?time=" . filemtime($dir_file);
    }

    return $file;
}

function images (string $imgUrl): string
{
    return "https://via.placeholder.com/1200x628/0984e3/FFF/?text={$imgUrl}";
}


function flash(string $type = null, string $message = null):?string
{
   if ($type && $message) {
       $_SESSION["flash"] = [
            "type" => $type,
            "message" => $message
       ];
   }

    if (!empty($_SESSION["flash"]) && $flash = $_SESSION["flash"]) {
       return "<div class=\" message {$flash["type"]} \">{$message}</div>";
    }

    return null;
}