<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Domain\Repositories\CustomerRepositoryInterface;
use App\Validation\Validator;

class CustomerController
{
    public function __construct(private CustomerRepositoryInterface $customers)
    {
    }

    public function index(Request $request): Response
    {
        $list = $this->customers->all(1);
        ob_start();
        include __DIR__ . '/../Views/customers/index.php';
        return new Response((string)ob_get_clean(), 200, ['Content-Type' => 'text/html']);
    }

    public function create(Request $request): Response
    {
        ob_start();
        include __DIR__ . '/../Views/customers/create.php';
        return new Response((string)ob_get_clean(), 200, ['Content-Type' => 'text/html']);
    }

    public function store(Request $request): Response
    {
        $data = [
            'code' => $request->input('code'),
            'name' => $request->input('name'),
        ];
        $errors = Validator::required($data, ['code', 'name']);
        if ($errors) {
            return new Response('Validation error', 400);
        }
        $customer = new \App\Domain\Entities\Customer(0, 1, $data['code'], $data['name'], '', '', '', '', '', 0.0);
        $id = $this->customers->create($customer);
        return new Response('Created ID ' . $id, 201);
    }
}
