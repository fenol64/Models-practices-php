<?php 
    
namespace Source\App;

use CoffeeCode\Optimizer\Optimizer;
use CoffeeCode\Router\Router;
use League\Plates\Engine;

abstract class Controller {
    /*
    @var Engine
    */
    protected $view;
    /*
    @var Router
    */
    protected $router;
    /*
    @var Optimizer
    */
    protected $seo;


    public function __construct($router) {
        $this->router = $router;
        $this->view = Engine::create(dirname(__FILE__ , 3) . "/views/", "php");
        $this->view->addData(["router" => $this->router]);
        
        $this->seo = new Optimizer();
        $this->seo->openGraph(site("name"), site("locale"), "article")
                  ->publisher(SOCIAL["facebook_page"], SOCIAL["facebook_author"])
                  ->twitterCard(SOCIAL["twitter_creator"], SOCIAL["twitter_site"], site("domain"))
                  ->facebook("facebook_appId");
    }


    public function ajax(string $param, array $values): string
    {
        return json_encode(array(
            $param => $values
        ));
    }
    
}