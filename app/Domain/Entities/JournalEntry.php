<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class JournalEntry
{
    /** @param JournalLine[] $lines */
    public function __construct(
        public int $id,
        public int $companyId,
        public string $date,
        public string $ref,
        public string $memo,
        public int $createdBy,
        public array $lines = []
    ) {
    }
}
