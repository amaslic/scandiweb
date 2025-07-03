<?php

namespace App\Repository;

use App\Model\Category;
use PDO;

class CategoryRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get all Categories.
     *
     * @return Category[]
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT id, name FROM categories");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];
        foreach ($rows as $row) {
            $categories[] = new Category((int) $row['id'], $row['name']);
        }

        return $categories;
    }

    /**
     * Get category by ID.
     *
     * @param int $id
     * @return Category|null
     */
    public function findById(int $id): ?Category
    {
        $stmt = $this->pdo->prepare("SELECT id, name FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Category((int) $row['id'], $row['name']);
    }

    public function findByName(string $name): ?Category
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE name = :name");
        $stmt->execute(['name' => $name]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Category((int) $row['id'], $row['name']);
    }
}
