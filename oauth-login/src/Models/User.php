<?php 
namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use Exception;

/**
 * User
 */
class User extends DataLayer
{     
     /**
      * __construct
      *
      * @return void
      */
     function __construct() {
        parent::__construct("oauth", [
            "first_name",
            "last_name",
            "email",
            "passwd"
        ]);
    }
    
    /**
     * save
     *
     * @return bool
     */
    
    public function save(): bool
    {
        if (!$this->validateEmail() || !$this->validadePass() || !parent::save()) {
            return false;
        }
        
        return true;
    }
    
    /**
     * validateEmail
     *
     * @return bool
     */
    public function validateEmail(): bool
    {
        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {

            $this->fail = new Exception("Informe um e-mail válido");

            return false;
        }

        $userByEmail = null;

        if (!$this->id) {
            $userByEmail = $this->find("email = :email", "email={$this->email}")->count;
        } else {
            $userByEmail = $this->find("email = :email AND id != :id", "email={$this->email}&id={$this->id}")->count;
        }

        if ($userByEmail) {
            $this->fail = new Exception("O e-mail informado já está em uso.");
            return false;
        }

        return true;
    }
    
    /**
     * validadePass
     *
     * @return bool
     */
    public function validadePass():bool
    {
        if (empty($this->passwd) || strlen($this->passwd) < 5) {
            $this->fail = new Exception("Informe uma senha com pelo menos 5 caracteres.");
            return false;
        }

        if (password_get_info($this->passwd)["algo"]) {
            return true;
        }
        
        $this->passwd = password_hash($this->passwd, PASSWORD_DEFAULT);
        return true;
    }

}
