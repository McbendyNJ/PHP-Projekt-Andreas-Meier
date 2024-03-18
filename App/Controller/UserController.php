<?php

namespace App\Controller;

use App\Model\User;


class UserController extends DefaultController {

    public function edit(int $id) {
        $user = User::findById($id);
        if ($user->getId() == $_SESSION["user"]->getId()) {
            $this->render("user-form.html.twig", [
                "user" => $user
            ]);
        } else {
            $this->redirect("/home");
        }
    }

    public function update(int $id, array $data) {
        
        $user = User::findById($id);
        $username = $user->getUsername();

        $rules = [
            "Firstname" => "required|alphanumeric",
            "Lastname" => "required|alphanumeric",
            "Email" => "required",
            "Username" => "required|alphanumeric",
            "currentPassword" => "required"
        ];

        $this->validate($data,$rules);

        $isPasswordCorrect = $user->passwordVerify($data["currentPassword"], $username);

        $user->setFirstname($data["Firstname"]);
        $user->setLastname($data["Lastname"]);
        $user->setEmail($data["Email"]);
        $user->setUsername($data["Username"]);
        if($isPasswordCorrect == true) {
            $user->setPassword($data["newPassword"]);
            $this->redirect("/");
        } elseif ($data["newPassword"] == "" and $isPasswordCorrect == false) {
            $user->setPassword($data["currentPassword"]);
            $this->redirect("/");

        } else {
            $this->errorRedirect("/user/" . $user->getId(), "Dein aktuelles Passwort wurde nicht korrekt eingegeben!");
        }
        $user->setPassword($data["newPassword"]);
        $user->save();
    }

    public function logout() {
        $loginController = new LoginController();
        $loginController->logout();
    }

    public function delete(int $id):void {
        $user = User::findById($id);
        $user->delete();
    
        $this->logout();
    }

}


?>