<?php

declare(strict_types=1);

namespace App\Models\Data;

use App\Models\Data\StubField;
use InvalidArgumentException;

readonly class Stub
{
    private const string ERROR_MESSAGE_MISSING_MANDATORY_FIELD = 'Missing mandatory field: "%s"';
    public const string FIELD_KEY = "key";
    public const string FIELD_VALUE = "value";
    /**
     * @param StubField[] $fields
     */
    public function __construct(
        private array $fields,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_reduce(
            $this->fields,
            fn (array $carry, StubField $field): array => array_merge($carry, $field->toArray()),
            []
        );
    }

    /**
     * @throws \JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * @param list<array<string, mixed>> $rawData
     */
    public static function fromArray(array $rawData): self
    {
        return self::createOutput($rawData);
    }

    /**
     * @param list<array<string, mixed>> $rawData
     */
    private static function createOutput(array $rawData): Stub
    {
        $fields = array_map(fn (array $item): StubField =>  self::createField($item), $rawData);

        return new self($fields);
    }

    /**
     * @param array<string, mixed> $item
     * @throws \InvalidArgumentException
     */
    private static function createField(array $item): StubField
    {
        $fieldKeyName = self::FIELD_KEY;
        $fieldValueName = self::FIELD_VALUE;

        if (!array_key_exists($fieldKeyName, $item)) {
            throw new InvalidArgumentException(
                sprintf(
                    self::ERROR_MESSAGE_MISSING_MANDATORY_FIELD,
                    $fieldKeyName,
                )
            );
        }

        if (!array_key_exists($fieldValueName, $item)) {
            throw new InvalidArgumentException(
                sprintf(
                    self::ERROR_MESSAGE_MISSING_MANDATORY_FIELD,
                    $fieldValueName,
                )
            );
        }

        /** @var string */
        $key = $item[$fieldKeyName];
        /**
         * @var list<array<string, mixed>>|array<string, mixed>
         */
        $value = $item[$fieldValueName];

        if (array_is_list($value)) {
            $value = self::createOutput($value);
        }

        return new StubField($key, $value);
    }
}
