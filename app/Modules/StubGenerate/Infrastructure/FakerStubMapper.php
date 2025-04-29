<?php

declare(strict_types=1);

namespace App\Modules\StubGenerate\Infrastructure;

use App\Enums\StubFieldContext;
use Faker\Generator;
use InvalidArgumentException;

class FakerStubMapper extends StubMapper
{
    /**
     * @param array<string, array<string>> $contextsMap
     */
    public function __construct(
        private Generator $generator,
        private array $contextsMap,
    ) {
    }

    protected function parseContext(StubFieldContext $context): mixed
    {
        if (!isset($this->contextsMap[$context->value])) {
            throw new InvalidArgumentException(sprintf('Unknown context: %s', $context->value));
        }

        $method = $this->contextsMap[$context->value][0];

        return $this->generator->$method();
    }
}
