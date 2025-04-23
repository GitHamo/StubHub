<?php

declare(strict_types=1);

namespace App\Modules\StubStorage\Infrastructure\Persistence\Eloquent;

use App\Modules\StubStorage\Domain\StubContent as StubContentEntity;
use App\Modules\StubStorage\Domain\StubContentDto;
use App\Modules\StubStorage\Infrastructure\Persistence\Eloquent\StubContent as StubContentModel;

readonly class StubContentEloquentRepository
{
    public function find(string $filename): StubContentEntity
    {
        return $this->mapToEntity(
            $this->findByName($filename)
        );
    }

    public function create(StubContentDto $dto): StubContentEntity
    {
        $model = StubContentModel::create([
            'filename' => $dto->name,
            'content' => $dto->content,
        ]);

        return $this->mapToEntity($model);
    }

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
        return StubContent::where('filename', $filename)->firstOrFail();
    }
}
