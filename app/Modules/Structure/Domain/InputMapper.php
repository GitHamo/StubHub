<?php

declare(strict_types=1);

namespace App\Modules\Structure\Domain;

use App\Modules\Structure\Domain\Structure;

interface InputMapper
{
    /**
     * @param list<array<string, mixed>> $rawData
     */
    public function map(array $rawData): Structure;
}
