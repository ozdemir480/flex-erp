<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Core\DB;
use App\Domain\Entities\Product;
use App\Domain\Repositories\ProductRepositoryInterface;
use PDO;

class PdoProductRepository implements ProductRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::get();
    }

    public function find(int $id): ?Product
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $this->map($row) : null;
    }

    public function all(int $companyId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE company_id = ?');
        $stmt->execute([$companyId]);
        $rows = $stmt->fetchAll();
        return array_map(fn($r) => $this->map($r), $rows);
    }

    public function create(Product $product): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO products (company_id, sku, name, unit, price, tax_rate, is_service) VALUES (?,?,?,?,?,?,?)');
        $stmt->execute([
            $product->companyId,
            $product->sku,
            $product->name,
            $product->unit,
            $product->price,
            $product->taxRate,
            $product->isService,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    private function map(array $row): Product
    {
        return new Product(
            (int)$row['id'],
            (int)$row['company_id'],
            $row['sku'],
            $row['name'],
            $row['unit'],
            (float)$row['price'],
            (float)$row['tax_rate'],
            (bool)$row['is_service']
        );
    }
}
