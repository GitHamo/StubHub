<?php

declare(strict_types=1);

namespace App\Modules\Endpoints\Domain;

use DateTimeImmutable;

class Endpoint
{
    public function __construct(
        private readonly string $id,
        private readonly int $userId,
        private readonly string $path,
        private readonly string $name,
        private readonly DateTimeImmutable $createdAt,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function userId(): int
    {
        return $this->userId;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
