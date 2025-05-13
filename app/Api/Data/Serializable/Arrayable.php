<?php

declare(strict_types=1);

namespace App\Api\Data\Serializable;

interface Arrayable
{
    /** @return array<mixed> */
    public function toArray(): array;
}
