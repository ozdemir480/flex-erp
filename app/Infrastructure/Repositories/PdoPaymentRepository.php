<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Core\DB;
use App\Domain\Entities\Payment;
use App\Domain\Repositories\PaymentRepositoryInterface;
use PDO;

class PdoPaymentRepository implements PaymentRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::get();
    }

    public function find(int $id): ?Payment
    {
        $stmt = $this->pdo->prepare('SELECT * FROM payments WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $this->map($row) : null;
    }

    public function all(int $companyId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM payments WHERE company_id = ?');
        $stmt->execute([$companyId]);
        $rows = $stmt->fetchAll();
        return array_map(fn($r) => $this->map($r), $rows);
    }

    public function create(Payment $payment): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO payments (company_id, customer_id, invoice_id, date, method, amount, notes) VALUES (?,?,?,?,?,?,?)');
        $stmt->execute([
            $payment->companyId,
            $payment->customerId,
            $payment->invoiceId,
            $payment->date,
            $payment->method,
            $payment->amount,
            $payment->notes,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    private function map(array $row): Payment
    {
        return new Payment(
            (int)$row['id'],
            (int)$row['company_id'],
            (int)$row['customer_id'],
            $row['invoice_id'] ? (int)$row['invoice_id'] : null,
            $row['date'],
            $row['method'],
            (float)$row['amount'],
            $row['notes']
        );
    }
}
