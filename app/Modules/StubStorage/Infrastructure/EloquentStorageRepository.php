<?php

declare(strict_types=1);

namespace App\Modules\StubStorage\Infrastructure;

use App\Models\Data\Stub;
use App\Modules\StubStorage\Domain\StubContentDto;
use App\Modules\StubStorage\Infrastructure\Persistence\Eloquent\StubContentEloquentRepository;
use App\Modules\StubStorage\StorageRepository;
use App\Support\JsonParser;

readonly class EloquentStorageRepository implements StorageRepository
{
    public function __construct(
        private StubContentEloquentRepository $repository,
        private JsonParser $parser,
        private string $secretKey,
    ) {
    }
    #[\Override]
    public function fetchById(string $fileId): string
    {
        return $this->repository->find($this->hash($fileId))->content();
    }

    #[\Override]
    public function create(string $path, Stub $stub): string
    {
        $content = $stub->toJson();
        $stubName = $this->hash($path);

        $this->repository->create(new StubContentDto($stubName, $content));

        return $stubName;
    }

    #[\Override]
    public function get(string $path): Stub
    {
        $stubName = $this->hash($path);
        $json = $this->repository->find($stubName)->content();
        $data = $this->parser->parse($json);

        return Stub::fromArray($data);
    }

    #[\Override]
    public function delete(string $path): void
    {
        $this->repository->delete($this->hash($path));
    }

    private function hash(string $value): string
    {
        return hash_hmac('sha256', $value, $this->secretKey);
    }
}
