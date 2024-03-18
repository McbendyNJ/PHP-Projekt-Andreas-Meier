<?php



namespace App\Controller;

use App\Model\Quiz;
use App\Model\Question;
use App\Model\Answer;


class QuizController extends DefaultController {

    

    public function index() {
        $quizes = Quiz::all();
       
        //$user = $_SESSION["user"];

        return $this->render("quizes.html.twig", [
            "quizes" => $quizes
            
        ]);

        
    }

    public function indexallquizes() {
        $quizes = Quiz::all();
        $quizesWithQuestions = [];

        foreach ($quizes as $quiz) {
            // Überprüfen Sie, ob der Onlinestatus des Quizzes auf 1 gesetzt ist
            if ($quiz->getOnlineStatus() == 1) {
                $questions = Question::findByQuizId($quiz->getId());

                if (count($questions) > 0) {
                    $hasQuestionWithTwoAnswers = false;
                    foreach ($questions as $question) {
                        $answers = Answer::findByQuestionId($quiz->getId());
                        if (count($answers) >= 2) {
                            foreach ($answers as $answer) {
                                if ($answer->getSolution() == 1) {
                                    $hasQuestionWithTwoAnswers = true;
                                    break 2; // Beendet beide Schleifen
                                }
                            }
                        }
                    }

                    if ($hasQuestionWithTwoAnswers) {   
                        $quizesWithQuestions[] = $quiz;
                    }
                }
            }
        }

        return $this->render("all-quizes.html.twig", [
            "quizes" => $quizesWithQuestions
        ]);
    }

    public function showresult() {
        $answers = $this->processSelectedAnswers();
        $selectedAnswers = $answers['selected'];
        $questionscount = $_POST["questionscount"];    

        // Laden Sie die result.html.twig Seite
        return $this->render('result.html.twig', [
            'selectedAnswers' => $selectedAnswers,
            'questionscount' => $questionscount
        ]);
    }
    

    public function store(array $data) {

        $rules = [
            "Quizname" => "required|alphanumeric|numeric",
        ];

        $this->validate($data,$rules); 
        
        $quiz = new Quiz();
        $quiz->setUserid($data["UserId"]);
        $quiz->setDescription($data["Description"]);
        $quiz->setQuizname($data["Quizname"]);
        $quiz->setOnlinestatus($data["Onlinestatus"]);
        $quiz->save();

        $this->redirect("/quizes");

    }

    public function create() {
        $this->render("quizes-form.html.twig");
    }

    public function edit(int $id) {
        $quiz = Quiz::findById($id);
        if ($quiz->getUserid() == $_SESSION["user"]->getId()) {
            $this->render("quizes-form.html.twig", [
                "quiz" => $quiz
            ]);
        } else {
            $this->errorRedirect("/quizes", "du hast keine Berechtigung!");
        }
    }

    public function update(int $id, array $data) {

        $quiz = Quiz::findById($id);
        $quiz->setUserid($data["UserId"]);
        $quiz->setDescription($data["Description"]);
        $quiz->setQuizname($data["Quizname"]);
        $quiz->setOnlinestatus($data["Onlinestatus"]);
        $quiz->save();

        $this->redirect("/quizes");
    }

    public function delete(int $id):void {
        $quiz = Quiz::findById($id);
        $quiz->delete();
    
        $this->redirect("/quizes");
    }

    
    public function addquestions(int $id) {
        $quiz = Quiz::findById($id);
        $_SESSION["quizid"] = $quiz->getId();
        $_SESSION["quizname"] = $quiz->getQuizname();
       

        $this->redirect("/questions");
        
        
    }

    public function playQuiz(int $id) {
        $questions = Question::findByQuizId($id);
        $answers = Answer::findByQuestionId($id);
        $countquestions = count($questions);

        $this->render("playquiz.html.twig", [
            "questions" => $questions,
            "answers" => $answers
        ]);

        $_SESSION["totalquestions"] = $countquestions;
       
        //$this->redirect("/quizes");
    }
    
    function processSelectedAnswers() {
        $selectedAnswers = [];
        
        
            if (!empty($_POST['selectedanswers'])) {
                // Loop to store values of individual checked checkbox.
                foreach ($_POST['selectedanswers'] as $selected) {
                    // Find the answer from the database
                $answer = Answer::findById($selected);
                // Add the answer and its solution to the selectedAnswers array
                
                $selectedAnswers[] = [
                    'answer' => $answer->getAnswer(),
                    'solution' => $answer->getSolution(),
                    'questionid' => $answer->getQuestionid()
                ];
            
                }
            } 

            
        
        return ['selected' => $selectedAnswers];
    }

}


?>