<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class JournalLine
{
    public function __construct(
        public int $id,
        public int $entryId,
        public int $accountId,
        public float $debit,
        public float $credit,
        public string $memo
    ) {
    }
}
