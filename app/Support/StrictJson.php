<?php

declare(strict_types=1);

namespace App\Support;

final readonly class StrictJson
{
    public static function encode(mixed $data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    }

    public static function decode(string $json, bool $assoc = true): mixed
    {
        return json_decode($json, $assoc, 512, JSON_THROW_ON_ERROR);
    }
}
