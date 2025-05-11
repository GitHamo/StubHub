<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Content\Infrastructure;

use App\Modules\Content\Infrastructure\EncryptionHelper;
use PHPUnit\Framework\TestCase;

final class EncryptionHelperTest extends TestCase
{
    private const string ENCRYPTION_KEY = 'foo';

    private EncryptionHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->helper = new EncryptionHelper(self::ENCRYPTION_KEY);
    }
    public function testHashStringValue(): void
    {
        $value = 'bar';
        $expected = hash_hmac('sha256', $value, self::ENCRYPTION_KEY);

        $actual = $this->helper->hash($value);

        static::assertSame($expected, $actual);
    }

    public function testGenerateRandomHexStringOfExpectedLength(): void
    {
        $length = mt_rand(3, 100);
        $actual = (new EncryptionHelper('foo'))->random($length);

        static::assertSame($length * 2, strlen($actual));
        static::assertMatchesRegularExpression('/^[a-f0-9]+$/i', $actual);
    }

    public function testReturnEmptyStringForZeroOrNegativeLength(): void
    {
        static::assertSame(
            '',
            $this->helper->random(0)
        );

        static::assertSame(
            '',
            $this->helper->random(-123)
        );
    }
}
