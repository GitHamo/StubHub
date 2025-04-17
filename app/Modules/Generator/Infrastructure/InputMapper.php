<?php

declare(strict_types=1);

namespace App\Modules\Generator\Infrastructure;

use App\Enums\ContextEnum;
use App\Exceptions\JsonParseException;
use App\Models\Data\Input;
use App\Support\JsonParser;
use InvalidArgumentException;

readonly class InputMapper
{
    private const string ERROR_MESSAGE_MISSING_MANDATORY_FIELD = 'Missing mandatory input field: "%s"';
    private const string INPUT_KEY = "key";
    private const string INPUT_CONTEXT = "type";

    public function __construct(private JsonParser $jsonParser)
    {}
    /**
     * @return Input[]
     */
    public function map(string $json): array
    {
        $rawInput = $this->jsonParser->parse($json);

        if (!is_array($rawInput) || !array_is_list($rawInput)) {
            throw new JsonParseException('JSON must decode to a listed array.');
        }

        return array_map(function(array $itemData): Input {
            if(array_is_list($itemData)) {
                throw new InvalidArgumentException('Item data must decode to an associated array.');
            }

            if (!array_key_exists(self::INPUT_KEY, $itemData)) {
                throw new InvalidArgumentException(
                    sprintf(
                        self::ERROR_MESSAGE_MISSING_MANDATORY_FIELD,
                        self::INPUT_KEY,
                    )
                );
            }
    
            if (!array_key_exists(self::INPUT_CONTEXT, $itemData)) {
                throw new InvalidArgumentException(
                    sprintf(
                        self::ERROR_MESSAGE_MISSING_MANDATORY_FIELD,
                        self::INPUT_CONTEXT,
                    )
                );
            }

            return new Input($itemData[self::INPUT_KEY], ContextEnum::fromName($itemData[self::INPUT_CONTEXT]));
        }, $rawInput);
    }
}
