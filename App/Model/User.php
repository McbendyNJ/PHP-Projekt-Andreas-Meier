<?php

namespace App\Model;
use App\Gateway\UserGateway;

Class User {
    private int $id = 0;
    private string $firstname;
    private string $lastname;
    private string $username;
    private string $email;
    private string $password;

    public function getId(): int {
        return $this->id;
    }

    public function getFirstname() :string {
        return $this->firstname;
    }

    public function setFirstname(string $firstname) {
        $this->firstname = $firstname;
    }

    public function getLastname(): string {
        return $this->lastname;
    }

    public function setLastname(string $lastname) {
        $this->lastname = $lastname;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function setUsername(string $username) {
        $this->username = $username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password) {
        $this->password =  password_hash($password, PASSWORD_BCRYPT);
    }

    public function save() {
        $gateway = new UserGateway();
        if($this->id > 0) {
            $gateway->update($this->id, $this->getAttributesAsAssociativeArray());
        } else {
            $this->id = $gateway->insert($this->getAttributesAsAssociativeArray());
        }
        
    }

    public function delete() {
        $gateway = new UserGateway();
        $gateway->delete($this->id);
    }

    public static function all(): array {
        $gateway = new UserGateway();
        $users = [];

        $dbUsers = $gateway->all();

        foreach( $dbUsers as $dbUser ) {
            $user = new User();
            $user->id = $dbUser["Id"];
            $user->firstname = $dbUser["Firstname"];
            $user->lastname = $dbUser["Lastname"];
            $user->username = $dbUser["Username"];
            $user->email = $dbUser["Email"];
            $user->password = $dbUser["Password"];

            $users[] = $user;
        }

        return $users;
    }

    public static function findById(int $id): ?User {   // : Book = gibt auf jedenfall ein wert aus, : ?Book = gibt entweder ein wert aus oder null.
        $gateway = new UserGateway();

        $tmpUser = $gateway->findById($id);
        $user = null;

        

        if( $tmpUser ) {
            $user = self::create($tmpUser);
        }

        return $user;
    }

    public static function findByUsernameAndPassword(string $username, string $password): ?User {
        $gateway = new UserGateway();
        $hash = password_hash($password, PASSWORD_BCRYPT);  
        $tmpUser = $gateway->findByFields([
            "Username" => $username
        ]);

        $user = null;

        if (count($tmpUser) === 1) {
            $userArray = $tmpUser[0];


            if( password_verify($password, $userArray["Password"]) ) {
                $user = new User();
                $user->id = $userArray["Id"];
                $user->firstname = $userArray["Firstname"];
                $user->lastname = $userArray["Lastname"];
                $user->username = $userArray["Username"];
                $user->email = $userArray["Email"];
            }

            return $user;
        }

    }

    public static function passwordVerify(string $password, string $username): bool {
        $gateway = new UserGateway();
        $hash = password_hash($password, PASSWORD_BCRYPT);  
        $tmpUser = $gateway->findByFields([
            "Username" => $username
        ]);

        $user = null;

        if (count($tmpUser) === 1) {
            $userArray = $tmpUser[0];

            if( password_verify($password, $userArray["Password"]) ) {
                return true;
            }
        }
        return false;

    }

    private static function create (array $tmpUser): User {
        $user = new User();
        $user->id = $tmpUser["Id"];
        $user->firstname = $tmpUser["Firstname"];
        $user->lastname = $tmpUser["Lastname"];
        $user->username = $tmpUser["Username"];
        $user->email = $tmpUser["Email"];
        $user->password = $tmpUser["Password"];

        return $user;
    }

        private function getAttributesAsAssociativeArray(): array {
            return [
                "Firstname" => $this->firstname,
                "Lastname" => $this->lastname,
                "Username" => $this->username,
                "Email" => $this->email,
                "Password" => $this->password
            ];
        }

    
}




?>