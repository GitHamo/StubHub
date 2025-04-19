<?php

declare(strict_types=1);

namespace App\Modules\Endpoints\Infrastructure\Persistence\Eloquent;

use App\Modules\Endpoints\Infrastructure\Persistence\Eloquent\Endpoint as EndpointModel;
use App\Modules\Endpoints\Domain\Endpoint as EndpointEntity;
use App\Modules\Endpoints\Domain\EndpointDto;
use App\Modules\Endpoints\Domain\EndpointRepository;

class EndpointEloquentRepository implements EndpointRepository
{
    #[\Override]
    public function findById(string $id): ?EndpointEntity
    {
        $model = EndpointModel::where('id', $id)->first();

        if ($model === null) {
            return null;
        }

        return $this->mapToEntity($model);
    }

    #[\Override]
    public function create(EndpointDto $endpointDto): EndpointEntity
    {
        $model = EndpointModel::create([
            'id' => $endpointDto->id,
            'user_id' => $endpointDto->userId,
            'path' => $endpointDto->path,
            'name' => $endpointDto->name,
        ]);

        return $this->mapToEntity($model);
    }


    private function mapToEntity(EndpointModel $model): EndpointEntity
    {
        return new EndpointEntity(
            $model->id,
            $model->user_id,
            $model->path,
            $model->name,
        );
    }
}
