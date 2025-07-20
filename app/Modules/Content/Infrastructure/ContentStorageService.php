<?php

declare(strict_types=1);

namespace App\Modules\Content\Infrastructure;

use App\Models\Data\SaveStubContentData;
use App\Models\Domain\Stub;
use App\Modules\Content\Domain\StubStorage;
use App\Repositories\StubContentRepository;

final readonly class ContentStorageService implements StubStorage
{
    private const int PATH_LENGTH = 20;

    public function __construct(
        private StubContentRepository $repository,
        private EncryptionHelper $encryptionHelper,
    ) {
    }

    #[\Override]
    public function get(string $path): string
    {
        return $this->repository->find($this->getStubName($path))->content();
    }

    #[\Override]
    public function create(Stub $stub): string
    {
        $path = $this->encryptionHelper->random(self::PATH_LENGTH);

        $this->repository->create($this->createSaveStubContentData($path, $stub));

        return $path;
    }

    #[\Override]
    public function update(string $path, Stub $stub): void
    {
        $this->repository->update($this->createSaveStubContentData($path, $stub));
    }

    #[\Override]
    public function delete(string $path): void
    {
        $this->repository->delete($this->getStubName($path));
    }

    private function createSaveStubContentData(string $path, Stub $stub): SaveStubContentData
    {
        return new SaveStubContentData($this->getStubName($path), $stub);
    }

    private function getStubName(string $path): string
    {
        if (trim($path) === '') {
            throw new \InvalidArgumentException('Path must not be empty.');
        }

        return $this->encryptionHelper->hash($path);
    }
}
