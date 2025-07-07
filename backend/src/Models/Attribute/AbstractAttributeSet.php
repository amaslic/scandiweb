<?php

declare(strict_types=1);

namespace App\Models\Attribute;

use Doctrine\DBAL\Connection;

abstract class AbstractAttributeSet
{
    protected Connection $connection;
    protected int $productId;

    protected int $id;

    protected string $name;

    /** @var AttributeItem[] */
    protected array $values = [];

    public function __construct(Connection $connection, int $productId, string $name, int $id)
    {
        $this->connection = $connection;
        $this->productId = $productId;
        $this->name = $name;
        $this->id = $id;
        $this->loadValues();
    }

    abstract protected function loadValues(): void;

     public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return AttributeItem[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    protected function addValue(AttributeItem $item): void
    {
        $this->values[] = $item;
    }
}
