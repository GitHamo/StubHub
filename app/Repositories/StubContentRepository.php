<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Data\CreateStubContentData;
use App\Models\Domain\StubContent;

interface StubContentRepository
{
    public function find(string $filename): StubContent;

    public function create(CreateStubContentData $dto): StubContent;

    public function delete(string $filename): void;
}
