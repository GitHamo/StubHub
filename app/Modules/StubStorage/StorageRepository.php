<?php

declare(strict_types=1);

namespace App\Modules\StubStorage;

use App\Models\Data\Stub;

interface StorageRepository
{
    public function fetchById(string $fileId): string;


    public function create(string $path, Stub $stub): string;

    public function get(string $path): Stub;

    public function save(string $path, Stub $output): void;
}
