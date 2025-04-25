<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\StubFieldContext;

final readonly class StubFieldContextMapper
{
    /**
     * @return string[][]
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
     * @return string[][]
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
