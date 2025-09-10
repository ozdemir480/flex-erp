<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class Invoice
{
    /** @param InvoiceItem[] $items */
    public function __construct(
        public int $id,
        public int $companyId,
        public int $customerId,
        public string $number,
        public string $issueDate,
        public string $dueDate,
        public string $status,
        public string $currencyCode,
        public float $subtotal,
        public float $taxTotal,
        public float $discountTotal,
        public float $total,
        public string $notes,
        public int $createdBy,
        public array $items = []
    ) {
    }
}
