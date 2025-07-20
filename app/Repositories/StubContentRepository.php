<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Data\SaveStubContentData;
use App\Models\Domain\StubContent;

interface StubContentRepository
{
    public function find(string $filename): StubContent;

    public function create(SaveStubContentData $dto): StubContent;

    public function update(SaveStubContentData $dto): StubContent;

    public function delete(string $filename): void;
}
