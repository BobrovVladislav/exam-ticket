<?php

require '../../vendor/autoload.php';

use Mpdf\Mpdf;
use Mpdf\MpdfException;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'));

    // Add your ticket data to the document
    $mpdf = new Mpdf();

    // Counter for the number of tickets on the current page
    $ticketNumber = 1;

    // Generate PDF content based on ticket data
    try {
        foreach ($data->tickets as $ticket) {

            if (($ticketNumber - 1) % 2 == 0) {
                $mpdf->AddPage();
            }

            $htmlContent = "
        <div style='border: 1px solid black; padding: 10px; margin-bottom: 25px;'>
        <p style='text-align: center; margin: 0'>ФГБОУ ВО «Воронежский государственный университет инженерных технологий»</p>
        <hr style='border: 1px solid black; width: 80%;' />
        <div style='margin: 0 auto; width: 80%; text-align: center;'>
        <table style='width: 100%; margin-bottom: 15px;'>
            <tr>
                <td>Цикловая комиссия $ticket->commission</td>
                <td>Факультет $ticket->faculty</td>
            </tr>
        </table>
        </div>
        <p style='text-align: center; margin-bottom: 35px;'>Направление подготовки/специальность: $ticket->specialization</p>
        <p style='text-align: center; margin-bottom: 35px;'>$ticket->subject</p>
        <h2 style='text-align: center; margin-bottom: 35px;'>БИЛЕТ №$ticketNumber</h2>
        ";
            $practiceCount = 0;
            $theoryCount = 0;
            foreach ($ticket->questions as $question) {
                if ($question->typeQuestion === 'Теория' && $theoryCount < 2) {
                    $htmlContent .= "
                    <p>Вопрос (Теория): $question->text</p>
                ";
                    $theoryCount++;
                } else if ($question->typeQuestion === 'Практика' && $practiceCount < 2) {
                    $htmlContent .= "
                    <p>Вопрос (Практика): $question->text</p>
                ";
                    $practiceCount++;
                }
            }
            $htmlContent .= "
            <div style='margin: 35px auto 0; width: 90%; text-align: center;'>
            <table style='width: 100%;'>
                 <tr>
                   <td>Экзаменатор ______ $ticket->examiner</td>
                   <td>Председатель ЦК ______ $ticket->chairman</td>
                 </tr>
            </table>
            </div>
        </div>
        ";
            $mpdf->WriteHTML($htmlContent);

            $ticketNumber++;
        }
        // Output the PDF
        $mpdf->Output();
    } catch (MpdfException $e) {
    }
} else {
    http_response_code(400);
    // Handle other HTTP request methods (GET, etc.) or return an error
    header("HTTP/1.1 405 Method Not Allowed");
    exit;
}