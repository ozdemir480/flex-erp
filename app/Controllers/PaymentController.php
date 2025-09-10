<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Domain\Repositories\PaymentRepositoryInterface;
use App\Services\AccountingService;

class PaymentController
{
    public function __construct(private PaymentRepositoryInterface $payments, private AccountingService $accounting)
    {
    }

    public function index(Request $request): Response
    {
        $list = $this->payments->all(1);
        ob_start();
        include __DIR__ . '/../Views/payments/index.php';
        return new Response((string)ob_get_clean(), 200, ['Content-Type' => 'text/html']);
    }

    public function create(Request $request): Response
    {
        ob_start();
        include __DIR__ . '/../Views/payments/new.php';
        return new Response((string)ob_get_clean(), 200, ['Content-Type' => 'text/html']);
    }

    public function store(Request $request): Response
    {
        $payment = new \App\Domain\Entities\Payment(0,1,1,null,date('Y-m-d'),$request->input('method'),(float)$request->input('amount'),'');
        $id = $this->payments->create($payment);
        $payment->id = $id;
        $this->accounting->postPayment($payment);
        return new Response('Payment ' . $id, 201);
    }
}
