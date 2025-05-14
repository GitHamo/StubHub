<?php

declare(strict_types=1);

namespace App\Models\Data;

use App\Api\Data\Serializable\Arrayable;

abstract class StructureInput implements Arrayable
{
    public function __construct(
        public string $key,
    ) {
    }

    #[\Override]
    public function toArray(): array
    {
        return [
            'key' => $this->key,
        ];
    }
}
