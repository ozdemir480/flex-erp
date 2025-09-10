<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class Product
{
    public function __construct(
        public int $id,
        public int $companyId,
        public string $sku,
        public string $name,
        public string $unit,
        public float $price,
        public float $taxRate,
        public bool $isService
    ) {
    }
}
