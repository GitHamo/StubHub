<?php

declare(strict_types=1);

namespace App\Models\Data\Input;

use App\Models\Data\StructureInput as StructureInput;

class Nested extends StructureInput
{
    /**
     * @param StructureInput[] $inputs
     */
    public function __construct(
        string $key,
        public array $inputs,
        public int $repeat,
    ) {
        parent::__construct($key);
    }

    #[\Override]
    public function toArray(): array
    {
        return parent::toArray() + [
            'inputs' => array_map(fn (StructureInput $input): array => $input->toArray(), $this->inputs),
            'repeat' => $this->repeat,
        ];
    }
}
