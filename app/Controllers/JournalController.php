<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Domain\Repositories\JournalRepositoryInterface;

class JournalController
{
    public function __construct(private JournalRepositoryInterface $journals)
    {
    }

    public function index(Request $request): Response
    {
        $list = $this->journals->recent(1);
        return new Response(json_encode($list, JSON_THROW_ON_ERROR), 200, ['Content-Type' => 'application/json']);
    }

    public function show(Request $request, array $params): Response
    {
        $entry = $this->journals->find((int)$params['id']);
        if (!$entry) {
            return new Response('Not found', 404);
        }
        return new Response(json_encode($entry, JSON_THROW_ON_ERROR), 200, ['Content-Type' => 'application/json']);
    }
}
