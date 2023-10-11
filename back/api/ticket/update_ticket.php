<?php

// Заголовки
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
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
$editedTicket = new Ticket($db);

// Устанавливаем значения
$editedTicket->ticketID = $data->ticketID;

$editedTicket->name = $data->name;
$editedTicket->commission = $data->commission;
$editedTicket->subject = $data->subject;
$editedTicket->faculty = $data->faculty;
$editedTicket->examiner = $data->examiner;
$editedTicket->specialization = $data->specialization;
$editedTicket->chairman = $data->chairman;

foreach ($data->questions as $questionData) {
    $question = new Question($db);
    $question->typeQuestion = $questionData->typeQuestion;
    $question->text = $questionData->text;
    $editedTicket->questions[] = $question;
}


if (
    !empty($editedTicket->ticketID) &&
    !empty($editedTicket->name) &&
    !empty($editedTicket->commission) &&
    !empty($editedTicket->subject) &&
    !empty($editedTicket->faculty) &&
    !empty($editedTicket->examiner) &&
    !empty($editedTicket->specialization) &&
    !empty($editedTicket->chairman) &&

    updateTicket($db, $editedTicket)

) {
    // Устанавливаем код ответа
    http_response_code(200);

    // Покажем сообщение о том, что билет был обновлён
    echo json_encode(array("message" => "Билет был обновлён"));
} // Сообщение, если не удаётся обновить билет
else {
    // Устанавливаем код ответа
    http_response_code(400);

    echo json_encode(array("message" => "Ошибка при обновлении билета"));
}