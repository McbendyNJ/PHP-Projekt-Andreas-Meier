
Drop Database if Exists M295P1;

Create Database M295P1;

USE M295P1;

Create Table tUser (
	Id INT NOT NULL auto_increment,
    PRIMARY KEY (Id) ,
    Firstname VARCHAR(255) NOT NULL,
    Lastname VARCHAR(255) NOT NULL,
    Username VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    Password VARCHAR(255) NOT NULL
);

Insert into tUser(Firstname, Lastname, Username, Email, Password) Values('Test', 'Testing', 'Test', 'Test.test@gmail.com','$2a$12$7zGSM1JOdKTD7iZBGwR/RuJioa4dYGuxBWa6YuDjGQLgykyz/zifG'); /* das passwort ist "testtest" */

Create Table tQuiz (
	Id INT NOT NULL auto_increment,
    PRIMARY KEY (Id) ,
	UserId INT NOT NULL,
    FOREIGN KEY (UserId) REFERENCES tUser(Id) ON DELETE CASCADE ON UPDATE CASCADE,
    Quizname VARCHAR(255) NOT NULL,
    Description VARCHAR(255),
    Onlinestatus TINYINT NOT NULL DEFAULT 0
);
Create Index IQuizname on tQuiz(Quizname);


Insert into tQuiz (UserId, Quizname, Description, Onlinestatus) VALUES(1,'First Quiz', 'Dies ist das erste Quiz auf dieser Website!', 1);

Create Table tQuestion (
	Id INT NOT NULL auto_increment,
    PRIMARY KEY (Id) ,
    QuizId INT NOT NULL,
    FOREIGN KEY (QuizId) REFERENCES tQuiz(Id) ON DELETE CASCADE ON UPDATE CASCADE,
    Question VARCHAR(255) NOT NULL    
);

Insert into tQuestion (QuizId, Question) VALUES(1,'Frage 1');

Create Table tAnswer (
	Id INT NOT NULL auto_increment,
    PRIMARY KEY (Id),
    QuestionId INT not null,
    FOREIGN KEY (QuestionId) REFERENCES tQuestion(Id) ON DELETE CASCADE ON UPDATE CASCADE,
    Answer VARCHAR(255) NOT NULL,
    Solution TINYINT NOT NULL DEFAULT 0
);

Insert into tAnswer (QuestionId,Answer, Solution) VALUES(1,'Richtig', 1);
Insert into tAnswer (QuestionId,Answer, Solution) VALUES(1,'Falsch 1', 0);
Insert into tAnswer (QuestionId,Answer, Solution) VALUES(1,'Falsch 2', 0);
Insert into tAnswer (QuestionId,Answer, Solution) VALUES(1,'Falsch 3', 0);



