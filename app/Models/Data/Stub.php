<?php

declare(strict_types=1);

namespace App\Models\Data;

use App\Models\Data\StubField;
use InvalidArgumentException;

readonly class Stub
{
    private const string ERROR_MESSAGE_MISSING_MANDATORY_FIELD = 'Missing mandatory field: "%s"';
    /**
     * @param StubField[] $fields
     */
    public function __construct(
        private array $fields,
    ) {
    }

    public function toArray(): array
    {
        return array_map(fn (StubField $field) => $field->toArray(), $this->fields);
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * @param array<array-key, array<string, mixed>> $rawData
     */
    public static function fromArray(array $rawData): self
    {
        return static::createOutput($rawData);
    }

    /**
     * @param array<string, mixed> $item
     * @throws \InvalidArgumentException
     */
    private static function createField(array $item): StubField
    {
        $fieldKeyName = StubField::FIELD_KEY;
        $fieldValueName = StubField::FIELD_VALUE;

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

        $key = $item[$fieldKeyName];
        $value = $item[$fieldValueName];

        if (is_array($value) && array_is_list($value)) {
            $value = static::createOutput($value);
        }

        return new StubField($key, $value);
    }

    /**
     * @param array<array-key, array<string, mixed>> $rawData
     */
    private static function createOutput(array $rawData): Stub
    {
        if (!is_array($rawData) || !array_is_list($rawData)) {
            throw new InvalidArgumentException('Array must decode to a list of fields.');
        }
        
        $fields = array_map(function (array $item): Field {
            if (array_is_list($item)) {
                throw new InvalidArgumentException('Field array must decode to an associative array.');
            }

            return static::createField($item);
        }, $rawData);

        return new self($fields);
    }
}
