<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CreateEndpointRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'inputs' => ['required', 'array'],
            'inputs.*' => ['array'],
            'inputs.*.key' => ['required', 'string', 'regex:/^[A-Za-z0-9\-_]+$/'],
            'inputs.*.context' => ['required_without:inputs.*.nested', 'string'],
            'inputs.*.repeat' => ['optional', 'integer', 'min:0'],
            'inputs.*.nested' => ['nullable', 'array', function ($attribute, $value, $fail) {
                // Validate nested elements recursively
                foreach ($value as $nestedKey => $nestedValue) {

                    $nestedKey = "{$attribute}.{$nestedKey}.nested";

                    if (!is_array($nestedValue) || array_is_list($nestedValue)) {
                        return $fail('Nested must be an associative array.');
                    }

                    // Check for presence of either context or nested at each level
                    if (!array_key_exists('context', $nestedValue) && !array_key_exists('nested', $nestedValue)) {
                        return $fail('Either context or nested must be present at each level.');
                    }

                    // Validate key at each level of nesting
                    if (!isset($nestedValue['key']) || !is_string($nestedValue['key'])) {
                        return $fail('The key must be a string at each level.');
                    }

                    /** @var array<string, array<string, mixed>> $nestedValue */

                    // Recursive validation
                    $this->validateNestedLevel($nestedValue);
                }
            }],
        ];
    }

    /**
     * @return array<string, string>
     */
    #[\Override]
    public function messages(): array
    {
        return [
            'inputs.*.key.regex' => 'The key must not contain spaces, or special characters and can include underscores or dashes.',
        ];
    }

    /**
     * Recursive function to handle deep nested levels
     *
     * @param array<string, array<string, mixed>> $nestedValue
     */
    protected function validateNestedLevel(array $nestedValue): void
    {
        foreach ($nestedValue as $value) {
            /** @var array{ context?: string, nested?: array<string, array<string, mixed>>} $value */

            // Validate context and nested at deeper levels
            if (isset($value['context'])) {
                // If context is present, ensure no nested is present at the same level
                if (isset($value['nested'])) {
                    /** @phpstan-ignore-next-line */
                    throw new ValidationException("Context and nested cannot exist at the same level.");
                }
            } elseif (isset($value['nested'])) {
                // If nested exists, context must not exist
                $this->validateNestedLevel($value['nested']);
            }
        }
    }
}
