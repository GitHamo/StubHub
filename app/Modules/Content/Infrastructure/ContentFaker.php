<?php

declare(strict_types=1);

namespace App\Modules\Content\Infrastructure;

use App\Enums\StubFieldContext;
use Faker\Generator;
use InvalidArgumentException;

class ContentFaker
{
    /**
     * @param array<string, array<string>> $contextsMap
     */
    public function __construct(
        private Generator $generator,
        private array $contextsMap,
    ) {
    }

    public function parse(StubFieldContext $context): mixed
    {
        if (!isset($this->contextsMap[$context->value])) {
            throw new InvalidArgumentException(sprintf('Unknown context: %s', $context->value));
        }

        $method = $this->contextsMap[$context->value][0];

        return $this->generator->$method();
    }
}
