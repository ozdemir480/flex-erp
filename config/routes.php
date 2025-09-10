<?php

declare(strict_types=1);

use App\Controllers\{HomeController,AuthController,CustomerController,ProductController,InvoiceController,PaymentController,JournalController,ReportController};
use App\Controllers\HomeController;
use App\Interfaces\RouterInterface;

return static function (RouterInterface $router): void {
    $router->get('/', [HomeController::class, 'index']);

    $router->get('/auth/login', [AuthController::class, 'loginForm']);
    $router->post('/auth/login', [AuthController::class, 'login']);
    $router->post('/auth/logout', [AuthController::class, 'logout']);

    $router->get('/customers', [CustomerController::class, 'index']);
    $router->get('/customers/create', [CustomerController::class, 'create']);
    $router->post('/customers', [CustomerController::class, 'store']);

    $router->get('/products', [ProductController::class, 'index']);
    $router->get('/products/create', [ProductController::class, 'create']);
    $router->post('/products', [ProductController::class, 'store']);

    $router->get('/invoices', [InvoiceController::class, 'index']);
    $router->get('/invoices/new', [InvoiceController::class, 'new']);
    $router->post('/invoices', [InvoiceController::class, 'store']);
    $router->get('/invoices/{id}', [InvoiceController::class, 'show']);
    $router->post('/invoices/{id}/post', [InvoiceController::class, 'post']);

    $router->get('/payments', [PaymentController::class, 'index']);
    $router->get('/payments/new', [PaymentController::class, 'create']);
    $router->post('/payments', [PaymentController::class, 'store']);

    $router->get('/journal', [JournalController::class, 'index']);
    $router->get('/journal/{id}', [JournalController::class, 'show']);

    $router->get('/reports/trial-balance', [ReportController::class, 'trialBalance']);
    $router->get('/reports/customer-statement/{id}', [ReportController::class, 'customerStatement']);
};
