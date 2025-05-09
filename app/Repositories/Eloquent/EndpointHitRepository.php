<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Hit as EndpointHitModel;
use App\Modules\Hits\Domain\HitDto;
use App\Repositories\EndpointHitRepository as EndpointHitRepositoryInterface;

class EndpointHitRepository implements EndpointHitRepositoryInterface
{
    #[\Override]
    public function create(HitDto $hitDto): void
    {
        EndpointHitModel::create([
            'endpoint_id' => $hitDto->endpointId,
            'signature' => $hitDto->signature,
        ]);
    }
}
