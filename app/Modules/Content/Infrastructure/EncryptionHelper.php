<?php

declare(strict_types=1);

namespace App\Modules\Content\Infrastructure;

class EncryptionHelper
{
    public function __construct(
        private string $secretKey
    ) {
    }

    public function hash(string $value): string
    {
        return hash_hmac('sha256', $value, $this->secretKey);
    }

    public function random(int $length): string
    {
        if ($length <= 0) {
            return '';
        }

        return bin2hex(random_bytes($length));
    }
}
