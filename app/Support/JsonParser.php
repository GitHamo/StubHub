<?php

declare(strict_types=1);

namespace App\Support;

use App\Exceptions\JsonParseException;
use JsonException;

readonly class JsonParser
{
    /**
     * @throws JsonParseException
     * @return array<string, mixed>
     */
    public function parse(string $json): array
    {
        try {
            $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new JsonParseException($e->getMessage(), previous: $e);
        }

        return $decoded;
    }
}
