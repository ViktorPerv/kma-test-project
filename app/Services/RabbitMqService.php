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
        $content = new UrlTransferDto($url, (new DateTime())->getTimestamp());

        $connection = Kernel::rabbitMq();
        $connection->sendMessage($content);
    }

    /**
     * @throws Exception
     */
    public function close(): void
    {
        $connection = Kernel::rabbitMq();
        $connection->channel()->close();
        $connection->close();
    }
}
