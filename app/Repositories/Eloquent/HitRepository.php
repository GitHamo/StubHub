<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Modules\Hits\Domain\HitDto;
use App\Modules\Hits\Domain\HitRepository as HitRepositoryInterface;
use App\Modules\Hits\Infrastructure\Persistence\Eloquent\Hit as HitModel;

class HitRepository implements HitRepositoryInterface
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
