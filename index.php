<?php

use Carbon\Carbon;
use TelegramBot\Api\BotApi;
use Symfony\Component\HttpFoundation\Request;
use Jenssegers\Blade\Blade;

use League\Csv\Writer;

require "vendor/autoload.php";
require "config.php";


getRequest();


function getRequest(): bool|string
{
    new Request($_GET,
        $_POST,
        [],
        $_COOKIE,
        $_FILES,
        $_SERVER);


    if (!empty($_GET)) {
        $need_param = "текст";

        if (isset($_GET[$need_param])) {
            $response_code = sendMessage(data: $_GET);

            if ($response_code == 200) {
                if (addLog(response_code: $response_code, server: $_SERVER, get: $_GET)) {
                    exit ("Сообщение отправлено. Лог обновлен");
                } else {
                    exit ("Сообщение отправлено. Ошибка записи в лог.");
                }
            } else {
                exit ("Сообщение Telegram не отправлено.");
            }
        }

        exit ("Отсутствует обязательный параметр: [$need_param]");
    }

    exit ("Пустой GET запрос");
}

function addLog(int $response_code, array $server, array $get): bool
{
    $filename = LOG_FILE_NAME . "_" . Carbon::now()->timestamp . ".csv";
    if (fopen("logs/$filename", "w+")) {
        $data =
            [
                ["DATE_TIME", "REMOTE_ADDR", "HTTP_X_FORWARDED_FOR", "TELEGRAM_CODE_RESPONSE", "HTTP_USER_AGENT"],
                [Carbon::now()->toDateTimeString(), $server['REMOTE_ADDR'], $server['HTTP_X_FORWARDED_FOR'] ?? "null", $response_code, $server['HTTP_USER_AGENT']]
            ];

        $writer = Writer::createFromPath("logs/$filename", 'w+');
        $writer->insertAll($data);
        return true;

    }

    return false;
}

function sendMessage(array $data): float|int
{
    try {
        $bot = new BotApi(token: TOKEN);
        $blade = new Blade('views', 'views');
        $text = $blade->render("message", ['data' => $data]);

        if ($bot->sendMessage(chatId: CID, text: "$text", parseMode: "HTML")) {
            return 200;
        };
    } catch (Exception $e) {
        $code = $e->getCode();
        $message = $e->getMessage();

        return $code;

        //TODO: Лог в файл об ошибке
    }

    exit ("Сообщение в Telegram не доставлено");
}
