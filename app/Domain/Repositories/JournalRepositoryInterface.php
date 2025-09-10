<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\JournalEntry;

interface JournalRepositoryInterface
{
    public function find(int $id): ?JournalEntry;
    /** @return JournalEntry[] */
    public function recent(int $companyId, int $limit = 20): array;
    public function create(JournalEntry $entry): int;
}
