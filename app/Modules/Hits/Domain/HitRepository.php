<?php

declare(strict_types=1);

namespace App\Modules\Hits\Domain;

interface HitRepository
{
    public function create(HitDto $hitDto): void;
}
