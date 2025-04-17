<?php

declare(strict_types=1);

namespace App\Modules\Generator\Infrastructure;

use App\Models\ContextEnum;
use App\Models\Data\Field;
use App\Models\Data\Input;
use App\Models\Data\Stub;
use Faker\Generator;
use InvalidArgumentException;

readonly class FakerService
{
    public function __construct(private Generator $generator)
    {
        // Faker\Factory::create();
    }

    public function generate(Input ...$inputs): Stub
    {
        $fields = array_map(function (Input $input): Field {
            $method = match ($input->context) {
                ContextEnum::CURRENCY_CODE => 'currencyCode',
                ContextEnum::COUNTRY_CODE => 'countryCode',
                ContextEnum::LANGUAGE_CODE => 'languageCode',
                ContextEnum::LOCALE => 'locale',
                ContextEnum::DESCRIPTION => 'paragraph',
                ContextEnum::FULLNAME => 'name',
                ContextEnum::FIRST_NAME => 'firstName',
                ContextEnum::LAST_NAME => 'lastName',
                ContextEnum::USERNAME => 'userName',
                ContextEnum::PASSWORD => 'password',
                ContextEnum::URL => 'url',
                ContextEnum::SLUG => 'slug',
                ContextEnum::EMAIL => 'safeEmail',
                ContextEnum::PRICE => 'randomFloat',
                ContextEnum::PHONE => 'phoneNumber',
                ContextEnum::UUID => 'randomHtml',
                ContextEnum::COLOR_HEX => 'hexColor',
                ContextEnum::HTML => 'randomHtml',
                ContextEnum::INTEGR => 'randomNumber',
                ContextEnum::FLOAT => 'randomFloat',
                ContextEnum::BOOLEAN => 'boolean',
                default => throw new InvalidArgumentException('Unsupported context'),
            };

            $value = $this->generator->$method();

            return new Field($input->key, $value);
        }, $inputs);

        return new Stub($fields);
    }
}
