<?php

declare(strict_types=1);

namespace App\Modules\Content\Domain;

use App\Models\Domain\Stub;

interface Storage
{
    public function get(string $path): string;

    /**
     * @return string stub path, not saved and returned once only
     */
    public function create(Stub $stub): string;

    public function delete(string $path): void;
}
