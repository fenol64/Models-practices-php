<?php


namespace Source\App;
use League\Plates\Engine;
use Source\Models\User;

class Web
{

    private $view;

    public function __construct()
    {
        $this->view = Engine::create(__DIR__.'/../../views', 'php');
    }

    public function home (): void
    {
        $users = (new User())->find()->fetch(true);

        echo $this->view->render('home', [
            "title" => "Home | ". TITLE,
            "users" => $users
        ]);
    }
}