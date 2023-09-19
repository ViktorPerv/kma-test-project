<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Response;
use App\Repository\ContentRepository;

class HomeController
{
    public function index(): Response
    {
        $data = (new ContentRepository())->getAllContent();

        $content = '
        <!doctype html>
        <html lang="en">
          <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Bootstrap demo</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
          </head>
          <body>
            <body>
            <main class="flex-shrink-0">
            <div class="container">
            <h1>Table of data</h1>
            <table class="table">
                <tr>
                    <th scope="row">За 1 минуту</th>
                    <th>Коли-во за 1 минуту</th>
                    <th>Средняя длина контента</th>
                    <th>Время первого сообщения в минуте</th>
                    <th>Время последнего сообщения в минуте</th>
                </tr>';
        foreach ($data as $row) {
            $content = $content . '
                <tr>
                    <td scope="row">'. $row['every1Min'] . '</td>
                    <td>'. $row['countPer1Minute'] . '</td>
                    <td>'. $row['avgContent'] . '</td>
                    <td>'. $row['minIn1Minute'] . '</td>
                    <td>'. $row['maxIn1Minute'] . '</td>
                </tr>
            ';
        }
        $content = $content . '</div></main>
        </table>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        </body></html>';
        return new Response($content, 200, []);
    }
}
