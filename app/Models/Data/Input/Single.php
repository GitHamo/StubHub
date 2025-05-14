<?php

declare(strict_types=1);

namespace App\Models\Data\Input;

use App\Enums\StubFieldContext;
use App\Models\Data\StructureInput as StructureInput;

class Single extends StructureInput
{
    public function __construct(string $key, public StubFieldContext $context)
    {
        parent::__construct($key);
    }

    #[\Override]
    public function toArray(): array
    {
        return parent::toArray() + [
            'context' => $this->context->value,
        ];
    }
}
