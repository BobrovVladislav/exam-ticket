<?php

require '../../vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'));

    // Create a new PHPWord instance
    $phpWord = new PhpWord();

// Initialize the ticket number
    $ticketNumber = 1;

// Create a section in the document
    $section = $phpWord->addSection();

    try {
        foreach ($data->tickets as $ticket) {
//             Create a table for the ticket content
            $table = $section->addTable(['borderColor' => '000', 'borderSize' => 3, 'cellMargin' => 250]);

            $table->addRow();
            $cell = $table->addCell();

            $htmlContent = "<p style='text-align: center; font-family: Times New Roman; font-size: 12pt'>ФГБОУ ВО «Воронежский государственный университет инженерных технологий»</p>";
            $htmlContent .= "<p style='text-align: center'>_____________________________________________________________</p>";
            $htmlContent .= "<p style='text-align: center; font-family: Times New Roman; font-size: 14pt'>Цикловая комиссия $ticket->commission \t\t Факультет $ticket->faculty</p>";
            $htmlContent .= "<p></p>";
            $htmlContent .= "<p style='text-align: center; font-family: Times New Roman; font-size: 12pt'>Направление подготовки/специальность: $ticket->specialization</p>";
            $htmlContent .= "<p></p>";
            $htmlContent .= "<p style='text-align: center; font-family: Times New Roman; font-size: 14pt'>$ticket->subject</p>";
            $htmlContent .= "<p></p>";
            $htmlContent .= "<p style='text-align: center; font-family: Times New Roman; font-weight: bold; font-size: 16pt'>БИЛЕТ №$ticketNumber</p>";
            $htmlContent .= "<p></p>";
            $practiceCount = 0;
            $theoryCount = 0;
            foreach ($ticket->questions as $question) {
                if ($question->typeQuestion === 'Теория' && $theoryCount < 2) {
                    $htmlContent .= "<p style='font-family: Times New Roman; font-size: 14pt'>Вопрос (Теория): $question->text</p>";
                    $theoryCount++;
                } else if ($question->typeQuestion === 'Практика' && $practiceCount < 2) {
                    $htmlContent .= "<p style='font-family: Times New Roman; font-size: 14pt'>Вопрос (Практика): $question->text</p>";
                    $practiceCount++;
                }
            }
            $htmlContent .= "
                <p style='text-align: center; font-family: Times New Roman; font-size: 12pt'>Экзаменатор ______ $ticket->examiner \t Председатель ЦК ______ $ticket->chairman</p>";
            Html::addHtml($cell, $htmlContent, false);

            $section->addPageBreak();
//          // Increment the ticket number
            $ticketNumber++;
        }
        // Save the document
        $filename = 'tickets.docx';
        header('Content-Type: application/msword');
        header('Content-Disposition: attachment; filename="' . $filename . '"');


        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save('php://output');

    } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
    }
} else {
    http_response_code(400);
    // Handle other HTTP request methods (GET, etc.) or return an error
    header("HTTP/1.1 405 Method Not Allowed");
    exit;
}