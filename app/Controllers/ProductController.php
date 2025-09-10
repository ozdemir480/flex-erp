<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Validation\Validator;

class ProductController
{
    public function __construct(private ProductRepositoryInterface $products)
    {
    }

    public function index(Request $request): Response
    {
        $list = $this->products->all(1);
        ob_start();
        include __DIR__ . '/../Views/products/index.php';
        return new Response((string)ob_get_clean(), 200, ['Content-Type' => 'text/html']);
    }

    public function create(Request $request): Response
    {
        ob_start();
        include __DIR__ . '/../Views/products/create.php';
        return new Response((string)ob_get_clean(), 200, ['Content-Type' => 'text/html']);
    }

    public function store(Request $request): Response
    {
        $data = [
            'sku' => $request->input('sku'),
            'name' => $request->input('name'),
            'unit' => $request->input('unit'),
            'price' => (float)$request->input('price'),
            'tax' => (float)$request->input('tax'),
        ];
        $errors = Validator::required($data, ['sku','name']);
        if ($errors) {
            return new Response('Validation error', 400);
        }
        $product = new \App\Domain\Entities\Product(0,1,$data['sku'],$data['name'],$data['unit'],$data['price'],$data['tax'],false);
        $id = $this->products->create($product);
        return new Response('Created ID ' . $id, 201);
    }
}
