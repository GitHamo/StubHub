<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\StubFieldContext;

final readonly class StubFieldContextMapper
{
    /**
     * @return array<string, string[]>
     */
    public static function flatMap(): array
    {
        return self::metadataMap("method", "type");
    }

    /**
     * @return array<string, array{
     *     label: string,
     *     inputs: list<array{
     *         label: string,
     *         value: string,
     *         input: string
     *     }>
     * }>
     */
    public static function categoryMap(): array
    {
        $map = [];
        $metadata = self::metadataMap("label", "input");

        foreach (self::CATEGORY_MAP as $category => $cases) {
            $map[$category] = [
                'label' => self::CATEGORY_LABELS[$category],
                'inputs' => array_map(function (StubFieldContext $case) use ($metadata): array {
                    [$label, $input] = $metadata[$case->value];

                    return [
                        'label' => $label,
                        'value' => $case->value,
                        'input' => $input,
                    ];
                }, $cases),
            ];
        }

        return $map;
    }

    /**
     * @return array<string, string[]>
     */
    private static function metadataMap(string ...$constants): array
    {
        $map = [];

        $constants = array_map(fn (string $key): string => 'CASE_' . strtoupper($key), $constants);

        foreach (StubFieldContext::cases() as $case) {
            /** @phpstan-ignore-next-line */
            $map[$case->value] = array_map(fn (string $key): string => constant(self::class . '::' . $key . '_' . $case->name), $constants);
        }

        return $map;
    }

    /**
     * TECHDOX
     *
     * To add new context:
     * - Add new case to StubFieldContext enum
     * - Add new case to CASE_LABEL_* constants
     * - Add new case to CASE_METHOD_* constants
     * - Add new case to CASE_TYPE_* constants
     * - Add new case to CASE_INPUT_* constants
     * - Add new case to CATEGORY_MAP
     */

    // Case label
    private const string CASE_LABEL_ADDRESS = 'Address';
    private const string CASE_LABEL_AM_PM = 'AM/PM';
    private const string CASE_LABEL_BOOLEAN = 'Boolean';
    private const string CASE_LABEL_CC_DETAILS = 'Credit Card Details';
    private const string CASE_LABEL_CITY = 'City';
    private const string CASE_LABEL_COLOR_HEX = 'Color (Hex)';
    private const string CASE_LABEL_COUNTRY = 'Country';
    private const string CASE_LABEL_COUNTRY_CODE = 'Country Code';
    private const string CASE_LABEL_CURRENCY_CODE = 'Currency Code';
    private const string CASE_LABEL_DATE = 'Date';
    private const string CASE_LABEL_DATETIME = 'Date & Time';
    private const string CASE_LABEL_EMAIL = 'Email';
    private const string CASE_LABEL_EMOJI = 'Emoji';
    private const string CASE_LABEL_FILE_EXT = 'File Extension';
    private const string CASE_LABEL_FIRST_NAME = 'First Name';
    private const string CASE_LABEL_FLOAT = 'Float';
    private const string CASE_LABEL_FULL_NAME = 'Full Name';
    private const string CASE_LABEL_HTML = 'HTML';
    private const string CASE_LABEL_IBAN = 'IBAN';
    private const string CASE_LABEL_INTEGR = 'Integer';
    private const string CASE_LABEL_IPV4 = 'IPv4 Address';
    private const string CASE_LABEL_IPV6 = 'IPv6 Address';
    private const string CASE_LABEL_LANGUAGE_CODE = 'Language Code';
    private const string CASE_LABEL_LAST_NAME = 'Last Name';
    private const string CASE_LABEL_LATITUDE = 'Latitude';
    private const string CASE_LABEL_LONGITUDE = 'Longitude';
    private const string CASE_LABEL_LOCALE = 'Locale';
    private const string CASE_LABEL_MIME_TYPE = 'MIME Type';
    private const string CASE_LABEL_PASSWORD = 'Password';
    private const string CASE_LABEL_PARAGRAPH = 'Paragraph';
    private const string CASE_LABEL_PARAGRAPHS = 'Paragraphs';
    private const string CASE_LABEL_PHONE = 'Phone Number';
    private const string CASE_LABEL_POSTCODE = 'Postcode';
    private const string CASE_LABEL_REGEX = 'Regex Pattern';
    private const string CASE_LABEL_SENTENCE = 'Sentence';
    private const string CASE_LABEL_SENTENCES = 'Sentences';
    private const string CASE_LABEL_SLUG = 'Slug';
    private const string CASE_LABEL_STATE = 'State';
    private const string CASE_LABEL_STREET_ADDRESS = 'Street Address';
    private const string CASE_LABEL_SWIFTCODE = 'Swift/BIC Number';
    private const string CASE_LABEL_TIME = 'Time';
    private const string CASE_LABEL_TIMEZONE = 'Timezone';
    private const string CASE_LABEL_TLD = 'Top-Level Domain';
    private const string CASE_LABEL_URL = 'URL';
    private const string CASE_LABEL_USERNAME = 'Username';
    private const string CASE_LABEL_UNIX = 'Unix Timestamp';
    private const string CASE_LABEL_UUID = 'UUID';
    private const string CASE_LABEL_WORD = 'Word';
    private const string CASE_LABEL_WORDS = 'Words';
    // Case method name
    private const string CASE_METHOD_ADDRESS = 'address';
    private const string CASE_METHOD_AM_PM = 'amPm';
    private const string CASE_METHOD_BOOLEAN = 'boolean';
    private const string CASE_METHOD_CC_DETAILS = 'creditCardDetails';
    private const string CASE_METHOD_CITY = 'city';
    private const string CASE_METHOD_COLOR_HEX = 'hexColor';
    private const string CASE_METHOD_COUNTRY = 'country';
    private const string CASE_METHOD_COUNTRY_CODE = 'countryCode';
    private const string CASE_METHOD_CURRENCY_CODE = 'currencyCode';
    private const string CASE_METHOD_DATE = 'date';
    private const string CASE_METHOD_DATETIME = 'dateTime';
    private const string CASE_METHOD_EMAIL = 'safeEmail';
    private const string CASE_METHOD_EMOJI = 'emoji';
    private const string CASE_METHOD_FILE_EXT = 'fileExtension';
    private const string CASE_METHOD_FIRST_NAME = 'firstName';
    private const string CASE_METHOD_FLOAT = 'randomFloat';
    private const string CASE_METHOD_FULL_NAME = 'name';
    private const string CASE_METHOD_HTML = 'randomHtml';
    private const string CASE_METHOD_IBAN = 'iban';
    private const string CASE_METHOD_INTEGR = 'randomNumber';
    private const string CASE_METHOD_IPV4 = 'ipv4';
    private const string CASE_METHOD_IPV6 = 'ipv6';
    private const string CASE_METHOD_LANGUAGE_CODE = 'languageCode';
    private const string CASE_METHOD_LAST_NAME = 'lastName';
    private const string CASE_METHOD_LATITUDE = 'latitude';
    private const string CASE_METHOD_LONGITUDE = 'longitude';
    private const string CASE_METHOD_LOCALE = 'locale';
    private const string CASE_METHOD_MIME_TYPE = 'mimeType';
    private const string CASE_METHOD_PASSWORD = 'password';
    private const string CASE_METHOD_PARAGRAPH = 'paragraph';
    private const string CASE_METHOD_PARAGRAPHS = 'paragraphs';
    private const string CASE_METHOD_PHONE = 'phoneNumber';
    private const string CASE_METHOD_POSTCODE = 'postcode';
    private const string CASE_METHOD_REGEX = 'regexify';
    private const string CASE_METHOD_SENTENCE = 'sentence';
    private const string CASE_METHOD_SENTENCES = 'sentences';
    private const string CASE_METHOD_SLUG = 'slug';
    private const string CASE_METHOD_STATE = 'state';
    private const string CASE_METHOD_STREET_ADDRESS = 'streetAddress';
    private const string CASE_METHOD_SWIFTCODE = 'swiftBicNumber';
    private const string CASE_METHOD_TIME = 'time';
    private const string CASE_METHOD_TIMEZONE = 'timezone';
    private const string CASE_METHOD_TLD = 'tld';
    private const string CASE_METHOD_URL = 'url';
    private const string CASE_METHOD_USERNAME = 'userName';
    private const string CASE_METHOD_UNIX = 'unixTime';
    private const string CASE_METHOD_UUID = 'uuid';
    private const string CASE_METHOD_WORD = 'word';
    private const string CASE_METHOD_WORDS = 'words';
    // Case input type
    private const string CASE_INPUT_ADDRESS = 'text';
    private const string CASE_INPUT_AM_PM = 'text';
    private const string CASE_INPUT_BOOLEAN = 'checkbox';
    private const string CASE_INPUT_CC_DETAILS = 'array';
    private const string CASE_INPUT_CITY = 'text';
    private const string CASE_INPUT_COLOR_HEX = 'text';
    private const string CASE_INPUT_COUNTRY = 'text';
    private const string CASE_INPUT_COUNTRY_CODE = 'text';
    private const string CASE_INPUT_CURRENCY_CODE = 'text';
    private const string CASE_INPUT_DATE = 'date';
    private const string CASE_INPUT_DATETIME = 'datetime-local';
    private const string CASE_INPUT_EMAIL = 'email';
    private const string CASE_INPUT_EMOJI = 'text';
    private const string CASE_INPUT_FILE_EXT = 'text';
    private const string CASE_INPUT_FIRST_NAME = 'text';
    private const string CASE_INPUT_FLOAT = 'number';
    private const string CASE_INPUT_FULL_NAME = 'text';
    private const string CASE_INPUT_HTML = 'textarea';
    private const string CASE_INPUT_IBAN = 'text';
    private const string CASE_INPUT_INTEGR = 'number';
    private const string CASE_INPUT_IPV4 = 'text';
    private const string CASE_INPUT_IPV6 = 'text';
    private const string CASE_INPUT_LANGUAGE_CODE = 'text';
    private const string CASE_INPUT_LAST_NAME = 'text';
    private const string CASE_INPUT_LATITUDE = 'text';
    private const string CASE_INPUT_LONGITUDE = 'text';
    private const string CASE_INPUT_LOCALE = 'text';
    private const string CASE_INPUT_MIME_TYPE = 'text';
    private const string CASE_INPUT_PASSWORD = 'password';
    private const string CASE_INPUT_PARAGRAPH = 'textarea';
    private const string CASE_INPUT_PARAGRAPHS = 'textarea';
    private const string CASE_INPUT_PHONE = 'tel';
    private const string CASE_INPUT_POSTCODE = 'text';
    private const string CASE_INPUT_REGEX = 'text';
    private const string CASE_INPUT_SENTENCE = 'text';
    private const string CASE_INPUT_SENTENCES = 'text';
    private const string CASE_INPUT_SLUG = 'text';
    private const string CASE_INPUT_STATE = 'text';
    private const string CASE_INPUT_STREET_ADDRESS = 'text';
    private const string CASE_INPUT_SWIFTCODE = 'text';
    private const string CASE_INPUT_TIME = 'time';
    private const string CASE_INPUT_TIMEZONE = 'text';
    private const string CASE_INPUT_TLD = 'text';
    private const string CASE_INPUT_URL = 'url';
    private const string CASE_INPUT_USERNAME = 'text';
    private const string CASE_INPUT_UNIX = 'number';
    private const string CASE_INPUT_UUID = 'text';
    private const string CASE_INPUT_WORD = 'text';
    private const string CASE_INPUT_WORDS = 'text';
    // Case value type
    private const string CASE_TYPE_ADDRESS = 'string';
    private const string CASE_TYPE_AM_PM = 'string';
    private const string CASE_TYPE_BOOLEAN = 'bool';
    private const string CASE_TYPE_CC_DETAILS = 'text';
    private const string CASE_TYPE_CITY = 'string';
    private const string CASE_TYPE_COLOR_HEX = 'string';
    private const string CASE_TYPE_COUNTRY = 'string';
    private const string CASE_TYPE_COUNTRY_CODE = 'string';
    private const string CASE_TYPE_CURRENCY_CODE = 'string';
    private const string CASE_TYPE_DATE = 'string';
    private const string CASE_TYPE_DATETIME = 'string';
    private const string CASE_TYPE_EMAIL = 'string';
    private const string CASE_TYPE_EMOJI = 'string';
    private const string CASE_TYPE_FILE_EXT = 'string';
    private const string CASE_TYPE_FIRST_NAME = 'string';
    private const string CASE_TYPE_FLOAT = 'float';
    private const string CASE_TYPE_FULL_NAME = 'string';
    private const string CASE_TYPE_HTML = 'string';
    private const string CASE_TYPE_IBAN = 'string';
    private const string CASE_TYPE_INTEGR = 'int';
    private const string CASE_TYPE_IPV4 = 'string';
    private const string CASE_TYPE_IPV6 = 'string';
    private const string CASE_TYPE_LANGUAGE_CODE = 'string';
    private const string CASE_TYPE_LAST_NAME = 'string';
    private const string CASE_TYPE_LATITUDE = 'string';
    private const string CASE_TYPE_LONGITUDE = 'string';
    private const string CASE_TYPE_LOCALE = 'string';
    private const string CASE_TYPE_MIME_TYPE = 'string';
    private const string CASE_TYPE_PASSWORD = 'string';
    private const string CASE_TYPE_PARAGRAPH = 'string';
    private const string CASE_TYPE_PARAGRAPHS = 'array';
    private const string CASE_TYPE_PHONE = 'string';
    private const string CASE_TYPE_POSTCODE = 'string';
    private const string CASE_TYPE_REGEX = 'string';
    private const string CASE_TYPE_SENTENCE = 'string';
    private const string CASE_TYPE_SENTENCES = 'array';
    private const string CASE_TYPE_SLUG = 'string';
    private const string CASE_TYPE_STATE = 'string';
    private const string CASE_TYPE_STREET_ADDRESS = 'string';
    private const string CASE_TYPE_SWIFTCODE = 'string';
    private const string CASE_TYPE_TIME = 'string';
    private const string CASE_TYPE_TIMEZONE = 'string';
    private const string CASE_TYPE_TLD = 'string';
    private const string CASE_TYPE_URL = 'string';
    private const string CASE_TYPE_USERNAME = 'string';
    private const string CASE_TYPE_UNIX = 'int';
    private const string CASE_TYPE_UUID = 'string';
    private const string CASE_TYPE_WORD = 'string';
    private const string CASE_TYPE_WORDS = 'array';

    // Category
    private const string CATEGORY_PERSONAL = 'personal_info';
    private const string CATEGORY_CONTENT = 'content';
    private const string CATEGORY_LOCALE = 'locale';
    private const string CATEGORY_DATETIME = 'date_time';
    private const string CATEGORY_ADDRESS = 'address';
    private const string CATEGORY_INTERNET = 'internet';
    private const string CATEGORY_PAYMENT = 'payment';
    private const string CATEGORY_MISC = 'general';
    private const array CATEGORY_MAP = [
        self::CATEGORY_PERSONAL => [
            StubFieldContext::FULL_NAME,
            StubFieldContext::FIRST_NAME,
            StubFieldContext::LAST_NAME,
            StubFieldContext::USERNAME,
            StubFieldContext::PASSWORD,
            StubFieldContext::EMAIL,
            StubFieldContext::PHONE,
        ],
        self::CATEGORY_CONTENT => [
            StubFieldContext::WORD,
            StubFieldContext::WORDS,
            StubFieldContext::SENTENCE,
            StubFieldContext::SENTENCES,
            StubFieldContext::PARAGRAPH,
            StubFieldContext::PARAGRAPHS,
        ],
        self::CATEGORY_LOCALE => [
            StubFieldContext::CURRENCY_CODE,
            StubFieldContext::COUNTRY_CODE,
            StubFieldContext::LANGUAGE_CODE,
            StubFieldContext::LOCALE,
        ],
        self::CATEGORY_DATETIME => [
            StubFieldContext::UNIX,
            StubFieldContext::DATETIME,
            StubFieldContext::DATE,
            StubFieldContext::TIME,
            StubFieldContext::AM_PM,
            StubFieldContext::TIMEZONE,
        ],
        self::CATEGORY_ADDRESS => [
            StubFieldContext::CITY,
            StubFieldContext::STATE,
            StubFieldContext::COUNTRY,
            StubFieldContext::ADDRESS,
            StubFieldContext::POSTCODE,
            StubFieldContext::STREET_ADDRESS,
            StubFieldContext::LATITUDE,
            StubFieldContext::LONGITUDE,
        ],
        self::CATEGORY_INTERNET => [
            StubFieldContext::TLD,
            StubFieldContext::IPV4,
            StubFieldContext::IPV6,
            StubFieldContext::MIME_TYPE,
            StubFieldContext::FILE_EXT,
            StubFieldContext::EMOJI,
            StubFieldContext::URL,
            StubFieldContext::SLUG,
            StubFieldContext::HTML,
            StubFieldContext::UUID,
        ],
        self::CATEGORY_PAYMENT => [
            StubFieldContext::CC_DETAILS,
            StubFieldContext::IBAN,
            StubFieldContext::SWIFTCODE,
        ],
        self::CATEGORY_MISC => [
            StubFieldContext::INTEGR,
            StubFieldContext::FLOAT,
            StubFieldContext::BOOLEAN,
            StubFieldContext::COLOR_HEX,
            StubFieldContext::REGEX,
        ],
    ];

    private const array CATEGORY_LABELS = [
        self::CATEGORY_PERSONAL => 'Personal Info',
        self::CATEGORY_CONTENT => 'Content',
        self::CATEGORY_LOCALE => 'Locale',
        self::CATEGORY_DATETIME => 'Date/Time',
        self::CATEGORY_ADDRESS => 'Address',
        self::CATEGORY_INTERNET => 'Internet',
        self::CATEGORY_PAYMENT => 'Payment',
        self::CATEGORY_MISC => 'General',
    ];
}
