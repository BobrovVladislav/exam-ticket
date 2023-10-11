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
$ticket->userID = $data->userID;

if (
    !empty($ticket->userID) &&

    getAll_tickets($db, $ticket) !== 0
) {
    // Устанавливаем код ответа
    http_response_code(200);

    // Покажем сообщение о том, что пользователь был создан
    echo json_encode(getAll_tickets($db, $ticket));
} // Сообщение, если не удаётся создать пользователя
else {
    // Устанавливаем код ответа
    http_response_code(400);

    echo json_encode(array("message" => "Ошибка при получении билетов"));
}