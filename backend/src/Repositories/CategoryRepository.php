<?php

declare(strict_types=1);

namespace App\Repositories;

use Doctrine\DBAL\Connection;

final class CategoryRepository
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        return $this->db->createQueryBuilder()
            ->select('id')
            ->from('categories')
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
