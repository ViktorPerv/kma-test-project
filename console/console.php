<?php

use App\Dtos\UrlTransferDto;
use App\Http\Kernel;
use App\Services\ContentService;
use App\Services\RabbitMqService;
use Dotenv\Dotenv;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH.'/vendor/autoload.php';

// флаг остановки
$shallStopWorking = false;

// сигнал об остановке от supervisord
pcntl_signal(SIGTERM, function () use (&$shallStopWorking) {
    echo "Received SIGTERM\n";
    $shallStopWorking = true;
});

// обработчик для ctrl+c
pcntl_signal(SIGINT,  function () use (&$shallStopWorking) {
    echo "Received SIGINT\n";
    $shallStopWorking = true;
});

echo "Started\n";

while (!$shallStopWorking) {

    $dotenv = Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();

    $kernel = new Kernel();

    $file = BASE_PATH . '/' . $_ENV['URLS_FILE'];

    $fileObj = new SplFileObject($file);
    $service = new RabbitMqService();

    foreach ($fileObj as $line) {
        echo 'Отправка данных в Rabbit' . PHP_EOL;
        $waitSeconds = rand(10,100);
        echo 'Ожидание ' . $waitSeconds . ' секунд';
        sleep($waitSeconds);
        try {
            $service->send($line);
        } catch (Exception $e) {
            die('Отправка данных не удалась: ' . $e->getMessage());
        }
    }

    $service->receive();

    // обработаем сигналы в конце итерации
    pcntl_signal_dispatch();
}

die("Finished\n");
