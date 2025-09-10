<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class Payment
{
    public function __construct(
        public int $id,
        public int $companyId,
        public int $customerId,
        public ?int $invoiceId,
        public string $date,
        public string $method,
        public float $amount,
        public string $notes
    ) {
    }
}
