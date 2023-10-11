<?php
// Заголовки
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Подключение к БД
// Файлы, необходимые для подключения к базе данных
include_once "../../config/database.php";
include_once "../../Models/Ticket.php";

// Получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// Получаем данные
$data = json_decode(file_get_contents("php://input"));

// Создание объекта "ticket"
$ticket = new Ticket($db);

// Устанавливаем значения
$ticket->name = $data->name;
$ticket->commission = $data->commission;
$ticket->subject = $data->subject;
$ticket->faculty = $data->faculty;
$ticket->examiner = $data->examiner;
$ticket->specialization = $data->specialization;
$ticket->chairman = $data->chairman;
$ticket->userID = $data->userID;

foreach ($data->questions as $questionData) {
    $question = new Question($db);
    $question->typeQuestion = $questionData->typeQuestion;
    $question->text = $questionData->text;
    $ticket->questions[] = $question;
}


if (
    !empty($ticket->name) &&
    !empty($ticket->commission) &&
    !empty($ticket->subject) &&
    !empty($ticket->faculty) &&
    !empty($ticket->examiner) &&
    !empty($ticket->specialization) &&
    !empty($ticket->chairman) &&
    !empty($ticket->userID) &&

    createTicket($db, $ticket)

) {
    // Устанавливаем код ответа
    http_response_code(200);

    // Покажем сообщение о том, что пользователь был создан
    echo json_encode(array("message" => "Билет был создан"));
} // Сообщение, если не удаётся создать пользователя
else {
    // Устанавливаем код ответа
    http_response_code(400);

    // Покажем сообщение о том, что создать пользователя не удалось
    echo json_encode(array("message" => "Ошибка при создании билета"));
}