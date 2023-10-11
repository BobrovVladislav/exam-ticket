<?php

class Question
{
    private $conn;
    private $table_name = "Question";

    // Свойства
    public $questionID;
    public $typeQuestion;
    public $text;
    public $ticketID;

    // Конструктор класса ticket
    public function __construct($db)
    {
        $this->conn = $db;
    }
}