<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Utils\Validator;

class DefaultController {

    private Environment $twig;
    private Validator $validator;

    public function __construct()
    {
        $loader = new FilesystemLoader("views");
        $this->twig = new Environment($loader);
        $this->twig->addGlobal('session', $_SESSION);
        $this->validator = new Validator();
    }

    protected function render(string $file, array $params = []) {
        $_SESSION["template"] = $file;
        $_SESSION["params"] = serialize($params);

        echo $this->twig->render($file, $params);
    }

    protected function redirect(string $path) {
        header("Location: $path");
    }

    public function errorRedirect(string $path, string $error) {
        if($error) {
            $_SESSION['errorRedirect'] = [];
            $_SESSION['errorRedirect']['error'] = $error;
            $_SESSION['errorRedirect_time'] = time();
        }
        header("Location: $path");
    }

    public function successRedirect(string $path, string $success) {
        if($success) {
            $_SESSION['successRedirect'] = [];
            $_SESSION['successRedirect']['success'] = $success;
            $_SESSION['successRedirect_time'] = time();
        }
        header("Location: $path");
    }

    
    protected function validate(array $values, array $rules): bool {
        
        $response = $this->validator->validate($values, $rules);

        if(count($response) > 0) {
            $values["error"] = $response;
            $values = array_merge($values, unserialize($_SESSION["params"]));

            $this->render($_SESSION["template"], $values);
            die();
        }
        
        return true;
    }
    

}



?>