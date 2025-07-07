<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Attribute\AbstractAttributeSet;
use App\Models\Attribute\SwatchAttributeSet;

final class AttributeSetResolver
{
    public function resolveId(AbstractAttributeSet $set): int
    {
        return $set->getId();
    }

    public function resolveName(AbstractAttributeSet $set): string
    {
        return $set->getName();
    }

    public function resolveType(AbstractAttributeSet $set): string
    {
        return $set instanceof SwatchAttributeSet ? 'swatch' : 'text';
    }

    public function resolveItems(AbstractAttributeSet $set): array
    {
        return $set->getValues();
    }
}
