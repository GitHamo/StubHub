<?php

declare(strict_types=1);

namespace App\Enums;

use InvalidArgumentException;

enum StubFieldContext: string
{
    case ADDRESS = 'address';
    case AM_PM = 'am_pm';
    case BOOLEAN = 'bool';
    case CC_DETAILS = 'cc_details';
    case CITY = 'city';
    case COLOR_HEX = 'color';
    case COUNTRY = 'country';
    case COUNTRY_CODE = 'country_code';
    case CURRENCY_CODE = 'currency_code';
    case DATE = 'date';
    case DATETIME = 'datetime';
    case EMAIL = 'email';
    case EMOJI = 'emoji';
    case FILE_EXT = 'file_ext';
    case FIRST_NAME = 'first_name';
    case FLOAT = 'float';
    case FULL_NAME = 'full_name';
    case HTML = 'html';
    case IBAN = 'iban';
    case INTEGR = 'number';
    case IPV4 = 'ipv4';
    case IPV6 = 'ipv6';
    case LANGUAGE_CODE = 'language_code';
    case LAST_NAME = 'last_name';
    case LATITUDE = 'latitude';
    case LONGITUDE = 'longitude';
    case LOCALE = 'locale';
    case MIME_TYPE = 'mime_type';
    case PASSWORD = 'password';
    case PARAGRAPH = 'paragraph';
    case PARAGRAPHS = 'paragraphs';
    case PHONE = 'phone';
    case POSTCODE = 'postcode';
    case REGEX = 'regex';
    case SENTENCE = 'sentence';
    case SENTENCES = 'sentences';
    case SLUG = 'slug';
    case STATE = 'state';
    case STREET_ADDRESS = 'street_address';
    case SWIFTCODE = 'swiftcode';
    case TIME = 'time';
    case TIMEZONE = 'timezone';
    case TLD = 'tld';
    case URL = 'url';
    case USERNAME = 'username';
    case UNIX = 'unix';
    case UUID = 'uuid';
    case WORD = 'word';
    case WORDS = 'words';

    public static function fromName(string $name): self
    {
        $normalized = strtolower(trim($name));

        return self::tryFrom(strtolower($normalized))
        ?? throw new InvalidArgumentException("Invalid context: {$name}");
    }
}
