<?php

namespace App\Gateway;

class AnswerGateway extends BasicTableGateway {
    protected string $table = "tAnswer";
    protected array $columns = [
        "Id", "QuestionId", "Answer", "Solution"
    ];

    
}

