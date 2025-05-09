<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\StubContent as StubContentModel;
use App\Modules\StubStorage\Domain\StubContent as StubContentEntity;
use App\Modules\StubStorage\Domain\StubContentDto;
use App\Repositories\StubContentRepository as StubContentRepositoryInterface;

class StubContentRepository implements StubContentRepositoryInterface
{
    #[\Override]
    public function find(string $filename): StubContentEntity
    {
        return $this->mapToEntity(
            $this->findByName($filename)
        );
    }

    #[\Override]
    public function create(StubContentDto $dto): StubContentEntity
    {
        $model = StubContentModel::create([
            'filename' => $dto->name,
            'content' => $dto->content,
        ]);

        return $this->mapToEntity($model);
    }

    #[\Override]
    public function delete(string $filename): void
    {
        $this->findByName($filename)->delete();
    }

    private function mapToEntity(StubContentModel $model): StubContentEntity
    {
        return new StubContentEntity(
            $model->filename,
            $model->content,
        );
    }

    private function findByName(string $filename): StubContentModel
    {
        return StubContentModel::where('filename', $filename)->firstOrFail();
    }
}
