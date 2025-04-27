<?php

declare(strict_types=1);

namespace App\Models\Data\Inputs;

readonly class Nested extends Input
{
    /**
     * @param Input[] $inputs
     */
    public function __construct(
        string $key,
        public array $inputs,
        public int $repeat,
    ) {
        parent::__construct($key);
    }
}
