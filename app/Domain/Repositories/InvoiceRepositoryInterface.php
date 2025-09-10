<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Invoice;

interface InvoiceRepositoryInterface
{
    public function find(int $id): ?Invoice;
    /** @return Invoice[] */
    public function all(int $companyId): array;
    public function create(Invoice $invoice): int;
    public function updateStatus(int $id, string $status): void;
}
