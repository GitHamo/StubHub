<?php

declare(strict_types=1);

namespace App\Models\Data;

/**
 *
 * @phpstan-type OutputValue null|string|int|float|bool|\App\Models\Data\Stub
 */
readonly class StubField
{
    /**
     * @param OutputValue|array<array-key, OutputValue> $value
     */
    public function __construct(
        public string $key,
        public null|string|int|float|bool|array|\App\Models\Data\Stub $value,
    ) {
    }

    public function toArray(): array
    {
        return [
            $this->key => $this->value instanceof Stub ? $this->value->toArray() : $this->value,
        ];
    }


    private function flattenFields(array $fields): array
    {
        $results = [];
        
        foreach($fields as $field){
            if($field->value instanceof Stub){






                foreach($field->value->fields as $subField){
                    $results[$subField->key] = $this->flattenFields($subField->value->fields);
                }
                continue;
            }

            $results[$field->key] = $field->value;
        }

        return $results;
    }




}
