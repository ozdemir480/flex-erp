<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\LoggerInterface;

class LoggerService implements LoggerInterface
{
    public function __construct(private string $path)
    {
    }

    public function log(string $message): void
    {
        $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
        file_put_contents($this->path, $line, FILE_APPEND);
    }
}
