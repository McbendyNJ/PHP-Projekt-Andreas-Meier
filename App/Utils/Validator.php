<?php

namespace App\Utils;

class Validator {

/*
    $validationRules =
    [
        "title => "alpanumeric|required"
    ]

    $values =
    [
        "title => "Judihui",
        "author => "Judihui46546"
    ]
*/

    public function validate(array $values, array $validationRules): array {
        $response = [];

        foreach($validationRules as $field => $rules) {
            foreach(explode("|", $rules) as $rule) {
                if($rule === "required" && ( !array_key_exists($field, $values) || empty(trim($values[$field])))) {
                    $this->checkArrayKey($response, $field);
                    $response[$field] .= "Das Feld $field wird benötigt";
                }

                if($rule == "alphanumeric" && !preg_match("/^[a-z0-9 .\-]+$/i", $values[$field])) {
                    $this->checkArrayKey($response, $field);
                    $response[$field] .= "Das Feld $field muss Buchstaben beeinhalten";
                }

                else if($rule == "numeric" && !preg_match("/^[a-z0-9 .\-]+$/i", $values[$field])) {
                    $this->checkArrayKey($response, $field);
                    $response[$field] .= "Das Feld $field muss Nummern beeinhalten";
                }
            }
        }

        return $response;
    }

    private function checkArrayKey(array &$response, string $field): void {
        if(!array_key_exists($field, $response)) {
            $response[$field] = "";
        } else {
            $response[$field] .= "<br>";
        }
    }
}

?>