<?php

declare(strict_types=1);

namespace App\Enums;

use InvalidArgumentException;

enum ContextEnum: string
{
    case CURRENCY_CODE = 'currency_code';
    case COUNTRY_CODE = 'coutnry_code';
    case LANGUAGE_CODE = 'language_code';
    case LOCALE = 'locale';
    case DESCRIPTION = 'description';
    case FULLNAME = 'name';
    case FIRST_NAME = 'first_name';
    case LAST_NAME = 'last_name';
    case USERNAME = 'username';
    case PASSWORD = 'password';
    case URL = 'url';
    case SLUG = 'slug';
    case EMAIL = 'email';
    case PRICE = 'price';
    case PHONE = 'phone';
    case UUID = 'uuid';
    case COLOR_HEX = 'color';
    case HTML = 'html';
    case INTEGR = 'number';
    case FLOAT = 'float';
    case BOOLEAN = 'bool';

    public static function fromName(string $name): self
    {
        $normalized = strtolower(trim($name));

        return self::tryFrom(strtolower($normalized))
        ?? throw new InvalidArgumentException("Invalid context: {$name}");
    }
}
