<?php

namespace App\Controller;


use App\Model\Answer;

class AnswerController extends DefaultController {

    public function index() {
        $answers = Answer::all();
        

        return $this->render("answers.html.twig", [
            "answers" => $answers
        ]);
    }

    public function store(array $data) {  
        
        $rules = [
            "Answer" => "required"
        ];

        $this->validate($data,$rules); 
        
        $answer = new Answer();
        $answer->setQuestionId($data["QuestionId"]);
        $answer->setAnswer($data["Answer"]);
        $answer->setSolution($data["Solution"]);
        $answer->save();

        $this->redirect("/answers");

    }

    public function create() {
        $this->render("answers-form.html.twig");
    }

    public function edit(int $id) {
        $answer = Answer::findById($id);
        if ($answer->getQuestionId() == $_SESSION["questionid"]) {
            $this->render("answers-form.html.twig", [
                "answer" => $answer
            ]);
        } else {
            $this->errorRedirect("/answers", "Du hast keine Berechtigung!");
        }
    }

    public function update(int $id, array $data) {

        $answer = Answer::findById($id);
        $answer->setQuestionId($data["QuestionId"]);
        $answer->setAnswer($data["Answer"]);
        $answer->setSolution($data["Solution"]);
        $answer->save();

        $this->redirect("/answers");
    }

    public function delete(int $id):void {
        $answer = Answer::findById($id);
        $answer->delete();
    
        $this->redirect("/answers");
    }

}


?>