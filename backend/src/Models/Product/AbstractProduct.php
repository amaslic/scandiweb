<?php

declare(strict_types=1);

namespace App\Models\Product;

use Doctrine\DBAL\Connection;

/**
 * Base abstract class for Product models.
 *
 * Defines core properties and loading contract for all product types.
 */
abstract class AbstractProduct
{
    protected  $connection;
    protected int $id;
    protected string $sku;
    protected string $name;
    protected bool $inStock;
    protected array $gallery = [];
    protected string $description;
    protected int $categoryId;
    protected string $brand;

    public function __construct(int $id, Connection $connection)
    {
        $this->connection = $connection;
        $this->id = $id;
        $this->load();
    }

    abstract protected function load(): void;
    abstract public function getPrices(): array;
    abstract public function getAttributes(): array;

    public function getId(): int
    {
        return $this->id;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isInStock(): bool
    {
        return $this->inStock;
    }

    public function getGallery(): array
    {
        return $this->gallery;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }
}
