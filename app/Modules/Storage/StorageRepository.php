<?php

declare(strict_types=1);

namespace App\Modules\Storage;

use App\Models\Data\Stub;

interface StorageRepository
{
    public function get(string $uuid): Stub;

    public function save(string $uuid, Stub $output): void;
}
