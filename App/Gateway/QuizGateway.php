<?php

namespace App\Gateway;

class QuizGateway extends BasicTableGateway {
    protected string $table = "tQuiz";
    protected array $columns = [
        "Id", "UserId", "Description", "Quizname", "Onlinestatus"
    ];
}

