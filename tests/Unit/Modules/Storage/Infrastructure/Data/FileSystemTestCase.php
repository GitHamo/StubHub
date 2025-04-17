<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Storage\Infrastructure\Data;

use PHPUnit\Framework\TestCase;

abstract class FileSystemTestCase extends TestCase
{
    protected function setUp(): void
    {
        $path = $this->getBasePath();

        // Ensure the test directory is clean before starting tests
        if (is_dir($path)) {
            $this->deleteDirectory($path);
        } else {
            mkdir($path, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        // Recursively remove files and directories in the fixtures directory
        $this->deleteDirectory(
            $this->getBasePath()
        );
    }

    protected function deleteDirectory(string $dir): void
    {
        // Check if the directory exists
        if (is_dir($dir)) {
            // Get all files and directories inside the directory
            $files = array_diff(scandir($dir), ['.', '..']);

            // Loop through all the files and directories and delete them
            foreach ($files as $file) {
                $filePath = $dir . DIRECTORY_SEPARATOR . $file;

                // If it's a directory, recursively call deleteDirectory
                if (is_dir($filePath)) {
                    $this->deleteDirectory($filePath);  // Recursive call to delete subdirectories
                } else {
                    unlink($filePath);  // Delete file
                }
            }

            // After cleaning up files and subdirectories, remove the directory itself
            rmdir($dir);
        }
    }

    protected function getBasePath(): string
    {
        return implode(
            DIRECTORY_SEPARATOR,
            [
                __DIR__,
                'Fixtures',
                basename(static::class),
            ],
        );
    }
}
