<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Core\DB;
use App\Domain\Entities\Account;
use App\Domain\Repositories\AccountRepositoryInterface;
use PDO;

class PdoAccountRepository implements AccountRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::get();
    }

    public function find(int $id): ?Account
    {
        $stmt = $this->pdo->prepare('SELECT * FROM accounts WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $this->map($row) : null;
    }

    public function all(int $companyId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM accounts WHERE company_id = ?');
        $stmt->execute([$companyId]);
        $rows = $stmt->fetchAll();
        return array_map(fn($r) => $this->map($r), $rows);
    }

    public function create(Account $account): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO accounts (company_id, code, name, type_id, is_active) VALUES (?,?,?,?,?)');
        $stmt->execute([
            $account->companyId,
            $account->code,
            $account->name,
            $account->typeId,
            $account->isActive,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function findByCode(int $companyId, string $code): ?Account
    {
        $stmt = $this->pdo->prepare('SELECT * FROM accounts WHERE company_id = ? AND code = ?');
        $stmt->execute([$companyId, $code]);
        $row = $stmt->fetch();
        return $row ? $this->map($row) : null;
    }

    private function map(array $row): Account
    {
        return new Account(
            (int)$row['id'],
            (int)$row['company_id'],
            $row['code'],
            $row['name'],
            (int)$row['type_id'],
            (bool)$row['is_active']
        );
    }
}
