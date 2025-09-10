<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Core\DB;
use App\Domain\Entities\Customer;
use App\Domain\Repositories\CustomerRepositoryInterface;
use PDO;

class PdoCustomerRepository implements CustomerRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::get();
    }

    public function find(int $id): ?Customer
    {
        $stmt = $this->pdo->prepare('SELECT * FROM customers WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $this->map($row) : null;
    }

    public function all(int $companyId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM customers WHERE company_id = ?');
        $stmt->execute([$companyId]);
        $rows = $stmt->fetchAll();
        return array_map(fn($r) => $this->map($r), $rows);
    }

    public function create(Customer $customer): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO customers (company_id, code, name, tax_no, phone, email, billing_address, shipping_address, balance) VALUES (?,?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $customer->companyId,
            $customer->code,
            $customer->name,
            $customer->taxNo,
            $customer->phone,
            $customer->email,
            $customer->billingAddress,
            $customer->shippingAddress,
            $customer->balance,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    private function map(array $row): Customer
    {
        return new Customer(
            (int)$row['id'],
            (int)$row['company_id'],
            $row['code'],
            $row['name'],
            $row['tax_no'],
            $row['phone'],
            $row['email'],
            $row['billing_address'],
            $row['shipping_address'],
            (float)$row['balance']
        );
    }
}
