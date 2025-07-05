<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Category\Category;
use App\Repositories\CategoryRepository;

final class CategoryResolver
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function resolveAll(): array
    {
        $rows = $this->categoryRepository->getAll();

        return array_map(
            fn (array $r) => new Category((int) $r['id']),
            $rows
        );
    }
}
