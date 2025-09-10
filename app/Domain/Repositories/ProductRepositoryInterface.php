<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Product;

interface ProductRepositoryInterface
{
    public function find(int $id): ?Product;
    /** @return Product[] */
    public function all(int $companyId): array;
    public function create(Product $product): int;
}
