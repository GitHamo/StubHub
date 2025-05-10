<?php

declare(strict_types=1);

namespace App\Modules\Content\Infrastructure;

use App\Models\Data\Stub;
use App\Modules\Content\Domain\Storage;
use App\Modules\StubStorage\Domain\StubContentDto;
use App\Repositories\Eloquent\StubContentRepository;

final readonly class ContentStorageService implements Storage
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
        $stubName = $this->getStubName($path);

        $this->repository->create(new StubContentDto($stubName, $stub->toJson()));

        return $path;
    }

    #[\Override]
    public function delete(string $path): void
    {
        $this->repository->delete($this->getStubName($path));
    }

    private function getStubName(string $path): string
    {
        if (trim($path) === '') {
            throw new \InvalidArgumentException('Path must not be empty.');
        }

        return $this->encryptionHelper->hash($path);
    }
}
