<?php

use App\Dtos\UrlTransferDto;
use App\Http\Kernel;
use App\Services\ContentService;
use App\Services\RabbitMqService;
use Dotenv\Dotenv;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH.'/vendor/autoload.php';
$consumerTag = 'consumer';

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$kernel = new Kernel();

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
    $service = new RabbitMqService();
    try {
        $service->close();
    } catch (Exception $e) {
        die('Не удалось закрыть коннект к RabbitMq ' . $e->getMessage());
    }
});

echo "Started\n";

while (!$shallStopWorking) {

    /**
     * @throws Exception
     */
    $callback = function ($msg) {
        $content = new ContentService();
        $urlTransferDto = unserialize($msg->body);
        if($urlTransferDto instanceof UrlTransferDto) {
            echo "Получение данных из rabbitMq" . PHP_EOL;
            $content->addContent($urlTransferDto);
        }
        $msg->ack();
    };

    $connection = $kernel::rabbitMq();

    $connection->channel->basic_qos(null, 1, null);
    $connection->channel->basic_consume($_ENV['QUEUE_NAME'], $consumerTag, false, false, false, false, $callback);

    while(count($connection->channel->callbacks)) {
        $connection->channel->wait();
        // обработаем сигналы в конце итерации
    }

    pcntl_signal_dispatch();
}

echo "Finished\n";
