<?php

namespace App\Model;
use App\Gateway\QuestionGateway;
use App\Gateway\AnswerGateway;
use App\Model\Answer;

Class Question {
    private int $id = 0;
    private int $quizid = 0;
    private string $question;
   

   
    public function setId(int $id) {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setQuizId(int $quizid) {
        $this->quizid = $quizid;
    }

    public function getQuizId(): int {
        return $this->quizid;
    }

    public function setQuestion(string $question) {
        $this->question = $question;
    }

    public function getQuestion(): string {
        return $this->question;
    }


    public function save() {
        $gateway = new QuestionGateway();
        if($this->id > 0) {
            $gateway->update($this->id, $this->getAttributesAsAssociativeArray());
        } else {
            $this->id = $gateway->insert($this->getAttributesAsAssociativeArray());
        }
    }

    public function delete() {
        $gateway = new QuestionGateway();
        $gateway->delete($this->id);
    }

    public static function all(): array {
        $gateway = new QuestionGateway();
        $questions = [];

        $dbQuestions = $gateway->all();

        foreach( $dbQuestions as $dbQuestion ) {
            $question = self::create($dbQuestion);
            $questions[] = $question;

        }

        return $questions;
    }

    public static function findById(int $id): ?Question {   // : Book = gibt auf jedenfall ein wert aus, : ?Book = gibt entweder ein wert aus oder null.
        $gateway = new QuestionGateway();

        $tmpQuestion = $gateway->findById($id);
        $question = null;

        if( $tmpQuestion ) {
            $question = self::create($tmpQuestion);
        }

        return $question;
    }

    public static function findByQuizId(int $quizid): array {
        $gateway = new QuestionGateway();
        $questions = [];

        $dbQuestions = $gateway->findByFields([
            "QuizId" => $quizid
        ]);

        foreach($dbQuestions as $dbQuestion) {
           $question = self::create($dbQuestion);
           $questions[] = $question;
        }
        return $questions;
    }

    

    private static function create (array $tmpQuestion): Question {

        $question = new Question();
        $question->id = $tmpQuestion["Id"];
        $question->quizid = $tmpQuestion["QuizId"];
        $question->question = $tmpQuestion["Question"];
        
    
        return $question;
    }

    private function getAttributesAsAssociativeArray(): array {
        return [
            "QuizId" => $this->quizid,
            "Question" => $this->question,
            
        ];
    }



}






?>