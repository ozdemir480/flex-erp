<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Services\InvoiceService;
use App\Services\AccountingService;
use App\Domain\Repositories\InvoiceRepositoryInterface;

class InvoiceController
{
    public function __construct(
        private InvoiceService $invoices,
        private AccountingService $accounting,
        private InvoiceRepositoryInterface $repo
    ) {
    }

    public function index(Request $request): Response
    {
        $list = $this->repo->all(1);
        ob_start();
        include __DIR__ . '/../Views/invoices/index.php';
        return new Response((string)ob_get_clean(), 200, ['Content-Type' => 'text/html']);
    }

    public function show(Request $request, array $params): Response
    {
        $invoice = $this->repo->find((int)$params['id']);
        if (!$invoice) {
            return new Response('Not found', 404);
        }
        ob_start();
        include __DIR__ . '/../Views/invoices/show.php';
        return new Response((string)ob_get_clean(), 200, ['Content-Type' => 'text/html']);
    }

    public function new(Request $request): Response
    {
        ob_start();
        include __DIR__ . '/../Views/invoices/new.php';
        return new Response((string)ob_get_clean(), 200, ['Content-Type' => 'text/html']);
    }

    public function store(Request $request): Response
    {
        $items = [[
            'description' => $request->input('desc'),
            'qty' => (float)$request->input('qty'),
            'unit_price' => (float)$request->input('price'),
            'tax_rate' => (float)$request->input('tax'),
        ]];
        $invoice = $this->invoices->create(1, 1, $items, 1);
        return new Response('Invoice ' . $invoice->id, 201);
    }

    public function post(Request $request, array $params): Response
    {
        $invoice = $this->repo->find((int)$params['id']);
        if (!$invoice) {
            return new Response('Not found', 404);
        }
        $this->accounting->postInvoice($invoice);
        return new Response('Posted', 200);
    }
}
