<?php

declare(strict_types=1);

namespace App\Http;

class Request
{
    public function __construct(
        private readonly array $getParams,
        private readonly array $postData,
        private readonly array $server
    ) {
    }

    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_SERVER);
    }

    public function getMethod()
    {
        return $this->server['REQUEST_METHOD'] ?? 'GET';
    }

    public function getPath(): false|string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }
}
