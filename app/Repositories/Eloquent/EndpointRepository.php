<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Modules\Endpoints\Infrastructure\Persistence\Eloquent\Endpoint as EndpointModel;
use App\Modules\Endpoints\Domain\Endpoint as EndpointEntity;
use App\Modules\Endpoints\Domain\EndpointDto;
use App\Modules\Endpoints\Domain\EndpointRepository as EndpointRepositoryInterface;

class EndpointRepository implements EndpointRepositoryInterface
{
    #[\Override]
    public function findByUserId(int $userId, int $limit): array
    {
        $models = EndpointModel::select('endpoints.*')
            ->where('user_id', $userId)
            ->leftJoin('endpoint_hits', 'endpoint_hits.endpoint_id', '=', 'endpoints.id')
            ->selectRaw('count(distinct endpoint_hits.signature) as unique_hits')
            ->withCount('hits as total_hits')
            ->groupBy(
                'endpoints.id',
                'endpoints.user_id',
                'endpoints.path',
                'endpoints.name',
                'endpoints.inputs',
                'endpoints.created_at',
                'endpoints.updated_at',
            )
            ->orderByDesc('endpoints.created_at')
            ->limit($limit)
            ->get()
            ->all();

        return array_map(fn (EndpointModel $model): EndpointEntity => $this->mapToEntity($model), $models);
    }

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
            'inputs' => $endpointDto->inputs,
        ]);

        return $this->mapToEntity($model);
    }

    #[\Override]
    public function deleteById(string $id): void
    {
        EndpointModel::where('id', $id)->delete();
    }

    private function mapToEntity(EndpointModel $model): EndpointEntity
    {
        return new EndpointEntity(
            $model->id,
            $model->user_id,
            $model->path,
            $model->name,
            $model->unique_hits ?? 0,
            $model->total_hits ?? 0,
            $model->created_at,
        );
    }
}
