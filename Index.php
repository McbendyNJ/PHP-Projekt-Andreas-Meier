<?php

require_once "App/Model/User.php";
session_start();

   //name = autoloading
   use App\Controller\HomeController;
   use App\Controller\QuizController;
   use App\Controller\RegisterController;
   use App\Controller\LoginController;
   use App\Controller\QuestionController;
   use App\Controller\PlayQuizController;
   use App\Controller\AnswerController;
   use App\Controller\UserController;
   use App\Middleware\Authenticated;
   use App\Model\User;
   use App\Model\Quiz;
   use App\Model\Question;
   use App\Model\Answer;
   
    require_once "vendor/autoload.php";

    $uri = $_SERVER["REQUEST_URI"]; // uri ist der URL link nach dem localhost:8080 kommt zb localhost8080/home -> /home
    $httpmethod = $_SERVER["REQUEST_METHOD"];
    $authenticated = new Authenticated();

    if( $uri === "/login" ) {
      $controller =  new LoginController();
      
      if($httpmethod == "GET") {
         $controller->index();
         die();
      } else {
         $controller->login($_POST);
         
      }
    } 

    if($uri == "/logout") {
      $controller = new LoginController();

      if ($httpmethod === "POST" && isset($_POST["_method"]) && $_POST["_method"] === "DELETE") {
         $controller->logout();
      }
   }

   if( $uri === "/register" ) {
      $controller =  new RegisterController();
      if($httpmethod === "GET"){
         $controller->index();
      } else if($httpmethod === "POST" ) {
         $controller->store($_POST);
      } die();
    } 

   

    

   if($authenticated->handle()) {
      

    if( $uri === "/" ) {
        $controller =  new HomeController();
        $controller->index();
    } 

    if($uri === "/quizes/create") {
      $controller = new QuizController();
      $controller->create();
    }

    if($uri === "/allquizes") {
      $controller = new QuizController();
      $controller->indexallquizes();
    } 

    if(preg_match("#/quizes/\d+$#", $uri)) {

      
      $matches = array();
      preg_match("/\d+/", $uri, $matches);
      $controller = new QuizController();
      
      if($httpmethod === "GET") {
         $controller->edit($matches[0]);
      } else if($httpmethod === "POST" && isset($_POST["_method"]) && $_POST["_method"] === "PUT") { // mit isset kann man andere Variablen überprüfen
         $controller->update($matches[0], $_POST);
      } else if($httpmethod ==="POST" && isset($_POST["_method"]) && $_POST["_method"] === "DELETE") {
         $controller->delete($matches[0]);
      } else if($httpmethod ==="POST" && isset($_POST["_method"]) && $_POST["_method"] === "ADDQUESTIONS") {
         $controller->addquestions($matches[0]);
      }
    }

    if(preg_match("#/quizes/play/\d+$#", $uri)) {
      
      $matches = array();
      preg_match("/\d+/", $uri, $matches);
      $controller = new QuizController();
      
      if($httpmethod === "GET") {
         $controller->playQuiz($matches[0]);
      }
      
    } 

   if ($uri === "/result" && $_SERVER['REQUEST_METHOD'] === 'POST') {
      $controller = new QuizController();
      $controller->showResult();
   }
    
    if( $uri === "/quizes" ) {
      
      $controller = new QuizController();
      if($httpmethod === "GET"){
         
         $controller->index();
      } else if($httpmethod === "POST" ) {
         $controller->store($_POST);
      }
   }

   if($uri === "/questions/create") {
      $controller = new QuestionController();
      $controller->create();
    }

    if(preg_match("#/questions/\d+$#", $uri)) {
      
      $matches = array();
      preg_match("/\d+/", $uri, $matches);
      $controller = new QuestionController();
      
      if($httpmethod === "GET") {
         $controller->edit($matches[0]);
      } else if($httpmethod === "POST" && isset($_POST["_method"]) && $_POST["_method"] === "PUT") { // mit isset kann man andere Variablen überprüfen
         $controller->update($matches[0], $_POST);
      } else if($httpmethod ==="POST" && isset($_POST["_method"]) && $_POST["_method"] === "DELETE") {
         $controller->delete($matches[0]);
      } else if($httpmethod ==="POST" && isset($_POST["_method"]) && $_POST["_method"] === "ADDANSWERS") {
         $controller->addanswers($matches[0]);
      }
      
    }
    
    if( $uri === "/questions" ) {
      
      $controller = new QuestionController();
      if($httpmethod === "GET"){
         
         $controller->index();
      } else if($httpmethod === "POST" ) {
         $controller->store($_POST);
      }
   }

   if($uri === "/answers/create") {
      $controller = new AnswerController();
      $controller->create();
    }

    if(preg_match("#/answers/\d+$#", $uri)) {
      
      $matches = array();
      preg_match("/\d+/", $uri, $matches);
      $controller = new AnswerController();
      
      if($httpmethod === "GET") {
         $controller->edit($matches[0]);
      } else if($httpmethod === "POST" && isset($_POST["_method"]) && $_POST["_method"] === "PUT") { // mit isset kann man andere Variablen überprüfen
         $controller->update($matches[0], $_POST);
      } else if($httpmethod ==="POST" && isset($_POST["_method"]) && $_POST["_method"] === "DELETE") {
         $controller->delete($matches[0]);
      }
      
    }
    
    if( $uri === "/answers" ) {
      
      $controller = new AnswerController();
      if($httpmethod === "GET"){
         
         $controller->index();
      } else if($httpmethod === "POST" ) {
         $controller->store($_POST);
      }
   }

   

   if(preg_match("#/user/\d+$#", $uri)) {
      
      $matches = array();
      preg_match("/\d+/", $uri, $matches);
      $controller = new UserController();
      
      if($httpmethod === "GET") {
         $controller->edit($matches[0]);
      } else if($httpmethod === "POST" && isset($_POST["_method"]) && $_POST["_method"] === "PUT") { // mit isset kann man andere Variablen überprüfen
         $controller->update($matches[0], $_POST);
      } else if($httpmethod ==="POST" && isset($_POST["_method"]) && $_POST["_method"] === "DELETE") {
         $controller->delete($matches[0]);
      }
      
    }

} 
/*
   if( $uri === "/allquizes" ) {
      
      $controller = new QuizController();
      if($httpmethod === "GET"){
         
         $controller->index();
      } else if($httpmethod === "POST" ) {
         $controller->store($_POST);
      }
   }
*/ 
   


  

   // --------------------------------------------------------------------------------------------------


