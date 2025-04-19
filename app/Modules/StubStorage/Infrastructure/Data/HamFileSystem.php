<?php

declare(strict_types=1);

namespace App\Modules\StubStorage\Infrastructure\Data;

use App\Modules\StubStorage\Exceptions\DirectoryDoesNotExistException;
use App\Modules\StubStorage\Exceptions\DirectoryNotReadableException;
use App\Modules\StubStorage\Exceptions\DirectoryNotWritableException;
use App\Modules\StubStorage\Exceptions\PathIsNotDirectoryException;

abstract class HamFileSystem
{
    protected const string FILE_EXT = 'ham';

    public function __construct(
        protected readonly string $directory
    ) {
        $this->validateDirectoryIsAccessible($this->directory);
    }

    protected function getFilePath(string $filename): string
    {
        return implode(
            DIRECTORY_SEPARATOR,
            [
                rtrim($this->directory, DIRECTORY_SEPARATOR),
                "{$filename}." . static::FILE_EXT,
            ]
        );
    }

    private function validateDirectoryIsAccessible(string $directory): void
    {
        if (!file_exists($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new DirectoryDoesNotExistException("Unable to create directory: {$directory}");
        }

        if (!is_dir($directory)) {
            throw new PathIsNotDirectoryException("Provided path is not a directory: {$directory}");
        }

        if (!is_readable($directory)) {
            throw new DirectoryNotReadableException("Directory is not readable: {$directory}");
        }

        if (!is_writable($directory)) {
            throw new DirectoryNotWritableException("Directory is not writable: {$directory}");
        }
    }
}
