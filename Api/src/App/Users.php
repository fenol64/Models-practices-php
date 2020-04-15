<?php 
    
    namespace Source\App;

    use Source\Models\User;
    

    class Users
    {

        private $clients, $users;

    
        public function go ($json) 
        {
            echo json_encode($json);
        }
        

        public function read()
        {
            $this->users = (new User())->find()->fetch(true);
            
            $this->clients = [];
            
            foreach ($this->users as $user) {
                $this->clients[] = $user->data();
            }

            $this->go($this->clients);

        }
        
        public function findUser($data)
        {

            $this->users = (new User())->find("codigo = :id", "id={$data['idUser']}")->fetch(true);

            $this->clients = [];
            
            foreach ($this->users as $user) {
                $this->clients[] = $user->data();
            }

            $this->go($this->clients);
        }
    }