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
include_once "../../Models/User.php";

// Получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// Создание объекта "user"
$user = new User($db);

// Получаем данные
$data = json_decode(file_get_contents("php://input"));

// Устанавливаем значения
$user->name = $data->name;
$user->email = $data->email;
$user->password = $data->password;
$user->tel = '-';
$user->faculty = '-';

// Поверка на существование e-mail в БД
$email_exists = $user->emailExists();

// Создание пользователя
if ($email_exists == true) {
    // Устанавливаем код ответа

    http_response_code(505);
    // Покажем сообщение о том, что создать пользователя не удалось
    echo json_encode(array("message" => "Почта занята"));
} else if (
    !empty($user->name) &&
    !empty($user->email) &&
    !empty($user->password) &&

    $user->create()
) {
    // Устанавливаем код ответа
    http_response_code(200);

    // Покажем сообщение о том, что пользователь был создан
    echo json_encode(array("message" => "Пользователь был создан"));
}

// Сообщение, если не удаётся создать пользователя
else {

    // Устанавливаем код ответа
    http_response_code(400);

    // Покажем сообщение о том, что создать пользователя не удалось
    echo json_encode(array("message" => "Ошибка при создании пользователя"));
}
