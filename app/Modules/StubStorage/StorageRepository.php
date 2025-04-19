<?php

declare(strict_types=1);

namespace App\Modules\StubStorage;

use App\Models\Data\Stub;

interface StorageRepository
{
    public function fetchById(string $fileId): string;


    public function create(string $uuid, Stub $stub): string;

    public function get(string $uuid): Stub;

    public function save(string $uuid, Stub $output): void;
}
