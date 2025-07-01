<?php

namespace App\Repository;

use App\Model\Attribute;
use PDO;

class AttributeRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get all attributes.
     *
     * @return Attribute[]
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT id, name FROM attributes");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $attributes = [];
        foreach ($rows as $row) {
            $attributes[] = new Attribute((int) $row['id'], $row['name']);
        }

        return $attributes;
    }

    public function findById(int $id): ?Attribute
    {
        $stmt = $this->pdo->prepare("SELECT id, name FROM attributes WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Attribute((int) $row['id'], $row['name']);
    }
}
