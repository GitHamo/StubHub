<?php

declare(strict_types=1);

namespace App\Models\Data\Inputs;

readonly class Nested extends Input
{
    public function __construct(string $key, public array $inputs)
    {
        parent::__construct($key);
    }
}
