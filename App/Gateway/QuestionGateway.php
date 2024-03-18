<?php

namespace App\Gateway;

class QuestionGateway extends BasicTableGateway {
    protected string $table = "tQuestion";
    protected array $columns = [
        "Id", "QuizId", "Question" // "Answer", "Solution"
    ];
}

?>