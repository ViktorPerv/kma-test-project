<?php

declare(strict_types=1);

namespace App\db;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * @mixin AMQPStreamConnection
 */
class RabbitMq
{
    private AMQPStreamConnection $connection;
    public AMQPChannel $channel;
    /**
     * @throws Exception
     */
    public function __construct()
    {
        try {
            $this->connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
            $this->channel = $this->connection->channel();
            $this->channel->queue_declare('kma', false, true, false, false);
        } catch (Exception $exception) {
            die('Подключение RabbitMq не установилось: ' . $exception->getMessage()) . PHP_EOL;
        }
    }

    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->connection, $name], $arguments);
    }

    public function sendMessage(object $model): void
    {
        $msg = new AMQPMessage(serialize($model), [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        echo '[x] Sent' . PHP_EOL;

        $this->channel->basic_publish($msg, '', 'kma');
    }
}
