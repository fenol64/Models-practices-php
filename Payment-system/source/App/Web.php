<?php


namespace source\App;
use League\Plates\Engine;

class Web
{

    private $view;

    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . "/../../Views", "php");
    }

    public function home(): void
    {
        echo $this->view->render('home', [
            "title" => "Home | ". TITLE
        ]);
    }

    public function payment($data): void
    {
        echo $this->view->render('payment', [
            "title" => "Payment | ". TITLE,
            "amount" => $data["amount"]
        ]);
    }

    public function error($data)
    {
        echo "<h1>oooops erro: {$data['errcode']}</h1>";

    }
}