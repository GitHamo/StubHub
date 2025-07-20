<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Data\SaveStubContentData;
use App\Models\Domain\StubContent as StubContentEntity;
use App\Models\Eloquent\StubContent as StubContentModel;
use App\Repositories\StubContentRepository as StubContentRepositoryInterface;
use App\Support\StrictJson;

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
    public function create(SaveStubContentData $dto): StubContentEntity
    {
        $model = StubContentModel::create([
            'filename' => $dto->name,
            'content' => StrictJson::encode($dto->stub->jsonSerialize()),
        ]);

        return $this->mapToEntity($model);
    }

    #[\Override]
    public function update(SaveStubContentData $dto): StubContentEntity
    {
        $model = $this->findByName($dto->name);

        $model->update([
            'content' => StrictJson::encode($dto->stub->jsonSerialize()),
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
