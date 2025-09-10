<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Http\Request;
use App\Http\Response;

interface RouterInterface
{
    public function get(string $path, callable|array $handler): void;
    public function post(string $path, callable|array $handler): void;
    public function dispatch(Request $request): Response;
}
