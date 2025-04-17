<?php

declare(strict_types=1);

namespace App\Modules\Storage\Infrastructure;

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
        private string $secretKey,
    ) {

    }

    public function get(string $uuid): Stub
    {
        $stubName = $this->hash($uuid);
        $json = $this->reader->get($stubName);
        $data = $this->parser->parse($json);

        return Stub::fromArray($data);
    }

    public function save(string $uuid, Stub $output): void
    {
        $content = $output->toJson();
        $stubName = $this->hash($uuid);

        $this->writer->create($stubName, $content);
    }

    private function hash(string $value): string
    {
        return hash_hmac('sha256', $value, $this->secretKey);
    }
}
