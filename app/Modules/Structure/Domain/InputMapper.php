<?php

declare(strict_types=1);

namespace App\Modules\Structure\Domain;

interface InputMapper
{
    /**
     * @param list<array<string, mixed>> $rawData
     */
    public function map(array $rawData): Structure;
}
