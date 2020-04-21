<?php 
namespace Source\App;
use Source\Models\User;

class Auth extends Controller {

    public function __construct($router)
    {
        parent::__construct($router);
    }

    public function register(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        if (in_array("", $data)) {
            echo $this->ajax("message", [
                "type" => "error",
                "message" => "Preencha todos os campos."
            ]);
            return;
        }

        $user = new User();
        $user->first_name = $data["first_name"];
        $user->last_name = $data["last_name"];
        $user->email = $data["email"];
        $user->passwd = $data["passwd"];

        if (!$user->save()) {
            echo $this->ajax("message", [
                "type" => "error",
                "message" => $user->fail()->getMessage()
            ]);
            return;
        }

        $_SESSION["user"] = $user->id;

        echo $this->ajax("redirect", [
            "url" => $this->router->route("dashboard.home")
        ]);
    }


    public function login($data)
    {
        $email = filter_var($data["email"], FILTER_VALIDATE_EMAIL);
        $pass = filter_var($data["passwd"], FILTER_DEFAULT);

        if (!$email || !$pass) {
            echo $this->ajax("message", [
                "type" => "error",
                "message" => "Informe um e-mail e senha para logar"
            ]);
            return;
        }

        $user = (new User)->find("email = :e", "e={$email}")->fetch();
        if (!$user || !password_verify($pass, $user->passwd)) {
            echo $this->ajax("message", [
                "type" => "error",
                "message" => "Email ou senha informados são inválidos"
            ]);
            return;
        }

        $_SESSION["user"] = $user->id;

        echo $this->ajax("redirect", ["url" => $this->router->route("dashboard.home")]);
    }
}