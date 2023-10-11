<?php

class User
{
    // Подключение к БД таблице "users"
    private $conn;
    private $table_name = "user";

    // Свойства
    public $id;
    public $name;
    public $email;
    public $password;
    public $tel;
    public $faculty;
    public $tickets = array();

    // Конструктор класса user
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Метод для создания нового пользователя
    function create()
    {
        // Запрос для добавления нового пользователя в БД
        $query = 'INSERT INTO ' . $this->table_name . '
                SET
                    name = :name,
                    email = :email,
                    password = :password,
                    tel = :tel,
                    faculty = :faculty';

        // Подготовка запроса
        $stmt = $this->conn->prepare($query);

        // Инъекция
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->tel = htmlspecialchars(strip_tags($this->tel));
        $this->faculty = htmlspecialchars(strip_tags($this->faculty));

        // Для защиты пароля
        // Хешируем пароль перед сохранением в базу данных
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        // Выполняем запрос
        // Если выполнение успешно, то информация о пользователе будет сохранена в базе данных
        if ($stmt->execute(array(
            ':name' => $this->name,
            ':email' => $this->email,
            ':password' => $password_hash,
            ':tel' => $this->tel,
            ':faculty' => $this->faculty
        ))) {
            return true;
        }
        return false;
    }

    // Обновить запись пользователя
    public function update($dataToUpdate)
    {
        // Initialize arrays for update clauses and parameters
        $updateColumns = [];
        $updateParams = [];

        // Build the SET clause for the update
        foreach ($dataToUpdate as $column => $value) {
            if ($column === 'jwt') {
                continue; // Skip the JWT token field
            }
            $updateColumns[] = "$column = :$column";
            $updateParams[":$column"] = $value;
        }

        // Construct the SQL update statement
        $sql = "UPDATE $this->table_name SET " . implode(", ", $updateColumns) . " WHERE userID = :id";
        $updateParams[':id'] = $this->id;

        try {
            // Prepare the SQL statement
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                return false; // Error preparing SQL statement
            }

            // Bind the parameters using PDO's bindValue
            foreach ($updateParams as $param => $paramValue) {
                $stmt->bindValue($param, $paramValue);
            }

            // Execute the statement
            if ($stmt->execute()) {
                return true; // Record updated successfully
            } else {
                return false; // Error updating record
            }
        } catch (PDOException $e) {
            // Handle exceptions, log errors, or return false
            return false;
        }
    }

    //Получение user
    function getUser()
    {
        // Запрос, чтобы получить пользователя
        $query = "SELECT name, email, password, tel, faculty
            FROM " . $this->table_name . "
            WHERE userID = :id";

        // Подготовка запроса
        $stmt = $this->conn->prepare($query);

        // Привязываем значение id
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            // Получаем значения
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Присвоим значения свойствам объекта
            $this->name = $row["name"];
            $this->email = $row["email"];
            $this->password = $row["password"];
            $this->tel = $row["tel"];
            $this->faculty = $row["faculty"];

            return true;
        }
        return false;
    }


// Проверка, существует ли электронная почта в нашей базе данных
    function emailExists()
    {
        // Запрос, чтобы проверить, существует ли электронная почта
        $query = "SELECT userID, name, email, password, tel, faculty
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";

        // Подготовка запроса
        $stmt = $this->conn->prepare($query);

        // Инъекция
        $this->email = htmlspecialchars(strip_tags($this->email));

        // Привязываем значение e-mail
        $stmt->bindParam(1, $this->email);

        // Выполняем запрос
        $stmt->execute();

        // Получаем количество строк
        $num = $stmt->rowCount();

        // Если электронная почта существует,
        // присвоим значения свойствам объекта для легкого доступа и использования для php сессий
        if ($num > 0) {

            // Получаем значения
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Присвоим значения свойствам объекта
            $this->id = $row["userID"];
            $this->name = $row["name"];
            $this->password = $row["password"];
            $this->tel = $row["tel"];
            $this->faculty = $row["faculty"];

            // Вернём "true", потому что в базе данных существует электронная почта
            return true;
        }
        // Вернём "false", если адрес электронной почты не существует в базе данных
        return false;
    }
}
