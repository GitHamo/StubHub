<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Data\CreateEndpointHitData;
use App\Models\Eloquent\EndpointHit as EndpointHitModel;
use App\Repositories\EndpointHitRepository as EndpointHitRepositoryInterface;

class EndpointHitRepository implements EndpointHitRepositoryInterface
{
    #[\Override]
    public function create(CreateEndpointHitData $hitDto): void
    {
        EndpointHitModel::create([
            'endpoint_id' => $hitDto->endpointId,
            'signature' => $hitDto->signature,
        ]);
    }
}
