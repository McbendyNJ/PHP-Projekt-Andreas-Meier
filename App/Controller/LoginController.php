<?php

namespace App\Controller;

use App\Model\User;

class LoginController extends DefaultController  {

    public function index() {
        
        $this->render("login.html.twig");
    }

    public function login(array $values) {
        $user = User::findByUsernameAndPassword($values['Username'], $values['Password']);

        if ($user) {
            $_SESSION["user"] = $user; //$user
            
            
        } else {
            return $this->render("login.html.twig", [
                "error" => "Username oder Passwort ist falsch!"
            ]);
        }

        $this->redirect("/");
    }

    public function logout()
    {
        session_destroy();
        $this->redirect("/login");
    }
}