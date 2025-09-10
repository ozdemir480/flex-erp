<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class Account
{
    public function __construct(
        public int $id,
        public int $companyId,
        public string $code,
        public string $name,
        public int $typeId,
        public bool $isActive
    ) {
    }
}
