<?php

include_once "Question.php";

class Ticket
{
    private $conn;
    private $table_name = "ExamTicket";

    // Свойства
    public $ticketID;
    public $name;
    public $commission;
    public $subject;
    public $faculty;
    public $examiner;
    public $specialization;
    public $chairman;
    public $userID;
    public $questions = [];

    // Конструктор класса ticket
    public function __construct($db)
    {
        $this->conn = $db;
    }

}

// Метод для создания нового билета
function createTicket($db, $ticket)
{
    //Останавливаем отправку запросов
    $db->beginTransaction();

    // Запрос для добавления нового пользователя в БД
    $queryTicket = 'INSERT INTO ExamTicket
                SET
                    name = :name,
                    commission = :commission,
                    subject = :subject,
                    faculty = :faculty,
                    examiner = :examiner,
                    specialization = :specialization,
                    chairman = :chairman,
                    userID = :userID';

    // Подготовка запроса
    $stmt = $db->prepare($queryTicket);

    // Инъекция


    // Выполняем запрос
    $stmt->execute(array(
        ':name' => $ticket->name,
        ':commission' => $ticket->commission,
        ':subject' => $ticket->subject,
        ':faculty' => $ticket->faculty,
        ':examiner' => $ticket->examiner,
        ':specialization' => $ticket->specialization,
        ':chairman' => $ticket->chairman,
        ':userID' => $ticket->userID,

    ));

    // Получаем ID созданного билета
    $ticket->ticketID = $db->lastInsertId();

    // Запрос для добавления нового пользователя в БД
    $queryQuestion = "INSERT INTO Question
        (typeQuestion, text, ticketID) VALUES (:typeQuestion ,:text, :ticketID)";

    // Подготовка запроса
    $stmt = $db->prepare($queryQuestion);

    // Инъекция


    // Переменная которая определяет выполнение запроса
    $success = true;

    // Выполняем запрос
    foreach ($ticket->questions as $question) {
        $stmt->bindParam(':typeQuestion', $question->typeQuestion, PDO::PARAM_STR);
        $stmt->bindParam(':text', $question->text, PDO::PARAM_STR);
        $stmt->bindParam(':ticketID', $ticket->ticketID, PDO::PARAM_INT);

        //Проверка выполнения запроса
        if (!$stmt->execute()) {
            $success = false;
            break;
        }
    }

    if ($success) {
        //Возобновляем отправку запросов
        $db->commit();

        return true;
    } else {
        // Roll back the transaction and return false if any insertion failed
        $db->rollback();

        return false;
    }
}

// Метод для редактирования билета
function updateTicket($db, $ticket)
{
    //Останавливаем отправку запросов
    $db->beginTransaction();

    // Запрос для добавления нового пользователя в БД
    $queryTicket = 'UPDATE ExamTicket
                SET
                    name = :name,
                    commission = :commission,
                    subject = :subject,
                    faculty = :faculty,
                    examiner = :examiner,
                    specialization = :specialization,
                    chairman = :chairman
                WHERE ticketID = :ticketID';

    // Подготовка запроса
    $stmt = $db->prepare($queryTicket);

//    $stmt->bindParam(':ticketID', $ticket->ticketID, PDO::PARAM_INT);

    // Инъекция


    // Выполняем запрос
    $stmt->execute(array(
        ':ticketID' => $ticket->ticketID,
        ':name' => $ticket->name,
        ':commission' => $ticket->commission,
        ':subject' => $ticket->subject,
        ':faculty' => $ticket->faculty,
        ':examiner' => $ticket->examiner,
        ':specialization' => $ticket->specialization,
        ':chairman' => $ticket->chairman
    ));

    //Удаление вопросов которые есть в БД
    $deleteQuestionsSQL = "DELETE FROM Question WHERE ticketID = :ticketID";
    $stmt = $db->prepare($deleteQuestionsSQL);
    $stmt->bindParam(':ticketID', $ticket->ticketID, PDO::PARAM_INT);
    $stmt->execute();

    // Запрос для добавления новых вопросов в БД
    $queryQuestion = "INSERT INTO Question
        (typeQuestion, text, ticketID) VALUES (:typeQuestion ,:text, :ticketID)";

    // Подготовка запроса
    $stmt = $db->prepare($queryQuestion);

    // Инъекция


    // Переменная которая определяет выполнение запроса
    $success = true;

    // Выполняем запрос
    foreach ($ticket->questions as $question) {
        $stmt->bindParam(':typeQuestion', $question->typeQuestion, PDO::PARAM_STR);
        $stmt->bindParam(':text', $question->text, PDO::PARAM_STR);
        $stmt->bindParam(':ticketID', $ticket->ticketID, PDO::PARAM_INT);

        //Проверка выполнения запроса
        if (!$stmt->execute()) {
            $success = false;
            break;
        }
    }

    if ($success) {
        //Возобновляем отправку запросов
        $db->commit();

        return true;
    } else {
        // Roll back the transaction and return false if any insertion failed
        $db->rollback();

        return false;
    }

}

function getAll_tickets($db, $ticket)
{
    $query = "SELECT et.*, q.questionID, q.text, q.typeQuestion
          FROM ExamTicket et
          LEFT JOIN Question q ON et.ticketID = q.ticketID
          WHERE et.userID = :userID";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':userID', $ticket->userID, PDO::PARAM_INT);

    if ($stmt->execute()) {

        $result = [];

        // Fetch the results and group questions by ExamTicket
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ticketID = $row['ticketID'];

            // If the ticket ID is not in the result, create a new entry
            if (!isset($result[$ticketID])) {
                $result[$ticketID] = [
                    'ticketID' => $row['ticketID'],
                    'name' => $row['name'],
                    'commission' => $row['commission'],
                    'subject' => $row['subject'],
                    'faculty' => $row['faculty'],
                    'examiner' => $row['examiner'],
                    'specialization' => $row['specialization'],
                    'chairman' => $row['chairman'],
                    'userID' => $row['userID'],
                    'questions' => [],
                ];
            }

            // Add the question to the questions array of the ExamTicket
            $result[$ticketID]['questions'][] = [
                'questionID' => $row['questionID'],
                'text' => $row['text'],
                'typeQuestion' => $row['typeQuestion'],
            ];
        }

// Convert the result array to a simple indexed array
        $result = array_values($result);
        return $result;
    } else {
        return 0;
    }
}