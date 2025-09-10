<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class InvoiceItem
{
    public function __construct(
        public int $id,
        public int $invoiceId,
        public ?int $productId,
        public string $description,
        public float $qty,
        public float $unitPrice,
        public float $taxRate,
        public float $lineSubtotal,
        public float $lineTax,
        public float $lineTotal
    ) {
    }
}
