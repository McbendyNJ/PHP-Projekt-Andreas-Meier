<?php

namespace App\Model;
use App\Gateway\QuizGateway;


Class Quiz {
    private int $id = 0;
    private int $userid = 0;
    private string $description;
    private string $quizname;
    private int $onlinestatus;

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setUserid(int $userid) {
        $this->userid = $userid;
    }

    public function getUserid(): int {
        return $this->userid;
    }

    public function setDescription(string $description) {
        $this->description = $description;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setQuizname(string $quizname) {
        $this->quizname = $quizname;
    }

    public function getQuizname(): string {
        return $this->quizname;
    }

    public function setOnlinestatus(int $onlinestatus) {
        $this->onlinestatus = $onlinestatus;
    }

    public function getOnlinestatus(): int {
        return $this->onlinestatus;
    }

   

    public function save() {
        $gateway = new QuizGateway();
        if($this->id > 0) {
            $gateway->update($this->id, $this->getAttributesAsAssociativeArray());
        } else {
            $this->id = $gateway->insert($this->getAttributesAsAssociativeArray());
        }
        
    }

    public function delete() {
        $gateway = new QuizGateway();
        $gateway->delete($this->id);
    }

    public static function all(): array {
        $gateway = new QuizGateway();
        $quizes = [];

        $dbQuizes = $gateway->all();

        foreach( $dbQuizes as $dbQuiz ) {
            $quiz = self::create($dbQuiz);
            $quizes[] = $quiz;
            
        }
        
        return $quizes;
    }

    public static function findById(int $id): ?Quiz {   // : Quiz = gibt auf jedenfall ein wert aus, : ?Book = gibt entweder ein wert aus oder null.
        $gateway = new QuizGateway();

        $tmpQuiz = $gateway->findById($id);
        $quiz = null;

        

        if( $tmpQuiz ) {
            $quiz = self::create($tmpQuiz);
        }

        return $quiz;
    }

    private static function create (array $tmpQuiz): Quiz {

        $quiz = new Quiz();
        $quiz->id = $tmpQuiz["Id"];
        $quiz->userid = $tmpQuiz["UserId"];            
        $quiz->description = $tmpQuiz["Description"];
        $quiz->quizname = $tmpQuiz["Quizname"];
        $quiz->onlinestatus = $tmpQuiz["Onlinestatus"];
       
        return $quiz;
           
    }

    
    public static function findByUserId(int $userid): array {
        $gateway = new QuizGateway();
        $quizes = [];

        $dbQuizes = $gateway->findByFields([
            "UserId" => $userId
        ]);

        foreach($dbQuizes as $dbQuiz) {
           $quiz = self::create($dbQuiz);
           $quizes[] = $quiz;
        }
        return $quizes;
    }

    


    private function getAttributesAsAssociativeArray(): array {
        return [
            "UserId" => $this->userid,
            "Description" => $this->description,
            "Quizname" => $this->quizname,
            "Onlinestatus" => $this->onlinestatus
        ];
    }
   
}

    
?>