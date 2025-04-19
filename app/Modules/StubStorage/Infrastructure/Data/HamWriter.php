<?php

declare(strict_types=1);

namespace App\Modules\StubStorage\Infrastructure\Data;

use App\Modules\StubStorage\Exceptions\FileAlreadyExistsException;
use App\Modules\StubStorage\Exceptions\FileWriteFailureException;

class HamWriter extends HamFileSystem
{
    public function create(string $filename, string $content): string
    {
        $filePath = $this->getFilePath($filename);

        if (file_exists($filePath)) {
            throw new FileAlreadyExistsException("File with name {$filename} already exists.");
        }

        if (@file_put_contents($filePath, $content) === false) {
            throw new FileWriteFailureException("Unable to write file at: {$filePath}");
        }

        return $filePath;
    }
}
