<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Response;
use App\Models\Content;
use App\Repository\ContentRepository;

class HomeController
{
    public function index(): Response
    {
        $data = (new Content())->getAllContent();

        $content = '<html>
            <head><style>
            table, th, td {
              border: 1px solid black;
              border-collapse: collapse;
            }
        </style></head>
        <body>
        <h1>Table of data</h1>
        <table>
            <tr>
                <th>id</th>
                <th>content</th>
                <th>time</th>
            </tr>';
        foreach ($data as $row) {
            $content = $content . '
                <tr>
                    <td>'. $row['id'] . '</td>
                    <td>'. $row['content'] . '</td>
                    <td>'. $row['timestamp'] . '</td>
                </tr>
            ';
        }

        $content = $content . '</table></body></html>';
        return new Response($content, 200, []);
    }
}