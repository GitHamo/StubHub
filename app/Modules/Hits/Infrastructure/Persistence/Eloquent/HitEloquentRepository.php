<?php

declare(strict_types=1);

namespace App\Modules\Hits\Infrastructure\Persistence\Eloquent;

use App\Modules\Hits\Domain\HitDto;
use App\Modules\Hits\Domain\HitRepository;
use App\Modules\Hits\Infrastructure\Persistence\Eloquent\Hit as HitModel;

class HitEloquentRepository implements HitRepository
{
    #[\Override]
    public function create(HitDto $hitDto): void
    {
        HitModel::create([
            'endpoint_id' => $hitDto->endpointId,
            'signature' => $hitDto->signature,
        ]);
    }
}
