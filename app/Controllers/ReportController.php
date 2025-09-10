<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class ReportController
{
    public function trialBalance(Request $request): Response
    {
        return new Response(json_encode(['data' => []], JSON_THROW_ON_ERROR), 200, ['Content-Type' => 'application/json']);
    }

    public function customerStatement(Request $request, array $params): Response
    {
        return new Response(json_encode(['customer' => $params['id'] ?? null, 'entries' => []], JSON_THROW_ON_ERROR), 200, ['Content-Type' => 'application/json']);
    }
}
