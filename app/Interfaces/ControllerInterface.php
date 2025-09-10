<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Http\Request;
use App\Http\Response;

interface ControllerInterface
{
    public function index(Request $request, array $params = []): Response;
}
