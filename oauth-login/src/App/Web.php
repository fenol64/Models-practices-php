<?php 

namespace Source\App;

class Web extends Controller {

    public function __construct($router) {

        parent::__construct($router);


        if (!empty($_SESSION["user"])) {
            $this->router->redirect("app.home");
        }
    }


    public function login(): void
    {
        $head = $this->seo->optimize(
            "Faça o login no " . site("name"),
            site("desc"),
            $this->router->route("web.login"),
            images("Login") 
        )->render();
        
        echo $this->view->render("theme/login", [
            "head" => $head
        ]);
    
    }

    public function register(): void
    {
        $head = $this->seo->optimize(
            "Crie seua conta no " . site("name"),
            site("desc"),
            $this->router->route("web.register"),
            images("register") 
        )->render();

        $formUser = new \stdClass();
        $formUser->first_name = null;
        $formUser->last_name = null;
        $formUser->email = null;
        
        echo $this->view->render("theme/register", [
            "head" => $head,
            "user" => $formUser
        ]);
    }


    public function forget(): void
    {
        $head = $this->seo->optimize(
            "recupere sua senha | " . site("name"),
            site("desc"),
            $this->router->route("web.forget"),
            images("forget") 
        )->render();

        echo $this->view->render("theme/forget", [
            "head" => $head,
        ]);
    }


    public function reset($data): void
    {
        $head = $this->seo->optimize(
            "Crie uma nova senha | " . site("name"),
            site("desc"),
            $this->router->route("web.reset"),
            images("Reset") 
        )->render();

        echo $this->view->render("theme/reset", [
            "head" => $head
        ]);
    }


    public function error($data): void
    {
       $error = filter_var($data["errcode"], FILTER_VALIDATE_INT);


       $head = $this->seo->optimize(
            "oops {$error} | " . site("name"),
            site("desc"),
            $this->router->route("web.error"),
            images($error) 
        )->render();

        echo $this->view->render("theme/error", [
            "head" => $head,
            "error" => $error
        ]);

    }

}