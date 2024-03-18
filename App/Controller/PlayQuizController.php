<?php

namespace App\Controller;

use App\Model\Quiz;

class PlayQuizController extends DefaultController {

    public function index() {
        $quizes = Quiz::all();
        //$user = $_SESSION["user"];

        return $this->render("all-quizes.html.twig", [
            "quizes" => $quizes
            //"user" => $user
        ]);
    }
    /*
    public function store(array $data) {
        

        $quiz = new Quiz();
        $quiz->setUserid($data["UserId"]);
        $quiz->setDescription($data["Description"]);
        $quiz->setQuizname($data["Quizname"]);
        $quiz->save();

        

        $this->redirect("/quizes");

    }

    public function create() {
        $this->render("quizes-form.html.twig");
    }

    public function edit(int $id) {
        $quiz = Quiz::findById($id);

        $this->render("quizes-form.html.twig", [
            "quiz" => $quiz
        ]);
    }

    public function update(int $id, array $data) {
        

        $quiz = Quiz::findById($id);
        $quiz->setUserid($data["UserId"]);
        $quiz->setDescription($data["Description"]);
        $quiz->setQuizname($data["Quizname"]);
        $quiz->save();

        $this->redirect("/quizes");
    }

    public function delete(int $id):void {
        $quiz = Quiz::findById($id);
        $quiz->delete();
    
        $this->redirect("/quizes");
    } */
    
    

}


?>