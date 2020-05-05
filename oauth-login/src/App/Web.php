<?php

namespace Source\App;

use Source\Models\User;

class Web extends Controller
{

    public function __construct($router)
    {

        parent::__construct($router);


        if (!empty($_SESSION["user"])) {
            $this->router->redirect("dashboard.home");
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

        $social_user = (!empty($_SESSION["facebook_auth"]) ? unserialize($_SESSION["facebook_auth"]) : 
                    (!empty($_SESSION["google_auth"]) ? unserialize($_SESSION["google_auth"]) : null));

        $formUser = new \stdClass();

        if ($social_user) {
            $formUser->first_name = $social_user->getFirstName();
            $formUser->last_name = $social_user->getLastName();
            $formUser->email = $social_user->getEmail();
        } else {
            $formUser->first_name = null;
            $formUser->last_name = null;
            $formUser->email = null;
        }

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

    
    /**
     * reset
     *
     * @param  mixed $data
     * @return void
     */


    public function reset($data): void
    {

        if (empty($_SESSION["forget"])) {
            flash("info", "Informe seu Email para recuperar");
            $this->router->redirect("web.forget");
        }

        $email = filter_var($data["email"], FILTER_VALIDATE_EMAIL);
        $forget = filter_var($data["forget"], FILTER_DEFAULT);

        if (empty($email || empty($forget))) {
            flash("error", "Não foi possível recuperar, tente novamente");
            $this->router->redirect("web.forget");
        }

        $user = (new User)->find("email = :e and forget = :f", "e={$email}&f={$forget}")->fetch();

        if (!$user) {
            flash("error", "Não foi possível recuperar, tente novamente");
            $this->router->redirect("web.forget");
        }

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
