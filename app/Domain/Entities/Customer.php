<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class Customer
{
    public function __construct(
        public int $id,
        public int $companyId,
        public string $code,
        public string $name,
        public string $taxNo,
        public string $phone,
        public string $email,
        public string $billingAddress,
        public string $shippingAddress,
        public float $balance
    ) {
    }
}
