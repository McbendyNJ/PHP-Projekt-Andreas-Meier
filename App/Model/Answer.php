<?php

namespace App\Model;
use App\Gateway\AnswerGateway;

Class Answer {
    private int $id = 0;
    private int $questionid = 0;
    private string $answer;
    private int $solution;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id; 
    }

    public function setQuestionId(int $questionid) {
        $this->questionid = $questionid;
    }

    public function getQuestionId(): int {
        return $this->questionid;
    }

    public function setAnswer(string $answer) {
        $this->answer = $answer;
    }

    public function getAnswer(): string {
        return $this->answer;
    }

    public function setSolution(int $solution) {
        $this->solution = $solution;
    }

    public function getSolution(): int {
        return $this->solution;
    }

    public function save() {
        $gateway = new AnswerGateway();
        if($this->id > 0) {
            $gateway->update($this->id, $this->getAttributesAsAssociativeArray());
        } else {
            $this->id = $gateway->insert($this->getAttributesAsAssociativeArray());
        }
        
    }

    public function delete() {
        $gateway = new AnswerGateway();
        $gateway->delete($this->id);
    }

    public static function all(): array {
        $gateway = new AnswerGateway();
        $answers = [];

        $dbAnswers = $gateway->all();

        foreach( $dbAnswers as $dbAnswer ) {
            $answer = self::create($dbAnswer);
            $answers[] = $answer;

        }

        return $answers;
    }

    public static function findById(int $id): ?Answer {   // : Book = gibt auf jedenfall ein wert aus, : ?Book = gibt entweder ein wert aus oder null.
        $gateway = new AnswerGateway();

        $tmpAnswer = $gateway->findById($id);
        $answer = null;

        

        if( $tmpAnswer ) {
            $answer = self::create($tmpAnswer);
        }

        return $answer;
    }

    public static function findByQuestionId(int $id): array {
        $questions = Question::findByQuizId($id);
        $gateway = new AnswerGateway();
        $answers = [];

        foreach($questions as $question) {
            $questionid = $question->getId(); 
       
            $dbAnswers = $gateway->findByFields([
                "QuestionId" => $questionid
            ]);
           
            foreach($dbAnswers as $dbAnswer) {
            $answer = self::create($dbAnswer);
            $answers[] = $answer;
            }
        }

        return $answers;
    }

    private static function create (array $tmpAnswer): Answer {
        $answer = new Answer();
        $answer->id = $tmpAnswer["Id"];
        $answer->questionid = $tmpAnswer["QuestionId"];
        $answer->answer = $tmpAnswer["Answer"];
        $answer->solution = $tmpAnswer["Solution"];

        return $answer;
    }

    private function getAttributesAsAssociativeArray(): array {
        return [
            "QuestionId" => $this->questionid,
            "Answer" => $this->answer,
            "Solution" => $this->solution
        ];
    }

}







?>