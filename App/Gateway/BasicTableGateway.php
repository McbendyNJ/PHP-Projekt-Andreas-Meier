<?php

namespace App\Gateway;

use PDO;
use PDOStatement;

abstract class BasicTableGateway
{
    private PDO $connection;
    protected string $table;
    protected array $columns;
    protected string $primary = "id";

    public function __construct()
    {
        $this->connection = new PDO("mysql:host=mysql;dbname=M295P1", "root", "test05");
    }

   public function all(): array { 
        $sql = $this->connection->prepare("SELECT * FROM $this->table");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false {
        $sql = $this->connection->prepare("SELECT * FROM $this->table WHERE $this->primary = $id");
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
        
    }

    public function findByQuizId(int $id) {
        $sql = $this->connection->prepare("SELECT * FROM $this->table Where QuizId = $id");
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    
    public function findByQuestionId(int $id) {
        $sql = $this->connection->prepare("SELECT tAnswer.* FROM M295P1.tQuestion 
                                            JOIN M295P1.tAnswer ON M295P1.tQuestion.Id = M295P1.tAnswer.QuestionId
                                                WHERE M295P1.tQuestion.QuizId = $id");
        
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    } 

    public function findByFields(array $fields): array|false {
       
        $sql = "SELECT * FROM $this->table WHERE ";
        
        $index = 0;
        foreach (array_keys($fields) as $key) {

            $sql .= "$key = ?";

            if ($index < count($fields) - 1) {
                $sql .= " AND ";
            }

            $index++;
    
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(array_values($fields));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    

    

    public function insert(array $data): int {
        /*
            ([
                                        "title" = array_keys
                                        $this->title = array_values
            "title" => $this->title,
            "author" => $this->author
        ]);

         */
        //array_keys($data) = ["title, "author"]

                                                                 //INSERT INTO book (title, author) VALUES (x,y)
       
        $columns = implode(",", array_keys($data));
        $placeholders = str_repeat("?, ", count($data) - 1) . "?";
        $values = array_values($data);
                                                            //$values = "'".implode("','", array_values($data))."'";

        $sql = "INSERT INTO $this->table($columns) VALUES ($placeholders)";
        
        $stmt = $this->connection->prepare($sql);
        
        $stmt->execute($values);
        

        


        return (int)$this->connection->lastInsertId();
    }

    public function update(int $id, array $data): void {
        //UPDATE book SET title = 'x', author = 'y' WHERE id = 1
        //UPDATE $this->table SET title = ?, author = ? WHERE $this->primary = $id

        $values = []; // beinhaltet alle ?
        $columns = ""; // beinhaltet alle strings

        foreach( $data as $key=>$value ) {
            $columns .= "$key = ?,";   
            $values[] = $value;             //.= == +=
        }

        $columns = rtrim($columns, ",");

        $sql = "UPDATE $this->table SET $columns WHERE $this->primary = $id";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($values);

        
    }

    public function delete(int $id) {
        $sql = $this->connection->prepare("DELETE FROM $this->table WHERE $this->primary = $id");
        $sql->execute();
    }

    public function getRelation(int $id, string $relationTable, string $type = null, string $intermediateTable = null) {
        if( $type == "n" ) {
            //SELECT * FROM actor AS p LEFT JOIN $intermediate AS i ON p.id = i.actor_id WHERE i.movie_id = $id
            $sql = "SELECT * FROM $relationTable AS p LEFT JOIN $intermediateTable AS i ON p.id = i.{$relationTable}_id WHERE i.{$this->table}_id = $id";          
        } else {
            //SELECT * FROM movie WHERE user_id = 1;
            $sql = "SELECT * FROM $relationTable WHERE {$this->table}_id = $id";
        }
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveRelation(int $objId, array $relationIds, string $relationTable, string $type = null, string $intermediateTable = null): void { //intermediateTable == zwischentabelle

        if($type == "n") {        
        // sql script== DELETE FROM actor_movie WHERE movie_id = 1;
        $sql = "DELETE FROM $intermediateTable WHERE {$this->table}_id = $objId";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        foreach($relationIds as $relationId) {
            //INSERT INTO actor_movie (movie_id, actor_id) VALUES (1,2);
            $sql = "INSERT INTO $intermediateTable ({$this->table}_id, {$relationTable}_id) VALUES ($objId, $relationId)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
        }
    } else {
        $sql = "UPDATE $relationTable SET {$this->table}_id = $relationIds[0]";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        }

    }
}


?>


