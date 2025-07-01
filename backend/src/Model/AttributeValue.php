<?php

namespace App\Model;

class AttributeValue
{
    private int $id;
    private int $productId;
    private Attribute $attribute;
    private string $value;

    public function __construct(int $id, int $productId, Attribute $attribute, string $value)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->attribute = $attribute;
        $this->value = $value;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
