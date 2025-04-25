<?php

declare(strict_types=1);

namespace App\Modules\StubStorage\Infrastructure;

use App\Models\Data\Stub;
use App\Modules\StubStorage\Infrastructure\Data\HamReader;
use App\Modules\StubStorage\Infrastructure\Data\HamWriter;
use App\Modules\StubStorage\StorageRepository;
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

    #[\Override]
    public function fetchById(string $fileId): string
    {
        return $this->reader->get($this->hash($fileId));
    }

    #[\Override]
    public function create(string $path, Stub $stub): string
    {
        $content = $stub->toJson();
        $stubName = $this->hash($path);

        $this->writer->create($stubName, $content);

        return $stubName;
    }

    #[\Override]
    public function get(string $path): Stub
    {
        $stubName = $this->hash($path);
        $json = $this->reader->get($stubName);
        /** @var list<array<string, mixed>> */
        $data = $this->parser->parse($json);

        return Stub::fromArray($data);
    }

    #[\Override]
    public function delete(string $path): void
    {
        $this->writer->delete($this->hash($path));
    }

    private function hash(string $value): string
    {
        return hash_hmac('sha256', $value, $this->secretKey);
    }
}
