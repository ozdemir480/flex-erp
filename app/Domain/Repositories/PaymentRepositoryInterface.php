<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Payment;

interface PaymentRepositoryInterface
{
    public function find(int $id): ?Payment;
    /** @return Payment[] */
    public function all(int $companyId): array;
    public function create(Payment $payment): int;
}
