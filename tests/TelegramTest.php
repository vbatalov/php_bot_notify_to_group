<?php

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

final class TelegramTest extends TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testRequest(): void
    {
        $query = [
            "text" => "<b>Текст</b> сообщения в параметре text=...text",
            "tags" => "Первый, Второй, С пробелами, Параметр tags",
            "group" => "personal",
            "Автор" => "Иван",
        ];

        $client = new Client();
        $response = $client->request("GET", "http://localhost:8000",
            [
                "query" => $query,
            ]);

        if ($response->getStatusCode() == 200) {
            print_r($response->getBody()->getContents());
            $this->assertTrue(true);
        }

    }

}