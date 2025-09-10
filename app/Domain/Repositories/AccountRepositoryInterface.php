<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Account;

interface AccountRepositoryInterface
{
    public function find(int $id): ?Account;
    /** @return Account[] */
    public function all(int $companyId): array;
    public function create(Account $account): int;
    public function findByCode(int $companyId, string $code): ?Account;
}
