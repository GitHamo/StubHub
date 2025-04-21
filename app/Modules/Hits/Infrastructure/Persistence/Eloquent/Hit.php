<?php

declare(strict_types=1);

namespace App\Modules\Hits\Infrastructure\Persistence\Eloquent;

use App\Modules\Endpoints\Infrastructure\Persistence\Eloquent\Endpoint;
use Database\Factories\HitFactory;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $endpoint_id
 * @property string $signature
 * @property DateTimeImmutable $created_at
 */
class Hit extends Model
{
    /** @use HasFactory<\Database\Factories\HitFactory> */
    use HasFactory;
    public $timestamps = false; // disables both created_at and updated_at
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'endpoint_hits';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'endpoint_id',
        'signature',
        'created_at',
    ];
    protected $casts = [
        'created_at' => 'immutable_datetime',
    ];

    #[\Override]
    public static function boot(): void
    {
        parent::boot();

        // Laravel to auto-manage only `created_at`
        static::creating(fn (Model $model): DateTimeImmutable => $model->created_at ??= now());
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): HitFactory
    {
        return HitFactory::new();
    }

    /**
     * Get the endpoint that owns the hit
     * @return BelongsTo<Endpoint>
     */
    public function endpoint(): BelongsTo
    {
        return $this->belongsTo(Endpoint::class);
    }
}
