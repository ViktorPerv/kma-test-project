<?php

declare(strict_types=1);

namespace App\Services;

use App\Dtos\UrlTransferDto;
use App\Http\Kernel;
use DateTime;
use Exception;

class RabbitMqService
{
    /**
     * @throws Exception
     */
    public function send(string $url): void
    {
        $content = new UrlTransferDto($url, (new DateTime)->getTimestamp());

        $connection = Kernel::rabbitMq();
        $connection->sendMessage($content);
    }

    public function receive(): void
    {
        /**
         * @throws Exception
         */
        $callback = function($msg) {
            $content = new ContentService();
            $urlTransferDto = unserialize($msg->body);
            if($urlTransferDto instanceof UrlTransferDto) {
                $content->addContent($urlTransferDto);
            }
        };

        $connection = Kernel::rabbitMq();

        $connection->channel->basic_qos(null, 1, null);
        $connection->channel->basic_consume('kma', '', false, false, false, false, $callback);

        while(count($connection->channel->callbacks)) {
            $connection->channel->wait();
        }
    }
}
