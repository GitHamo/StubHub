<?php

declare(strict_types=1);

namespace App\Modules\Storage\Infrastructure;

use App\Models\Data\Field;
use App\Models\Data\Stub;
use App\Modules\Storage\Infrastructure\Data\HamReader;
use App\Modules\Storage\Infrastructure\Data\HamWriter;
use App\Modules\Storage\StorageRepository;
use App\Support\JsonParser;

readonly class FileStorageRepository implements StorageRepository
{
    public function __construct(
        private HamReader $reader,
        private HamWriter $writer,
        private JsonParser $parser,
    ) {

    }

    public function get(string $uuid): Stub
    {
        $json = $this->reader->get($uuid);
        $data = $this->parser->parse($json);

        return Stub::fromArray($data);
    }

    public function save(string $uuid, Stub $output): void
    {
        $content = $output->toJson();

        $this->writer->create($uuid, $content);
    }
}
