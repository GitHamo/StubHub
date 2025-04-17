<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Storage\Infrastructure;

use App\Models\Data\Field;
use App\Models\Data\Stub;
use App\Modules\Storage\Infrastructure\Data\HamReader;
use App\Modules\Storage\Infrastructure\Data\HamWriter;
use App\Modules\Storage\Infrastructure\FileStorageRepository;
use App\Support\JsonParser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileStorageRepositoryTest extends TestCase
{
    private FileStorageRepository $repository;
    private HamReader|MockObject $reader;
    private HamWriter|MockObject $writer;
    private JsonParser|MockObject $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new FileStorageRepository(
            $this->reader = $this->createMock(HamReader::class),
            $this->writer = $this->createMock(HamWriter::class),
            $this->parser = $this->createMock(JsonParser::class),
        );
    }

    public function testUsesComponentsToGetOutputByUuid(): void
    {
        $uuid = 'foo';
        $json = '[{"key":"foobar","value":"baz"}]';
        $content = [['key' => 'foobar', 'value' => 'baz']];
        $expected = new Stub([new Field('foobar', 'baz')]);

        $this->reader->expects(static::once())->method('get')->with(static::identicalTo($uuid))->willReturn($json);
        $this->parser->expects(static::once())->method('parse')->with(static::identicalTo($json))->willReturn($content);

        $actual = $this->repository->get($uuid);

        static::assertEquals($expected, $actual);
    }

    public function testUsesComponentsToSaveOutput(): void
    {
        $uuid = 'foo';
        $content = 'bar';
        $arguments = [
            $uuid,
            $content,
        ];
        $output = $this->createConfiguredMock(Stub::class, [
            'toJson' => $content,
        ]);

        $this->writer->expects(static::once())->method('create')->with(...$arguments);

        $this->repository->save($uuid, $output);
    }
}
