<?php

declare(strict_types=1);

namespace App\Models\Data\Input;

use App\Enums\StubFieldContext;
use App\Models\Data\Input;

class Single extends Input
{
    public function __construct(string $key, public StubFieldContext $context)
    {
        parent::__construct($key);
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return parent::jsonSerialize() + [
            'context' => $this->context->value,
        ];
    }
}
