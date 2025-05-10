<?php

declare(strict_types=1);

namespace App\Modules\StubStorage\Domain;

readonly class StubContent
{
    public function __construct(
        private string $name,
        private string $content,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function content(): string
    {
        return $this->content;
    }
}
