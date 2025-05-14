<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Domain;

use App\Models\Domain\Endpoint;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class EndpointTest extends TestCase
{
    private Endpoint $endpoint;
    private string $id;
    private int $userId;
    private string $path;
    private string $name;
    private int $uniqueHits;
    private int $totalHits;
    private DateTimeImmutable $createdAt;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = new Endpoint(
            $this->id = 'endpoint-uuid',
            $this->userId = 123,
            $this->path = '/path',
            $this->name = 'name',
            $this->uniqueHits = 1,
            $this->totalHits = 1,
            $this->createdAt = new DateTimeImmutable(),
        );
    }

    public function testHasAccessorToId(): void
    {
        static::assertSame($this->id, $this->endpoint->id());
    }

    public function testHasAccessorToUserId(): void
    {
        static::assertSame($this->userId, $this->endpoint->userId());
    }

    public function testHasAccessorToPath(): void
    {
        static::assertSame($this->path, $this->endpoint->path());
    }

    public function testHasAccessorToName(): void
    {
        static::assertSame($this->name, $this->endpoint->name());
    }

    public function testHasAccessorToUniqueHits(): void
    {
        static::assertSame($this->uniqueHits, $this->endpoint->uniqueHits());
    }

    public function testHasAccessorToTotalHits(): void
    {
        static::assertSame($this->totalHits, $this->endpoint->totalHits());
    }

    public function testHasAccessorToCreatedAt(): void
    {
        static::assertSame($this->createdAt, $this->endpoint->createdAt());
    }
}
