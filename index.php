<?php

use Carbon\Carbon;
use JetBrains\PhpStorm\NoReturn;
use League\Csv\Reader;
use TelegramBot\Api\BotApi;
use Symfony\Component\HttpFoundation\Request;
use Jenssegers\Blade\Blade;

use League\Csv\Writer;

require "vendor/autoload.php";
require "config.php";

getRequest();

#[NoReturn] function getRequest()
{
    new Request($_GET,
        $_POST,
        [],
        $_COOKIE,
        $_FILES,
        $_SERVER);

    if (!empty($_GET)) {
        $params = [
            "text",
            "group",
        ];

        foreach ($params as $param) {
            if (!isset($_GET[$param])) exit ("Отсутствует обязательный параметр [$param]");
        }

        $response_code = sendMessage(GET: $_GET);

        if ($response_code == true) {
            addLog(response_code: 200, server: $_SERVER, get: $_GET);
            exit ("Сообщение отправлено. Файл логирования сохранен.");
        } else {
            exit ("Сообщение Telegram не отправлено. Ошибка будет отображена в файле лог.");
        }
    }

    exit ("Пустой GET запрос");
}

/** Создать файл лога */
function addLog(mixed $response_code, array $server, array $get): bool
{
    // наименование файла
    $filename = LOG_FILE_NAME . ".csv";

    $header = [
        "DATE_TIME",
        "REMOTE_ADDR",
        "HTTP_X_FORWARDED_FOR",
        "TELEGRAM_CODE_RESPONSE",
        "HTTP_USER_AGENT",
        "GET"
    ];


    if (!file_exists("logs/$filename")) {
        fopen("logs/$filename", "w");
        $data =
            [
                $header,
                [
                    Carbon::now()->toDateTimeString(),
                    $server['REMOTE_ADDR'] ?? "null",
                    $server['HTTP_X_FORWARDED_FOR'] ?? "null",
                    $response_code,
                    $server['HTTP_USER_AGENT'] ?? "null",
                    json_encode($get, JSON_UNESCAPED_UNICODE)
                ]
            ];

        $writer = Writer::createFromPath("logs/$filename", 'w+');
    } else {
        $data =
            [
                [
                    Carbon::now()->toDateTimeString(),
                    $server['REMOTE_ADDR'] ?? "null",
                    $server['HTTP_X_FORWARDED_FOR'] ?? "null",
                    $response_code,
                    $server['HTTP_USER_AGENT'] ?? "null",
                    json_encode($get, JSON_UNESCAPED_UNICODE)
                ]
            ];

        $writer = Writer::createFromPath("logs/$filename", 'a+');
    }
    $writer->insertAll($data);

    return true;
}

/**
 * Отправить сообщение в Telegram
 * @param array $GET Данные GET запроса
 */
function sendMessage(array $GET): bool
{
    $group = $GET['group'];


    try {
        $bot = new BotApi(GROUPS[$group]['token']);
        $blade = new Blade('views', 'cache');
        $text = $blade->render("message", ['data' => $GET]);

        if ($bot->sendMessage(chatId: GROUPS[$group]['chat_id'], text: $text, parseMode: "HTML")) {
            return true;
        };
    } catch (Exception $e) {
        $code = $e->getCode();
        $message = $e->getMessage();

        addLog(response_code: "$code $message", server: $_SERVER, get: $_GET); // Лог с ошибкой

        return false;
    }

    exit ("Сообщение в Telegram не доставлено");
}
