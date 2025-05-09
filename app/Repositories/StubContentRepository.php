<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Modules\StubStorage\Domain\StubContent as StubContentEntity;
use App\Modules\StubStorage\Domain\StubContentDto;

interface StubContentRepository
{
    public function find(string $filename): StubContentEntity;

    public function create(StubContentDto $dto): StubContentEntity;

    public function delete(string $filename): void;
}
