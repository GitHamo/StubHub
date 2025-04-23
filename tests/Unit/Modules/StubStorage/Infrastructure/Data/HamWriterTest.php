<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\StubStorage\Infrastructure\Data;

use App\Modules\StubStorage\Infrastructure\Data\HamWriter;
use App\Modules\StubStorage\Exceptions\FileAlreadyExistsException;
use App\Modules\StubStorage\Exceptions\FileWriteFailureException;

class HamWriterTest extends FileSystemTestCase
{
    private HamWriter $writer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->writer = new HamWriter($this->getBasePath());
    }

    public function testCreatesFileSuccessfully(): void
    {
        $filename = 'test';
        $content = 'Hello, world!';
        $permissions = PHP_OS_FAMILY === 'Windows' ? '666' : '644';

        $filePath = $this->writer->create($filename, $content);

        static::assertFileExists($filePath);
        static::assertSame($content, file_get_contents($filePath));
        static::assertSame($permissions, decoct(fileperms($filePath) & 0777));
    }

    public function testThrowsExceptionIfFileAlreadyExists(): void
    {
        $filename = 'existing';
        $path = $this->getBasePath() . "/{$filename}.ham";

        file_put_contents($path, 'Existing content');

        static::expectException(FileAlreadyExistsException::class);
        static::expectExceptionMessage("File with name {$filename} already exists.");

        $this->writer->create($filename, 'New content');
    }

    public function testThrowsExceptionIfUnableToWrite(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Skipping permission test on Windows.');
        }

        $protectedDir = $this->getBasePath() . '/protected';
        mkdir($protectedDir, 0555); // Not writable
        $writer = new HamWriter($protectedDir);

        static::expectException(FileWriteFailureException::class);
        static::expectExceptionMessage('Unable to write file');

        $writer->create('forbidden', 'Nope');
    }

    public function testDeletesFileSuccessfully(): void
    {
        $filename = 'test';
        $path = $this->getBasePath() . "/{$filename}.ham";

        file_put_contents($path, 'Existing content');

        $this->writer->delete($filename);

        static::assertFileDoesNotExist($path);
    }
}
