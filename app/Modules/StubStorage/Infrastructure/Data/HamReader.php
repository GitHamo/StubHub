<?php

declare(strict_types=1);

namespace App\Modules\StubStorage\Infrastructure\Data;

use App\Modules\StubStorage\Exceptions\FileNotReadableException;
use App\Modules\StubStorage\Exceptions\FileNotFoundException;

class HamReader extends HamFileSystem
{
    public function get(string $filename): string
    {
        $filePath = $this->getFilePath($filename);

        // Check if the file exists
        if (!file_exists($filePath)) {
            throw new FileNotFoundException("File not found: {$filename}");
        }

        // Check if the file is readable
        if (!is_readable($filePath)) {
            throw new FileNotReadableException("File is not readable: {$filename}");
        }

        // Read the file and return its contents
        return file_get_contents($filePath);
    }
}
