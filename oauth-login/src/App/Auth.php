<?php 

namespace Source\App;
use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\GoogleUser;
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

        $this->socialValidate($user);
        
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

        $this->socialValidate($user);

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
    
    /**
     * facebook
     *
     * @return void
     */
    public function facebook(): void
    {
        $facebook = new Facebook(FACEBOOK_LOGIN);
        $error = filter_input(INPUT_GET, "error", FILTER_SANITIZE_STRIPPED);
        $code = filter_input(INPUT_GET, "code", FILTER_SANITIZE_STRIPPED);

        if (!$error && !$code) {
            $auth_url = $facebook->getAuthorizationUrl(["scope" => "email"]);
            header("Location: {$auth_url}");
            return;
        }

        if ($error) {
            flash("error", "Não foi possivel logar com o facebook");
            $this->router->route("web.login");
        }

        if ($code && empty($_SESSION["facebook_auth"])) {
            try {
                $token = $facebook->getAccessToken("authorization_code", ["code" => $code]);
                $_SESSION["facebook_auth"] = serialize($facebook->getResourceOwner($token));
            } catch (\Exception $th) {
                flash("error", "Não foi possivel logar com o facebook");
                $this->router->redirect("web.login");             
            }
        }

        /** @var $facebook_user FacebookUser **/
        
        $facebook_user = unserialize($_SESSION["facebook_auth"]);

        $userById = (new User())->find("facebook_id = :id", "id={$facebook_user->getId()}")->fetch();

        if ($userById) {
            unset($_SESSION["facebook_auth"]);
            $_SESSION["user"] = $userById->id;
            $this->router->redirect("dashboard.home");    
        }

        $userByemail = (new User())->find("email = :e", "e={$facebook_user->getEmail()}")->fetch();
        if ($userByemail) {
            flash("info", "Olá {$facebook_user->getFirstName()}, Faça o login para conectar seu facebook");
            $this->router->redirect("web.login");     
        }

        $link = $this->router->route("web.login");
        flash(
            "info", 
            "Olá {$facebook_user->getFirstName()}, se ja tem conta clique em <a href='{$link}'>FAZER LOGIN</a> ou complete seu cadastro"
        );
        $this->router->redirect("web.register");
    }

    public function google(): void
    {
        $google = new Google(GOOGLE_LOGIN);
        $error = filter_input(INPUT_GET, "error", FILTER_SANITIZE_STRIPPED);
        $code = filter_input(INPUT_GET, "code", FILTER_SANITIZE_STRIPPED);

        if (!$error && !$code) {
            $auth_url = $google->getAuthorizationUrl();
            header("Location: {$auth_url}");
            return;
        }

        if ($error) {
            flash("error", "Não foi possivel logar com o Google");
            $this->router->route("web.login");
        }

        if ($code && empty($_SESSION["google_auth"])) {
            try {
                $token = $google->getAccessToken("authorization_code", ["code" => $code]);
                $_SESSION["google_auth"] = serialize($google->getResourceOwner($token));
            } catch (\Exception $th) {
                flash("error", "Não foi possivel logar com o google");
                $this->router->redirect("web.login");             
            }
        }

        /** @var $google_user GoogleUser **/
        
        $google_user = unserialize($_SESSION["google_auth"]);

        $userById = (new User())->find("facebook_id = :id", "id={$google_user->getId()}")->fetch();

        if ($userById) {
            unset($_SESSION["google_auth"]);
            $_SESSION["user"] = $userById->id;
            $this->router->redirect("dashboard.home");    
        }

        $userByemail = (new User())->find("email = :e", "e={$google_user->getEmail()}")->fetch();
        if ($userByemail) {
            flash("info", "Olá {$google_user->getFirstName()}, Faça o login para conectar seu google");
            $this->router->redirect("web.login");     
        }

        $link = $this->router->route("web.login");
        flash(
            "info", 
            "Olá {$google_user->getFirstName()}, se ja tem conta clique em <a href='{$link}'>FAZER LOGIN</a> ou complete seu cadastro"
        );
        $this->router->redirect("web.register");
    }

    public function socialValidate(User $user): void
    {
        if (!empty($_SESSION["facebook_auth"])) {
           /** @var $facebook_user FacebookUser **/
            $facebook_user = unserialize($_SESSION["facebook_auth"]);

            $user->facebook_id = $facebook_user->getId();
            $user->photo = $facebook_user->getPictureUrl();
            $user->save();

            unset($_SESSION["facebook_auth"]);

        }

        if (!empty($_SESSION["google_auth"])) {
            /** @var $google_user GoogleUser **/
             $google_user = unserialize($_SESSION["google_auth"]);
 
             $user->google_id = $google_user->getId();
             $user->photo = $google_user->getAvatar();
             $user->save();
 
             unset($_SESSION["google_auth"]);
 
         }
    }
}
