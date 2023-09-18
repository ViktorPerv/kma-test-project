<?php

declare(strict_types=1);

namespace App\Dtos;

class UrlTransferDto
{
    public function __construct(
        public string $url,
        public int $timestamp
    ) {
    }
}
