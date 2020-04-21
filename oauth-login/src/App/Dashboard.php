<?php 
namespace Source\App;
use Source\Models\User;

class Dashboard extends Controller
{
    protected $user;

    public function __construct($router)
    {
        parent::__construct($router);

        if (empty($_SESSION["user"]) || !$this->user = (new User())->findById($_SESSION["user"])) {
            unset($_SESSION["user"]);
            flash("info", "Acesso negado. por favor logue-se");
            $this->router->redirect("web.login");
        }
    }

    public function home(): void
    {

        $head = $this->seo->optimize(
            "Bem-vindo(a) {$this->user->first_name}" . site("name"),
            site("desc"),
            $this->router->route("dashboard.home"),
            images("Conta de {$this->user->first_name}") 
        )->render();
        
        echo $this->view->render("theme/dashboard", [
            "head" => $head,
            "user" => $this->user

        ]);
    
    }

    public function logoff(): void
    {
        unset($_SESSION["user"]);
        flash("info", "Você saiu com sucesso!, volte logo {$this->user->first_name}");
        $this->router->redirect("web.login");
    }
}