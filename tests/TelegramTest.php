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
            "текст" => "Заказ номер такой-то тут рандом: " . rand(1, 200),
            "Теги" => "Тег 1, тег 2",
            "Источник" => "bitrix",
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