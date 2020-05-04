<?php 
namespace Source\App;
use Source\Models\User;
use Source\Support\Email;
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

        $user = (new User())->find("email = :e", "e={$email}")->fetch();
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


    public function forget($data)
    {
        $email = filter_var($data["email"], FILTER_VALIDATE_EMAIL);
    
        if (!$email) {
            echo $this->ajax("message", [
                "type" => "alert",
                "message" => "Informe seu e-mail corretamente"
            ]);
            return;
        }

        $user = (new User())->find("email = :e", "e={$email}")->fetch();

        if (!$user) {
            echo $this->ajax("message", [
                "type" => "error",
                "message" => "O e-mail informado não é cadastrado"
            ]);
            return;
        }

        $user->forget = (md5(uniqid(rand(), true)));
        $user->save();

        $_SESSION["forget"] = $user->id;


        $email = new Email();
        
        $send = $email->add(
            "Recupere sua senha | ". site("name"),
            $this->view->render("emails/recover", [
                "user" => $user,
                "link" => $this->router->route("web.reset", [
                    "email" => $user->email,
                    "forget" => $user->forget
                ])
            ]),
            "{$user->first_name} {$user->last_name}",
            $user->email
        )->send();
        
        if (!$send) {
            flash("error", $email->error()->getMessage());
        }else{
            flash("success", "enviamos um link de recuperação para seu email");
        }
        

        echo $this->ajax("redirect", [
            "url" => $this->router->route("web.forget")
        ]);
    }


    public function reset($data): void
    {
        if (!empty($_SESSION["forget"]) || !$user = (new User())->findById($_SESSION["forget"])) {
            flash("error", "Não foi possível recuperar, tente novamente");
            echo $this->ajax("redirect", [
                "url" => $this->router->route("web.forget")
            ]);
            return;
        }

        if (empty($data["password"]) || empty($data["password_re"])) {
            echo $this->ajax("message", [
                "type" => "alert" ,
                "message" => "Informe as senhas"
            ]);
            return;
        }

        if ($data["password"] != $data["password_re"]) {
            echo $this->ajax("message", [
                "type" => "error" ,
                "message" => "Digite as senhas iguais"
            ]);
            return;
        }

        $user->passwd = $data["password"];
        $user->forget = null;
        if (!$user->save()) {
            echo $this->ajax("message", [
                "type" => "error" ,
                "message" => $user->fail()->getMessage()
            ]);
            return;
        }

        unset($_SESSION["forget"]);
        flash("success", "Sua senha foi alterada com sucesso");

        echo $this->ajax("redirect", [
            "url" => $this->router->route("web.login")
        ]);
        return;
    }
}
