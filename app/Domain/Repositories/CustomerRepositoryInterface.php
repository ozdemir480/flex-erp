<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Customer;

interface CustomerRepositoryInterface
{
    public function find(int $id): ?Customer;
    /** @return Customer[] */
    public function all(int $companyId): array;
    public function create(Customer $customer): int;
}
