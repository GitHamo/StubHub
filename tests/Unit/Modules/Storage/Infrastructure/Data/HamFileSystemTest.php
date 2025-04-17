<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Storage\Infrastructure\Data;

use App\Modules\Storage\Exceptions\DirectoryDoesNotExistException;
use App\Modules\Storage\Exceptions\DirectoryNotReadableException;
use App\Modules\Storage\Exceptions\DirectoryNotWritableException;
use App\Modules\Storage\Exceptions\PathIsNotDirectoryException;
use App\Modules\Storage\Infrastructure\Data\HamFileSystem;

class HamFileSystemTest extends FileSystemTestCase
{
    public function testValidDirectory(): void
    {
        $this->createFileSystemInstance($this->getBasePath());

        // Ensure no exceptions are thrown and the directory is validated
        static::assertDirectoryExists($this->getBasePath());
    }

    public function testCreateDirectoryWhenDoesNotExist(): void
    {
        $newDir = $this->getBasePath() . '/newDirectory';

        // Ensuring the directory does not exist before the test
        if (is_dir($newDir)) {
            $this->deleteDirectory($newDir);
        }

        $this->createFileSystemInstance($newDir);

        // Assert the directory was created
        static::assertDirectoryExists($newDir);
    }

    public function testThrowsExceptionWhenNotDirectory(): void
    {
        static::expectException(PathIsNotDirectoryException::class);
        static::expectExceptionMessage('Provided path is not a directory');

        // Set up a file instead of a directory for testing
        $filePath = $this->getBasePath() . '/file.txt';
        file_put_contents($filePath, 'Test content');

        $this->createFileSystemInstance($filePath); // This should throw exception
    }

    public function testThrowsExceptionWhenDirectoryIsNotReadable(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Test skipped on Windows OS due to `is_readable` behavior.');
        }

        static::expectException(DirectoryNotReadableException::class);
        static::expectExceptionMessage('Directory is not readable');

        // Create a directory that is not readable
        $dir = $this->getBasePath() . '/nonReadableDirectory';
        mkdir($dir, 0000); // No read permissions
        chmod($dir, 0000); // Ensure directory is not readable

        $this->createFileSystemInstance($dir);
    }

    public function testThrowsExceptionWhenDirectoryIsNotWritable(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Test skipped on Windows OS due to `is_writable` behavior.');
        }

        static::expectException(DirectoryNotWritableException::class);
        static::expectExceptionMessage('Directory is not writable');

        // Create a directory that is not writable
        $dir = $this->getBasePath() . '/nonWritableDirectory';
        mkdir($dir, 0555); // No write permissions

        $this->createFileSystemInstance($dir);
    }

    public function testThrowsExceptionWhenDirectoryDoesNotExist(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Test skipped on Windows OS due to `mkdir` behavior.');
        }

        static::expectException(DirectoryDoesNotExistException::class);
        static::expectExceptionMessage('Unable to create directory');

        $nonExistentDir = $this->getBasePath() . '/nonExistentDirectory';
        // Ensure directory does not exist
        if (is_dir($nonExistentDir)) {
            $this->deleteDirectory($nonExistentDir);
        }

        $this->createFileSystemInstance($nonExistentDir);
    }

    private function createFileSystemInstance(string $dirPath): void
    {
        new class ($dirPath) extends HamFileSystem { };
    }
}
