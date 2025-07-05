<?php

declare(strict_types=1);

namespace App\Models\Attribute;

final class AttributeItem
{
    public function __construct(
        private int $id,
        private string $value,
        private ?string $displayValue,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDisplayValue(): ?string
    {
        return $this->displayValue;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'displayValue' => $this->displayValue,
        ];
    }
}
