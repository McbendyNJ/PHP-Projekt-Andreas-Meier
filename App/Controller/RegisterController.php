<?php

namespace App\Controller;

use App\Model\User;

class RegisterController extends DefaultController  {

    public function index() {
        
        $this->render("register-form.html.twig");
    }

    public function store(array $data) {
        
        $rules = [
            "Firstname" => "required|alphanumeric",
            "Lastname" => "required|alphanumeric",
            "Email" => "required",
            "Username" => "required",
            "Password" => "required"
        ];



        $this->validate($data,$rules); 

        $user = new User();
        $user->setFirstname($data["Firstname"]);
        $user->setLastname($data["Lastname"]);
        $user->setEmail($data["Email"]);
        $user->setUsername($data["Username"]);
        $user->setPassword($data["Password"]);
        $user->save();

        $this->redirect("/login");

    }

    public function edit(int $id) {
        $user = User::findById($id);
        if ($user->getId() == $_SESSION["user"]->getId()) {
            $this->render("register-form.html.twig", [
                "user" => $user
            ]);
        } else {
            $this->redirect("/home");
        }
    }

    public function update(int $id, array $data) {

        $user = User::findById($id);
        $user->setFirstname($data["Firstname"]);
        $user->setLastname($data["Lastname"]);
        $user->setEmail($data["Email"]);
        $user->setUsername($data["Username"]);
        $user->setPassword($data["Password"]);
        $user->save();

        $this->redirect("/home");
    }

}