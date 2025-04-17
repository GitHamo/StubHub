<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Storage\Infrastructure\Data;

use App\Modules\Storage\Exceptions\FileNotReadableException;
use App\Modules\Storage\Exceptions\FileNotFoundException;
use App\Modules\Storage\Infrastructure\Data\HamReader;

class HamReaderTest extends FileSystemTestCase
{
    private const string TEST_FILE_NAME = 'testFile';

    private $reader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reader = new HamReader(
            $this->getBasePath()
        );
    }

    public function testGetFileContentSuccessfully(): void
    {
        $reader = new HamReader(__DIR__ . '/Fixtures');

        $result = $reader->get(self::TEST_FILE_NAME);

        // Assert that the content is correctly fetched
        static::assertSame('Sample file content', $result);
    }

    public function testThrowsExceptionWhenFileNotFound(): void
    {
        static::expectException(FileNotFoundException::class);
        static::expectExceptionMessage('File not found: nonExistentFile');

        $this->reader->get('nonExistentFile');
    }

    public function testThrowsExceptionWhenFileIsNotReadable(): void
    {
        // Skip on Windows as `is_readable` has a different behavior on Windows OS
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Test skipped on Windows OS due to `is_readable` behavior.');
        }

        file_put_contents($this->getBasePath() . '/' . self::TEST_FILE_NAME, 'Sample content');

        // Make the file unreadable (remove read permissions)
        chmod($this->getBasePath() . '/' . self::TEST_FILE_NAME, 0000);

        static::expectException(FileNotReadableException::class);
        static::expectExceptionMessage('File is not readable: ' . self::TEST_FILE_NAME);

        $this->reader->get(self::TEST_FILE_NAME);
    }
}
