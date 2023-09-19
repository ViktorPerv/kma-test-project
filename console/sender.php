<?php

use App\Http\Kernel;
use App\Services\RabbitMqService;
use Dotenv\Dotenv;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH.'/vendor/autoload.php';

// флаг остановки
$shallStopWorking = false;

// сигнал об остановке от supervisord
pcntl_signal(SIGTERM, function () use (&$shallStopWorking) {
    $shallStopWorking = true;
    echo "Received SIGTERM\n";
});

// обработчик для ctrl+c
pcntl_signal(SIGINT, function () use (&$shallStopWorking) {
    $shallStopWorking = true;
    echo "Received SIGINT\n";
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
        $waitSeconds = rand(10, 100);
        echo 'Ожидание ' . $waitSeconds . ' секунд' . PHP_EOL;
        sleep($waitSeconds);
        try {
            $service->send($line);
            echo 'Url: ' . $line . ' отправлен' . PHP_EOL;
        } catch (Exception $e) {
            die('Отправка данных не удалась: ' . $e->getMessage()) . PHP_EOL;
        }
    }

    try {
        $service->close();
    } catch (Exception $e) {
        die('Не удалось закрыть коннект к RabbitMq ' . $e->getMessage());
    }

    echo 'Все данные отправлены' . PHP_EOL;

    // обработаем сигналы в конце итерации
    pcntl_signal_dispatch();

    die();
}

echo "Finished\n";
