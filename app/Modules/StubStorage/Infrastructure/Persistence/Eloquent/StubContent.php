<?php

declare(strict_types=1);

namespace App\Modules\StubStorage\Infrastructure\Persistence\Eloquent;

use Database\Factories\StubContentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $filename
 * @property string $content
 * @property \DateTimeImmutable $created_at
 * @property \DateTimeImmutable $updated_at
 */
class StubContent extends Model
{
    /** @use HasFactory<\Database\Factories\StubContentFactory> */
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'filename',
        'content',
    ];
    /**
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    protected static function newFactory(): StubContentFactory
    {
        return StubContentFactory::new();
    }
}
