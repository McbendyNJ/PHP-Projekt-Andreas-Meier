<?php

namespace App\Controller;

use App\Model\Question;


class QuestionController extends DefaultController {

    public function index() {
        $questions = Question::all();
        

        return $this->render("questions.html.twig", [
            "questions" => $questions
        ]);
    }

    public function store(array $data) {

        
        $rules = [
            "Question" => "required"
        ];

        $this->validate($data, $rules);
        

        
        
        $question = new Question();
        $question->setQuizId($data["QuizId"]);
        $question->setQuestion($data["Question"]);
        $question->save();

        $this->redirect("/questions");

    }

    public function create() {
        $this->render("questions-form.html.twig");
    }

    public function edit(int $id) {
        $question = Question::findById($id);
        if ($question->getQuizId() == $_SESSION["quizid"]) {
            $this->render("questions-form.html.twig", [
                "question" => $question
            ]);
        } else {
            $this->errorRedirect("/questions", "Du hast keine Berechtigung!");
        }
    }

    public function update(int $id, array $data) {

        $question = Question::findById($id);
        $question->setQuizId($data["QuizId"]);
        $question->setQuestion($data["Question"]);
        $question->save();

        $this->redirect("/questions");
    }

    public function delete(int $id):void {
        $question = Question::findById($id);
        $question->delete();
    
        $this->redirect("/questions");
    }

    public function addAnswers(int $id) {
        $question = Question::findById($id);
        $_SESSION["questionid"] = $question->getId();
        $_SESSION["questionQ"] = $question->getQuestion();
       

        $this->redirect("/answers");
        
    }

}


?>