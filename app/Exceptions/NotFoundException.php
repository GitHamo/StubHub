<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class NotFoundException extends ModelNotFoundException
{
    public function __construct(string $message = 'Not Found', int $code = 404)
    {
        parent::__construct($message, $code);
    }
}
