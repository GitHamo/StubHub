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
            'inputs.*.nested' => ['nullable', 'array', function ($attribute, $value, $fail) {
                // Validate nested elements recursively
                foreach ($value as $nestedKey => $nestedValue) {
                    $nestedKey = "{$attribute}.{$nestedKey}.nested";

                    // Check for presence of either context or nested at each level
                    if (!array_key_exists('context', $nestedValue) && !array_key_exists('nested', $nestedValue)) {
                        return $fail('Either context or nested must be present at each level.');
                    }

                    // Validate key at each level of nesting
                    if (!isset($nestedValue['key']) || !is_string($nestedValue['key'])) {
                        return $fail('The key must be a string at each level.');
                    }

                    // Recursive validation
                    $this->validateNestedLevel($nestedValue);
                }
            }],
        ];
    }

    public function messages(): array
    {
        return [
            'inputs.*.key.regex' => 'The key must not contain spaces, or special characters and can include underscores or dashes.',
        ];
    }
    
    // Recursive function to handle deep nested levels
    protected function validateNestedLevel($nestedValue)
    {
        foreach ($nestedValue as $key => $value) {
            // Validate context and nested at deeper levels
            if (isset($value['context'])) {
                // If context is present, ensure no nested is present at the same level
                if (isset($value['nested'])) {
                    throw new ValidationException("Context and nested cannot exist at the same level.");
                }
            } elseif (isset($value['nested'])) {
                // If nested exists, context must not exist
                $this->validateNestedLevel($value['nested']);
            }
        }
    }
}
