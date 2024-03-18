<?php

namespace App\Gateway;

class UserGateway extends BasicTableGateway {
    protected string $table = "tUser";
    protected array $columns = [
        "Id", "Firstname", "Lastname", "Username", "Email", "Password"
    ];
}