/*//$number = 0;
         //echo $number == "0" /true
         //echo $number === "0" /false
         //echo $number === 0 /true
   spl_autoload_register(function($className){
      $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
      include_once "$className.php";                                              //"{$_SERVER['DOCUMENT_ROOT']}/$className.php";
   });

   */
/*
   $books = Book::all();
   $movies = Movie::all();
   $actors = Actor::all();

  */

   //$db = new BasicTableGateway();

   //$resultSet = $db->executeQuery("SELECT * FROM book");


   ?>


<!--
// Aufg 1


   /*
   //phpinfo(); 

   include "App/Computer/Component/Monitor.php";
   include "App/Computer/Component/ComputerCase.php";
   include "App/Computer/Component/Motherboard.php";
   include "App/Computer/Computer.php";



   use App\Computer\Component\Monitor;
   use App\Computer\Component\ComputerCase;
   use App\Computer\Component\Motherboard;
   use App\Computer\Computer;

   $monitor = new Monitor("Bildschrim", [1920, 1080]);
  
  
   $case = new ComputerCase("CoolerMaster");
  

   $motherboard = new Motherboard ("Motherboard 1");
   

   $computer = new Computer($monitor, $case, $motherboard);

   echo "Der Computer besitzt ein Monitor vom Typ " . $computer->getMonitor()->getType();
   
   echo $computer->getComputerCase()->getName();
   */


// Aufg 2

/*use App\School\Subject;
use App\School\Klass;
use App\School\Person;
use App\School\Teacher;
use App\School\Student;

$teacher = new Teacher("Hugo", "Boss");
$student1 = new Student("Tim", "Boss");
$student2 = new Student("Hanz", "Boss");
$student3 = new Student("herald", "Boss");
$tmpStudents = new Student("ufhru","fhruhf");
$students = [
   "Key1" => $student1,
   "Key2" => $student2,
   "Key3" => $student3
];

$tmpStudents = [
   $student1, $student2, $student3
];

$subject = new Subject("PHP Programmieren", 295, $teacher);

$class = new Klass($students, [$subject]);


foreach( $students as $key=>$student ) {
   echo "$key: " . $student->getFullname() . "<br>";
}

echo $tmpStudents[0]->getFullname() . "<br>";
echo $students["Key2"]->getFullname() . "<br>";

*/

//echo '<a href="Page2.php">Page 2</a>';

?>


