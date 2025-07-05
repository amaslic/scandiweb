<?php

declare(strict_types=1);

namespace App\Models\Category;

use Doctrine\DBAL\Connection;

/**
 * Base abstract class for Category models.
 *
 * Handles generic loading of category data.
 */
abstract class AbstractCategory
{
    protected Connection $connection;
    protected int $id;
    protected string $name;

    public function __construct(int $id, Connection $connection)
    {
        $this->id = $id;
        $this->connection = $connection;
        $this->load();
    }

    abstract protected function load(): void;
    abstract public function getProducts(): array;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
