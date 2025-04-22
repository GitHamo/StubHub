<?php

declare(strict_types=1);

namespace App\Models\Data\Inputs;

use App\Enums\StubFieldContext;

readonly class Single extends Input
{
    public function __construct(string $key, public StubFieldContext $context)
    {
        parent::__construct($key);
    }
}
